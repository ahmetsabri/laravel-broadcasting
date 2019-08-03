<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    
    public $to_id;
    public $username;
    public $msg;
    public $full_image_64 ;
    public function __construct($username,$to_id,$msg,$img=null)
    {
        $this->to_id = $to_id ;
        $this->msg = $msg  ;    
        $this->username = $username;
        $this->full_image_64=$img;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.'.$this->to_id);
    }
}
