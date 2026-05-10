<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = $request->input('message');
        $apiKey = env('OPENROUTER_API_KEY');

        if (!$apiKey) {
            return response()->json([
                'error' => 'OpenRouter API key not configured.',
            ], 500);
        }

        // Fetch "Sensor Data" from the latest SoilSample entry for the authenticated user
        $user = auth()->user();
        $latestSample = \App\Models\SoilSample::where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->first();

        // Get Weather Data (simplified call to OpenWeather logic)
        $weatherData = "No weather data available.";
        try {
            $weatherController = new WeatherController();
            $weatherResponse = $weatherController->fetch(new Request(['city' => $latestSample->location ?? 'Delhi']));
            if ($weatherResponse->getStatusCode() == 200) {
                $w = $weatherResponse->getData(true);
                $weatherData = "City: {$w['city']['name']}, Temp: {$w['current']['temperature']}°C, Condition: {$w['current']['description']}, Humidity: {$w['current']['humidity']}%";
            }
        } catch (\Exception $e) {
            Log::warning('Could not fetch weather for AgriBot: ' . $e->getMessage());
        }

        $temperature = data_get($w ?? [], 'current.temperature', 'N/A');
        $humidity = data_get($w ?? [], 'current.humidity', 'N/A');
        $soilMoisture = $latestSample->moisture_value ?? 'N/A';
        $rainStatus = (str_contains(strtolower(data_get($w ?? [], 'current.main', '')), 'rain')) ? 'Raining' : 'No Rain';
        $light = (now()->hour >= 6 && now()->hour <= 18) ? 'Bright Sun' : 'Low Light';
        $crop = $latestSample->crop_type ?? 'General Crops';

        $systemPrompt = "You are AgriBot, an intelligent AI assistant for a Smart Agriculture Climate Monitoring System.
Your job is to help farmers understand environmental conditions, crop health, irrigation needs, and climate risks using real-time sensor data.

Current Sensor Data:
- Temperature: {$temperature} °C
- Humidity: {$humidity} %
- Soil Moisture: {$soilMoisture} %
- Rain Status: {$rainStatus}
- Light Intensity: {$light}
- Crop Type: {$crop}

Weather Information:
{$weatherData}

Rules:
1. Always answer in simple farmer-friendly language.
2. Keep responses short and actionable.
3. If soil moisture is low, recommend irrigation.
4. If rain is expected, avoid unnecessary watering.
5. Warn users about dangerous temperature conditions.
6. Suggest best farming actions based on data.
7. If user asks unrelated questions, politely redirect to agriculture topics.
8. Support multilingual responses if possible.
9. Never provide harmful farming advice.
10. Prioritize water conservation and crop safety.";

        $url = "https://openrouter.ai/api/v1/chat/completions";

        $modelsToTry = [
            'google/gemma-4-31b-it:free',
            'minimax/minimax-m2.5:free',
            'liquid/lfm-2.5-1.2b-instruct:free',
            'qwen/qwen3-coder:free',
            'meta-llama/llama-3.3-70b-instruct:free'
        ];

        foreach ($modelsToTry as $model) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => config('app.url'),
                    'X-Title' => config('app.name'),
                ])->post($url, [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $message]
                    ]
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $botResponse = $data['choices'][0]['message']['content'] ?? 'I apologize, but I couldn\'t generate a response.';
                    return response()->json(['response' => $botResponse]);
                }

                // If it's a 429 Rate Limit error, continue to the next model
                if ($response->status() === 429) {
                    continue;
                }

                // For other errors, log and return
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? 'Failed to communicate with AI service.';
                Log::error('OpenRouter API Error (' . $model . '): ' . $response->body());
                
                return response()->json([
                    'error' => $errorMessage,
                    'code' => $response->status()
                ], $response->status());

            } catch (\Exception $e) {
                Log::error('Chatbot Exception (' . $model . '): ' . $e->getMessage());
                // Try next model if it's a network exception
            }
        }

        // If all models failed with 429 or exceptions
        return response()->json([
            'error' => 'All free AI models are currently overloaded. Please wait a moment and try again.',
            'code' => 429
        ], 429);

    }
}
