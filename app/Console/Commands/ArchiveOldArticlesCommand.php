<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use Carbon\Carbon;

class ArchiveOldArticlesCommand extends Command
{
    protected $signature = 'articles:archive {days=30 : The number of days to look back for old articles}';

    protected $description = 'Archive articles that are not published and exceed the specified age in days';

    public function handle()
    {
        $days = $this->argument('days');
        
        $targetDate = Carbon::now()->subDays($days);

        $articlesQuery = Article::where('status', '!=', 'published')
            ->where('created_at', '<', $targetDate);

        $count = $articlesQuery->count();

        if ($count === 0) {
            $this->info("No unpublished articles found older than {$days} days to archive.");
            return self::SUCCESS;
        }

        $articlesQuery->update(['status' => 'archived']);

        $this->info("Successfully archived {$count} article(s) older than {$days} days.");
        
        return self::SUCCESS;
    }
}