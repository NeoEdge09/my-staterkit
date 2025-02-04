<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    private $apiUrl = 'https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=36.71.02.1002';

    public function getCurrentWeather()
    {
        try {
            return Cache::remember('weather_data', 3600, function () {
                $response = Http::get($this->apiUrl);

                if (!$response->successful()) {
                    return response()->json([
                        'error' => 'Failed to fetch weather data'
                    ], 500);
                }

                $data = $response->json();

                return response()->json([
                    'data' => $data['data']
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Weather service unavailable'
            ], 500);
        }
    }
}
