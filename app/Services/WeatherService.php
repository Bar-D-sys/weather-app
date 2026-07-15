<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function getWeather(string $city)
    {
        return Http::get(
            'https://api.openweathermap.org/data/2.5/weather',
            [
                'q' => $city,
                'appid' => config('services.openweather.key'),
                'units' => 'metric',
            ]
        );
    }

    public function getForecast(string $city)
    {
        return Http::get(
            'https://api.openweathermap.org/data/2.5/forecast',
            [
                'q' => $city,
                'appid' => config('services.openweather.key'),
                'units' => 'metric',
            ]
        );
    }

    public function getWeatherByCoordinates($lat, $lon)
    {
        return Http::get(
            'https://api.openweathermap.org/data/2.5/weather',
            [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => config('services.openweather.key'),
                'units' => 'metric',
            ]
        );
    }

    public function getForecastByCoordinates($lat, $lon)
    {
        return Http::get(
            'https://api.openweathermap.org/data/2.5/forecast',
            [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => config('services.openweather.key'),
                'units' => 'metric',
            ]
        );
    }

    public function getAirQuality($lat, $lon)
    {
        return Http::get(
            'https://api.openweathermap.org/data/2.5/air_pollution',
            [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => config('services.openweather.key'),
            ]
        );
    }
}