<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PruebaParticipante;
use App\Models\BlackjackTable;
use App\Models\BlackjackSeat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BlackjackController extends Controller
{
    private function generateDeck()
    {
        $suits = ['hearts', 'diamonds', 'clubs', 'spades'];
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
        $deck = [];

        // 6 Decks for a shoe
        for ($i=0; $i<6; $i++) {
            foreach ($suits as $suit) {
                foreach ($values as $value) {
                    $deck[] = ['suit' => $suit, 'value' => $value];
                }
            }
        }
        shuffle($deck);
        return $deck;
    }

    private function calculateHandValue($hand)
    {
        if (!$hand) return 0;
        $value = 0;
        $aces = 0;

        foreach ($hand as $card) {
            if (in_array($card['value'], ['J', 'Q', 'K'])) {
                $value += 10;
            } elseif ($card['value'] === 'A') {
                $aces += 1;
                $value += 11;
            } else {
                $value += (int)$card['value'];
            }
        }

        while ($value > 21 && $aces > 0) {
            $value -= 10;
            $aces -= 1;
        }

        return $value;
    }

    public function index(Request $request)
    {
        $token = $request->cookie('participante_token');
        $participanteLogueado = null;
        if ($token) {
            $participanteLogueado = PruebaParticipante::where('token', $token)->first();
        }

        // Obtener o crear Mesa 1
        $table = BlackjackTable::firstOrCreate(
            ['name' => 'Mesa Principal'],
            ['status' => 'waiting_bets']
        );

        // Ensure 5 seats exist
        for ($i = 1; $i <= 5; $i++) {
            BlackjackSeat::firstOrCreate([
                'blackjack_table_id' => $table->id,
                'seat_number' => $i
            ]);
        }

        return view('casino.blackjack.multiplayer', compact('participanteLogueado', 'table'));
    }

    public function state(Request $request, $id)
    {
        $table = BlackjackTable::with('seats.participante')->findOrFail($id);
        
        // Hide dealer's second card if playing
        $dealerHand = $table->dealer_hand;
        if ($table->status === 'playing' && count($dealerHand ?? []) == 2) {
            $dealerHand[1] = ['suit' => 'hidden', 'value' => 'hidden'];
        }

        return response()->json([
            'table' => [
                'id' => $table->id,
                'status' => $table->status,
                'dealer_hand' => $dealerHand,
                'dealer_value' => $table->status === 'dealer_turn' || $table->status === 'finished' ? $this->calculateHandValue($table->dealer_hand) : null,
                'current_turn_seat' => $table->current_turn_seat
            ],
            'seats' => $table->seats->map(function($seat) {
                return [
                    'id' => $seat->id,
                    'seat_number' => $seat->seat_number,
                    'status' => $seat->status,
                    'bet_amount' => $seat->bet_amount,
                    'hand' => $seat->hand,
                    'hand_value' => $this->calculateHandValue($seat->hand),
                    'result' => $seat->result,
                    'payout' => $seat->payout,
                    'player' => $seat->participante ? [
                        'id' => $seat->participante->id,
                        'name' => $seat->participante->nombre,
                        'saldo' => $seat->participante->saldo_fichas
                    ] : null
                ];
            })
        ]);
    }

    public function sit(Request $request, $tableId, $seatNumber)
    {
        $token = $request->cookie('participante_token');
        $participante = PruebaParticipante::where('token', $token)->first();
        if (!$participante) return response()->json(['error' => 'No autorizado'], 401);

        $seat = BlackjackSeat::where('blackjack_table_id', $tableId)->where('seat_number', $seatNumber)->firstOrFail();
        
        if ($seat->participante_id) {
            return response()->json(['error' => 'Asiento ocupado'], 400);
        }

        // Remove from other seats on this table
        BlackjackSeat::where('blackjack_table_id', $tableId)
            ->where('participante_id', $participante->id)
            ->update(['participante_id' => null, 'status' => 'empty', 'bet_amount' => 0, 'hand' => null]);

        $seat->participante_id = $participante->id;
        $seat->status = 'waiting';
        $seat->save();

        return response()->json(['success' => true]);
    }

    public function leave(Request $request, $tableId)
    {
        $token = $request->cookie('participante_token');
        $participante = PruebaParticipante::where('token', $token)->first();
        if (!$participante) return response()->json(['error' => 'No autorizado'], 401);

        BlackjackSeat::where('blackjack_table_id', $tableId)
            ->where('participante_id', $participante->id)
            ->whereIn('status', ['waiting', 'empty'])
            ->update(['participante_id' => null, 'status' => 'empty', 'bet_amount' => 0]);

        return response()->json(['success' => true]);
    }

    public function bet(Request $request, $tableId)
    {
        $token = $request->cookie('participante_token');
        $participante = PruebaParticipante::where('token', $token)->first();
        if (!$participante) return response()->json(['error' => 'No autorizado'], 401);

        $amount = (float) $request->input('amount', 0);
        if ($amount <= 0) return response()->json(['error' => 'Apuesta inválida'], 400);

        if ($participante->saldo_fichas < $amount) {
            return response()->json(['error' => 'Saldo insuficiente'], 400);
        }

        $table = BlackjackTable::findOrFail($tableId);
        if ($table->status !== 'waiting_bets') {
            return response()->json(['error' => 'No se pueden hacer apuestas ahora'], 400);
        }

        $seat = BlackjackSeat::where('blackjack_table_id', $tableId)
            ->where('participante_id', $participante->id)
            ->first();

        if (!$seat) return response()->json(['error' => 'No estás sentado'], 400);

        DB::beginTransaction();
        try {
            $participante->saldo_fichas -= $amount;
            $participante->save();

            $seat->bet_amount += $amount;
            $seat->status = 'betting';
            $seat->save();

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error interno'], 500);
        }
    }

    public function deal(Request $request, $tableId)
    {
        // En una app real esto sería un CRON o WebSocket timer.
        // Aquí permitimos que el primero que le de a "Repartir" inicie.
        $table = BlackjackTable::findOrFail($tableId);
        if ($table->status !== 'waiting_bets') return response()->json(['error' => 'Ya está en curso'], 400);

        $bettingSeats = BlackjackSeat::where('blackjack_table_id', $tableId)->where('status', 'betting')->orderBy('seat_number')->get();
        if ($bettingSeats->count() === 0) return response()->json(['error' => 'No hay apuestas'], 400);

        $deck = $this->generateDeck();
        
        // Deal 2 cards to each player, 2 to dealer
        foreach ($bettingSeats as $seat) {
            $seat->hand = [array_pop($deck), array_pop($deck)];
            $seat->status = 'playing';
            
            // Check blackjack
            if ($this->calculateHandValue($seat->hand) === 21) {
                $seat->status = 'stood'; // Natural blackjack stands automatically
            }
            $seat->save();
        }

        $table->dealer_hand = [array_pop($deck), array_pop($deck)];
        $table->deck = $deck;
        $table->status = 'playing';
        
        // Find first active player
        $firstSeat = BlackjackSeat::where('blackjack_table_id', $tableId)->where('status', 'playing')->orderBy('seat_number')->first();
        if ($firstSeat) {
            $table->current_turn_seat = $firstSeat->seat_number;
        } else {
            // Everyone got blackjack? Jump to dealer
            $table->status = 'dealer_turn';
            $table->current_turn_seat = null;
        }

        $table->save();

        if ($table->status === 'dealer_turn') {
            $this->processDealerTurn($table);
        }

        return response()->json(['success' => true]);
    }

    public function hit(Request $request, $tableId)
    {
        return $this->processPlayerAction($request, $tableId, 'hit');
    }

    public function stand(Request $request, $tableId)
    {
        return $this->processPlayerAction($request, $tableId, 'stand');
    }

    private function processPlayerAction(Request $request, $tableId, $action)
    {
        $token = $request->cookie('participante_token');
        $participante = PruebaParticipante::where('token', $token)->first();
        if (!$participante) return response()->json(['error' => 'No autorizado'], 401);

        $table = BlackjackTable::findOrFail($tableId);
        if ($table->status !== 'playing') return response()->json(['error' => 'Turno incorrecto'], 400);

        $seat = BlackjackSeat::where('blackjack_table_id', $tableId)
            ->where('seat_number', $table->current_turn_seat)
            ->first();

        if (!$seat || $seat->participante_id !== $participante->id) {
            return response()->json(['error' => 'No es tu turno'], 400);
        }

        $deck = $table->deck;

        if ($action === 'hit') {
            $hand = $seat->hand;
            $hand[] = array_pop($deck);
            $seat->hand = $hand;
            
            if ($this->calculateHandValue($hand) >= 21) {
                $seat->status = $this->calculateHandValue($hand) == 21 ? 'stood' : 'busted';
                $this->advanceTurn($table);
            }
        } elseif ($action === 'stand') {
            $seat->status = 'stood';
            $this->advanceTurn($table);
        }

        $seat->save();
        $table->deck = $deck;
        $table->save();

        if ($table->status === 'dealer_turn') {
            $this->processDealerTurn($table);
        }

        return response()->json(['success' => true]);
    }

    private function advanceTurn(BlackjackTable $table)
    {
        // Find next seat that is 'playing'
        $nextSeat = BlackjackSeat::where('blackjack_table_id', $table->id)
            ->where('status', 'playing')
            ->where('seat_number', '>', $table->current_turn_seat)
            ->orderBy('seat_number')
            ->first();

        if ($nextSeat) {
            $table->current_turn_seat = $nextSeat->seat_number;
        } else {
            $table->current_turn_seat = null;
            $table->status = 'dealer_turn';
        }
    }

    private function processDealerTurn(BlackjackTable $table)
    {
        $deck = $table->deck;
        $dealerHand = $table->dealer_hand;

        // Check if all players busted
        $allBusted = !BlackjackSeat::where('blackjack_table_id', $table->id)
            ->where('status', 'stood')
            ->exists();

        if (!$allBusted) {
            while ($this->calculateHandValue($dealerHand) < 17) {
                $dealerHand[] = array_pop($deck);
            }
        }

        $dealerValue = $this->calculateHandValue($dealerHand);
        $table->dealer_hand = $dealerHand;
        $table->deck = $deck;
        $table->status = 'finished';
        $table->save();

        // Calculate payouts
        $seats = BlackjackSeat::where('blackjack_table_id', $table->id)
            ->whereIn('status', ['stood', 'busted'])
            ->get();

        DB::beginTransaction();
        try {
            foreach ($seats as $seat) {
                $playerValue = $this->calculateHandValue($seat->hand);
                $participante = $seat->participante;

                if ($seat->status === 'busted') {
                    $seat->result = 'loss';
                    $seat->payout = 0;
                } else {
                    if ($dealerValue > 21 || $playerValue > $dealerValue) {
                        // Win
                        // Check if natural blackjack (2 cards = 21)
                        if ($playerValue == 21 && count($seat->hand) == 2) {
                            $seat->result = 'blackjack';
                            $seat->payout = $seat->bet_amount + ($seat->bet_amount * 1.5);
                        } else {
                            $seat->result = 'win';
                            $seat->payout = $seat->bet_amount * 2;
                        }
                    } elseif ($playerValue < $dealerValue) {
                        $seat->result = 'loss';
                        $seat->payout = 0;
                    } else {
                        // Push
                        $seat->result = 'push';
                        $seat->payout = $seat->bet_amount;
                    }
                }

                if ($seat->payout > 0 && $participante) {
                    $participante->saldo_fichas += $seat->payout;
                    $participante->save();
                }

                $seat->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error procesando pagos de blackjack: " . $e->getMessage());
        }

        // Setup clear timeout via AJAX later, for now we will add an endpoint to clear
    }

    public function clear(Request $request, $tableId)
    {
        $table = BlackjackTable::findOrFail($tableId);
        if ($table->status === 'finished') {
            $table->status = 'waiting_bets';
            $table->dealer_hand = null;
            $table->deck = null;
            $table->current_turn_seat = null;
            $table->save();

            BlackjackSeat::where('blackjack_table_id', $tableId)
                ->where('status', '!=', 'empty')
                ->update([
                    'status' => 'waiting',
                    'bet_amount' => 0,
                    'hand' => null,
                    'result' => null,
                    'payout' => 0
                ]);
        }
        return response()->json(['success' => true]);
    }
}
