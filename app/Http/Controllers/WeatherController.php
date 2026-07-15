<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use App\Models\FavoriteCity;

class WeatherController extends Controller
{
    public function index()
    {
        $favorites = FavoriteCity::latest()->get();

        return view('home', [
            'lastCity' => session('last_city'),
            'favorites' => $favorites,
        ]);
    }
    public function search(Request $request, WeatherService $weatherService)
    {
        $request->validate([
            'city' => 'required|string|max:100',
        ]);

        $city = $request->city;

        session(['last_city' => $city]);

        $response = $weatherService->getWeather($city);
        $forecastResponse = $weatherService->getForecast($city);

        $weather = $response->json();
        $forecast = $forecastResponse->json();

        if ($response->failed() || $forecastResponse->failed()) {

            $favorites = FavoriteCity::latest()->get();

            return view('home', [
                'error' => $weather['message'] ?? $forecast['message'] ?? 'Something went wrong.',
                'favorites' => $favorites,
            ]);
        }

        $airQualityResponse = $weatherService->getAirQuality(
            $weather['coord']['lat'],
            $weather['coord']['lon']
        );

        $airQuality = $airQualityResponse->json();

        $favorites = FavoriteCity::latest()->get();

        return view('home', [
            'city' => $city,
            'weather' => $weather,
            'forecast' => $forecast,
            'airQuality' => $airQuality,
            'favorites' => $favorites,
        ]);
    }

    public function location(Request $request, WeatherService $weatherService)
    {
        $lat = $request->lat;
        $lon = $request->lon;

        $response = $weatherService->getWeatherByCoordinates($lat, $lon);
        $forecastResponse = $weatherService->getForecastByCoordinates($lat, $lon);
        $airQualityResponse = $weatherService->getAirQuality($lat, $lon);

        $weather = $response->json();
        $forecast = $forecastResponse->json();
        $airQuality = $airQualityResponse->json();
        if ($response->failed() || $forecastResponse->failed()) {

            $favorites = FavoriteCity::latest()->get();

            return view('home', [
                'error' => $weather['message'] ?? $forecast['message'] ?? 'Something went wrong.',
                'favorites' => $favorites,
            ]);
        }

        session(['last_city' => $weather['name']]);
        $favorites = FavoriteCity::latest()->get();

        return view('home', [
            'city' => $weather['name'],
            'weather' => $weather,
            'forecast' => $forecast,
            'airQuality' => $airQuality,
            'favorites' => $favorites,
        ]);
    }

    public function saveFavorite(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:100',
        ]);

        FavoriteCity::firstOrCreate([
            'city' => $request->city,
        ]);

        return redirect()->back()->with('success', 'City added to favorites!');
    }

    public function deleteFavorite(FavoriteCity $favorite)
    {
        $favorite->delete();

        return redirect()->back()->with(
            'success',
            'City removed from favorites!'
        );
    }
}