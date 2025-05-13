<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JurnalMengajarBelumDibuat
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $guruId;
    public $pesan;
    /**
     * Create a new event instance.
     */
    public function __construct($guruId, $pesan)
    {
        $this->guruId = $guruId;
        $this->pesan = $pesan;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new PrivateChannel('notifikasi.' . $this->guruId);
    }

    public function broadcastAs()
    {
        return 'jurnal.belumdibuat';
    }
}
