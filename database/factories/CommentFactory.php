<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\Article; 
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'body' => $this->faker->paragraph(),
            'commentable_id' => Article::factory(), 
            'commentable_type' => Article::class,
        ];
    }

    public function forProfile()
    {
        return $this->state([
            'commentable_type' => 'App\Models\Profile',
            'commentable_id' => \App\Models\Profile::factory(),
        ]);
    }
}