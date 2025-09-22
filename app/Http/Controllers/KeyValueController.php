<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKeyValueRequest;
use App\Http\Resources\KeyValueCollection;
use App\Http\Resources\KeyValueResource;
use App\Models\KeyValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use OpenApi\Attributes as OA;

class KeyValueController extends Controller
{
    #[OA\Get(
        path: '/object/get_all_records',
        responses: [new OA\Response(ref: '#/components/responses/KeyValueCollection', response: 200, description: 'Success')]
    )]
    public function index()
    {
        $keyValues = KeyValue::query()
            ->groupBy('key')
            ->havingRaw('max(created_at)')
            ->get();

        return KeyValueCollection::make($keyValues);
    }

    #[OA\Post(
        path: '/object',
        requestBody: new OA\RequestBody(ref: '#/components/requestBodies/StoreKeyValueRequest'),
        responses: [
            new OA\Response(response: 201, description: 'Success', content: new OA\JsonContent(default: null, nullable: true)),
            new OA\Response(ref: '#/components/responses/InvalidData', response: 422, description: 'Invalid'),
        ]
    )]
    public function store(StoreKeyValueRequest $request)
    {
        $keyValues = $request->validated()['data'];

        KeyValue::query()->insert($keyValues);

        return response()->json([], 201);
    }

    #[OA\Get(
        path: '/object/{key}',
        parameters: [
            new OA\Parameter(name: 'key', description: 'The key', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'timestamp', description: 'The timestamp', in: 'query', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(ref: '#/components/responses/KeyValueResource', response: 200, description: 'Success'),
            new OA\Response(response: 404, description: 'Not Found', content: new OA\JsonContent(default: null, nullable: true)),
            new OA\Response(ref: '#/components/responses/InvalidData', response: 422, description: 'Invalid'),
        ]
    )]
    public function show(Request $request, string $key)
    {
        $request->validate([
            'timestamp' => 'sometimes|date_format:U',
        ]);

        $keyValue = KeyValue::query()
            ->where('key', $key)
            ->when($request->query('timestamp'), function (Builder $query, string $timestamp) {
                $query->where('created_at', '<=', Date::createFromTimestamp($timestamp));
            })
            ->latest()
            ->firstOrFail();

        return KeyValueResource::make($keyValue);
    }
}
