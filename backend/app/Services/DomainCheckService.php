<?php

namespace App\Services;

use App\Models\Domain;
use App\Models\DomainCheck;
use App\Notifications\DomainNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DomainCheckService
{
    public function getHistoryForDomain(Domain $domain): Collection
    {
        return $domain
            ->checks()
            ->latest()
            ->limit(50)
            ->get();
    }

    public function check(Domain $domain): DomainCheck
    {
        $startTime = microtime(true);
        Log::info("Run domain check: {$domain->name}");

        try {
            $response = Http::timeout($domain->timeout)->head($domain->name);

            $endTime = microtime(true);

            return $domain->checks()->create([
                'status_code' => $response->status(),
                'response_time' => round($endTime - $startTime, 3),
                'is_healthy' => $response->successful(),
                'error_message' => null,
            ]);
        } catch (\Exception $e) {
            $checkData = [
                'status_code' => null,
                'response_time' => 0,
                'is_healthy' => false,
                'error_message' => substr($e->getMessage(), 0, 255),
            ];
        }

        $check = $domain->checks()->create($checkData);

        if (!$check->is_healthy) {
            $domain->user->notify(new DomainNotification($domain));
            Log::warning("Domain is unavailable: {$domain->name}", ['status' => $response->status()]);
        }

        return $check;
    }

}
