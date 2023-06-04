<?php

namespace App\Events;

use App\WhatsappMessages;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WhatsappEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $name;
    public $link;
    public $created;

    private $user_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user_id, WhatsappMessages $message)
    {
        $this->name = '';
        $this->link = '';
        $this->created = $message->created_at->diffForHumans();
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('messages.'.$this->user_id);
    }
}
