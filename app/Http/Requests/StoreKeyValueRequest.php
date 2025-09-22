<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request: 'StoreKeyValueRequest',
    required: true,
    content: new OA\JsonContent(properties: [
        new OA\Property(type: 'object'),
    ]),
)]
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
            'data' => 'required|array|min:1',
            'data.*.key' => 'required|string|max:255',
            'data.*.value' => 'required|json',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->replace([
            'data' => $this->collect()
                ->map(fn ($value, $key) => [
                    'key' => $key,
                    'value' => rescue(fn () => json_encode($value, JSON_THROW_ON_ERROR), null, false),
                ])
                ->values()
                ->all(),
        ]);
    }
}
