<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKeyValueRequest;
use App\Http\Resources\KeyValueCollection;
use App\Http\Resources\KeyValueResource;
use App\Models\KeyValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class KeyValueController extends Controller
{
    public function index()
    {
        $keyValues = KeyValue::query()
            ->groupBy('key')
            ->havingRaw('max(created_at)')
            ->get();

        return KeyValueCollection::make($keyValues);
    }

    public function store(StoreKeyValueRequest $request)
    {
        $keyValues = $request->validated()['data'];

        KeyValue::query()->insert($keyValues);

        return response()->json([], 201);
    }

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
