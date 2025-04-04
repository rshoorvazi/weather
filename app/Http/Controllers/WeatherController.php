<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\City;
use App\Services\WeatherService;
use Illuminate\Contracts\View\View;

#[AllowDynamicProperties] class WeatherController extends Controller
{
    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index( ): View
    {
        $city = City::find(986);
        $weatherData = $this->getWeather($city);
        return view('weather', $weatherData);
    }

    public function getWeather(City $city): array
    {
        $lat = $city->lat;
        $lon = $city->lon;

        $currentWeather = $this->weatherService->getCurrentWeather($lat, $lon);
        $forecast = $this->weatherService->getWeatherForecast($lat, $lon);
        $date = $this->weatherService->getDate($lat, $lon);

        return [
            'current' => $currentWeather,
            'forecast' => $forecast,
            'city_name' => $city->city,
            'date' => $date,
        ];
    }
}
