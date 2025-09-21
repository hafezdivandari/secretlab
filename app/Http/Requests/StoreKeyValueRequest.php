<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKeyValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            '*.key' => 'required|string|max:255',
            '*.value' => 'required|json',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->replace(
            $this->collect()
                ->map(fn ($value, $key) => [
                    'key' => $key,
                    'value' => rescue(fn () => json_encode($value, JSON_THROW_ON_ERROR), null, false),
                ])
                ->values()
                ->all()
        );
    }
}
