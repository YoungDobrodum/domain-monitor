<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDomainRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'           => 'required|url|unique:domains,name',
            'timeout'        => 'required|integer|min:1|max:60',
            'check_interval' => 'required|integer|min:1|max:1440'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
