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
    private function limpiarTelefono($telefono)
    {
        // Remueve espacios, guiones, paréntesis y el signo más.
        // Ej: "+54 9 3804 250-007" -> "5493804250007"
        return preg_replace('/[^0-9]/', '', $telefono);
    }

    public function showTienda(Request $request, $jugadaId)
    {
        $jugada = Jugada::with('institucion', 'organizador')->findOrFail($jugadaId);
        
        $participanteLogueado = null;
        $token = $request->cookie('participante_token');
        if ($token) {
            $participanteLogueado = PruebaParticipante::where('token', $token)->first();
        }

        return view('tienda.compra', compact('jugada', 'participanteLogueado'));
    }

    public function cerrarSesion()
    {
        return redirect()->route('tienda.show', 1)->withCookie(cookie('participante_token', '', -1));
    }

    public function procesarCompra(Request $request, $jugadaId)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
            'prefijo' => 'nullable|string|max:10',
            'cantidad' => 'required|integer|min:1|max:4',
        ]);

        $jugada = Jugada::findOrFail($jugadaId);
        
        $telefonoCompleto = $request->prefijo . $request->telefono;
        $telefonoLimpio = $this->limpiarTelefono($telefonoCompleto);

        $participante = PruebaParticipante::firstOrCreate(
            ['telefono' => $telefonoLimpio],
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
            return back()
                ->withCookie(cookie('participante_token', $participante->token, 525600)) // Guardar cookie igual
                ->with('error', "No tienes suficientes Fichas Infinity. Necesitas $costoTotal pero tienes {$participante->saldo_fichas}.");
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

        return redirect()->route('tienda.gracias', ['token' => $participante->token, 'j' => $jugada->id])
                         ->withCookie(cookie('participante_token', $participante->token, 525600)); // Cookie dura 1 año
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
        $telefonoRaw = $request->query('t');
        $prefijo = $request->query('prefijo', '');
        
        if(!$telefonoRaw) {
            return redirect()->route('tienda.show', 1)->with('error', 'Debes ingresar tu teléfono para acceder al cajero.');
        }

        $telefonoCompleto = $prefijo . $telefonoRaw;
        $telefono = $this->limpiarTelefono($telefonoCompleto);

        // Si el usuario ingresa un teléfono pero es su primera vez en la vida (no existe en DB),
        // lo creamos automáticamente para que pueda fondear su cuenta antes de comprar cartones.
        $participante = PruebaParticipante::firstOrCreate(
            ['telefono' => $telefono],
            [
                'nombre' => 'Jugador ' . rand(1000, 9999), // Nombre genérico que puede cambiar después
                'token' => (string) Str::uuid(),
                'codigo_acceso' => strtoupper(Str::random(6)),
                'saldo_fichas' => 0,
                'es_prueba' => false
            ]
        );

        return response(view('tienda.cajero', compact('participante')))
               ->withCookie(cookie('participante_token', $participante->token, 525600));
    }

    public function cajeroProcesar(Request $request)
    {
        $request->validate([
            'telefono' => 'required|string',
            'metodo_pago' => 'required|in:mp,prex_ar,prex_uy,airtm,arq',
            'paquete_fichas' => 'required|integer|in:500,1000,5000'
        ]);

        $telefono = $this->limpiarTelefono($request->telefono);
        $participante = PruebaParticipante::where('telefono', $telefono)->firstOrFail();
        
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
        return redirect()->route('tienda.show', 1)
               ->withCookie(cookie('participante_token', $participante->token, 525600))
               ->with('success', 'Transacción creada. Por favor, realiza la transferencia con tu método elegido y guarda tu comprobante. Nuestro equipo lo verificará en breve.');
    }

    private function procesarMercadoPago($transaccion, $participante)
    {
        // MODO DEMO / SIMULACIÓN: Saltamos la API real de MercadoPago para no frenar las pruebas
        $fakePaymentId = rand(100000000, 999999999);
        
        $transaccion->detalles_adicionales = "MOCK_PREF_" . rand(1000, 9999);
        $transaccion->save();

        // Redirigimos automáticamente a nuestra propia ruta de éxito simulando que MercadoPago aprobó el pago
        return redirect()->route('cajero.mp_success', [
            'payment_id' => $fakePaymentId,
            'status' => 'approved',
            'external_reference' => $transaccion->id
        ])->with('success_mock', '¡Simulación de MercadoPago exitosa! (No se usó dinero real)');
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
                    ->withCookie(cookie('participante_token', $participante->token, 525600))
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
