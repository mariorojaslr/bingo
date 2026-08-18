<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PruebaParticipante;
use App\Services\RouletteService;
use Illuminate\Support\Facades\Log;

class RouletteController extends Controller
{
    protected $rouletteService;

    public function __construct(RouletteService $rouletteService)
    {
        $this->rouletteService = $rouletteService;
    }

    /**
     * Cargar la vista principal de la ruleta.
     */
    public function index(Request $request)
    {
        $token = $request->cookie('participante_token');
        $participanteLogueado = PruebaParticipante::where('token', $token)->first();

        // Obtener historial de números ganadores desde la sesión o usar un mock inicial
        $history = session('roulette_history', []);

        return view('casino.ruleta', compact('participanteLogueado', 'history'));
    }

    /**
     * Procesar una tirada (spin).
     * Recibe las apuestas vía AJAX.
     */
    public function spin(Request $request)
    {
        $token = $request->cookie('participante_token');
        $participante = PruebaParticipante::where('token', $token)->first();

        if (!$participante) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión expirada o no iniciada. Por favor recarga la página.'
            ], 401);
        }

        // Recuperar apuestas del request
        // Formato esperado: [['type' => 'straight', 'value' => 15, 'amount' => 10], ...]
        $bets = $request->input('bets', []);
        
        if (empty($bets)) {
            return response()->json([
                'success' => false,
                'message' => 'No se han colocado apuestas.'
            ], 400);
        }

        // Calcular total apostado
        $totalBet = 0;
        foreach ($bets as $bet) {
            $totalBet += floatval($bet['amount'] ?? 0);
        }

        // Validar saldo
        if ($participante->saldo_fichas < $totalBet) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo insuficiente para realizar esta apuesta.'
            ], 400);
        }

        // Descontar saldo inmediatamente (bloqueo)
        $participante->saldo_fichas -= $totalBet;
        $participante->save();

        try {
            // Calcular resultado usando el servicio
            $result = $this->rouletteService->spinAndCalculate($bets);

            // Sumar ganancias al saldo
            if ($result['totalWon'] > 0) {
                $participante->saldo_fichas += $result['totalWon'];
                $participante->save();
            }

            // Guardar el número ganador en el historial de sesión (máximo 10)
            $history = session('roulette_history', []);
            array_unshift($history, [
                'number' => $result['winningNumber'],
                'color' => $result['isRed'] ? 'red' : ($result['isBlack'] ? 'black' : 'green')
            ]);
            $history = array_slice($history, 0, 10);
            session(['roulette_history' => $history]);

            return response()->json([
                'success' => true,
                'winningNumber' => $result['winningNumber'],
                'isRed' => $result['isRed'],
                'isBlack' => $result['isBlack'],
                'isEven' => $result['isEven'],
                'isOdd' => $result['isOdd'],
                'totalBet' => $result['totalBet'],
                'totalWon' => $result['totalWon'],
                'netProfit' => $result['netProfit'],
                'winningBets' => $result['winningBets'],
                'newBalance' => $participante->saldo_fichas,
                'history' => $history
            ]);

        } catch (\Exception $e) {
            Log::error('Error en Ruleta spin: ' . $e->getMessage());
            
            // Revertir descuento de saldo en caso de error crítico interno
            $participante->saldo_fichas += $totalBet;
            $participante->save();

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al procesar la jugada. Se ha devuelto el saldo.'
            ], 500);
        }
    }
}
