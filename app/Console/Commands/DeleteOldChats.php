<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Chat;
use App\Models\Message;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DeleteOldChats extends Command
{
    protected $signature = 'cleanup:old-chats';
    protected $description = 'Delete chats, messages, and media older than 7 days';

    public function handle()
    {
        $thresholdDate = Carbon::now()->subDays(7);

        // Get chats with last update older than 7 days
        $oldChats = Chat::where('updated_at', '<', $thresholdDate)->get();
        $totalChats = $oldChats->count();
        $totalMessages = 0;
        $totalMedia = 0;

        foreach ($oldChats as $chat) {
            foreach ($chat->messages as $message) {
                // Delete associated media
                foreach ($message->getMedia('msg-media') as $media) {
                    $media->delete();
                    $totalMedia++;
                }

                $message->delete();
                $totalMessages++;
            }

            $chat->delete();
        }

        $this->info("✅ Deleted $totalChats chats, $totalMessages messages, $totalMedia media files older than 7 days.");
    }

    // public function handle()
    // {
    //     $days = 7;

    //     $oldMedia = Media::where('created_at', '<', now()->subDays($days))->get();

    //     foreach ($oldMedia as $media) {
    //         Storage::disk($media->disk)->deleteDirectory($media->id); // delete folder
    //         $media->delete(); // delete DB entry
    //     }

    //     $this->info("Deleted {$oldMedia->count()} old media files.");
    // }
}
