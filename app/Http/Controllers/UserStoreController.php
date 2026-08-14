<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jugada;
use App\Models\Carton;
use App\Models\PruebaParticipante;
use App\Models\ParticipanteCartonPrueba;
use App\Models\TransaccionCajero;
use Illuminate\Support\Str;
use App\Services\BingoCardService;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class UserStoreController extends Controller
{
    public function showTienda($jugadaId)
    {
        $jugada = Jugada::with('institucion', 'organizador')->findOrFail($jugadaId);
        return view('tienda.compra', compact('jugada'));
    }

    public function procesarCompra(Request $request, $jugadaId)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
            'cantidad' => 'required|integer|min:1|max:4',
        ]);

        $jugada = Jugada::findOrFail($jugadaId);

        $participante = PruebaParticipante::firstOrCreate(
            ['telefono' => $request->telefono],
            [
                'nombre' => mb_strtoupper($request->nombre),
                'token' => (string) Str::uuid(),
                'codigo_acceso' => strtoupper(Str::random(6)),
                'saldo_fichas' => 0,
                'es_prueba' => false
            ]
        );

        $precioPorCarton = 50;
        $costoTotal = $request->cantidad * $precioPorCarton; 
        
        if ($participante->saldo_fichas < $costoTotal) {
            return back()->with('error', "No tienes suficientes Fichas Infinity. Necesitas $costoTotal pero tienes {$participante->saldo_fichas}.");
        }

        $participante->saldo_fichas -= $costoTotal;
        $participante->save();

        for($i = 0; $i < $request->cantidad; $i++) {
            $carton = new Carton();
            $carton->serie = 'DIGITAL-' . date('ym');
            $carton->numero_carton = mt_rand(100000, 999999);
            $carton->formato = 'digital_online';
            
            $bingoService = new BingoCardService();
            $carton->grilla = $bingoService->generarGrilla();
            
            $carton->estado = 'activo';
            $carton->es_prueba = $participante->es_prueba;
            $carton->save();

            ParticipanteCartonPrueba::create([
                'participante_prueba_id' => $participante->id,
                'jugada_id' => $jugada->id,
                'carton_id' => $carton->id
            ]);
        }

        return redirect()->route('tienda.gracias', ['token' => $participante->token, 'j' => $jugada->id]);
    }

    public function gracias(Request $request, $token)
    {
        $jugadaId = $request->query('j');
        $participante = PruebaParticipante::where('token', $token)->firstOrFail();
        $jugada = Jugada::find($jugadaId);

        $comprados = ParticipanteCartonPrueba::where('participante_prueba_id', $participante->id)
            ->where('jugada_id', $jugadaId)
            ->count();

        return view('tienda.gracias', compact('participante', 'jugada', 'comprados'));
    }

    /*
    |--------------------------------------------------------------------------
    | CAJERO MULTIPASARELA (MercadoPago, Prex, Airtm, ARQ)
    |--------------------------------------------------------------------------
    */
    public function cajeroShow(Request $request)
    {
        $telefono = $request->query('t');
        if(!$telefono) {
            return redirect()->route('tienda.show', 1)->with('error', 'Debes ingresar tu teléfono para acceder al cajero.');
        }

        $participante = PruebaParticipante::where('telefono', $telefono)->first();
        if(!$participante) {
            return redirect()->route('tienda.show', 1)->with('error', 'Usuario no encontrado. Inicia tu compra primero.');
        }

        return view('tienda.cajero', compact('participante'));
    }

    public function cajeroProcesar(Request $request)
    {
        $request->validate([
            'telefono' => 'required|string',
            'metodo_pago' => 'required|in:mp,prex_ar,prex_uy,airtm,arq',
            'paquete_fichas' => 'required|integer|in:500,1000,5000'
        ]);

        $participante = PruebaParticipante::where('telefono', $request->telefono)->firstOrFail();
        
        $montoFiat = $request->paquete_fichas; // 1 Ficha = 1 ARS Base

        // Aplicar Pass-Through Fees (Recargos)
        if ($request->metodo_pago === 'mp') {
            $montoFiat = $montoFiat * 1.10; // +10% MP
        } elseif ($request->metodo_pago === 'airtm') {
            $montoFiat = $montoFiat * 1.05; // +5% Airtm
        }

        $transaccion = TransaccionCajero::create([
            'participante_id' => $participante->id,
            'metodo_pago' => $request->metodo_pago,
            'fichas' => $request->paquete_fichas,
            'monto_fiat' => $montoFiat,
            'estado' => 'pendiente'
        ]);

        if ($request->metodo_pago === 'mp') {
            return $this->procesarMercadoPago($transaccion, $participante);
        }

        // Para métodos manuales (Prex, Airtm, ARQ)
        return back()->with('success', 'Transacción creada. Por favor, realiza la transferencia con tu método elegido y guarda tu comprobante. Nuestro equipo lo verificará en breve.');
    }

    private function procesarMercadoPago($transaccion, $participante)
    {
        MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN', 'APP_USR-123456789-test'));

        $client = new PreferenceClient();

        try {
            $preference = $client->create([
                "items" => [
                    [
                        "id" => "FICHAS-" . $transaccion->fichas,
                        "title" => $transaccion->fichas . " Fichas Infinity",
                        "quantity" => 1,
                        "currency_id" => "ARS",
                        "unit_price" => (float) $transaccion->monto_fiat
                    ]
                ],
                "payer" => [
                    "name" => $participante->nombre,
                    "surname" => "",
                    "email" => "jugador" . $participante->id . "@test.com",
                ],
                "back_urls" => [
                    "success" => route('cajero.mp_success'),
                    "failure" => route('cajero.mp_failure'),
                    "pending" => route('cajero.mp_failure')
                ],
                "auto_return" => "approved",
                "external_reference" => (string) $transaccion->id
            ]);

            $transaccion->detalles_adicionales = $preference->id;
            $transaccion->save();

            return redirect($preference->init_point);

        } catch (MPApiException $e) {
            \Log::error("MercadoPago API Error: " . $e->getMessage());
            return back()->with('error', 'Hubo un error al conectar con MercadoPago. Intenta nuevamente más tarde.');
        }
    }

    public function mpSuccess(Request $request)
    {
        $paymentId = $request->query('payment_id');
        $status = $request->query('status');
        $externalReference = $request->query('external_reference');

        if ($status === 'approved' && $externalReference) {
            $transaccion = TransaccionCajero::find($externalReference);

            if ($transaccion && $transaccion->estado === 'pendiente') {
                $transaccion->estado = 'aprobado';
                $transaccion->comprobante_externo = $paymentId;
                $transaccion->save();

                $participante = $transaccion->participante;
                $participante->saldo_fichas += $transaccion->fichas;
                $participante->save();

                return redirect()->route('tienda.show', 1)
                    ->with('success', "¡Pago Aprobado! Se acreditaron {$transaccion->fichas} Fichas Infinity a tu cuenta.");
            }
        }

        return redirect()->route('tienda.show', 1)->with('error', 'No se pudo verificar el estado del pago.');
    }

    public function mpFailure(Request $request)
    {
        return redirect()->route('tienda.show', 1)->with('error', 'El pago fue rechazado o cancelado.');
    }

    public function mpWebhook(Request $request)
    {
        // En producción aquí se valida la firma de MercadoPago y se actualiza la BD de forma asíncrona.
        return response()->json(['status' => 'ok']);
    }

}
