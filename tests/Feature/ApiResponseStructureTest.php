<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiResponseStructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_articles_list_returns_data_and_meta()
    {
        $user = User::factory()->create();
        Article::factory()->count(3)->create(['user_id' => $user->id]);

        $this->actingAs($user)
             ->getJson('/api/v1/articles')
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => ['id', 'title', 'content']
                 ],
                 'meta' => ['current_page', 'total']
             ]);
    }

    public function test_article_details_does_not_reveal_sensitive_data()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
             ->getJson("/api/v1/articles/{$article->id}")
             ->assertStatus(200)
             ->assertJsonMissing(['password']); 
    }
}