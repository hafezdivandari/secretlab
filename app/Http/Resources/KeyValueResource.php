<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'KeyValueResource',
    properties: [
        new OA\Property(property: 'key', type: 'string'),
        new OA\Property(property: 'value', type: 'object'),
        new OA\Property(property: 'timestamp', type: 'integer'),
    ]
)]
#[OA\Response(
    response: 'KeyValueResource',
    description: 'The key-value response',
    content: new OA\JsonContent(properties: [
        new OA\Property(property: 'data', ref: '#/components/schemas/KeyValueResource'),
    ])
)]
class KeyValueResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
            'timestamp' => $this->created_at->timestamp,
        ];
    }
}
