<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'KeyValueCollection',
    description: 'The collection of key-values',
    content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/KeyValueResource')),
)]
class KeyValueCollection extends ResourceCollection
{
    public static $wrap = null;

    public $collects = KeyValueResource::class;
}
