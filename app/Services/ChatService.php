<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Events\NewChatCreated;

class ChatService
{
    public static function sendMessage($type, $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $chatFunction = match ($type) {
            'deposit_chat' => 'depositChat',
            'withdraw_chat' => 'withdrawChat',
        };

        $chat = $user->chats()->{$chatFunction}()
            ->firstOrCreate(['type' => $type]);

        if ($chat->wasRecentlyCreated) {
            event(new NewChatCreated($chat));
        }

        $message = $chat->messages()->create([
            'user_id' => $user->id,
            'message' => $request->message ?? '',
        ]);

        $chat->touch();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Determine the type based on the file's MIME type
            $mime = $file->getMimeType();
            if (strpos($mime, 'image') !== false) {
                $type = 'image';
            } elseif (strpos($mime, 'audio') !== false) {
                $type = 'audio';
            } elseif (strpos($mime, 'video') !== false) {
                $type = 'video';
            } elseif (strpos($mime, 'pdf') !== false) {
                $type = 'pdf';
            } else {
                return response()->json(['error' => 'Unsupported file type'], 400);
            }

            // Save the file and update the message
            $extension = $file->getClientOriginalExtension();
            $media = $message->addMedia($file)->toMediaCollection('msg-media');
            
            // Generate correct URL with /storage/app/public/ path
            $fileUrl = env('APP_URL') . '/storage/app/public/' . $media->id . '/' . $media->file_name;
            
            $message->update([
                'type' => $type,
                // 'file_url' => $media->getUrl(),
                'file_url' => $fileUrl,
                'file_type' => $extension,
            ]);
        }
        event(new MessageSent($chat, $message));
    }
}
