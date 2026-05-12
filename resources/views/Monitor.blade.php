<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-time Field Monitoring | FarmForecast</title>
    <link rel="stylesheet" href="monitoring-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
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

    <div class="monitoring-dashboard">
        <h2>Real-time Field Monitoring</h2>

        <div class="dashboard-nav">
            <div class="date-display">
                <i class="fas fa-calendar-alt"></i> <span id="current-date">Loading...</span>
            </div>
            <div class="dashboard-controls">
                <select id="city-selector" class="city-selector" style="padding: 5px 10px; border-radius: 5px; border: 1px solid #ccc; margin-right: 10px; font-weight: bold; color: #2c3e50;">
                    <option value="Jaipur" selected>Jaipur</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Bangalore">Bangalore</option>
                    <option value="Hyderabad">Hyderabad</option>
                    <option value="Chennai">Chennai</option>
                    <option value="Kolkata">Kolkata</option>
                    <option value="Ahmedabad">Ahmedabad</option>
                    <option value="Pune">Pune</option>
                    <option value="Surat">Surat</option>
                    <option value="Indore">Indore</option>
                    <option value="Chandigarh">Chandigarh</option>
                    <option value="Lucknow">Lucknow</option>
                    <option value="Bhopal">Bhopal</option>
                    <option value="Patna">Patna</option>
                </select>
                <button id="refresh-btn"><i class="fas fa-sync-alt"></i> Refresh</button>
                <button id="export-btn"><i class="fas fa-download"></i> Export</button>
            </div>
        </div>

        <div class="sensor-data">
            <div class="data-item">
                <i class="fas fa-thermometer-half"></i>
                <h3>Temperature</h3>
                <p><span id="temperature">--</span> °C</p>
                <div class="sensor-trend" id="temp-trend"></div>
                <div class="status-indicator" id="temp-status"></div>
            </div>
            <div class="data-item">
                <i class="fas fa-tint"></i>
                <h3>Humidity</h3>
                <p><span id="humidity">--</span> %</p>
                <div class="sensor-trend" id="humidity-trend"></div>
                <div class="status-indicator" id="humidity-status"></div>
            </div>
            <div class="data-item">
                <i class="fas fa-water"></i>
                <h3>Soil Moisture</h3>
                <p><span id="soil-moisture">--</span> %</p>
                <div class="sensor-trend" id="moisture-trend"></div>
                <div class="status-indicator" id="moisture-status"></div>
            </div>
            <div class="data-item">
                <i class="fas fa-sun"></i>
                <h3>Solar Radiation</h3>
                <p><span id="solar-radiation">--</span> W/m²</p>
                <div class="sensor-trend" id="radiation-trend"></div>
                <div class="status-indicator" id="radiation-status"></div>
            </div>
        </div>

        <div class="data-preview">
            <h3>Historical Data & Trends</h3>

            <div class="parameter-selector">
                <label><input type="checkbox" class="chart-param" value="temperature" checked> Temperature</label>
                <label><input type="checkbox" class="chart-param" value="humidity" checked> Humidity</label>
                <label><input type="checkbox" class="chart-param" value="soil-moisture"> Soil Moisture</label>
                <label><input type="checkbox" class="chart-param" value="solar-radiation"> Solar Radiation</label>
            </div>

            <div class="chart-container">
                <canvas id="monitoring-chart"></canvas>
            </div>

            <div class="data-actions">
                <button id="settings-btn" style="width: 100%;"><i class="fas fa-cog"></i> Settings</button>
            </div>
        </div>

        <footer>
            <p>&copy; <span id="current-year">2024</span> Real-time Field Monitoring | Last update: <span id="last-update">Loading...</span></p>
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script>
        // Current date and time display
        function updateDateTime() {
            const now = new Date();
            document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('current-year').textContent = now.getFullYear();
            document.getElementById('last-update').textContent = now.toLocaleTimeString('en-US');
        }

        updateDateTime();
        setInterval(updateDateTime, 60000); // Update every minute

        // Dashboard global settings
        let dashboardSettings = {
            updateFrequency: 5000, // default 5 seconds
            tempUnit: 'metric'     // 'metric' for C, 'imperial' for F
        };

        let dataUpdateInterval;

        // Simulated sensor data
        let sensorData = {
            temperature: {
                current: 0,
                history: [],
                min: 15,
                max: 35,
                trend: 'stable',
                status: 'normal'
            },
            humidity: {
                current: 0,
                history: [],
                min: 30,
                max: 80,
                trend: 'stable',
                status: 'normal'
            },
            soilMoisture: {
                current: 0,
                history: [],
                min: 20,
                max: 60,
                trend: 'stable',
                status: 'normal'
            },
            solarRadiation: {
                current: 0,
                history: [],
                min: 0,
                max: 1000,
                trend: 'stable',
                status: 'normal'
            }
        };

        // Generate random data points for the last 24 hours
        function generateHistoricalData() {
            const hoursData = 24;
            const now = new Date();

            ['temperature', 'humidity', 'soilMoisture', 'solarRadiation'].forEach(param => {
                sensorData[param].history = [];

                for (let i = hoursData; i >= 0; i--) {
                    const time = new Date(now);
                    time.setHours(now.getHours() - i);

                    let value;
                    if (param === 'temperature') {
                        // Temperature follows a pattern - cooler at night, warmer during day
                        const hour = time.getHours();
                        const baseTemp = 25; // Base daytime temperature
                        const amplitude = 8; // Daily temperature range
                        value = baseTemp - amplitude * Math.cos((hour / 24) * 2 * Math.PI);
                        value += (Math.random() * 2) - 1; // Add some noise
                    } else if (param === 'humidity') {
                        // Humidity often inversely related to temperature
                        const hour = time.getHours();
                        const baseHumidity = 60;
                        const amplitude = 20;
                        value = baseHumidity + amplitude * Math.cos((hour / 24) * 2 * Math.PI);
                        value += (Math.random() * 5) - 2.5;
                    } else if (param === 'soilMoisture') {
                        // Soil moisture decreases over time with occasional spikes (irrigation)
                        const baseValue = 40;
                        value = baseValue - (i * 0.3) + (Math.random() * 5);

                        // Simulate irrigation every 8 hours
                        if (i % 8 === 0) {
                            value += 15;
                        }

                        value = Math.max(15, Math.min(60, value));
                    } else {
                        // Solar radiation follows a bell curve during the day with zero at night
                        const hour = time.getHours();
                        if (hour >= 6 && hour <= 18) { // Daylight hours
                            const peak = 12; // Noon
                            const maxRadiation = 900;
                            value = maxRadiation * Math.exp(-0.5 * Math.pow((hour - peak) / 4, 2));
                            value += (Math.random() * 100) - 50;
                        } else {
                            value = Math.random() * 5; // Near zero at night
                        }
                    }

                    sensorData[param].history.push({
                        time: time,
                        value: Math.round(value * 10) / 10
                    });
                }

                // Set current value to the latest
                sensorData[param].current = sensorData[param].history[sensorData[param].history.length - 1].value;
            });
        }

        generateHistoricalData();

        // Update sensor displays
        function updateSensorDisplays() {
            // Temperature conversion
            let displayTemp = sensorData.temperature.current;
            let tempSymbol = "°C";
            
            if (dashboardSettings.tempUnit === 'imperial') {
                displayTemp = (displayTemp * 9/5) + 32;
                tempSymbol = "°F";
            }
            
            document.getElementById('temperature').textContent = displayTemp.toFixed(1);
            document.querySelector('.data-item:nth-child(1) p').innerHTML = `<span id="temperature">${displayTemp.toFixed(1)}</span> ${tempSymbol}`;
            updateStatusAndTrend('temp', sensorData.temperature);

            // Humidity
            document.getElementById('humidity').textContent = sensorData.humidity.current;
            updateStatusAndTrend('humidity', sensorData.humidity);

            // Soil Moisture
            document.getElementById('soil-moisture').textContent = sensorData.soilMoisture.current;
            updateStatusAndTrend('moisture', sensorData.soilMoisture);

            // Solar Radiation
            document.getElementById('solar-radiation').textContent = sensorData.solarRadiation.current;
            updateStatusAndTrend('radiation', sensorData.solarRadiation);
        }

        // Update status indicators and trends
        function updateStatusAndTrend(elementId, sensorInfo) {
            const statusElement = document.getElementById(`${elementId}-status`);
            const trendElement = document.getElementById(`${elementId}-trend`);

            // Calculate status
            if (sensorInfo.current < sensorInfo.min || sensorInfo.current > sensorInfo.max) {
                statusElement.className = 'status-indicator status-alert';
                sensorInfo.status = 'alert';
            } else if (sensorInfo.current < sensorInfo.min + (sensorInfo.max - sensorInfo.min) * 0.2 ||
                sensorInfo.current > sensorInfo.max - (sensorInfo.max - sensorInfo.min) * 0.2) {
                statusElement.className = 'status-indicator status-warning';
                sensorInfo.status = 'warning';
            } else {
                statusElement.className = 'status-indicator status-normal';
                sensorInfo.status = 'normal';
            }

            // Calculate trend based on last few values
            const history = sensorInfo.history;
            if (history.length >= 3) {
                const last = history[history.length - 1].value;
                const prev = history[history.length - 3].value;
                const diff = last - prev;

                if (diff > 2) {
                    trendElement.innerHTML = '<i class="fas fa-arrow-up"></i> Rising';
                    trendElement.className = 'sensor-trend trend-up';
                    sensorInfo.trend = 'rising';
                } else if (diff < -2) {
                    trendElement.innerHTML = '<i class="fas fa-arrow-down"></i> Falling';
                    trendElement.className = 'sensor-trend trend-down';
                    sensorInfo.trend = 'falling';
                } else {
                    trendElement.innerHTML = '<i class="fas fa-arrows-alt-h"></i> Stable';
                    trendElement.className = 'sensor-trend trend-stable';
                    sensorInfo.trend = 'stable';
                }
            }
        }

        // Initialize chart
        let monitoringChart;

        function initChart() {
            if (monitoringChart) {
                monitoringChart.destroy();
            }
            
            const ctx = document.getElementById('monitoring-chart').getContext('2d');

            const timeLabels = sensorData.temperature.history.map(item => {
                return item.time.getHours() + ':00';
            });

            monitoringChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: timeLabels,
                    datasets: [{
                            label: `Temperature (${dashboardSettings.tempUnit === 'metric' ? '°C' : '°F'})`,
                            data: sensorData.temperature.history.map(item => {
                                let val = item.value;
                                if (dashboardSettings.tempUnit === 'imperial') val = (val * 9/5) + 32;
                                return Math.round(val * 10) / 10;
                            }),
                            borderColor: '#e74c3c',
                            backgroundColor: 'rgba(231, 76, 60, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Humidity (%)',
                            data: sensorData.humidity.history.map(item => item.value),
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Time (Hours)',
                                color: '#2c3e50',
                                font: {
                                    weight: 'bold'
                                }
                            }
                        },
                        y: {
                            beginAtZero: false,
                            display: true,
                            title: {
                                display: true,
                                text: 'Measured Value',
                                color: '#2c3e50',
                                font: {
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        }

        // Update chart based on selected parameters
        function updateChartParameters() {
            const checkboxes = document.querySelectorAll('.chart-param');
            const datasets = [];

            if (checkboxes[0].checked) { // Temperature
                datasets.push({
                    label: `Temperature (${dashboardSettings.tempUnit === 'metric' ? '°C' : '°F'})`,
                    data: sensorData.temperature.history.map(item => {
                        let val = item.value;
                        if (dashboardSettings.tempUnit === 'imperial') val = (val * 9/5) + 32;
                        return Math.round(val * 10) / 10;
                    }),
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
                    tension: 0.4,
                    fill: true
                });
            }

            if (checkboxes[1].checked) { // Humidity
                datasets.push({
                    label: 'Humidity (%)',
                    data: sensorData.humidity.history.map(item => item.value),
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    tension: 0.4,
                    fill: true
                });
            }

            if (checkboxes[2].checked) { // Soil Moisture
                datasets.push({
                    label: 'Soil Moisture (%)',
                    data: sensorData.soilMoisture.history.map(item => item.value),
                    borderColor: '#f39c12',
                    backgroundColor: 'rgba(243, 156, 18, 0.1)',
                    tension: 0.4,
                    fill: true
                });
            }

            if (checkboxes[3].checked) { // Solar Radiation
                datasets.push({
                    label: 'Solar Radiation (W/m²)',
                    data: sensorData.solarRadiation.history.map(item => item.value),
                    borderColor: '#9b59b6',
                    backgroundColor: 'rgba(155, 89, 182, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                });

                monitoringChart.options.scales.y1 = {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Radiation (W/m²)',
                        color: '#9b59b6',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                };
            } else {
                // Remove second y-axis if solar radiation is not selected
                if (monitoringChart.options.scales.y1) {
                    delete monitoringChart.options.scales.y1;
                }
            }

            monitoringChart.data.datasets = datasets;
            monitoringChart.update();
        }

        // Fetch real data from our backend API
        async function fetchAndUpdateData() {
            const city = document.getElementById("city-selector").value;
            const url = `/climate-data/fetch?city=${city}`;

            try {
                const res = await fetch(url);
                const data = await res.json();

                if (data.current) {
                    sensorData.temperature.current = data.current.temperature;
                    sensorData.humidity.current = data.current.humidity;
                    // Soil moisture isn't in OpenWeather, so we use indices or simulate if missing
                    // Let's use a derived value or the random one if preferred, but let's try to be consistent
                    sensorData.soilMoisture.current = Math.floor(Math.random() * 30 + 30); 
                    // Solar radiation can be estimated from clouds
                    sensorData.solarRadiation.current = data.current.clouds * 10;
                }

                // Update latest values in history array
                const now = new Date();

                ['temperature', 'humidity', 'soilMoisture', 'solarRadiation'].forEach(param => {
                    sensorData[param].history[sensorData[param].history.length - 1] = {
                        time: now,
                        value: sensorData[param].current
                    };
                });

                // Update displays
                updateSensorDisplays();

                // Update chart if it exists
                if (monitoringChart) {
                    monitoringChart.data.datasets.forEach((dataset) => {
                        if (dataset.label.includes('Temperature')) {
                            dataset.data[dataset.data.length - 1] = sensorData.temperature.current;
                        } else if (dataset.label.includes('Humidity')) {
                            dataset.data[dataset.data.length - 1] = sensorData.humidity.current;
                        } else if (dataset.label.includes('Soil Moisture')) {
                            dataset.data[dataset.data.length - 1] = sensorData.soilMoisture.current;
                        } else if (dataset.label.includes('Solar Radiation')) {
                            dataset.data[dataset.data.length - 1] = sensorData.solarRadiation.current;
                        }
                    });
                    monitoringChart.update();
                }

                // Update Forecast if data is available
                if (data.forecast) {
                    updateWeatherForecast(data.forecast);
                }
            } catch (err) {
                console.error('Error fetching weather data:', err);
            }
        }

        // Event handlers
        document.getElementById('city-selector').addEventListener('change', function() {
            fetchAndUpdateData();
        });

        document.getElementById('refresh-btn').addEventListener('click', function() {
            this.classList.add('rotating');
            setTimeout(() => {
                this.classList.remove('rotating');
                generateHistoricalData();
                updateSensorDisplays();
                if (monitoringChart) {
                    monitoringChart.destroy();
                }
                initChart();
                updateChartParameters();
            }, 1000);
        });

        document.getElementById('export-btn').addEventListener('click', function() {
            const rows = [
                ["Date & Time", "Temperature (°C)", "Humidity (%)", "Soil Moisture (%)", "Solar Radiation (W/m²)"],
            ];

            // Use temperature history as the primary timeline
            sensorData.temperature.history.forEach((item, index) => {
                const dateTime = item.time.toLocaleString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });

                rows.push([
                    `"${dateTime}"`,
                    sensorData.temperature.history[index].value,
                    sensorData.humidity.history[index].value,
                    sensorData.soilMoisture.history[index].value,
                    sensorData.solarRadiation.history[index].value
                ]);
            });

            let csvContent = "data:text/csv;charset=utf-8," 
                + rows.map(e => e.join(",")).join("\n");

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `FarmForecast_Report_${timestamp}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            showAlert('Field report exported successfully!', 'success');
        });

        document.getElementById('settings-btn').addEventListener('click', function() {
            const settingsModal = document.createElement('div');
            settingsModal.id = 'settings-modal';
            settingsModal.style.position = 'fixed';
            settingsModal.style.top = '0';
            settingsModal.style.left = '0';
            settingsModal.style.width = '100%';
            settingsModal.style.height = '100%';
            settingsModal.style.backgroundColor = 'rgba(0,0,0,0.5)';
            settingsModal.style.display = 'flex';
            settingsModal.style.justifyContent = 'center';
            settingsModal.style.alignItems = 'center';
            settingsModal.style.zIndex = '1001';
            
            const freqInSeconds = dashboardSettings.updateFrequency / 1000;
            
            settingsModal.innerHTML = `
                <div style="background: white; padding: 2rem; border-radius: 10px; max-width: 400px; width: 90%;">
                    <h3 style="margin-bottom: 1rem;">Dashboard Settings</h3>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem;">Update Frequency (seconds)</label>
                        <input type="number" id="update-freq-input" value="${freqInSeconds}" min="1" max="300" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem;">Temperature Unit</label>
                        <select id="temp-unit-select" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="metric" ${dashboardSettings.tempUnit === 'metric' ? 'selected' : ''}>Celsius (°C)</option>
                            <option value="imperial" ${dashboardSettings.tempUnit === 'imperial' ? 'selected' : ''}>Fahrenheit (°F)</option>
                        </select>
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 2rem;">
                        <button id="close-settings" style="background: #95a5a6;">Cancel</button>
                        <button id="save-settings">Save Changes</button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(settingsModal);
            
            document.getElementById('close-settings').onclick = () => settingsModal.remove();
            document.getElementById('save-settings').onclick = () => {
                const newFreq = parseInt(document.getElementById('update-freq-input').value) * 1000;
                const newUnit = document.getElementById('temp-unit-select').value;
                
                dashboardSettings.updateFrequency = newFreq;
                dashboardSettings.tempUnit = newUnit;
                
                settingsModal.remove();
                showAlert('Settings saved and applied!', 'success');
                
                // Restart interval with new frequency
                clearInterval(dataUpdateInterval);
                dataUpdateInterval = setInterval(fetchAndUpdateData, dashboardSettings.updateFrequency);
                
                // Immediate update for units
                updateSensorDisplays();
                initChart(); // Re-init chart to update axes if needed
                updateChartParameters();
            };
        });

        // Parameter selection for chart
        document.querySelectorAll('.chart-param').forEach(checkbox => {
            checkbox.addEventListener('change', updateChartParameters);
        });

        // Initialize
        updateSensorDisplays();
        initChart();

        // Start real-time updates
        fetchAndUpdateData(); // Initial call
        dataUpdateInterval = setInterval(fetchAndUpdateData, dashboardSettings.updateFrequency);

        // Add rotating animation for refresh button
        document.head.insertAdjacentHTML('beforeend', `
            <style>
                @keyframes rotating {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                .rotating i {
                    animation: rotating 1s linear infinite.rotating i {
                    animation: rotating 1s linear infinite;
                }
            </style>
        `);

        // Add alert functions for notifications
        function showAlert(message, type = 'info') {
            // Create alert element
            const alertEl = document.createElement('div');
            alertEl.className = `system-alert alert-${type}`;
            alertEl.innerHTML = `
                <div class="alert-icon">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                                    type === 'warning' ? 'fa-exclamation-triangle' : 
                                    type === 'error' ? 'fa-times-circle' : 'fa-info-circle'}"></i>
                </div>
                <div class="alert-content">${message}</div>
                <button class="alert-close"><i class="fas fa-times"></i></button>
            `;

            // Add to DOM
            document.body.appendChild(alertEl);

            // Style for alerts
            alertEl.style.position = 'fixed';
            alertEl.style.top = '20px';
            alertEl.style.right = '20px';
            alertEl.style.backgroundColor = type === 'success' ? '#2ecc71' :
                type === 'warning' ? '#f39c12' :
                type === 'error' ? '#e74c3c' : '#3498db';
            alertEl.style.color = 'white';
            alertEl.style.padding = '15px';
            alertEl.style.borderRadius = '5px';
            alertEl.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            alertEl.style.display = 'flex';
            alertEl.style.alignItems = 'center';
            alertEl.style.maxWidth = '400px';
            alertEl.style.zIndex = '1000';
            alertEl.style.transition = 'all 0.3s ease';
            alertEl.style.animation = 'slideIn 0.3s forwards';

            // Alert animation
            const styleEl = document.createElement('style');
            styleEl.innerHTML = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
                
                .alert-icon {
                    margin-right: 15px;
                    font-size: 24px;
                }
                
                .alert-content {
                    flex: 1;
                }
                
                .alert-close {
                    background: none;
                    border: none;
                    color: white;
                    cursor: pointer;
                    padding: 5px;
                    margin-left: 10px;
                    opacity: 0.7;
                    transition: opacity 0.3s;
                }
                
                .alert-close:hover {
                    opacity: 1;
                }
            `;
            document.head.appendChild(styleEl);

            // Close button functionality
            const closeBtn = alertEl.querySelector('.alert-close');
            closeBtn.addEventListener('click', () => {
                alertEl.style.animation = 'slideOut 0.3s forwards';
                setTimeout(() => {
                    alertEl.remove();
                }, 300);
            });

            // Auto-dismiss
            setTimeout(() => {
                if (alertEl.parentNode) {
                    alertEl.style.animation = 'slideOut 0.3s forwards';
                    setTimeout(() => {
                        if (alertEl.parentNode) {
                            alertEl.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }

        // Threshold monitoring functionality
        function monitorThresholds() {
            // Check for temperature alerts
            if (sensorData.temperature.current > 32 && sensorData.temperature.status === 'alert') {
                showAlert('⚠️ High temperature alert! Current reading: ' + sensorData.temperature.current + '°C', 'warning');
            } else if (sensorData.temperature.current < 18 && sensorData.temperature.status === 'alert') {
                showAlert('❄️ Low temperature alert! Current reading: ' + sensorData.temperature.current + '°C', 'warning');
            }

            // Check for soil moisture alerts
            if (sensorData.soilMoisture.current < 25 && sensorData.soilMoisture.status === 'alert') {
                showAlert('🌱 Low soil moisture alert! Plants may need watering.', 'error');
            }

            // Check for combined conditions (example of more complex alerts)
            if (sensorData.temperature.current > 30 && sensorData.humidity.current < 40) {
                showAlert('🌡️ Hot and dry conditions detected. Consider irrigation.', 'warning');
            }
        }

        // Run threshold monitoring every 10 seconds
        setInterval(monitorThresholds, 10000);

        // Simulated irrigation system control
        let irrigationActive = false;

        function toggleIrrigation() {
            irrigationActive = !irrigationActive;

            if (irrigationActive) {
                showAlert('🚿 Irrigation system activated', 'success');
                document.getElementById('irrigation-btn').innerHTML = '<i class="fas fa-stop"></i> Stop Irrigation';
                document.getElementById('irrigation-btn').classList.add('active-irrigation');

                // Simulate soil moisture increase when irrigation is active
                const irrigationInterval = setInterval(() => {
                    if (irrigationActive && sensorData.soilMoisture.current < 55) {
                        sensorData.soilMoisture.current += 0.5;
                        sensorData.soilMoisture.current = Math.round(sensorData.soilMoisture.current * 10) / 10;
                        updateSensorDisplays();
                    } else if (sensorData.soilMoisture.current >= 55) {
                        // Auto-stop when moisture is high enough
                        irrigationActive = false;
                        document.getElementById('irrigation-btn').innerHTML = '<i class="fas fa-play"></i> Start Irrigation';
                        document.getElementById('irrigation-btn').classList.remove('active-irrigation');
                        showAlert('🌱 Irrigation completed - soil moisture optimal', 'success');
                        clearInterval(irrigationInterval);
                    }
                }, 1000);

            } else {
                showAlert('🚿 Irrigation system stopped', 'info');
                document.getElementById('irrigation-btn').innerHTML = '<i class="fas fa-play"></i> Start Irrigation';
                document.getElementById('irrigation-btn').classList.remove('active-irrigation');
            }
        }

        // Add irrigation control button to the UI
        function addIrrigationControl() {
            const controlsDiv = document.querySelector('.dashboard-controls');
            const irrigationBtn = document.createElement('button');
            irrigationBtn.id = 'irrigation-btn';
            irrigationBtn.innerHTML = '<i class="fas fa-play"></i> Start Irrigation';
            irrigationBtn.style.backgroundColor = '#2ecc71';

            irrigationBtn.addEventListener('click', toggleIrrigation);
            controlsDiv.appendChild(irrigationBtn);

            // Add style for active irrigation
            const style = document.createElement('style');
            style.textContent = `
                .active-irrigation {
                    background-color: #e74c3c !important;
                    animation: pulse-button 2s infinite;
                }
                
                @keyframes pulse-button {
                    0% { opacity: 1; }
                    50% { opacity: 0.7; }
                    100% { opacity: 1; }
                }
            `;
            document.head.appendChild(style);
        }

        // Update weather forecast section with dynamic data
        function updateWeatherForecast(forecastData) {
            let weatherSection = document.querySelector('.weather-forecast');
            
            if (!weatherSection) {
                const dashboardDiv = document.querySelector('.monitoring-dashboard');
                weatherSection = document.createElement('div');
                weatherSection.className = 'weather-forecast';
                
                // Add initial styling once
                const style = document.createElement('style');
                style.textContent = `
                    .weather-forecast {
                        background: white;
                        padding: 2rem;
                        border-radius: 8px;
                        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
                        margin-top: 2rem;
                    }
                    .weather-forecast h3 {
                        margin-bottom: 1.5rem;
                        color: #2c3e50;
                        position: relative;
                        padding-bottom: 0.5rem;
                    }
                    .weather-forecast h3:after {
                        content: '';
                        position: absolute;
                        bottom: 0;
                        left: 0;
                        width: 50px;
                        height: 2px;
                        background-color: #2ecc71;
                    }
                    .forecast-container {
                        display: flex;
                        justify-content: space-between;
                        overflow-x: auto;
                        gap: 1rem;
                        padding-bottom: 5px;
                    }
                    .forecast-day {
                        text-align: center;
                        padding: 1rem;
                        min-width: 140px;
                        background-color: #f8f9fa;
                        border-radius: 8px;
                        transition: transform 0.3s;
                    }
                    .forecast-day:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                    }
                    .forecast-date { font-weight: bold; margin-bottom: 0.5rem; }
                    .forecast-icon { font-size: 2rem; margin: 0.5rem 0; color: #f39c12; filter: drop-shadow(0 0 5px rgba(243, 156, 18, 0.3)); }
                    .forecast-temp { font-size: 1.5rem; font-weight: bold; margin: 0.5rem 0; color: #2c3e50; }
                    .forecast-condition { color: #555; font-size: 0.9rem; text-transform: capitalize; font-weight: 500; }
                `;
                document.head.appendChild(style);
                
                const footer = document.querySelector('footer');
                dashboardDiv.insertBefore(weatherSection, footer);
            }

            // Process forecast data to get daily summaries (it's 3-hourly)
            const dailyForecasts = {};
            forecastData.forEach(item => {
                const date = new Date(item.dt * 1000).toLocaleDateString('en-US', { weekday: 'short', day: 'numeric', month: 'short' });
                if (!dailyForecasts[date]) {
                    dailyForecasts[date] = item;
                }
            });

            weatherSection.innerHTML = '<h3>5-Day Weather Forecast</h3>';
            const forecastContainer = document.createElement('div');
            forecastContainer.className = 'forecast-container';

            Object.keys(dailyForecasts).slice(0, 5).forEach(date => {
                const day = dailyForecasts[date];
                const iconCode = day.icon;
                
                // Map OpenWeather icons to FontAwesome for better coloring control
                let faIcon = 'fa-sun';
                let iconColor = '#f1c40f'; // Default Yellow for Sun
                
                if (iconCode.includes('01')) {
                    faIcon = 'fa-sun';
                    iconColor = '#f1c40f'; // Yellow Sun
                } else if (iconCode.includes('02') || iconCode.includes('03') || iconCode.includes('04')) {
                    faIcon = 'fa-cloud';
                    iconColor = '#3498db'; // Blue Cloud
                } else if (iconCode.includes('09') || iconCode.includes('10')) {
                    faIcon = 'fa-cloud-showers-heavy';
                    iconColor = '#3498db'; // Blue for rain clouds
                } else if (iconCode.includes('11')) {
                    faIcon = 'fa-bolt';
                    iconColor = '#f1c40f'; // Yellow for lightning
                } else if (iconCode.includes('13')) {
                    faIcon = 'fa-snowflake';
                    iconColor = '#3498db'; // Blue for snow
                } else if (iconCode.includes('50')) {
                    faIcon = 'fa-smog';
                    iconColor = '#95a5a6'; // Grey for mist
                }
                
                forecastContainer.innerHTML += `
                    <div class="forecast-day">
                        <div class="forecast-date">${date}</div>
                        <div class="forecast-icon"><i class="fas ${faIcon}" style="color: ${iconColor};"></i></div>
                        <div class="forecast-temp">${Math.round(day.temp)}°C</div>
                        <div class="forecast-condition">${day.description}</div>
                    </div>
                `;
            });

            weatherSection.appendChild(forecastContainer);
        }

        // Call additional UI enhancements after initial load
        setTimeout(() => {
            addIrrigationControl();
            // Initial forecast will be loaded by fetchAndUpdateData()

            // Show welcome message
            showAlert('🌱 Welcome to the Field Monitoring Dashboard! Real-time data is now streaming.', 'success');
        }, 1000);
    </script>
</body>

</html>