<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyValue extends Model
{
    /** @use HasFactory<\Database\Factories\KeyValueFactory> */
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'key',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'json',
        ];
    }
}
