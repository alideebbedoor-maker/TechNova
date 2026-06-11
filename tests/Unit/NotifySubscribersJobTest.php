<?php

use App\Jobs\NotifySubscribersJob;
use App\Models\Article;

test('notify subscribers job stores the article correctly in constructor', function () {
    $article = new Article([
        'title' => 'Advanced Testing in TechNova',
        'status' => 'published'
    ]);
    
    $article->id = 99;

    $job = new NotifySubscribersJob($article);

    expect($job->article)->toBeInstanceOf(Article::class)
        ->and($job->article->id)->toBe(99)
        ->and($job->article->title)->toBe('Advanced Testing in TechNova');
});