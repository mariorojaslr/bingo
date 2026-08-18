<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MegaSorteo;
use App\Models\MegaSorteoTicket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RunMegaSorteo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sorteo:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sortea el Mega Sorteo actual y genera uno nuevo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando proceso de Mega Sorteo...');

        // Buscar sorteo pendiente
        $sorteo = MegaSorteo::where('status', 'pending')->first();

        if (!$sorteo) {
            $this->warn('No hay sorteos pendientes.');
            return;
        }

        DB::beginTransaction();

        try {
            // Generar 6 números ganadores (0-45)
            $winningNumbers = [];
            while (count($winningNumbers) < 6) {
                $num = rand(0, 45);
                if (!in_array($num, $winningNumbers)) {
                    $winningNumbers[] = $num;
                }
            }
            sort($winningNumbers);

            $this->info('Números ganadores: ' . implode(', ', $winningNumbers));

            // Actualizar sorteo
            $sorteo->winning_numbers = $winningNumbers;
            $sorteo->status = 'drawn';
            
            $jackpot = $sorteo->accumulated_jackpot;
            $jackpotWon = false;
            $winnersCount = 0;

            // Procesar tickets
            $tickets = $sorteo->tickets()->with('participante')->get();
            
            foreach ($tickets as $ticket) {
                $hits = count(array_intersect($ticket->numbers, $winningNumbers));
                $ticket->hits = $hits;
                
                $wonAmount = 0;
                
                if ($hits === 6) {
                    // Jackpot
                    $wonAmount = $jackpot;
                    $jackpotWon = true;
                } elseif ($hits === 5) {
                    $wonAmount = 1000; // Premio fijo o porcentaje
                } elseif ($hits === 4) {
                    $wonAmount = 100;
                } elseif ($hits === 3) {
                    $wonAmount = $sorteo->ticket_price; // Recupera el ticket
                }

                $ticket->won_amount = $wonAmount;
                $ticket->save();

                // Pagar premio
                if ($wonAmount > 0 && $ticket->participante) {
                    $ticket->participante->saldo_fichas += $wonAmount;
                    $ticket->participante->save();
                    $winnersCount++;
                }
            }

            $sorteo->save();

            // Generar nuevo sorteo
            $newJackpot = $jackpotWon ? 100000 : $jackpot; // Base 100,000 si sale, sino se arrastra
            
            $nextSorteo = MegaSorteo::create([
                'empresa_id' => $sorteo->empresa_id,
                'draw_date' => now()->addMinutes(30), // Sorteos cada 30 min por ejemplo
                'status' => 'pending',
                'ticket_price' => $sorteo->ticket_price,
                'accumulated_jackpot' => $newJackpot
            ]);

            DB::commit();

            $this->info("Sorteo #{$sorteo->id} finalizado. $winnersCount ganadores.");
            if ($jackpotWon) {
                $this->info("¡JACKPOT GANADO!");
            }
            $this->info("Siguiente sorteo #{$nextSorteo->id} programado.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en RunMegaSorteo: ' . $e->getMessage());
            $this->error('Ocurrió un error al procesar el sorteo.');
        }
    }
}
