<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_open_the_weather_page(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get(route('weather'));

        $response->assertOk();
        $response->assertViewIs('weather');
        $response->assertSee(route('weather.data'), false);
    }

    public function test_weather_endpoint_returns_normalized_weather_data(): void
    {
        $user = $this->createUser();

        config(['services.openweather.key' => 'test-key']);

        Http::fake([
            'https://api.openweathermap.org/data/2.5/weather*' => Http::response([
                'cod' => 200,
                'name' => 'Delhi',
                'timezone' => 19800,
                'dt' => 1714300000,
                'weather' => [
                    [
                        'main' => 'Rain',
                        'description' => 'light rain',
                        'icon' => '10d',
                    ],
                ],
                'main' => [
                    'temp' => 30.2,
                    'feels_like' => 33.1,
                    'humidity' => 88,
                    'pressure' => 1004,
                ],
                'wind' => [
                    'speed' => 3.5,
                ],
                'clouds' => [
                    'all' => 75,
                ],
                'sys' => [
                    'country' => 'IN',
                    'sunrise' => 1714265000,
                    'sunset' => 1714311000,
                ],
            ], 200),
            'https://api.openweathermap.org/data/2.5/forecast*' => Http::response([
                'cod' => '200',
                'list' => [
                    [
                        'dt' => 1714300800,
                        'main' => ['temp' => 29],
                        'weather' => [
                            [
                                'icon' => '10d',
                                'description' => 'light rain',
                            ],
                        ],
                    ],
                    [
                        'dt' => 1714311600,
                        'main' => ['temp' => 31],
                        'weather' => [
                            [
                                'icon' => '10d',
                                'description' => 'light rain',
                            ],
                        ],
                    ],
                    [
                        'dt' => 1714387200,
                        'main' => ['temp' => 28],
                        'weather' => [
                            [
                                'icon' => '04d',
                                'description' => 'broken clouds',
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($user)->getJson(route('weather.data', ['city' => 'Delhi']));

        $response->assertOk();
        $response->assertJsonPath('city.name', 'Delhi');
        $response->assertJsonPath('city.country', 'IN');
        $response->assertJsonPath('current.main', 'Rain');
        $response->assertJsonPath('current.temperature', 30.2);
        $response->assertJsonPath('forecast.0.avg_temp', 30);
        $response->assertJsonPath('forecast.1.description', 'broken clouds');
        $response->assertJsonCount(3, 'recommendations');
    }

    public function test_weather_endpoint_returns_a_clear_error_when_api_key_is_missing(): void
    {
        $user = $this->createUser();

        config(['services.openweather.key' => null]);

        $response = $this->actingAs($user)->getJson(route('weather.data', ['city' => 'Delhi']));

        $response->assertStatus(500);
        $response->assertJson([
            'message' => 'OpenWeather API key is not configured. Set OPENWEATHER_API_KEY in your .env file.',
        ]);
    }

    protected function createUser(): User
    {
        return User::query()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
