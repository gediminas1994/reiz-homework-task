<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJobRequest extends FormRequest
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
        return [
            'urls' => 'required|array|min:1',
            'urls.*' => 'url',
            'selectors' => 'required|array|min:1',
            'selectors.*' => 'string'
        ];
    }

    public function messages(): array
    {
        return [
            'urls.required' => 'At least one URL is needed',
            'selectors.required' => 'At least one selector is needed',
            'urls.*.url' => 'URL must be a valid URL',
            'selectors.*.string' => 'Selector should be a valid string'
        ];
    }
}
