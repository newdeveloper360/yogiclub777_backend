<?php

namespace App\Http\Controllers\Api\Chat;


use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Services\ChatService;
use Illuminate\Http\Request;

class WithdrawChatController extends Controller
{
    public function get()
    {
        /** @var User $user */
        $user = auth()->user();

        $chat = $user->chats()
            ->withdrawChat()
            ->with('messages')
            ->firstOrCreate(['type' => 'withdraw_chat']);

        return response()->success("Get all withdraw chat.", [
            'chat' => $chat,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required_without:file|string|max:255',
            'file' => 'required_without:message|file|mimetypes:image/*,audio/*,video/*,application/pdf|max:50000',
            // 'file' => 'required_without:message|mimetypes:text/plain,image/jpeg,image/png,image/gif,application/pdf,audio/mpeg,audio/webm,audio/wav,video/mp4|max:50000'
        ]);
        ChatService::sendMessage('withdraw_chat', $request);

        // Get the last message
        $lastMessage = Message::where('user_id', auth()->id())->latest()->first();

        return response()->success("Sent chat success.", [
            'isSent' => true,
            'message' => $lastMessage,
        ]);
    }

    public function getUnreadMessagesCount()
    {
        /** @var User $user */
        $user = auth()->user();

        $unreadMessagesCount = $user
            ->chats()
            ->withdrawChat()
            ->first()
            ?->messages()
            ->unreadMessages()
            ->count();

        return response()->success("Get unread messages count.", [
            'unreadMessagesCount' => $unreadMessagesCount
        ]);
    }
}
