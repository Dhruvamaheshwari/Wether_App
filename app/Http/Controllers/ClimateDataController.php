<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ClimateDataController extends Controller
{
    /**
     * Show the climate data dashboard.
     */
    public function index(): View
    {
        return view('ClimateData', [
            'defaultCity' => 'Delhi',
            'indianCities' => $this->getIndianCities(),
            'climateZones' => $this->getClimateZones(),
        ]);
    }

    /**
     * Fetch climate data for a given city via the OpenWeatherMap API.
     */
    public function fetch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'city' => ['nullable', 'string', 'max:100'],
        ]);

        $city = trim($validated['city'] ?? 'Delhi');
        $apiKey = config('services.openweather.key');

        if ($city === '') {
            $city = 'Delhi';
        }

        if (blank($apiKey)) {
            return response()->json([
                'message' => 'OpenWeather API key is not configured. Set OPENWEATHER_API_KEY in your .env file.',
            ], 500);
        }

        $query = [
            'q'     => $city,
            'appid' => $apiKey,
            'units' => 'metric',
        ];

        // Fetch current weather
        $currentResponse = Http::baseUrl('https://api.openweathermap.org/data/2.5')
            ->acceptJson()
            ->timeout(10)
            ->get('/weather', $query);

        if (! $currentResponse->ok()) {
            $message = data_get($currentResponse->json(), 'message', 'Unable to fetch climate data.');
            return response()->json(['message' => ucfirst((string) $message)], $currentResponse->status() > 0 ? $currentResponse->status() : 502);
        }

        // Fetch 5-day forecast
        $forecastResponse = Http::baseUrl('https://api.openweathermap.org/data/2.5')
            ->acceptJson()
            ->timeout(10)
            ->get('/forecast', $query);

        $current  = $currentResponse->json();
        $forecast = $forecastResponse->ok() ? $forecastResponse->json() : null;

        // Build climate indices
        $temp     = (float) data_get($current, 'main.temp');
        $humidity = (int)   data_get($current, 'main.humidity');
        $wind     = (float) data_get($current, 'wind.speed');
        $clouds   = (int)   data_get($current, 'clouds.all');
        $pressure = (int)   data_get($current, 'main.pressure');

        return response()->json([
            'city' => [
                'name'    => data_get($current, 'name'),
                'country' => data_get($current, 'sys.country'),
                'lat'     => data_get($current, 'coord.lat'),
                'lon'     => data_get($current, 'coord.lon'),
            ],
            'current' => [
                'temperature'  => $temp,
                'feels_like'   => (float) data_get($current, 'main.feels_like'),
                'temp_min'     => (float) data_get($current, 'main.temp_min'),
                'temp_max'     => (float) data_get($current, 'main.temp_max'),
                'description'  => data_get($current, 'weather.0.description'),
                'main'         => data_get($current, 'weather.0.main'),
                'icon'         => data_get($current, 'weather.0.icon'),
                'humidity'     => $humidity,
                'wind_speed'   => $wind,
                'wind_deg'     => (int) data_get($current, 'wind.deg', 0),
                'clouds'       => $clouds,
                'pressure'     => $pressure,
                'visibility'   => (int) data_get($current, 'visibility', 0),
                'sunrise'      => (int) data_get($current, 'sys.sunrise'),
                'sunset'       => (int) data_get($current, 'sys.sunset'),
                'timestamp'    => (int) data_get($current, 'dt'),
                'timezone'     => (int) data_get($current, 'timezone'),
            ],
            'indices' => $this->buildAgricultureIndices($temp, $humidity, $wind, $clouds, $pressure),
            'forecast' => $forecast ? $this->buildForecastTimeline(data_get($forecast, 'list', [])) : [],
            'seasonalAdvice' => $this->seasonalAdvice($temp, $humidity),
        ]);
    }

    /**
     * Build agricultural climate indices from current weather.
     */
    protected function buildAgricultureIndices(float $temp, int $humidity, float $wind, int $clouds, int $pressure): array
    {
        // Heat Index (simplified)
        $heatIndex = $temp;
        if ($temp >= 27 && $humidity >= 40) {
            $heatIndex = $temp + (0.33 * ($humidity / 100 * 6.105 * exp(17.27 * $temp / (237.7 + $temp)))) - 4.0;
        }

        // Evapotranspiration estimate (Hargreaves simplified)
        $etEstimate = round(0.0023 * ($temp + 17.8) * max(0, ($temp + 5)) * 0.5 * (1 - $clouds / 200), 2);

        // Frost risk
        $frostRisk = $temp <= 2 ? 'High' : ($temp <= 5 ? 'Moderate' : 'Low');

        // Drought stress index (simplified)
        $droughtIndex = 'Low';
        if ($humidity < 30 && $temp > 35) {
            $droughtIndex = 'Severe';
        } elseif ($humidity < 40 && $temp > 30) {
            $droughtIndex = 'Moderate';
        } elseif ($humidity < 50 && $temp > 28) {
            $droughtIndex = 'Mild';
        }

        // Growing Degree Days (base 10°C)
        $gdd = max(0, round($temp - 10, 1));

        // Wind chill (if temp < 10°C)
        $windChill = $temp;
        if ($temp < 10 && $wind > 1.3) {
            $windChill = round(13.12 + 0.6215 * $temp - 11.37 * pow($wind * 3.6, 0.16) + 0.3965 * $temp * pow($wind * 3.6, 0.16), 1);
        }

        // Crop suitability score (0-100)
        $cropScore = 100;
        if ($temp < 10 || $temp > 40) $cropScore -= 40;
        elseif ($temp < 15 || $temp > 35) $cropScore -= 20;
        if ($humidity < 30 || $humidity > 90) $cropScore -= 25;
        elseif ($humidity < 40 || $humidity > 80) $cropScore -= 10;
        if ($wind > 10) $cropScore -= 15;
        elseif ($wind > 6) $cropScore -= 5;
        $cropScore = max(0, min(100, $cropScore));

        return [
            'heat_index'      => round($heatIndex, 1),
            'wind_chill'      => $windChill,
            'et_estimate'     => $etEstimate,
            'frost_risk'      => $frostRisk,
            'drought_index'   => $droughtIndex,
            'gdd'             => $gdd,
            'crop_score'      => $cropScore,
            'uv_estimate'     => $this->estimateUV($clouds),
            'dew_point'       => round($temp - ((100 - $humidity) / 5), 1),
        ];
    }

    /**
     * Estimate UV index from cloud cover.
     */
    protected function estimateUV(int $clouds): string
    {
        if ($clouds < 20) return 'Very High';
        if ($clouds < 40) return 'High';
        if ($clouds < 60) return 'Moderate';
        if ($clouds < 80) return 'Low';
        return 'Very Low';
    }

    /**
     * Build a richer forecast timeline with hourly-like data.
     */
    protected function buildForecastTimeline(array $forecastItems): array
    {
        $timeline = [];

        foreach (array_slice($forecastItems, 0, 40) as $item) {
            $timestamp = (int) data_get($item, 'dt');
            $timeline[] = [
                'dt'          => $timestamp,
                'date'        => gmdate('Y-m-d', $timestamp),
                'time'        => gmdate('H:i', $timestamp),
                'temp'        => (float) data_get($item, 'main.temp'),
                'feels_like'  => (float) data_get($item, 'main.feels_like'),
                'humidity'    => (int) data_get($item, 'main.humidity'),
                'pressure'    => (int) data_get($item, 'main.pressure'),
                'wind_speed'  => (float) data_get($item, 'wind.speed'),
                'clouds'      => (int) data_get($item, 'clouds.all'),
                'description' => data_get($item, 'weather.0.description'),
                'icon'        => data_get($item, 'weather.0.icon'),
                'pop'         => (float) data_get($item, 'pop', 0),
                'rain'        => (float) data_get($item, 'rain.3h', 0),
            ];
        }

        return $timeline;
    }

    /**
     * Provide seasonal farming advice.
     */
    protected function seasonalAdvice(float $temp, int $humidity): array
    {
        $advice = [];

        if ($temp >= 30 && $temp <= 40) {
            $advice[] = ['icon' => '🌾', 'title' => 'Kharif Season Active', 'text' => 'Conditions suitable for rice, maize, and cotton cultivation. Ensure adequate irrigation.'];
            $advice[] = ['icon' => '💧', 'title' => 'Irrigation Advisory', 'text' => 'Schedule irrigation during early morning or late evening to minimize evaporation losses.'];
        } elseif ($temp >= 15 && $temp < 30) {
            $advice[] = ['icon' => '🌿', 'title' => 'Rabi Season Conditions', 'text' => 'Favorable for wheat, mustard, and pulses. Monitor soil temperature for germination.'];
            $advice[] = ['icon' => '🌡️', 'title' => 'Temperature Watch', 'text' => 'Moderate temperatures support healthy vegetative growth. Maintain soil moisture.'];
        } elseif ($temp < 15) {
            $advice[] = ['icon' => '❄️', 'title' => 'Cold Weather Alert', 'text' => 'Protect crops from frost damage. Consider mulching and protective covers.'];
            $advice[] = ['icon' => '🧊', 'title' => 'Frost Prevention', 'text' => 'Apply light irrigation in the evening to raise soil temperature and reduce frost impact.'];
        } else {
            $advice[] = ['icon' => '🔥', 'title' => 'Extreme Heat Warning', 'text' => 'Heat stress risk for crops. Increase irrigation frequency and consider shade netting.'];
            $advice[] = ['icon' => '🚨', 'title' => 'Livestock Advisory', 'text' => 'Ensure adequate water and shade for livestock. Avoid fieldwork during peak hours.'];
        }

        if ($humidity >= 80) {
            $advice[] = ['icon' => '🍄', 'title' => 'Disease Risk High', 'text' => 'High humidity increases fungal disease risk. Apply preventive fungicide sprays.'];
        } elseif ($humidity <= 30) {
            $advice[] = ['icon' => '🏜️', 'title' => 'Dry Conditions', 'text' => 'Low humidity may accelerate crop wilting. Implement drip irrigation for efficiency.'];
        }

        return $advice;
    }

    /**
     * Get a curated list of major Indian cities.
     */
    protected function getIndianCities(): array
    {
        return [
            'Delhi', 'Mumbai', 'Bangalore', 'Hyderabad', 'Chennai',
            'Kolkata', 'Pune', 'Ahmedabad', 'Jaipur', 'Lucknow',
            'Chandigarh', 'Bhopal', 'Indore', 'Patna', 'Surat',
            'Nagpur', 'Visakhapatnam', 'Coimbatore', 'Thiruvananthapuram', 'Guwahati',
        ];
    }

    /**
     * Get climate zone information.
     */
    protected function getClimateZones(): array
    {
        return [
            ['name' => 'Tropical Wet',     'color' => '#00c853', 'regions' => 'Western Ghats, NE India'],
            ['name' => 'Tropical Dry',     'color' => '#ff9100', 'regions' => 'Deccan Plateau, Central India'],
            ['name' => 'Arid',             'color' => '#ff3d00', 'regions' => 'Rajasthan, Gujarat'],
            ['name' => 'Semi-Arid',        'color' => '#ffc400', 'regions' => 'Punjab, Haryana, MP'],
            ['name' => 'Subtropical Humid', 'color' => '#2979ff', 'regions' => 'UP, Bihar, West Bengal'],
            ['name' => 'Alpine',           'color' => '#6200ea', 'regions' => 'Himalayas, J&K, Uttarakhand'],
        ];
    }
}
