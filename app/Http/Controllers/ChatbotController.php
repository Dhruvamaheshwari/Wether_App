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
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json([
                'error' => 'Gemini API key not configured.',
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
10. Prioritize water conservation and crop safety.

User Query: " . $message;

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $botResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'I apologize, but I couldn\'t generate a response.';
                return response()->json(['response' => $botResponse]);
            }

            Log::error('Gemini API Error: ' . $response->body());
            return response()->json(['error' => 'Failed to communicate with AI service.'], 500);

        } catch (\Exception $e) {
            Log::error('Chatbot Exception: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
