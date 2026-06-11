<?php

use App\Models\User;
use App\Models\Comment;
use App\Notifications\NewCommentNotification;

test('it returns correct notification channels based on user role', function () {

    $comment = new Comment(['content' => 'Great article!']);
    $notification = new NewCommentNotification($comment);

    $writer = new User(['role' => 'writer']);
    $admin = new User(['role' => 'admin']);

    expect($notification->via($writer))->toBe(['mail']);

    expect($notification->via($admin))->toBe(['database']);
});