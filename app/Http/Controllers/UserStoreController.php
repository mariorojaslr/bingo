<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jugada;
use App\Models\Carton;
use App\Models\PruebaParticipante;
use App\Models\ParticipanteCartonPrueba;
use Illuminate\Support\Str;
use App\Services\BingoCardService;

class UserStoreController extends Controller
{
    /**
     * Mostrar la Landing de Compra B2C
     */
    public function showTienda($jugadaId)
    {
        $jugada = Jugada::with('institucion', 'organizador')->findOrFail($jugadaId);
        return view('tienda.compra', compact('jugada'));
    }

    /**
     * Procesar el Ticket de Compra B2C (Generación Automática de Cartones en Demo)
     */
    public function procesarCompra(Request $request, $jugadaId)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
            'cantidad' => 'required|integer|min:1|max:4', // Regla de Juego (Max 4 cartones digital)
        ]);

        $jugada = Jugada::findOrFail($jugadaId);

        // Buscar o crear al usuario por teléfono
        $participante = PruebaParticipante::firstOrCreate(
            ['telefono' => $request->telefono],
            [
                'nombre' => mb_strtoupper($request->nombre),
                'token' => (string) Str::uuid(),
                'codigo_acceso' => strtoupper(Str::random(6)),
                'saldo_fichas' => 0, // Inicia sin fichas Infinity
                'es_prueba' => false // Por defecto, es jugador real de B2C
            ]
        );

        // Validar y descontar Fichas Infinity
        $precioPorCarton = 50; // Asumimos 50 Infinity por cartón
        $costoTotal = $request->cantidad * $precioPorCarton; 
        
        if ($participante->saldo_fichas < $costoTotal) {
            return back()->with('error', "No tienes suficientes Fichas Infinity. Necesitas $costoTotal pero tienes {$participante->saldo_fichas}.");
        }

        // Descontar saldo
        $participante->saldo_fichas -= $costoTotal;
        $participante->save();

        // Generar la cantidad de cartones 100% digitales On-The-Fly para esta jugada
        for($i = 0; $i < $request->cantidad; $i++) {
            $carton = new Carton();
            $carton->serie = 'DIGITAL-' . date('ym');
            $carton->numero_carton = mt_rand(100000, 999999);
            $carton->formato = 'digital_online';
            
            $bingoService = new BingoCardService();
            $carton->grilla = $bingoService->generarGrilla();
            
            $carton->estado = 'activo';
            $carton->es_prueba = $participante->es_prueba; // Hereda el estado de prueba
            $carton->save();

            // Asignarlo al jugador vinculado a esta jugada
            ParticipanteCartonPrueba::create([
                'participante_prueba_id' => $participante->id,
                'jugada_id' => $jugada->id,
                'carton_id' => $carton->id
            ]);
        }

        // Redirigir a la confirmación (Ticket Digital)
        return redirect()->route('tienda.gracias', ['token' => $participante->token, 'j' => $jugada->id]);
    }

    /**
     * Landing de Éxito y Ticket Digital con el Link Mágico
     */
    public function gracias(Request $request, $token)
    {
        $jugadaId = $request->query('j');
        $participante = PruebaParticipante::where('token', $token)->firstOrFail();
        $jugada = Jugada::find($jugadaId);

        // Cartones adquiridos hoy
        $comprados = ParticipanteCartonPrueba::where('participante_prueba_id', $participante->id)
            ->where('jugada_id', $jugadaId)
            ->count();

        return view('tienda.gracias', compact('participante', 'jugada', 'comprados'));
    }

    /**
     * Comprar Fichas Infinity (Demo: inyectar fichas gratis)
     */
    public function comprarFichas(Request $request)
    {
        $request->validate([
            'telefono' => 'required|string|max:50',
            'monto' => 'required|numeric|min:1'
        ]);

        $participante = PruebaParticipante::where('telefono', $request->telefono)->first();
        if ($participante) {
            $participante->saldo_fichas += $request->monto;
            $participante->save();
            return back()->with('success', "¡Has adquirido {$request->monto} Fichas Infinity con éxito!");
        }

        return back()->with('error', "Debes ingresar tu nombre y teléfono abajo primero antes de comprar fichas.");
    }

}
