<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateArticlesReportCommand extends Command
{
    protected $signature = 'articles:report';

    protected $description = 'Generate a report of the number of articles published for each writer during the current month and save it in the Log ';

    public function handle()
    {
        $this->info("---Generating articles report for the current month---");

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $writers = User::withCount(['articles' => function ($query) use ($startOfMonth, $endOfMonth) {
            $query->where('status', 'published')
                  ->whereBetween('published_at', [$startOfMonth, $endOfMonth]);
        }])->get();

        $reportLines = [];
        $reportLines[] = "Report date : " . Carbon::now()->toDateTimeString();

        foreach ($writers as $writer) {
            if ($writer->articles_count > 0) {
                $line = "writer: {$writer->name} (Email: {$writer->email}) - Number of articles published : {$writer->articles_count}";
                
                $this->line($line);
                
                $reportLines[] = $line;
            }
        }

        $reportLines[] = "--------------------------------------------------\n";
        $fullReportText = implode("\n", $reportLines);

        Log::channel('single')->info($fullReportText);

        $this->info("The report was successfully saved to the log file.");
        
        return self::SUCCESS;
    }
}