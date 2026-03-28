<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupPosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $groupPost;

    /**
     * Create a new event instance.
     */
    public function __construct($groupPost)
    {
        $this->groupPost = [
            'id' => $groupPost->id,
            'message' => $groupPost->message,
            'user' => [
                'id' => $groupPost->user->id,
                'name' => $groupPost->user->name,
            ],
            'created_at' => $groupPost->created_at->toDateTimeString(),
            'updated_at' => $groupPost->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // new PrivateChannel('group-post.' . $this->id),
            new Channel('group.posted'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'group.posted';
    }
}
