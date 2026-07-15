<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherApiController extends Controller
{

    public function current(Request $request, WeatherService $weatherService)
    {
        $request->validate([
            'city' => 'required|string|max:100',
        ]);

        $response = $weatherService->getWeather($request->city);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => $response->json()['message'] ?? 'City not found.',
            ], 404);
        }

        $data = $response->json();

        return response()->json([
            'success' => true,
            'city' => $data['name'],
            'country' => $data['sys']['country'],
            'coordinates' => [
                'latitude' => $data['coord']['lat'],
                'longitude' => $data['coord']['lon'],
            ],
            'temperature' => $data['main']['temp'],
            'feels_like' => $data['main']['feels_like'],
            'humidity' => $data['main']['humidity'],
            'pressure' => $data['main']['pressure'],
            'visibility' => $data['visibility'],
            'wind_speed' => $data['wind']['speed'],
            'weather' => [
                'main' => $data['weather'][0]['main'],
                'description' => $data['weather'][0]['description'],
                'icon' => $data['weather'][0]['icon'],
            ],
            'updated_at' => now()->toDateTimeString(),
        ]);
    }

    public function forecast(Request $request, WeatherService $weatherService)
    {
        $request->validate([
            'city' => 'required|string|max:100',
        ]);

        $response = $weatherService->getForecast($request->city);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'City not found.',
            ], 404);
        }

        $data = $response->json();

        $forecast = [];

        foreach ($data['list'] as $item) {

            if (\Carbon\Carbon::parse($item['dt_txt'])->format('H:i') == '12:00') {

                $forecast[] = [
                    'date' => \Carbon\Carbon::parse($item['dt_txt'])->toDateString(),
                    'temperature' => $item['main']['temp'],
                    'temp_max' => $item['main']['temp_max'],
                    'temp_min' => $item['main']['temp_min'],
                    'humidity' => $item['main']['humidity'],
                    'weather' => $item['weather'][0]['description'],
                    'icon' => $item['weather'][0]['icon'],
                ];
            }
        }

        return response()->json([
            'success' => true,
            'city' => $data['city']['name'],
            'country' => $data['city']['country'],
            'forecast' => $forecast,
        ]);
    }

    public function airQuality(Request $request, WeatherService $weatherService)
    {
        $request->validate([
            'city' => 'required|string|max:100',
        ]);

        $weatherResponse = $weatherService->getWeather($request->city);

        if ($weatherResponse->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'City not found.',
            ], 404);
        }

        $weather = $weatherResponse->json();

        $response = $weatherService->getAirQuality(
            $weather['coord']['lat'],
            $weather['coord']['lon']
        );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch air quality.',
            ], 404);
        }

        $aqi = $response->json()['list'][0]['main']['aqi'];

        $status = match ($aqi) {
            1 => 'Good',
            2 => 'Fair',
            3 => 'Moderate',
            4 => 'Poor',
            5 => 'Very Poor',
            default => 'Unknown',
        };

        return response()->json([
            'success' => true,
            'city' => $weather['name'],
            'country' => $weather['sys']['country'],
            'aqi' => [
                'index' => $aqi,
                'status' => $status,
            ],
        ]);
    }
}