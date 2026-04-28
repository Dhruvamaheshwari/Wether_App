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
    <div class="monitoring-dashboard">
        <h2>Real-time Field Monitoring</h2>
        
        <div class="dashboard-nav">
            <div class="date-display">
                <i class="fas fa-calendar-alt"></i> <span id="current-date">Loading...</span>
            </div>
            <div class="dashboard-controls">
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
                <button id="view-report-btn">View Full Report</button>
                <button id="settings-btn"><i class="fas fa-cog"></i> Settings</button>
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
            // Temperature
            document.getElementById('temperature').textContent = sensorData.temperature.current;
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
            const ctx = document.getElementById('monitoring-chart').getContext('2d');
            
            const timeLabels = sensorData.temperature.history.map(item => {
                return item.time.getHours() + ':00';
            });
            
            monitoringChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: timeLabels,
                    datasets: [
                        {
                            label: 'Temperature (°C)',
                            data: sensorData.temperature.history.map(item => item.value),
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
                        y: {
                            beginAtZero: false
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
                    label: 'Temperature (°C)',
                    data: sensorData.temperature.history.map(item => item.value),
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
        
        // Simulate real-time data updates
        function simulateDataUpdates() {
            // Simulate small changes to current values
            sensorData.temperature.current += (Math.random() * 0.6) - 0.3;
            sensorData.temperature.current = Math.round(sensorData.temperature.current * 10) / 10;
            
            sensorData.humidity.current += (Math.random() * 1.2) - 0.6;
            sensorData.humidity.current = Math.round(sensorData.humidity.current * 10) / 10;
            
            sensorData.soilMoisture.current -= Math.random() * 0.2; // Soil moisture tends to decrease
            sensorData.soilMoisture.current = Math.round(sensorData.soilMoisture.current * 10) / 10;
            
            // Solar radiation depends on time of day
            const hour = new Date().getHours();
            if (hour >= 6 && hour <= 18) {
                const peak = 12; // Noon
                const maxChange = 50;
                const baseChange = maxChange * Math.exp(-0.5 * Math.pow((hour - peak) / 4, 2));
                sensorData.solarRadiation.current += (Math.random() * baseChange) - (baseChange / 2);
            } else {
                sensorData.solarRadiation.current = Math.random() * 5; // Near zero at night
            }
            sensorData.solarRadiation.current = Math.round(sensorData.solarRadiation.current);
            
            // Update latest values in history array
            const now = new Date();
            sensorData.temperature.history[sensorData.temperature.history.length - 1] = {
                time: now,
                value: sensorData.temperature.current
            };
            
            sensorData.humidity.history[sensorData.humidity.history.length - 1] = {
                time: now,
                value: sensorData.humidity.current
            };
            
            sensorData.soilMoisture.history[sensorData.soilMoisture.history.length - 1] = {
                time: now,
                value: sensorData.soilMoisture.current
            };
            
            sensorData.solarRadiation.history[sensorData.solarRadiation.history.length - 1] = {
                time: now,
                value: sensorData.solarRadiation.current
            };
            
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
        }
        
        // Event handlers
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
            alert('Data export functionality would be implemented here!');
        });
        
        document.getElementById('view-report-btn').addEventListener('click', function() {
            alert('Full report view would be opened here!');
        });
        
        document.getElementById('settings-btn').addEventListener('click', function() {
            alert('Settings panel would be developed Soon!');
        });
        
        // Parameter selection for chart
        document.querySelectorAll('.chart-param').forEach(checkbox => {
            checkbox.addEventListener('change', updateChartParameters);
        });
        
        // Initialize
        updateSensorDisplays();
        initChart();
        
        // Start real-time updates
        setInterval(simulateDataUpdates, 3000);
        
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
        
        // Add weather forecast section
        function addWeatherForecast() {
            const forecastData = [
                { day: 'Today', icon: 'sun', temp: '36°C', condition: 'Sunny' },
                { day: 'Tomorrow', icon: 'cloud-sun', temp: '34°C', condition: 'Partly Cloudy' },
                { day: 'Wed', icon: 'cloud-rain', temp: '25°C', condition: 'Light Rain' },
                { day: 'Thu', icon: 'cloud', temp: '28°C', condition: 'Cloudy' },
                { day: 'Fri', icon: 'sun', temp: '35°C', condition: 'Sunny' }
            ];
            
            const dashboardDiv = document.querySelector('.monitoring-dashboard');
            const weatherSection = document.createElement('div');
            weatherSection.className = 'weather-forecast';
            weatherSection.innerHTML = '<h3>Weather Forecast</h3>';
            
            const forecastContainer = document.createElement('div');
            forecastContainer.className = 'forecast-container';
            
            forecastData.forEach(day => {
                forecastContainer.innerHTML += `
                    <div class="forecast-day">
                        <div class="forecast-date">${day.day}</div>
                        <div class="forecast-icon"><i class="fas fa-${day.icon}"></i></div>
                        <div class="forecast-temp">${day.temp}</div>
                        <div class="forecast-condition">${day.condition}</div>
                    </div>
                `;
            });
            
            weatherSection.appendChild(forecastContainer);
            
            // Insert before footer
            const footer = document.querySelector('footer');
            dashboardDiv.insertBefore(weatherSection, footer);
            
            // Add styling
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
                    color: var(--dark-color);
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
                    background-color: var(--primary-color);
                }
                
                .forecast-container {
                    display: flex;
                    justify-content: space-between;
                    overflow-x: auto;
                    gap: 1rem;
                }
                
                .forecast-day {
                    text-align: center;
                    padding: 1rem;
                    min-width: 170px;
                    background-color: #f8f9fa;
                    border-radius: 8px;
                    transition: transform 0.3s;
                }
                
                .forecast-day:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                }
                
                .forecast-date {
                    font-weight: bold;
                    margin-bottom: 0.5rem;
                }
                
                .forecast-icon {
                    font-size: 2rem;
                    margin: 0.5rem 0;
                    color: var(--primary-color);
                }
                
                .forecast-icon .fa-sun {
                    color: #f39c12;
                }
                
                .forecast-icon .fa-cloud-rain {
                    color: #3498db;
                }
                
                .forecast-icon .fa-cloud,
                .forecast-icon .fa-cloud-sun {
                    color: #95a5a6;
                }
                
                .forecast-temp {
                    font-size: 1.5rem;
                    font-weight: bold;
                    margin: 0.5rem 0;
                }
                
                .forecast-condition {
                    color: #7f8c8d;
                }
                
                @media (max-width: 768px) {
                    .forecast-container {
                        overflow-x: auto;
                        padding-bottom: 1rem;
                    }
                    
                    .forecast-day {
                        min-width: 80px;
                        padding: 0.8rem;
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Call additional UI enhancements after initial load
        setTimeout(() => {
            addIrrigationControl();
            addWeatherForecast();
            
            // Show welcome message
            showAlert('🌱 Welcome to the Field Monitoring Dashboard! Real-time data is now streaming.', 'success');
        }, 1000);
    </script>
</body>
</html>