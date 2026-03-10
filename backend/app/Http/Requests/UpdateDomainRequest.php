<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDomainRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $domainId = $this->route('domain')->id;

        return [
            'name' => [
                'required',
                'url',
                Rule::unique('domains', 'name')->ignore($domainId),
            ],
            'timeout'        => 'required|integer|min:1|max:60',
            'check_interval' => 'required|integer|min:1|max:1440'
        ];
    }
}
