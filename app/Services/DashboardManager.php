<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Cache;

class DashboardManager
{
    private const CACHE_PREFIX = 'dashboard:stats:';
    private const LOCK_KEY = 'dashboard:rebuild:lock';

    public function getMetrics(): array
    {
        $counts = Cache::get(self::CACHE_PREFIX . 'counts');
        $latestArticles = Cache::get(self::CACHE_PREFIX . 'latest_articles');

        if ($counts && $latestArticles) {
            return array_merge($counts, ['latest_articles' => $latestArticles]);
        }

        return Cache::lock(self::LOCK_KEY, 10)->block(5, function () {
            
            $statsCounts = [
                'total_articles' => Article::count(),
                'total_writers'  => User::where('role', 'writer')->count(),
                'total_comments' => Comment::count(),
            ];

            $topArticles = Article::with(['author'])
                ->latest()
                ->take(5)
                ->get();      

            Cache::put(self::CACHE_PREFIX . 'counts', $statsCounts, now()->addHours(24));
            Cache::put(self::CACHE_PREFIX . 'latest_articles', $topArticles, now()->addHours(24));

            return array_merge($statsCounts, ['latest_articles' => $topArticles]);
        });
    }

    public function clearMetricsCache(): void
    {
        Cache::forget(self::CACHE_PREFIX . 'counts');
        Cache::forget(self::CACHE_PREFIX . 'latest_articles');
    }
}