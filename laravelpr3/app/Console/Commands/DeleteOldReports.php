<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;
use Carbon\Carbon;

class DeleteOldReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verwijder opgeloste meldingen die ouder zijn dan 7 dagen';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oneWeekAgo = Carbon::now()->subWeek();
        
        $deletedCount = Report::where('status', 'resolved')
            ->where('updated_at', '<', $oneWeekAgo)
            ->delete();
        
        if ($deletedCount > 0) {
            $this->info("✓ {$deletedCount} oude opgeloste melding(en) verwijderd");
        } else {
            $this->info("Geen oude opgeloste meldingen gevonden om te verwijderen");
        }
        
        return 0;
    }
}
