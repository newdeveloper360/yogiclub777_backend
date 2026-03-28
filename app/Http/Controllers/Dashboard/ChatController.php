<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use App\Notifications\ChatNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::with('user')->latest('updated_at')->get();
        return view("dashboard.chats.index", compact('chats'));
    }

    public function getChat(Chat $chat)
    {
        $chat->load(['user', 'messages']);

        $chat->messages()
            ->whereNot('user_id', auth()->id())
            ->unreadMessages()->update(['is_read' => true]);

        return response()->success('Chat get successfully.', [
            'chat' => $chat,
            'messages' => $chat->messages
        ]);
    }

    public function sendMessage(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required_without:file|string|max:255',
            'file' => 'required_without:message|file|mimetypes:image/*,audio/*,video/*,application/pdf|max:50000',
        ]);

        $message = $chat->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message ?? '',
        ]);

        // Send chat notification
        $user = User::findOrFail($chat->user_id);
        $oneSignalsubscriptionId  = $user->one_signalsubscription_id;
        if($user->role == 'user' && $oneSignalsubscriptionId != null) {
            $user->notify(new ChatNotification($request->message, $user->fcm, $oneSignalsubscriptionId));
        }

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

        return response()->success("Sent chat success.", [
            'isSent' => true,
            'message' => $message
        ]);
    }

    public function readMessage(Chat $chat)
    {
        $chat->messages()->update(['is_read' => true]);
        return response()->success("Message Read successfully.", [
            'isRead' => true,
        ]);
    }

    public function unreadChatsCount()
    {
        $countUnreadChats = Chat::whereHas('messages', function ($query) {
            $query->whereNot('user_id', auth()->id())
                ->where('is_read', false);
        })
            ->whereNot('user_id', auth()->id())
            ->count();
        return response()->success("Unread Chats counts.", [
            'countUnreadChats' => $countUnreadChats,
        ]);
    }
}
