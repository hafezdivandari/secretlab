<?php

arch()->preset()->laravel();

arch()
    ->expect('App')
    ->not->toUse(['die', 'dd', 'dump', 'var_dump']);

arch()
    ->expect('App\Http\Controllers')
    ->toBeClasses()
    ->toExtend('App\Http\Controllers\Controller');

arch()
    ->expect('App\Models')
    ->toBeClasses()
    ->toExtend('Illuminate\Database\Eloquent\Model')
    ->ignoring('App\Models\User');
