<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Morilog\Jalali\Jalalian;

class WeatherService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('WEATHER_API_KEY');
    }

    public function getCurrentWeather(float $lat, float $lon): array
    {
        $url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&units=metric&appid=$this->apiKey";

        $response = Http::get($url);

        if ($response->failed()) {
            return ['error' => 'Failed to fetch current weather'];
        }

        $data = $response->json();
        $currentTime = time();
        $isNight = ($currentTime < $data['sys']['sunrise'] || $currentTime > $data['sys']['sunset']);
        return [
            'temperature' => $data['main']['temp'] ?? null,
            'humidity' => $data['main']['humidity'] ?? null,
            'wind_speed' => $data['wind']['speed'] ?? null,
            'precipitation' => $data['rain']['1h'] ?? 0,
            'clouds' => $data['clouds']['all'] ?? 0,
            'weather' => $data['weather'][0]['description'] ?? null,
            'weather_class' => $this->getWeatherClass($data['weather'][0]['description'] , $isNight),
        ];
    }


    private function getWeatherIcon(string $weatherDescription): string
    {

        if (str_contains($weatherDescription, 'clear')) {
            $icon = '☀️';
        } elseif (str_contains($weatherDescription, 'cloud')) {
            $icon = '🌥️';
        } elseif (str_contains($weatherDescription, 'rain')) {
            $icon = '🌧️';
        } elseif (str_contains($weatherDescription, 'snow')) {
            $icon = '❄️';
        } else {
            $icon = '☀️';
        }

        return $icon;
    }

    public function getWeatherForecast(float $lat, float $lon): array
    {
        $url = "https://api.openweathermap.org/data/2.5/forecast?lat=$lat&lon=$lon&units=metric&appid=$this->apiKey";

        $response = Http::get($url);

        if ($response->failed()) {
            return ['error' => 'Failed to fetch weather forecast'];
        }

        $data = $response->json();
        $forecasts = array_slice($data['list'], 0, 8);

        return [
            'hours' => array_map(function ($forecast) {
                $weatherDescription = $forecast['weather'][0]['description'];
                $icon = $this->getWeatherIcon($weatherDescription); // فراخوانی تابع جداگانه برای گرفتن آیکون
                return $icon . ' ' . date('H:i', strtotime($forecast['dt_txt'])); // اضافه کردن ساعت به آیکون
            }, $forecasts),
            'temperatures' => array_map(fn($f) => $f['main']['temp'], $forecasts),
        ];
    }

    public function getWeatherClass(string $description, bool $isNight): array
    {
        $mappings = config('weather_classes');

        $description = strtolower($description);

        foreach ($mappings as $key => $value) {
            if (str_contains($description, $key)) {
                // تعیین day/night یا any
                if (isset($value['any'])) {
                    return $value['any'];
                }

                return $isNight ? $value['night'] : $value['day'];
            }
        }

        return ['card-default', ['default-icon']];
    }

    public function getDate(): string
    {
        $today = Jalalian::now();
        return $today->format('%A') .  " " . $today->format('Y/m/d');
    }


}
