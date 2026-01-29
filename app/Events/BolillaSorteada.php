<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BolillaSorteada implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $jugadaId;
    public int $bolilla;
    public array $ultimas;

    public function __construct(int $jugadaId, int $bolilla, array $ultimas)
    {
        $this->jugadaId = $jugadaId;
        $this->bolilla = $bolilla;
        $this->ultimas = $ultimas;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('jugada.' . $this->jugadaId);
    }

    public function broadcastAs(): string
    {
        return 'BolillaSorteada'; // EXACTO lo que escucha JS
    }
}
