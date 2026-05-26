<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'     => 'System Administrator',
            'email'    => 'admin@technova.com',
            'password' => Hash::make('password'),
            'role'     => 'admin', 
        ]);

        $writer = User::create([
            'name'     => 'Professional Writer',
            'email'    => 'writer@technova.com',
            'password' => Hash::make('password'),
            'role'     => 'writer',
        ]);

        $reader = User::create([
            'name'     => 'Regular Reader',
            'email'    => 'reader@technova.com',
            'password' => Hash::make('password'),
            'role'     => 'reader',
        ]);

        $tags = [];
        foreach (['Laravel', 'API', 'Security'] as $tagName) {
            $tags[] = Tag::create(['name' => $tagName]);
        }

        $article = Article::create([
            'title'        => 'Mastering Laravel API Architecture',
            'content'      => 'Clean architecture guide using custom requests and resources.',
            'status'       => 'published',
            'user_id'      => $writer->id,
            'approved_by'  => $admin->id,
            'published_at' => now(),
        ]);

        $article->tags()->attach([$tags[0]->id, $tags[1]->id]);

        $article->comments()->create([
            'user_id' => $reader->id,
            'body'    => 'This is an amazing architectural approach!',
        ]);
    }
}