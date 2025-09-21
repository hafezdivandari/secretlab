<?php

use App\Http\Controllers\KeyValueController;
use Illuminate\Support\Facades\Route;

Route::prefix('object')->group(function () {
    Route::get('/get_all_records', [KeyValueController::class, 'index'])->name('objects.index');

    Route::get('/{key}', [KeyValueController::class, 'show'])->name('objects.show');

    Route::post('/', [KeyValueController::class, 'store'])->name('objects.store');
});
