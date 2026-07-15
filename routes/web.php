<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

Route::get('/', [WeatherController::class, 'index'])->name('home');

Route::get('/weather', [WeatherController::class, 'search'])
    ->name('weather.search');

Route::get('/weather/location', [WeatherController::class, 'location'])
    ->name('weather.location');

Route::post('/favorites', [WeatherController::class, 'saveFavorite'])
    ->name('favorites.save');

Route::delete('/favorites/{favorite}', [WeatherController::class, 'deleteFavorite'])
    ->name('favorites.delete');