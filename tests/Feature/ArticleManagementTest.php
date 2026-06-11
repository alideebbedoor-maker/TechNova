<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_view_articles_list()
    {
        $response = $this->getJson('/api/v1/articles');

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_view_articles_list()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/v1/articles');

        $response->assertStatus(200);
    }

    /** @test */
    public function only_writer_can_create_article()
    {
        $reader = User::factory()->create(['role' => 'reader']);

        $response = $this->actingAs($reader)->postJson('/api/writer/articles', [
            'title'   => 'Test Article Title',
            'content' => 'This is a valid content with more than 100 characters. Lorem ipsum dolor sit amet.',
            'status'  => 'draft'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function writer_must_provide_required_fields()
    {
        $writer = User::factory()->create(['role' => 'writer']);

        $response = $this->actingAs($writer)->postJson('/api/writer/articles', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'content', 'status']);
    }

    /** @test */
    public function writer_can_create_article_and_it_is_saved_in_database()
    {
        $writer = User::factory()->create(['role' => 'writer']);

        $payload = [
            'title'   => 'Valid Article Title',
            'content' => str_repeat('A', 120),
            'status'  => 'draft'
        ];

        $response = $this->actingAs($writer)->postJson('/api/writer/articles', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('articles', [
            'title'   => 'Valid Article Title',
            'status' => 'pending_review',
            'user_id' => $writer->id
        ]);
    }
/** @test */
public function authenticated_user_can_view_single_article()
{
    $user = User::factory()->create();
    $article = Article::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->getJson("/api/v1/articles/{$article->id}");

    $response->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'id' => $article->id,
                     'title' => $article->title,
                     'content' => $article->content,
                 ]
             ]);
}

/** @test */
public function guest_cannot_view_single_article()
{
    $article = Article::factory()->create();

    $response = $this->getJson("/api/v1/articles/{$article->id}");

    $response->assertStatus(401);
}

/** @test */
public function returns_404_if_article_not_found()
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson("/api/v1/articles/999999");

    $response->assertStatus(404);
}

/** @test */
public function writer_can_update_his_own_article()
{
    $writer = User::factory()->create(['role' => 'writer']);

    $article = Article::factory()->create([
        'user_id' => $writer->id,
        'title' => 'Old Title',
        'content' => str_repeat('A', 120),
        'status' => 'draft'
    ]);

    $payload = [
        'title' => 'Updated Title',
        'content' => str_repeat('B', 150),
        'status' => 'draft'
    ];

    $response = $this->actingAs($writer)->putJson("/api/v1/articles/{$article->id}", $payload);

    $response->assertStatus(200);

    $this->assertDatabaseHas('articles', [
        'id' => $article->id,
        'title' => 'Updated Title',
    ]);
}
/** @test */
public function writer_cannot_update_someone_elses_article()
{
    $writer = User::factory()->create(['role' => 'writer']);
    $otherWriter = User::factory()->create(['role' => 'writer']);

    $article = Article::factory()->create([
        'user_id' => $otherWriter->id
    ]);

    $payload = [
        'title' => 'Hacked Title',
        'content' => str_repeat('X', 150),
        'status' => 'draft'
    ];

    $response = $this->actingAs($writer)->putJson("/api/v1/articles/{$article->id}", $payload);

    $response->assertStatus(403);
}
/** @test */
public function guest_cannot_update_article()
{
    $article = Article::factory()->create();

    $payload = [
        'title' => 'New Title',
        'content' => str_repeat('Y', 150),
        'status' => 'draft'
    ];

    $response = $this->putJson("/api/v1/articles/{$article->id}", $payload);

    $response->assertStatus(401);
}
/** @test */
public function admin_can_list_all_articles()
{
    $admin = User::factory()->create(['role' => 'admin']);

    Article::factory()->count(3)->create();

    $response = $this->actingAs($admin)->getJson('/api/admin/articles');

    $response->assertStatus(200)
             ->assertJsonCount(3, 'data');
}
/** @test */
public function non_admin_cannot_list_all_articles()
{
    $writer = User::factory()->create(['role' => 'writer']);

    $response = $this->actingAs($writer)->getJson('/api/admin/articles');

    $response->assertStatus(403);
}
/** @test */
public function admin_can_change_article_status()
{
    $admin = User::factory()->create(['role' => 'admin']);
    $article = Article::factory()->create(['status' => 'pending_review']);

    $payload = ['status' => 'published'];

    $response = $this->actingAs($admin)->patchJson("/api/admin/articles/{$article->id}/status", $payload);

    $response->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'id' => $article->id,
                     'status' => 'published'
                 ]
             ]);

    $this->assertDatabaseHas('articles', [
        'id' => $article->id,
        'status' => 'published'
    ]);
}
/** @test */
public function non_admin_cannot_change_article_status()
{
    $writer = User::factory()->create(['role' => 'writer']);
    $article = Article::factory()->create(['status' => 'pending_review']);

    $payload = ['status' => 'published'];

    $response = $this->actingAs($writer)->patchJson("/api/admin/articles/{$article->id}/status", $payload);

    $response->assertStatus(403);
}
/** @test */
public function admin_can_soft_delete_an_article()
{
    $admin = User::factory()->create(['role' => 'admin']);
    $article = Article::factory()->create();

    $response = $this->actingAs($admin)->deleteJson("/api/admin/articles/{$article->id}");

    $response->assertStatus(200); // أو 204 حسب الـ Response في الـ Controller الخاص بكِ

    $this->assertSoftDeleted('articles', [
        'id' => $article->id
    ]);
}
/** @test */
public function non_admin_cannot_delete_an_article()
{
    $writer = User::factory()->create(['role' => 'writer']);
    $article = Article::factory()->create();

    $response = $this->actingAs($writer)->deleteJson("/api/admin/articles/{$article->id}");

    $response->assertStatus(403);
    
    $this->assertDatabaseHas('articles', [
        'id' => $article->id
    ]);
}
}