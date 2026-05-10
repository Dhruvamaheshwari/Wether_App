<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SoilSampleController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\MailController;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('Index');
    })->name('index');

    // Authentication Routes
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/home', function () {
        return view('homepage');
    })->name('home');

    Route::get('/about', function () {
        return view('AboutUs');
    })->name('about');

    Route::get('/contact', function () {
        return view('contact');
    })->name('contact.view');

    Route::post('/contact', [MailController::class, 'send'])->name('contact.send');

    Route::get('/register', function () {
        return view('register');
    })->name('register');

    Route::get('/forgot', function () {
        return view('forgot');
    })->name('forgot');

    Route::get('/reset', function () {
        return view('reset');
    })->name('reset');

    Route::get('/monitor', function () {
        return view('Monitor');
    })->name('monitor');

    Route::get('/soil-analysis', [SoilSampleController::class, 'index'])->name('soil');
    Route::post('/soil-analysis', [SoilSampleController::class, 'store'])->name('soil.store');
    Route::get('/api/soil-samples/{sample_id}', [SoilSampleController::class, 'show'])->name('soil.show');

    Route::get('/history-trend', function () {
        return view('HisTrend');
    })->name('history');

    Route::get('/support', function () {
        return view('Support');
    })->name('support');

    Route::get('/office', function () {
        return view('office');
    })->name('office');

    Route::get('/alert', function () {
        return view('Alert');
    })->name('alert');

    Route::get('/chatbot', function () {
        return view('chatbot');
    })->name('chatbot');

    Route::get('/weather/data', [WeatherController::class, 'fetch'])->name('weather.data');
    Route::get('/weather', [WeatherController::class, 'index'])->name('weather');
});
