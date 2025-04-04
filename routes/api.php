<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/cities', [CityController::class, 'index']);
Route::get('/weather/{city}' , [WeatherController::class, 'getWeather']);
