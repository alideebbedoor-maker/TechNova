<?php

namespace Database\Factories;

use App\Models\Attachment;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    public function definition(): array
    {
        return [
            'file_path' => $this->faker->filePath(),
            'file_type' => $this->faker->mimeType(),
            'attachable_id' => Article::factory(),
            'attachable_type' => Article::class,
        ];
    }

    public function forProfile()
    {
        return $this->state([
            'attachable_type' => 'App\Models\Profile',
            'attachable_id' => \App\Models\Profile::factory(),
        ]);
    }
}