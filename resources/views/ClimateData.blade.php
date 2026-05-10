<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌍 Climate Data | FarmForecast</title>
    <meta name="description" content="Real-time agricultural climate data dashboard with farming indices, forecasts, and seasonal advice powered by FarmForecast.">
    <link rel="stylesheet" href="{{ asset('climate-data.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body data-fetch-url="{{ route('climate.fetch') }}" data-default-city="{{ $defaultCity }}">

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Fetching climate data…</p>
        </div>
    </div>

    <!-- ===== HEADER ===== -->
    <header class="climate-header">
        <div class="header-inner">
            <div class="header-brand">
                <div class="brand-icon">🌍</div>
                <div>
                    <h1>Climate Data</h1>
                    <p>Agricultural Climate Intelligence Dashboard</p>
                </div>
            </div>

            <div class="header-controls">
                <div class="search-box">
                    <i class="fa fa-search search-icon"></i>
                    <input type="text" id="cityInput" placeholder="Search city…" value="{{ $defaultCity }}">
                </div>
                <button class="btn-primary" id="searchBtn" onclick="loadClimateData()">
                    <i class="fa fa-satellite-dish"></i> Fetch Data
                </button>
                <a href="{{ route('home') }}" class="btn-secondary">
                    <i class="fa fa-arrow-left"></i> Home
                </a>
            </div>
        </div>
    </header>

    <!-- ===== ERROR BANNER ===== -->
    <div class="climate-main">
        <div class="error-banner" id="errorBanner">
            <i class="fa fa-circle-exclamation"></i>
            <span id="errorMessage"></span>
        </div>

        <!-- ===== QUICK STATS ===== -->
        <section class="quick-stats animate-in animate-delay-1" id="quickStats">
            <div class="stat-card" style="--stat-accent: #00e676">
                <div class="stat-label"><span class="stat-emoji">🌡️</span> Temperature</div>
                <div class="stat-value" id="statTemp">--°C</div>
                <div class="stat-sub" id="statFeels">Feels like --°C</div>
            </div>
            <div class="stat-card" style="--stat-accent: #00bcd4">
                <div class="stat-label"><span class="stat-emoji">💧</span> Humidity</div>
                <div class="stat-value" id="statHumidity">--%</div>
                <div class="stat-sub" id="statDewPoint">Dew point --°C</div>
            </div>
            <div class="stat-card" style="--stat-accent: #ff9100">
                <div class="stat-label"><span class="stat-emoji">💨</span> Wind Speed</div>
                <div class="stat-value" id="statWind">-- m/s</div>
                <div class="stat-sub" id="statWindDir">Direction --°</div>
            </div>
            <div class="stat-card" style="--stat-accent: #7c4dff">
                <div class="stat-label"><span class="stat-emoji">☁️</span> Cloud Cover</div>
                <div class="stat-value" id="statClouds">--%</div>
                <div class="stat-sub" id="statVisibility">Visibility -- km</div>
            </div>
            <div class="stat-card" style="--stat-accent: #ffc107">
                <div class="stat-label"><span class="stat-emoji">🌾</span> Crop Score</div>
                <div class="stat-value" id="statCropScore">--</div>
                <div class="stat-sub">Overall suitability</div>
            </div>
            <div class="stat-card" style="--stat-accent: #ff5252">
                <div class="stat-label"><span class="stat-emoji">🔥</span> Heat Index</div>
                <div class="stat-value" id="statHeatIndex">--°C</div>
                <div class="stat-sub" id="statUV">UV: --</div>
            </div>
        </section>

        <!-- ===== MAIN DASHBOARD ===== -->
        <div class="dashboard-grid">

            <!-- Weather Overview -->
            <div class="panel animate-in animate-delay-2">
                <div class="panel-header">
                    <div class="panel-title"><span class="title-dot"></span> Current Conditions</div>
                    <span class="panel-badge" id="liveTimestamp">Live</span>
                </div>
                <div class="weather-overview">
                    <div class="weather-main">
                        <div class="weather-icon" id="weatherEmoji">🌤️</div>
                        <div class="weather-temp" id="mainTemp">--°</div>
                        <div class="weather-desc" id="mainDesc">Loading…</div>
                        <div class="weather-location">
                            <i class="fa fa-location-dot" style="color: var(--clr-primary);"></i>
                            <span id="mainCity">{{ $defaultCity }}</span>
                        </div>
                    </div>
                    <div class="weather-details-grid">
                        <div class="detail-item">
                            <div class="detail-label">Pressure</div>
                            <div class="detail-value" id="detPressure">-- hPa</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Humidity</div>
                            <div class="detail-value" id="detHumidity">--%</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Wind</div>
                            <div class="detail-value" id="detWind">-- m/s</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Clouds</div>
                            <div class="detail-value" id="detClouds">--%</div>
                        </div>
                    </div>
                </div>

                <!-- Sun Timeline -->
                <div class="sun-timeline">
                    <div class="sun-time">
                        <span class="sun-emoji">🌅</span>
                        <span class="sun-label">Sunrise</span>
                        <span class="sun-value" id="sunRise">--:--</span>
                    </div>
                    <div class="sun-time">
                        <span class="sun-emoji">☀️</span>
                        <span class="sun-label">Solar Noon</span>
                        <span class="sun-value" id="solarNoon">--:--</span>
                    </div>
                    <div class="sun-time">
                        <span class="sun-emoji">🌇</span>
                        <span class="sun-label">Sunset</span>
                        <span class="sun-value" id="sunSet">--:--</span>
                    </div>
                </div>
            </div>

            <!-- Agriculture Indices -->
            <div class="panel animate-in animate-delay-3">
                <div class="panel-header">
                    <div class="panel-title"><span class="title-dot"></span> Agriculture Indices</div>
                    <span class="panel-badge">Smart Analysis</span>
                </div>

                <!-- Crop Score Gauge -->
                <div class="crop-score-gauge">
                    <div class="gauge-ring">
                        <svg width="120" height="120" viewBox="0 0 120 120">
                            <circle class="gauge-bg" cx="60" cy="60" r="50"></circle>
                            <circle class="gauge-fill" id="gaugeCircle" cx="60" cy="60" r="50"
                                    stroke-dasharray="314.16"
                                    stroke-dashoffset="314.16"></circle>
                        </svg>
                        <div class="gauge-text" id="gaugeValue">--</div>
                    </div>
                    <div class="gauge-info">
                        <h4>Crop Suitability Score</h4>
                        <p id="gaugeDesc">Analysing current weather conditions to determine overall crop growing suitability for your region.</p>
                    </div>
                </div>

                <div class="indices-grid" id="indicesGrid">
                    <div class="index-item">
                        <span class="index-emoji">🌡️</span>
                        <div class="index-name">Heat Index</div>
                        <div class="index-value" id="idxHeat">--</div>
                    </div>
                    <div class="index-item">
                        <span class="index-emoji">❄️</span>
                        <div class="index-name">Frost Risk</div>
                        <div class="index-value" id="idxFrost">--</div>
                        <div class="index-status" id="idxFrostBadge"></div>
                    </div>
                    <div class="index-item">
                        <span class="index-emoji">🏜️</span>
                        <div class="index-name">Drought Stress</div>
                        <div class="index-value" id="idxDrought">--</div>
                        <div class="index-status" id="idxDroughtBadge"></div>
                    </div>
                    <div class="index-item">
                        <span class="index-emoji">🌱</span>
                        <div class="index-name">GDD (Base 10°C)</div>
                        <div class="index-value" id="idxGDD">--</div>
                    </div>
                    <div class="index-item">
                        <span class="index-emoji">💦</span>
                        <div class="index-name">ET Estimate</div>
                        <div class="index-value" id="idxET">--</div>
                    </div>
                    <div class="index-item">
                        <span class="index-emoji">☀️</span>
                        <div class="index-name">UV Risk</div>
                        <div class="index-value" id="idxUV">--</div>
                    </div>
                </div>
            </div>

            <!-- Forecast Chart -->
            <div class="panel panel-full animate-in animate-delay-4">
                <div class="panel-header">
                    <div class="panel-title"><span class="title-dot"></span> 5-Day Forecast</div>
                    <div class="chart-tabs">
                        <button class="chart-tab active" data-chart="temp">Temperature</button>
                        <button class="chart-tab" data-chart="humidity">Humidity</button>
                        <button class="chart-tab" data-chart="wind">Wind</button>
                        <button class="chart-tab" data-chart="rain">Rainfall</button>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="forecastChart"></canvas>
                </div>

                <!-- Hourly Forecast Timeline -->
                <div style="margin-top: 1.25rem;">
                    <div style="font-size: 0.82rem; font-weight: 600; color: var(--clr-text-muted); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">
                        Hourly Forecast
                    </div>
                    <div class="forecast-timeline" id="forecastTimeline">
                        <!-- JS fills this -->
                    </div>
                </div>
            </div>

            <!-- Seasonal Advice -->
            <div class="panel animate-in animate-delay-5">
                <div class="panel-header">
                    <div class="panel-title"><span class="title-dot"></span> Seasonal Advice</div>
                    <span class="panel-badge">AI-Powered</span>
                </div>
                <div class="advice-grid" id="adviceGrid">
                    <!-- JS fills this -->
                </div>
            </div>

            <!-- Climate Zones -->
            <div class="panel animate-in animate-delay-5">
                <div class="panel-header">
                    <div class="panel-title"><span class="title-dot"></span> Indian Climate Zones</div>
                </div>
                <div class="zones-grid">
                    @foreach ($climateZones as $zone)
                        <div class="zone-chip" style="--zone-clr: {{ $zone['color'] }}">
                            <div class="zone-name">{{ $zone['name'] }}</div>
                            <div class="zone-region">{{ $zone['regions'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <footer class="climate-footer">
        <p>&copy; {{ date('Y') }} Climate Data Dashboard | FarmForecast &mdash; Agricultural Climate Intelligence</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        /* ============================================
           Climate Data Dashboard – Client Script
           ============================================ */

        const FETCH_URL  = document.body.dataset.fetchUrl;
        const DEFAULT_CITY = document.body.dataset.defaultCity;

        let forecastChart = null;
        let forecastData  = [];
        let activeChartType = 'temp';

        // ──── Weather icon mapper ────
        function weatherEmoji(iconCode) {
            const map = {
                '01d': '☀️', '01n': '🌙',
                '02d': '⛅', '02n': '☁️',
                '03d': '☁️', '03n': '☁️',
                '04d': '☁️', '04n': '☁️',
                '09d': '🌧️', '09n': '🌧️',
                '10d': '🌦️', '10n': '🌧️',
                '11d': '⛈️', '11n': '⛈️',
                '13d': '❄️', '13n': '❄️',
                '50d': '🌫️', '50n': '🌫️',
            };
            return map[iconCode] || '🌤️';
        }

        // ──── Utility: format unix timestamp ────
        function formatTime(unix, tz) {
            const d = new Date((unix + tz) * 1000);
            const h = d.getUTCHours().toString().padStart(2, '0');
            const m = d.getUTCMinutes().toString().padStart(2, '0');
            return `${h}:${m}`;
        }

        // ──── Risk-level badge helper ────
        function riskBadge(level) {
            const cls = {
                'Low': 'badge-low', 'Moderate': 'badge-moderate', 'Mild': 'badge-moderate',
                'High': 'badge-high', 'Severe': 'badge-severe', 'Very High': 'badge-severe',
                'Very Low': 'badge-good'
            };
            return `<span class="stat-badge ${cls[level] || 'badge-good'}">${level}</span>`;
        }

        // ──── Gauge animation ────
        function setGauge(score) {
            const circumference = 314.16;
            const offset = circumference - (circumference * score / 100);
            const circle = document.getElementById('gaugeCircle');
            circle.style.strokeDashoffset = offset;

            // Color
            if (score >= 70) circle.style.stroke = '#00e676';
            else if (score >= 40) circle.style.stroke = '#ffc107';
            else circle.style.stroke = '#ff5252';

            document.getElementById('gaugeValue').textContent = score;
        }

        // ──── Load climate data ────
        async function loadClimateData() {
            const city = document.getElementById('cityInput').value.trim() || DEFAULT_CITY;
            const overlay = document.getElementById('loadingOverlay');
            const errorBanner = document.getElementById('errorBanner');

            overlay.classList.remove('hidden');
            errorBanner.classList.remove('show');

            try {
                const res = await fetch(`${FETCH_URL}?city=${encodeURIComponent(city)}`);
                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data.message || 'Failed to fetch climate data.');
                }

                renderDashboard(data);
            } catch (err) {
                document.getElementById('errorMessage').textContent = err.message;
                errorBanner.classList.add('show');
            } finally {
                overlay.classList.add('hidden');
            }
        }

        // ──── Render the full dashboard ────
        function renderDashboard(data) {
            const c = data.current;
            const idx = data.indices;
            const tz = c.timezone;

            // Quick stats
            document.getElementById('statTemp').textContent       = `${c.temperature}°C`;
            document.getElementById('statFeels').textContent      = `Feels like ${c.feels_like}°C`;
            document.getElementById('statHumidity').textContent   = `${c.humidity}%`;
            document.getElementById('statDewPoint').textContent   = `Dew point ${idx.dew_point}°C`;
            document.getElementById('statWind').textContent       = `${c.wind_speed} m/s`;
            document.getElementById('statWindDir').textContent    = `Direction ${c.wind_deg}°`;
            document.getElementById('statClouds').textContent     = `${c.clouds}%`;
            document.getElementById('statVisibility').textContent = `Visibility ${(c.visibility / 1000).toFixed(1)} km`;
            document.getElementById('statCropScore').textContent  = `${idx.crop_score}/100`;
            document.getElementById('statHeatIndex').textContent  = `${idx.heat_index}°C`;
            document.getElementById('statUV').textContent         = `UV: ${idx.uv_estimate}`;

            // Weather overview
            document.getElementById('weatherEmoji').textContent = weatherEmoji(c.icon);
            document.getElementById('mainTemp').textContent     = `${Math.round(c.temperature)}°`;
            document.getElementById('mainDesc').textContent     = c.description;
            document.getElementById('mainCity').textContent     = `${data.city.name}, ${data.city.country}`;
            document.getElementById('detPressure').textContent  = `${c.pressure} hPa`;
            document.getElementById('detHumidity').textContent  = `${c.humidity}%`;
            document.getElementById('detWind').textContent      = `${c.wind_speed} m/s`;
            document.getElementById('detClouds').textContent    = `${c.clouds}%`;

            // Sun times
            document.getElementById('sunRise').textContent  = formatTime(c.sunrise, tz);
            document.getElementById('sunSet').textContent   = formatTime(c.sunset, tz);
            const noon = Math.round((c.sunrise + c.sunset) / 2);
            document.getElementById('solarNoon').textContent = formatTime(noon, tz);

            // Live timestamp
            document.getElementById('liveTimestamp').textContent = 'Live · ' + new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            // Indices
            document.getElementById('idxHeat').textContent    = `${idx.heat_index}°C`;
            document.getElementById('idxFrost').textContent   = idx.frost_risk;
            document.getElementById('idxFrostBadge').innerHTML = riskBadge(idx.frost_risk);
            document.getElementById('idxDrought').textContent = idx.drought_index;
            document.getElementById('idxDroughtBadge').innerHTML = riskBadge(idx.drought_index);
            document.getElementById('idxGDD').textContent     = `${idx.gdd}°C·d`;
            document.getElementById('idxET').textContent      = `${idx.et_estimate} mm`;
            document.getElementById('idxUV').textContent      = idx.uv_estimate;

            // Gauge
            setGauge(idx.crop_score);
            const gaugeTexts = {
                high:   'Excellent growing conditions. Crops should thrive under current temperature and humidity levels.',
                medium: 'Moderate growing conditions. Some stress factors present — monitor crops closely.',
                low:    'Poor growing conditions. High stress from temperature, humidity, or wind. Take protective measures.'
            };
            document.getElementById('gaugeDesc').textContent =
                idx.crop_score >= 70 ? gaugeTexts.high :
                idx.crop_score >= 40 ? gaugeTexts.medium : gaugeTexts.low;

            // Forecast
            forecastData = data.forecast || [];
            renderForecastChart(activeChartType);
            renderForecastTimeline();

            // Seasonal advice
            renderAdvice(data.seasonalAdvice || []);
        }

        // ──── Forecast Chart ────
        function renderForecastChart(type) {
            activeChartType = type;
            const ctx = document.getElementById('forecastChart');

            if (forecastChart) forecastChart.destroy();

            const labels = forecastData.map(f => {
                const d = new Date(f.dt * 1000);
                return d.toLocaleDateString('en', { weekday: 'short', day: 'numeric' }) + ' ' + f.time;
            });

            let values, label, color, bgColor;
            switch (type) {
                case 'humidity':
                    values = forecastData.map(f => f.humidity);
                    label = 'Humidity (%)';
                    color = '#00bcd4';
                    bgColor = 'rgba(0,188,212,0.12)';
                    break;
                case 'wind':
                    values = forecastData.map(f => f.wind_speed);
                    label = 'Wind Speed (m/s)';
                    color = '#ff9100';
                    bgColor = 'rgba(255,145,0,0.12)';
                    break;
                case 'rain':
                    values = forecastData.map(f => f.rain);
                    label = 'Rainfall (mm)';
                    color = '#7c4dff';
                    bgColor = 'rgba(124,77,255,0.12)';
                    break;
                default:
                    values = forecastData.map(f => f.temp);
                    label = 'Temperature (°C)';
                    color = '#00e676';
                    bgColor = 'rgba(0,230,118,0.12)';
            }

            forecastChart = new Chart(ctx, {
                type: type === 'rain' ? 'bar' : 'line',
                data: {
                    labels,
                    datasets: [{
                        label,
                        data: values,
                        borderColor: color,
                        backgroundColor: bgColor,
                        borderWidth: 2.5,
                        pointRadius: 3,
                        pointBackgroundColor: color,
                        tension: 0.35,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(22, 34, 49, 0.95)',
                            titleColor: '#e0e6ed',
                            bodyColor: '#e0e6ed',
                            borderColor: 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 10,
                            titleFont: { family: 'Inter', size: 13, weight: 600 },
                            bodyFont: { family: 'Inter', size: 12 },
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255,255,255,0.04)' },
                            ticks: {
                                color: '#8a9bb5',
                                font: { family: 'Inter', size: 10 },
                                maxRotation: 45,
                                maxTicksLimit: 16,
                            }
                        },
                        y: {
                            grid: { color: 'rgba(255,255,255,0.04)' },
                            ticks: {
                                color: '#8a9bb5',
                                font: { family: 'Inter', size: 11 },
                            }
                        }
                    }
                }
            });
        }

        // ──── Forecast Timeline ────
        function renderForecastTimeline() {
            const container = document.getElementById('forecastTimeline');
            container.innerHTML = '';

            const slots = forecastData.slice(0, 16);
            slots.forEach(f => {
                const slot = document.createElement('div');
                slot.className = 'forecast-slot';
                slot.innerHTML = `
                    <div class="slot-time">${f.time}</div>
                    <div class="slot-icon">${weatherEmoji(f.icon)}</div>
                    <div class="slot-temp">${Math.round(f.temp)}°</div>
                    ${f.pop > 0 ? `<div class="slot-rain">💧 ${Math.round(f.pop * 100)}%</div>` : ''}
                `;
                container.appendChild(slot);
            });
        }

        // ──── Advice Cards ────
        function renderAdvice(items) {
            const grid = document.getElementById('adviceGrid');
            grid.innerHTML = '';

            if (!items.length) {
                grid.innerHTML = '<p style="color: var(--clr-text-muted); font-size: 0.85rem;">No specific advice for current conditions.</p>';
                return;
            }

            items.forEach(item => {
                const card = document.createElement('div');
                card.className = 'advice-card';
                card.innerHTML = `
                    <div class="advice-icon">${item.icon}</div>
                    <div>
                        <div class="advice-title">${item.title}</div>
                        <div class="advice-text">${item.text}</div>
                    </div>
                `;
                grid.appendChild(card);
            });
        }

        // ──── Event Listeners ────
        document.addEventListener('DOMContentLoaded', () => {
            loadClimateData();

            // Enter key in search
            document.getElementById('cityInput').addEventListener('keypress', (e) => {
                if (e.key === 'Enter') loadClimateData();
            });

            // Chart tabs
            document.querySelectorAll('.chart-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    document.querySelectorAll('.chart-tab').forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    renderForecastChart(tab.dataset.chart);
                });
            });
        });
    </script>
</body>
</html>
