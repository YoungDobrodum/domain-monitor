<?php

namespace App\Console\Commands;

use App\Jobs\CheckDomainJob;
use App\Models\Domain;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckDomainsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domains:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a check of all domains in the queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $allDomains = Domain::all();
        $domainsToCheck = $allDomains->filter(function ($domain) {
            $lastCheck = $domain->checks()->latest()->first();
            if (!$lastCheck) return true;

            return $lastCheck->created_at->addMinutes($domain->check_interval) <= now();
        });
        Log::debug("Scheduler: Of {$allDomains->count()} domains, it is time to {$domainsToCheck->count()}.");

        if ($domainsToCheck->isEmpty()) {
            $this->info('There are no domains ready for verification at this time.');
            return;
        }
        $this->info("Starting check for {$domainsToCheck->count()} domains...");

        $domainsToCheck->each(function ($domain) {
            Log::info("Submit to queue: Domain #{$domain->id} ({$domain->name})");

            CheckDomainJob::dispatch($domain);
        });

        $this->info('All tasks have been sent to the queue!');
    }
}
