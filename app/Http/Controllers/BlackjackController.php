<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PruebaParticipante;
use App\Models\BlackjackGame;
use Illuminate\Support\Facades\DB;

class BlackjackController extends Controller
{
    private $suits = ['H', 'D', 'C', 'S'];
    private $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

    public function index(Request $request)
    {
        $participanteLogueado = null;
        $token = $request->cookie('participante_token');
        if ($token) {
            $participanteLogueado = PruebaParticipante::where('token', $token)->first();
        }

        // Recuperar juego activo si existe
        $activeGame = null;
        if ($participanteLogueado) {
            $activeGame = BlackjackGame::where('participante_id', $participanteLogueado->id)
                ->whereIn('estado', ['playing', 'dealer_turn'])
                ->first();
        }

        return view('casino.blackjack', compact('participanteLogueado', 'activeGame'));
    }

    private function generateDeck($numDecks = 6)
    {
        $deck = [];
        for ($d = 0; $d < $numDecks; $d++) {
            foreach ($this->suits as $suit) {
                foreach ($this->values as $value) {
                    $deck[] = ['suit' => $suit, 'value' => $value];
                }
            }
        }
        shuffle($deck);
        return $deck;
    }

    private function calculateHandValue($hand)
    {
        $value = 0;
        $aces = 0;

        foreach ($hand as $card) {
            if (in_array($card['value'], ['J', 'Q', 'K'])) {
                $value += 10;
            } elseif ($card['value'] === 'A') {
                $aces += 1;
                $value += 11;
            } else {
                $value += intval($card['value']);
            }
        }

        while ($value > 21 && $aces > 0) {
            $value -= 10;
            $aces -= 1;
        }

        return $value;
    }

    public function bet(Request $request)
    {
        $token = $request->cookie('participante_token');
        $participante = PruebaParticipante::where('token', $token)->first();

        if (!$participante) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $betAmount = floatval($request->input('amount', 0));

        if ($betAmount <= 0 || $participante->saldo_fichas < $betAmount) {
            return response()->json(['error' => 'Saldo insuficiente'], 400);
        }

        // Cancelar juegos previos no terminados
        BlackjackGame::where('participante_id', $participante->id)
            ->whereIn('estado', ['playing', 'dealer_turn'])
            ->update(['estado' => 'finished', 'result' => 'loss']);

        DB::beginTransaction();
        try {
            $participante->saldo_fichas -= $betAmount;
            $participante->save();

            $deck = $this->generateDeck();
            $playerHand = [array_pop($deck), array_pop($deck)];
            $dealerHand = [array_pop($deck), array_pop($deck)];

            $game = BlackjackGame::create([
                'empresa_id' => $participante->empresa_id ?? null,
                'participante_id' => $participante->id,
                'estado' => 'playing',
                'bet_amount' => $betAmount,
                'deck' => $deck,
                'player_hand' => $playerHand,
                'dealer_hand' => $dealerHand,
            ]);

            // Comprobar Blackjack natural
            $playerValue = $this->calculateHandValue($playerHand);
            $dealerValue = $this->calculateHandValue($dealerHand);

            if ($playerValue == 21) {
                if ($dealerValue == 21) {
                    $game->estado = 'finished';
                    $game->result = 'push';
                    $participante->saldo_fichas += $betAmount; // Devolver apuesta
                } else {
                    $game->estado = 'finished';
                    $game->result = 'blackjack';
                    $participante->saldo_fichas += $betAmount + ($betAmount * 1.5); // Paga 3 a 2
                }
                $participante->save();
                $game->save();
            }

            DB::commit();

            return response()->json([
                'game_id' => $game->id,
                'player_hand' => $game->player_hand,
                'dealer_hand' => [['suit' => $game->dealer_hand[0]['suit'], 'value' => $game->dealer_hand[0]['value']], ['suit' => 'hidden', 'value' => 'hidden']], // Ocultar segunda carta
                'player_value' => $playerValue,
                'estado' => $game->estado,
                'result' => $game->result,
                'saldo' => $participante->saldo_fichas
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al procesar la apuesta'], 500);
        }
    }

    public function hit(Request $request)
    {
        $token = $request->cookie('participante_token');
        $participante = PruebaParticipante::where('token', $token)->first();

        if (!$participante) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $game = BlackjackGame::where('participante_id', $participante->id)
            ->where('estado', 'playing')
            ->first();

        if (!$game) {
            return response()->json(['error' => 'No hay juego activo'], 400);
        }

        $deck = $game->deck;
        $playerHand = $game->player_hand;
        
        $playerHand[] = array_pop($deck);
        $playerValue = $this->calculateHandValue($playerHand);

        $game->deck = $deck;
        $game->player_hand = $playerHand;

        if ($playerValue > 21) {
            $game->estado = 'finished';
            $game->result = 'loss';
        }

        $game->save();

        return response()->json([
            'player_hand' => $game->player_hand,
            'player_value' => $playerValue,
            'estado' => $game->estado,
            'result' => $game->result
        ]);
    }

    public function stand(Request $request)
    {
        $token = $request->cookie('participante_token');
        $participante = PruebaParticipante::where('token', $token)->first();

        if (!$participante) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $game = BlackjackGame::where('participante_id', $participante->id)
            ->where('estado', 'playing')
            ->first();

        if (!$game) {
            return response()->json(['error' => 'No hay juego activo'], 400);
        }

        $game->estado = 'dealer_turn';
        $game->save();

        return $this->playDealer($game, $participante);
    }

    public function doubleDown(Request $request)
    {
        $token = $request->cookie('participante_token');
        $participante = PruebaParticipante::where('token', $token)->first();

        if (!$participante) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $game = BlackjackGame::where('participante_id', $participante->id)
            ->where('estado', 'playing')
            ->first();

        if (!$game) {
            return response()->json(['error' => 'No hay juego activo'], 400);
        }

        if (count($game->player_hand) !== 2) {
            return response()->json(['error' => 'Solo puedes doblar con las 2 cartas iniciales'], 400);
        }

        if ($participante->saldo_fichas < $game->bet_amount) {
            return response()->json(['error' => 'Saldo insuficiente para doblar'], 400);
        }

        DB::beginTransaction();
        try {
            $participante->saldo_fichas -= $game->bet_amount;
            $participante->save();

            $game->bet_amount *= 2;
            $deck = $game->deck;
            $playerHand = $game->player_hand;
            
            $playerHand[] = array_pop($deck);
            $playerValue = $this->calculateHandValue($playerHand);

            $game->deck = $deck;
            $game->player_hand = $playerHand;

            if ($playerValue > 21) {
                $game->estado = 'finished';
                $game->result = 'loss';
                $game->save();
                DB::commit();
                
                return response()->json([
                    'player_hand' => $game->player_hand,
                    'player_value' => $playerValue,
                    'estado' => $game->estado,
                    'result' => $game->result,
                    'saldo' => $participante->saldo_fichas,
                    'bet_amount' => $game->bet_amount
                ]);
            }

            $game->estado = 'dealer_turn';
            $game->save();
            DB::commit();

            return $this->playDealer($game, $participante);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al doblar apuesta'], 500);
        }
    }

    private function playDealer(BlackjackGame $game, PruebaParticipante $participante)
    {
        $deck = $game->deck;
        $dealerHand = $game->dealer_hand;
        $playerValue = $this->calculateHandValue($game->player_hand);

        while ($this->calculateHandValue($dealerHand) < 17) {
            $dealerHand[] = array_pop($deck);
        }

        $dealerValue = $this->calculateHandValue($dealerHand);

        if ($dealerValue > 21 || $playerValue > $dealerValue) {
            $game->result = 'win';
            $participante->saldo_fichas += $game->bet_amount * 2; // Paga 1 a 1 (devuelve apuesta + ganancia)
            $participante->save();
        } elseif ($playerValue < $dealerValue) {
            $game->result = 'loss';
        } else {
            $game->result = 'push';
            $participante->saldo_fichas += $game->bet_amount; // Devuelve apuesta
            $participante->save();
        }

        $game->deck = $deck;
        $game->dealer_hand = $dealerHand;
        $game->estado = 'finished';
        $game->save();

        return response()->json([
            'dealer_hand' => $game->dealer_hand,
            'dealer_value' => $dealerValue,
            'player_value' => $playerValue,
            'estado' => $game->estado,
            'result' => $game->result,
            'saldo' => $participante->saldo_fichas,
            'bet_amount' => $game->bet_amount
        ]);
    }
}
