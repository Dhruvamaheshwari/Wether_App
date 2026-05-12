<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather-App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('Weather/styles.css') }}">
    <style>
        .recommendation-text {
            display: inline-block;
            animation: scroll-up 35000ms ease-in-out infinite;
        }
    </style>
</head>
<body data-weather-url="{{ route('weather.data') }}" data-default-city="{{ $defaultCity }}">
    <div class="back-btn-container">
        <a href="{{ route('home') }}?explore=true" class="back-btn" title="Back to Home">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <style>
        .back-btn-container {
            position: fixed;
            top: 25px;
            left: 25px;
            z-index: 9999;
        }
        .back-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background-color: white;
            color: #2e7d32;
            border-radius: 50%;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-size: 1.3rem;
            border: 2px solid #2e7d32;
        }
        .back-btn:hover {
            background-color: #2e7d32;
            color: white;
            transform: scale(1.15) rotate(-10deg);
            box-shadow: 0 6px 20px rgba(46, 125, 50, 0.3);
        }
    </style>

    <div class="container">
        <div class="weather-input">
            <div class="input-group">
                <input type="text" id="userLocation" placeholder="Search for places...">

                <select class="converter" id="converter">
                    <option>°C</option>
                    <option>°F</option>
                </select>

                <i class="fa fa-search" onclick="findLocation()"></i>
            </div>

            <div class="weatherIcon"></div>
            <h2 class="temperature">--<span>°C</span></h2>
            <div class="feelsLike">Feels like --</div>
            <div class="description">Loading weather...</div>
            <hr>
            <div class="date"></div>
            <div class="city"></div>
        </div>

        <div class="weather-output">
            <h2 class="heading">Today's Highlights</h2>
            <div class="Highlights">
                <div class="Humidity">
                    Humidity
                    <i class="fa-solid fa-water"></i>
                    <h1 id="HValue"></h1>
                </div>

                <div class="wind-speed">
                    Wind Speed
                    <i class="fa-solid fa-wind"></i>
                    <h1 id="WValue"></h1>
                </div>

                <div class="sun">
                    <span>
                        <i class="fa-solid fa-sun"></i>
                        <p><span id="SRValue"></span>Sunrise</p>
                    </span>

                    <span>
                        <i class="fa-regular fa-sun"></i>
                        <p><span id="SSValue"></span>Sunset</p>
                    </span>
                </div>

                <div class="clouds">
                    Clouds
                    <i class="fa-solid fa-cloud"></i>
                    <h1 id="CValue"></h1>
                </div>

                <div class="pressure">
                    Pressure
                    <i class="fa-solid fa-volcano"></i>
                    <h1 id="PValue"></h1>
                </div>
            </div>

            <br>

            <h2 class="heading">This week</h2>
            <div class="Forecast"></div>

            <h2 class="heading">Farming Recommendations</h2>
            <div class="recommendation-container">
                <div class="recommendation-text" id="recommendationText">
                    @foreach ($recommendations as $recommendation)
                        <p>{{ $recommendation }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('Weather/updatedScript.js') }}" defer></script>
</body>
</html>
