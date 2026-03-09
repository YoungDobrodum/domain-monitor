<?php

namespace App\Services;

use App\Models\Domain;
use Illuminate\Support\Facades\Auth;

class DomainService
{
    public function getAll() {
        return Auth::user()->domains;
    }

    public function create(array $data) {
        return Auth::user()->domains()->create($data);
    }

    public function update(Domain $domain, array $data): Domain
    {
        $domain->update($data);
        return $domain;
    }

    public function delete(Domain $domain): ?bool
    {
        return $domain->delete();
    }
}
