<?php

use App\Models\User;
use App\Models\Article;
use App\Jobs\SendArticlePublishedEmailJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\ArticlePublishedMail;

uses(RefreshDatabase::class);

test('admin publishing an article pushes confirmation job to queue', function () {
    Queue::fake();

    $admin = User::factory()->create(['role' => 'admin']);
    $writer = User::factory()->create(['role' => 'writer']);
    
    $article = Article::factory()->create([
        'status' => 'draft',
        'user_id' => $writer->id
    ]);

    $response = $this->actingAs($admin)
                     ->patchJson("/api/admin/articles/{$article->id}/status", [
                         'status' => 'published'
                     ]);

    $response->assertStatus(200);

    Queue::assertPushed(SendArticlePublishedEmailJob::class, function ($job) use ($article) {
        return $job->article->id === $article->id;
    });
});

test('no job is pushed to queue if article is already published', function () {
    Queue::fake();

    $admin = User::factory()->create(['role' => 'admin']);
    $writer = User::factory()->create(['role' => 'writer']);
    
    $article = Article::factory()->create([
        'status' => 'published',
        'user_id' => $writer->id
    ]);

    $response = $this->actingAs($admin)
                     ->patchJson("/api/admin/articles/{$article->id}/status", [
                         'status' => 'published'
                     ]);

    Queue::assertNotPushed(SendArticlePublishedEmailJob::class);
});
test('send article published email job dispatches the actual email', function () {
    // 1. Fake the Mail facade to prevent real emails from sending
    Mail::fake();

    $writer = User::factory()->create(['role' => 'writer']);
    $article = Article::factory()->create([
        'status' => 'published',
        'user_id' => $writer->id
    ]);

    // 2. Act: Instantiate the Job manually and execute its handle method
    $job = new SendArticlePublishedEmailJob($article);
    $job->handle();

    // 3. Assert: Verify that the exact Mailable was sent to the correct writer
    Mail::assertSent(ArticlePublishedMail::class, function ($mail) use ($writer, $article) {
        return $mail->hasTo($writer->email) && $mail->article->id === $article->id;
    });
});