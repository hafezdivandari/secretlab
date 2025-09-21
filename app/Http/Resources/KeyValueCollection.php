<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class KeyValueCollection extends ResourceCollection
{
    public static $wrap = null;

    public $collects = KeyValueResource::class;
}
