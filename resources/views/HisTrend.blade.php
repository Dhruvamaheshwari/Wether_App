<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trends | FarmForecast</title>
    <style>
        :root {
            --primary: #2e7d32;
            --primary-light: #60ad5e;
            --primary-dark: #005005;
            --secondary: #f5f5f5;
            --text: #333;
            --accent: #ffeb3b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--secondary);
            color: var(--text);
            line-height: 1.6;
        }
        
        header {
            background: linear-gradient(rgba(76, 175, 80, 0.8), rgba(46, 125, 50, 0.8)), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 3rem;
            gap: 2rem;
        }
        
        .hero-content {
            flex: 1;
        }
        
        .hero-image {
            flex: 1;
            text-align: center;
        }
        
        h1{
            color: #eee;
        }
        h2, h3 {
            color: var(--primary-dark);
            margin-bottom: 1rem;
        }
        
        p {
            margin-bottom: 1.5rem;
        }
        
        .btn {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .feature-card {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .data-section {
            background-color: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .chart-container {
            width: 100%;
            height: 400px;
            margin: 2rem 0;
            position: relative;
            background-color: #fafafa;
            border: 1px solid #eee;
            border-radius: 8px;
        }
        
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: var(--primary-dark);
        }
        
        select, input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        .data-insights {
            background-color: #f9f9f9;
            padding: 1rem;
            border-left: 4px solid var(--primary);
            margin-top: 1rem;
        }
        
        footer {
            background-color: var(--primary-dark);
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
            }
            
            .filters {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Historical Climate Trends</h1>
        <p>Access years of climate data to identify patterns and optimize your farming strategy</p>
    </header>
    
    <div class="container">
        <section class="hero">
            <div class="hero-content">
                <h2>Make Data-Driven Farming Decisions</h2>
                <p>Our platform provides comprehensive historical climate data analysis to help you understand weather patterns, predict optimal planting times, and improve crop yields through informed decision-making.</p>
                <a href="#data-explorer" class="btn">Explore Climate Data</a>
            </div>
            <div class="hero-image">
                <img src="FarmC.png" alt="Farm with climate visualization" width="820" height="470" />
            </div>
        </section>
        
        <section class="features">
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Historical Trends Analysis</h3>
                <p>Explore decades of temperature, rainfall, and humidity data to identify long-term climate patterns affecting your region.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌱</div>
                <h3>Seasonal Planting Guides</h3>
                <p>Get customized planting recommendations based on historical climate data and crop-specific requirements.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚠️</div>
                <h3>Risk Assessment</h3>
                <p>Identify potential climate risks like frost, drought, or excessive rainfall based on historical patterns.</p>
            </div>
        </section>
        
        <section id="data-explorer" class="data-section">
            <h2>Climate Data Explorer</h2>
            <p>Select your location and parameters to view historical climate trends</p>
            
            <div class="filters">
                <div class="filter-group">
                    <label for="location">Location</label>
                    <select id="location">
                        <option value="midwest">Midwest Region</option>
                        <option value="southeast">Southeast Region</option>
                        <option value="northwest">Northwest Region</option>
                        <option value="southwest">Southwest Region</option>
                        <option value="northeast">Northeast Region</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="dataType">Data Type</label>
                    <select id="dataType">
                        <option value="temperature">Temperature</option>
                        <option value="rainfall">Rainfall</option>
                        <option value="humidity">Humidity</option>
                        <option value="growing-days">Growing Days</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="yearRange">Year Range</label>
                    <select id="yearRange">
                        <option value="5">Last 5 Years</option>
                        <option value="10">Last 10 Years</option>
                        <option value="20">Last 20 Years</option>
                        <option value="50">Last 50 Years</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button id="updateChart" class="btn">Update Chart</button>
                </div>
            </div>
            
            <div class="chart-container">
                <canvas id="climateChart"></canvas>
            </div>
            
            <div class="data-insights" id="insights">
                <h3>Data Insights</h3>
                <p>Select a location and data type to view climate insights.</p>
            </div>
        </section>
        
        <section>
            <h2>Optimize Your Farming Strategy</h2>
            <p>Use our historical climate data to make informed decisions about:</p>
            <ul>
                <li>Optimal planting and harvesting times</li>
                <li>Crop selection based on climate patterns</li>
                <li>Irrigation planning</li>
                <li>Frost and extreme weather preparation</li>
                <li>Long-term agricultural investments</li>
            </ul>
            <p>Our data-driven approach helps farmers increase yields while reducing risks and resource usage.</p>
        </section>
    </div>
    
    <footer>
        <p>&copy; 2025 Historical Climate Trends | FarmForecast</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Sample data for demonstration
        const climateData = {
            midwest: {
                temperature: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [52, 53, 54, 55, 56],
                    insight: "Average temperatures in the Midwest have shown a steady increase of approximately 1°F per year over the last 5 years. This trend indicates a lengthening growing season but may increase water requirements for certain crops."
                },
                rainfall: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [35, 32, 38, 30, 28],
                    insight: "Annual rainfall has been decreasing in recent years, with 2024 showing the lowest precipitation in the 5-year period. Consider drought-resistant crops or improved irrigation systems."
                },
                humidity: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [65, 63, 68, 64, 62],
                    insight: "Humidity levels have fluctuated but remain within normal ranges for the Midwest region. Monitor humidity trends for potential fungal disease risk."
                },
                "growing-days": {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [180, 185, 187, 190, 195],
                    insight: "The growing season has extended by approximately 15 days over the last 5 years. This may allow for longer-season crop varieties or double cropping opportunities."
                }
            },
            southeast: {
                temperature: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [68, 69, 70, 71, 72],
                    insight: "The Southeast region has experienced a consistent warming trend, with temperatures rising approximately 1°F per year. This may affect crop stress during summer months."
                },
                rainfall: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [52, 58, 48, 60, 55],
                    insight: "Rainfall patterns show high variability year-to-year, with no clear trend. Planning for both excessive rainfall and potential dry periods is recommended."
                },
                humidity: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [75, 78, 74, 76, 77],
                    insight: "Consistently high humidity levels present challenges for disease management. Consider disease-resistant varieties and appropriate fungicide programs."
                },
                "growing-days": {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [220, 225, 228, 230, 235],
                    insight: "The already lengthy growing season continues to extend, potentially allowing for additional crop cycles or new crop varieties not previously suitable for the region."
                }
            },
            northwest: {
                temperature: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [48, 49, 50, 51, 52],
                    insight: "The Northwest region has experienced gradual warming, with temperatures increasing steadily over the past 5 years. This trend may benefit some cool-season crops but could affect water availability."
                },
                rainfall: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [45, 42, 40, 38, 36],
                    insight: "Annual rainfall has been decreasing steadily, potentially indicating a drying trend in the region. Consider water conservation strategies and drought-resistant crop varieties."
                },
                humidity: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [70, 68, 69, 67, 65],
                    insight: "Humidity levels have been gradually decreasing, which may reduce disease pressure but could increase irrigation requirements for some crops."
                },
                "growing-days": {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [165, 168, 170, 175, 178],
                    insight: "The growing season has extended by approximately 13 days over the last 5 years. Consider adapting crop selection to take advantage of this trend."
                }
            },
            southwest: {
                temperature: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [72, 74, 75, 77, 78],
                    insight: "The Southwest region has experienced significant warming, with average temperatures rising by 6°F over the past 5 years. This rapid warming may stress many traditional crops and increase water demands."
                },
                rainfall: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [15, 12, 10, 8, 7],
                    insight: "Rainfall has decreased dramatically, indicating worsening drought conditions. Consider drought-resistant crops and advanced irrigation systems."
                },
                humidity: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [35, 32, 30, 28, 25],
                    insight: "Humidity levels have been consistently low and continue to decrease, requiring careful water management strategies."
                },
                "growing-days": {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [240, 245, 250, 255, 260],
                    insight: "The already long growing season continues to extend, potentially allowing for multiple crop cycles but requiring careful water management."
                }
            },
            northeast: {
                temperature: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [50, 51, 52, 53, 54],
                    insight: "The Northeast has seen moderate warming, with temperatures increasing by approximately 4°F over the past 5 years. This may benefit certain crops but could affect others."
                },
                rainfall: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [42, 45, 48, 50, 52],
                    insight: "Rainfall has been increasing steadily, which may benefit some crops but could lead to increased disease pressure and waterlogging in poorly drained soils."
                },
                humidity: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [65, 68, 70, 71, 73],
                    insight: "Humidity levels have been increasing, which may lead to higher disease pressure for some crops. Consider disease-resistant varieties and appropriate fungicide programs."
                },
                "growing-days": {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    values: [175, 178, 180, 185, 188],
                    insight: "The growing season has extended by approximately 13 days over the last 5 years. This trend may allow for new crop varieties not previously suitable for the region."
                }
            }
        };
        
        // Initialize with default data
        let currentChart = null;
        
        function updateChart() {
            const location = document.getElementById('location').value;
            const dataType = document.getElementById('dataType').value;
            const yearRange = document.getElementById('yearRange').value;
            
            // Get data based on selection
            let dataToShow = climateData[location] && climateData[location][dataType];
            
            if (!dataToShow) {
                document.getElementById('insights').innerHTML = '<h3>Data Insights</h3><p>No data available for the selected parameters.</p>';
                return;
            }
            
            // Update insights
            document.getElementById('insights').innerHTML = `
                <h3>Data Insights</h3>
                <p>${dataToShow.insight}</p>
            `;
            
            // Create or update chart
            const ctx = document.getElementById('climateChart');
            
            // Destroy previous chart if it exists
            if (currentChart) {
                currentChart.destroy();
            }
            
            // Set chart title and y-axis label based on data type
            let chartTitle = 'Historical ';
            let yAxisLabel = '';
            
            switch(dataType) {
                case 'temperature':
                    chartTitle += 'Temperature';
                    yAxisLabel = 'Temperature (°F)';
                    break;
                case 'rainfall':
                    chartTitle += 'Rainfall';
                    yAxisLabel = 'Rainfall (inches)';
                    break;
                case 'humidity':
                    chartTitle += 'Humidity';
                    yAxisLabel = 'Humidity (%)';
                    break;
                case 'growing-days':
                    chartTitle += 'Growing Days';
                    yAxisLabel = 'Days';
                    break;
            }
            
            chartTitle += ` Data for ${location.charAt(0).toUpperCase() + location.slice(1)} Region`;
            
            // Create new chart
            currentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dataToShow.labels,
                    datasets: [{
                        label: yAxisLabel,
                        data: dataToShow.values,
                        backgroundColor: 'rgba(46, 125, 50, 0.2)',
                        borderColor: 'rgba(46, 125, 50, 1)',
                        borderWidth: 3,
                        pointBackgroundColor: 'rgba(46, 125, 50, 1)',
                        pointRadius: 5,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: chartTitle,
                            font: {
                                size: 16
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(46, 125, 50, 0.8)',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 14
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            title: {
                                display: true,
                                text: yAxisLabel
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Year'
                            }
                        }
                    }
                }
            });
        }
        
        // Initialize chart on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Make sure Chart.js is loaded before trying to use it
            if (typeof Chart !== 'undefined') {
                updateChart();
                
                // Add event listener for button click
                document.getElementById('updateChart').addEventListener('click', updateChart);
                
                // Also update when selections change
                document.getElementById('location').addEventListener('change', updateChart);
                document.getElementById('dataType').addEventListener('change', updateChart);
                document.getElementById('yearRange').addEventListener('change', updateChart);
                
                // Smooth scroll to data explorer when clicking the explore button
                document.querySelector('a[href="#data-explorer"]').addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector('#data-explorer').scrollIntoView({ 
                        behavior: 'smooth' 
                    });
                });
            } else {
                console.error('Chart.js library not loaded');
                document.querySelector('.chart-container').innerHTML = '<p class="error" style="text-align: center; padding: 2rem;">Error: Chart library not loaded. Please check your internet connection and refresh the page.</p>';
            }
        });
    </script>
</body>
</html>