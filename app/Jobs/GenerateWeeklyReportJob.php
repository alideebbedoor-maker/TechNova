<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\User;
use App\Notifications\WeeklyReportNotification;    
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateWeeklyReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // جمع المقالات المنشورة في آخر 7 أيام
        $articles = Article::where('created_at', '>=', now()->subDays(7))->get();
        
        // إرسالها للأدمن
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new WeeklyReportNotification($articles));
        }
    }
}