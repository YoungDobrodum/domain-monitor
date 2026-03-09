<?php

namespace App\Jobs;

use App\Models\Domain;
use App\Services\DomainCheckService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckDomainJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Domain $domain)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(DomainCheckService $service): void
    {
        $service->check($this->domain);
    }
}
