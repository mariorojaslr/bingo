<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JuegoReiniciado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $jugadaId;

    public function __construct(int $jugadaId)
    {
        $this->jugadaId = $jugadaId;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('jugada.' . $this->jugadaId);
    }

    public function broadcastAs(): string
    {
        return 'juego.reiniciado';
    }
}
