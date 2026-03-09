<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\Models\Domain;
use App\Services\DomainService;

class DomainController extends Controller
{
    public function index(DomainService $service) {
        return response()->json($service->getAll());
    }

    public function store
    (
        StoreDomainRequest $request,
        DomainService $service)
    {
        $domain = $service->create($request->validated());
        return response()->json($domain, 201);
    }

    public function update
    (
        UpdateDomainRequest $request,
        Domain $domain,
        DomainService $service)
    {
        $updated = $service->update($domain, $request->validated());
        return response()->json($updated);
    }

    public function destroy
    (
        Domain $domain,
        DomainService $service
    )
    {
        $service->delete($domain);
        return response()->json(null, 204);
    }
}
