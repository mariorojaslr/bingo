<?php

namespace App\Events;

use App\Models\Sorteo;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SorteoActualizado implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $jugadaId;
    public ?int $bolilla;
    public array $bolillas;
    public array $ultimas;
    public string $estado;

    public function __construct(Sorteo $sorteo)
    {
        $this->jugadaId = $sorteo->jugada_id;
        $this->bolilla  = $sorteo->bolilla_actual;
        $this->bolillas = $sorteo->getBolillas();
        $this->ultimas  = array_slice(array_reverse($this->bolillas), 0, 9);
        $this->estado   = $sorteo->estado;
    }

    public function broadcastOn()
    {
        return new Channel('jugada.' . $this->jugadaId);
    }

    public function broadcastAs()
    {
        return 'SorteoActualizado';
    }
}
