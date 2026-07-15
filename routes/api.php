<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WeatherApiController;

Route::prefix('v1')->group(function () {

    Route::get('/weather', [WeatherApiController::class, 'current']);

    Route::get('/forecast', [WeatherApiController::class, 'forecast']);

    Route::get('/air-quality', [WeatherApiController::class, 'airQuality']);

});