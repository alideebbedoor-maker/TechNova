<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter; 
use Illuminate\Http\Request;            
use Illuminate\Cache\RateLimiting\Limit;    

use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\EloquentArticleRepository;
use App\Services\Notifications\NotificationSenderInterface;
use App\Services\Notifications\EmailNotificationSender;
use App\Services\DashboardManager;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Observers\ArticleObserver; 
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ArticleRepositoryInterface::class, EloquentArticleRepository::class);
        $this->app->bind(NotificationSenderInterface::class, EmailNotificationSender::class);
    }

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        Article::observe(ArticleObserver::class);

        $this->registerDashboardCacheClearers();
    }

    private function registerDashboardCacheClearers(): void
    {
        $clearDashboardCache = function () {
            app(DashboardManager::class)->clearMetricsCache();
        };

        Comment::saved($clearDashboardCache);
        Comment::deleted($clearDashboardCache);

        User::saved($clearDashboardCache);
        User::deleted($clearDashboardCache);
    }
}