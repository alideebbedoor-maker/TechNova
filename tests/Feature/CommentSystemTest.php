<?php

use App\Models\User;
use App\Models\Article;
use App\Notifications\NewCommentNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('reader can comment and notify author via v1', function () {
    $this->withoutExceptionHandling();

    Notification::fake();

    $writer = User::factory()->create(['role' => 'writer']);
    $reader = User::factory()->create(['role' => 'reader']);
    $article = Article::factory()->create([
        'status' => 'published',
        'user_id' => $writer->id
    ]);

    $payload = [
        'article_id' => $article->id,
        'content'    => 'This is a precise test comment'
    ];

    $response = $this->actingAs($reader)
                     ->postJson("/api/v1/comments", $payload); 

    $response->assertStatus(201);

    Notification::assertSentTo([$writer], NewCommentNotification::class);
    Notification::assertNotSentTo([$reader], NewCommentNotification::class);
}); 

test('reader cannot comment on a draft article', function () {
    $writer = User::factory()->create(['role' => 'writer']);
    $reader = User::factory()->create(['role' => 'reader']);
    $article = Article::factory()->create([
        'status'  => 'draft',
        'user_id' => $writer->id
    ]);

    $payload = [
        'article_id' => $article->id,
        'content'    => 'This should fail'
    ];

    $response = $this->actingAs($reader)
                     ->postJson("/api/v1/comments", $payload); 

    $response->assertStatus(404);
});
test('notification channels logic based on author role', function () {
    $comment = new \App\Models\Comment(['body' => 'Test comment']);
    $notification = new NewCommentNotification($comment);

    $writer = User::factory()->make(['role' => 'writer']);
    $admin = User::factory()->make(['role' => 'admin']);

    expect($notification->via($writer))->toBe(['mail'])
        ->and($notification->via($admin))->toBe(['database']);
});