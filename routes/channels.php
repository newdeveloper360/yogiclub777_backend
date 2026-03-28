<?php

use App\Models\Chat;
use App\Models\GroupPosting;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/* Broadcast Channels */

Broadcast::channel('chats.{chatId}', function (User $user, int $chatId) {
    return $user->isAdmin() || Chat::findOrFail($chatId)->user_id == $user->id;
});

Broadcast::channel('group-post.{postId}', function (User $user, int $postId) {
    return $user->isUser() &&
        GroupPosting::findOrFail($postId)->user_id != $user->id;
});

//only for admin
Broadcast::channel('new-chat-created', function (User $user) {
    return $user->isAdmin();
});
