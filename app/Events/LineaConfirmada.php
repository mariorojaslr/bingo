<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LineaConfirmada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $jugadaId;
    public int $cartonId;

    public function __construct(int $jugadaId, int $cartonId)
    {
        $this->jugadaId = $jugadaId;
        $this->cartonId = $cartonId;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('jugada.' . $this->jugadaId);
    }

    public function broadcastAs(): string
    {
        return 'linea.confirmada';
    }
}
