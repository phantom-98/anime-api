<?php

use App\Http\Controllers\AnimeController;

Route::get('/anime/{slug}', [AnimeController::class, 'show']);
Route::middleware('throttle:60,1')->get('/anime/{slug}', [AnimeController::class, 'show']);

Route::middleware(['rate.limit'])->group(function () {
    Route::get('/your-route', [YourController::class, 'yourMethod']);
});