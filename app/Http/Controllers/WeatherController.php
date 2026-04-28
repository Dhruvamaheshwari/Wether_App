<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class WeatherController extends Controller
{
    public function index(): View
    {
        return view('weather', [
            'defaultCity' => 'Delhi',
            'recommendations' => $this->defaultRecommendations(),
        ]);
    }

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
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
        ];

        $currentResponse = $this->weatherClient()->get('/weather', $query);

        if (! $currentResponse->ok()) {
            return $this->weatherErrorResponse($currentResponse, 'Unable to fetch current weather data right now.');
        }

        $forecastResponse = $this->weatherClient()->get('/forecast', $query);

        if (! $forecastResponse->ok()) {
            return $this->weatherErrorResponse($forecastResponse, 'Unable to fetch forecast data right now.');
        }

        $current = $currentResponse->json();
        $forecast = $forecastResponse->json();

        return response()->json([
            'city' => [
                'name' => data_get($current, 'name'),
                'country' => data_get($current, 'sys.country'),
            ],
            'current' => [
                'temperature' => (float) data_get($current, 'main.temp'),
                'feels_like' => (float) data_get($current, 'main.feels_like'),
                'description' => data_get($current, 'weather.0.description'),
                'main' => data_get($current, 'weather.0.main'),
                'icon' => data_get($current, 'weather.0.icon'),
                'humidity' => (int) data_get($current, 'main.humidity'),
                'wind_speed' => (float) data_get($current, 'wind.speed'),
                'clouds' => (int) data_get($current, 'clouds.all'),
                'pressure' => (int) data_get($current, 'main.pressure'),
                'sunrise' => (int) data_get($current, 'sys.sunrise'),
                'sunset' => (int) data_get($current, 'sys.sunset'),
                'timestamp' => (int) data_get($current, 'dt'),
                'timezone' => (int) data_get($current, 'timezone'),
            ],
            'forecast' => $this->summarizeForecast(data_get($forecast, 'list', [])),
            'recommendations' => $this->recommendationsFor(
                data_get($current, 'weather.0.main'),
                (float) data_get($current, 'main.temp'),
                (int) data_get($current, 'main.humidity')
            ),
        ]);
    }

    protected function weatherClient()
    {
        return Http::baseUrl('https://api.openweathermap.org/data/2.5')
            ->acceptJson()
            ->timeout(10);
    }

    protected function weatherErrorResponse($response, string $fallbackMessage): JsonResponse
    {
        $message = data_get($response->json(), 'message', $fallbackMessage);

        return response()->json([
            'message' => ucfirst((string) $message),
        ], $response->status() > 0 ? $response->status() : 502);
    }

    protected function summarizeForecast(array $forecastItems): array
    {
        $dailyForecast = [];

        foreach ($forecastItems as $item) {
            $timestamp = (int) data_get($item, 'dt');
            $dayKey = gmdate('Y-m-d', $timestamp);

            if (! isset($dailyForecast[$dayKey])) {
                $dailyForecast[$dayKey] = [
                    'date' => $dayKey,
                    'temperatures' => [],
                    'icon' => data_get($item, 'weather.0.icon'),
                    'description' => data_get($item, 'weather.0.description'),
                ];
            }

            $dailyForecast[$dayKey]['temperatures'][] = (float) data_get($item, 'main.temp');
        }

        return collect($dailyForecast)
            ->take(7)
            ->map(function (array $forecastDay) {
                $temperatures = $forecastDay['temperatures'];
                $averageTemperature = count($temperatures) > 0
                    ? array_sum($temperatures) / count($temperatures)
                    : 0;

                return [
                    'date' => $forecastDay['date'],
                    'avg_temp' => round($averageTemperature),
                    'icon' => $forecastDay['icon'],
                    'description' => $forecastDay['description'],
                ];
            })
            ->values()
            ->all();
    }

    protected function recommendationsFor(?string $weatherMain, float $temperature, int $humidity): array
    {
        $condition = strtolower((string) $weatherMain);

        $conditionRecommendations = match ($condition) {
            'rain', 'drizzle', 'thunderstorm' => [
                'Heavy moisture expected. Delay irrigation and ensure field drainage is open.',
                'Avoid spraying fertilizers or pesticides until rainfall settles.',
            ],
            'clear' => [
                'Clear conditions are suitable for sowing, spraying, and harvesting operations.',
                'Check soil moisture during the afternoon because evaporation can rise quickly.',
            ],
            'clouds' => [
                'Moderate cloud cover is good for transplanting and nursery work with lower heat stress.',
                'Use this window for field inspection and light maintenance tasks.',
            ],
            'mist', 'fog', 'haze' => [
                'Low visibility conditions are better for monitoring than machine-heavy field work.',
                'Watch crops closely for leaf wetness that can support fungal diseases.',
            ],
            default => $this->defaultRecommendations(),
        };

        if ($temperature >= 35) {
            $conditionRecommendations[] = 'High temperature detected. Arrange irrigation timing for early morning or evening.';
        }

        if ($humidity >= 85) {
            $conditionRecommendations[] = 'Humidity is high, so keep an eye out for fungal infection and pest pressure.';
        }

        return array_values(array_unique($conditionRecommendations));
    }

    protected function defaultRecommendations(): array
    {
        return [
            'Review local field moisture before irrigating so water is not wasted.',
            'Use the weekly forecast to plan spraying, harvesting, and soil preparation work.',
            'Monitor crop stress signs daily whenever temperature and humidity change sharply.',
        ];
    }
}
