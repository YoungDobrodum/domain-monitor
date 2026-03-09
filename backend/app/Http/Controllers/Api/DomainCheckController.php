<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Services\DomainCheckService;
use Illuminate\Http\JsonResponse;

class DomainCheckController extends Controller
{
    public function __construct(
        protected DomainCheckService $service
    ) {}

    public function index(Domain $domain): JsonResponse
    {
        if ($domain->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $checks = $this->service->getHistoryForDomain($domain);

        return response()->json($checks);
    }
}
