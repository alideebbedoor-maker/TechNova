<?php

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('writer can upload attachment to an article and it is saved as polymorphic relation', function () {
    Storage::fake('public');

    $writer = User::factory()->create(['role' => 'writer']);
    $article = Article::factory()->create(['user_id' => $writer->id]);

    Sanctum::actingAs($writer);

    $dummyFile = UploadedFile::fake()->create('report.pdf', 500);

    
    $response = $this->postJson("/api/articles/{$article->id}/attachments", [
        'file' => $dummyFile,
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('attachments', [
        'attachable_id'   => $article->id,
        'attachable_type' => Article::class, 
    ]);

    $attachment = $article->attachments()->first();
    Storage::disk('public')->assertExists($attachment->file_path);
});