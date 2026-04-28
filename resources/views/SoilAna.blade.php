<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soil | FarmForecast</title>
    <style>
        :root {
            --primary: #2e7d32;
            --primary-light: #60ad5e;
            --primary-dark: #005005;
            --secondary: #795548;
            --light: #f5f5f5;
            --dark: #263238;
            --danger: #d32f2f;
            --warning: #ffa000;
            --success: #388e3c;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: var(--dark);
        }
        
        header {
            /* background-color: var(--primary); */
            background: linear-gradient(rgba(76, 175, 80, 0.8), rgba(46, 125, 50, 0.8)), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .dashboard {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            margin-top: 20px;
        }
        
        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
        }
        
        .panel {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .panel-title {
            color: var(--primary);
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-top: 0;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        input, select {
            width: 95%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: var(--primary-dark);
        }
        
        .results {
            display: none;
        }
        
        .meter {
            height: 20px;
            background-color: #e0e0e0;
            border-radius: 10px;
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
        }
        
        .meter-fill {
            height: 100%;
            border-radius: 10px;
            transition: width 1s ease-in-out;
        }
        
        .meter-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .nutrient-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        
        .nutrient-box {
            background-color: #f5f5f5;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .nutrient-value {
            font-size: 24px;
            font-weight: bold;
        }
        
        .nutrient-name {
            color: var(--secondary);
            margin-top: 5px;
        }
        
        .status {
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 14px;
            display: inline-block;
        }
        
        .status-low {
            background-color: #ffcdd2;
            color: #c62828;
        }
        
        .status-optimal {
            background-color: #c8e6c9;
            color: #2e7d32;
        }
        
        .status-high {
            background-color: #fff9c4;
            color: #f57f17;
        }
        
        .recommendation {
            background-color: #e8f5e9;
            border-left: 4px solid var(--primary);
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }
        
        .chart-container {
            height: 300px;
            margin-top: 20px;
        }
        
        .sample-history {
            margin-top: 30px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f5f5f5;
            font-weight: 600;
        }
        
        tr:hover {
            background-color: #f9f9f9;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
        
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border-left-color: var(--primary);
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .recommendations-list {
            list-style-type: none;
            padding: 0;
        }
        
        .recommendations-list li {
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }
        
        .recommendations-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: var(--primary);
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Soil Health Analysis Tool</h1>
        <p>Get detailed insights into your soil composition</p>
    </header>
    
    <div class="container">
        <div class="dashboard">
            <div>
                <div class="panel">
                    <h2 class="panel-title">Sample Information</h2>
                    <div class="form-group">
                        <label for="sample-id">Sample ID</label>
                        <input type="text" id="sample-id" placeholder="Enter sample ID or generate new">
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" placeholder="Field location">
                    </div>
                    <div class="form-group">
                        <label for="sample-date">Sample Date</label>
                        <input type="date" id="sample-date">
                    </div>
                    <div class="form-group">
                        <label for="soil-type">Soil Type</label>
                        <select id="soil-type">
                            <option value="">Select soil type</option>
                            <option value="clay">Clay</option>
                            <option value="silt">Silt</option>
                            <option value="sand">Sandy</option>
                            <option value="loam">Loam</option>
                            <option value="clay-loam">Clay Loam</option>
                            <option value="sandy-loam">Sandy Loam</option>
                            <option value="silt-loam">Silt Loam</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="crop-type">Intended Crop</label>
                        <select id="crop-type">
                            <option value="">Select crop type</option>
                            <option value="corn">Corn</option>
                            <option value="wheat">Wheat</option>
                            <option value="soybean">Soybean</option>
                            <option value="cotton">Cotton</option>
                            <option value="vegetables">Vegetables</option>
                            <option value="fruits">Fruits</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <button id="analyze-btn">Analyze Sample</button>
                </div>
                
                <div class="panel sample-history">
                    <h2 class="panel-title">Sample History</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Sample ID</th>
                                <th>Date</th>
                                <th>pH</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>S21-0583</td>
                                <td>2021-10-15</td>
                                <td>6.2</td>
                                <td><span class="status status-low">Low</span></td>
                            </tr>
                            <tr>
                                <td>S22-0127</td>
                                <td>2022-04-03</td>
                                <td>6.5</td>
                                <td><span class="status status-optimal">Optimal</span></td>
                            </tr>
                            <tr>
                                <td>S23-0932</td>
                                <td>2023-09-22</td>
                                <td>6.7</td>
                                <td><span class="status status-optimal">Optimal</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div>
                <div class="loading">
                    <div class="spinner"></div>
                    <p>Analyzing soil sample...</p>
                </div>
                
                <div class="results">
                    <div class="panel">
                        <h2 class="panel-title">Soil pH Analysis</h2>
                        <div class="meter-label">
                            <span>Acidic</span>
                            <span>Neutral</span>
                            <span>Alkaline</span>
                        </div>
                        <div class="meter">
                            <div class="meter-fill" id="ph-meter" style="width: 0%; background-color: #4caf50;"></div>
                        </div>
                        <p>Your soil pH is <strong id="ph-value">0.0</strong> - <span id="ph-status" class="status"></span></p>
                        <p id="ph-description"></p>
                    </div>
                    
                    <div class="panel">
                        <h2 class="panel-title">Nutrient Content</h2>
                        <div class="nutrient-grid">
                            <div class="nutrient-box">
                                <div class="nutrient-value" id="nitrogen-value">0</div>
                                <div class="nutrient-name">Nitrogen (N)</div>
                                <div id="nitrogen-status" class="status"></div>
                            </div>
                            <div class="nutrient-box">
                                <div class="nutrient-value" id="phosphorus-value">0</div>
                                <div class="nutrient-name">Phosphorus (P)</div>
                                <div id="phosphorus-status" class="status"></div>
                            </div>
                            <div class="nutrient-box">
                                <div class="nutrient-value" id="potassium-value">0</div>
                                <div class="nutrient-name">Potassium (K)</div>
                                <div id="potassium-status" class="status"></div>
                            </div>
                            <div class="nutrient-box">
                                <div class="nutrient-value" id="calcium-value">0</div>
                                <div class="nutrient-name">Calcium (Ca)</div>
                                <div id="calcium-status" class="status"></div>
                            </div>
                            <div class="nutrient-box">
                                <div class="nutrient-value" id="magnesium-value">0</div>
                                <div class="nutrient-name">Magnesium (Mg)</div>
                                <div id="magnesium-status" class="status"></div>
                            </div>
                            <div class="nutrient-box">
                                <div class="nutrient-value" id="sulfur-value">0</div>
                                <div class="nutrient-name">Sulfur (S)</div>
                                <div id="sulfur-status" class="status"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel">
                        <h2 class="panel-title">Moisture Retention Capacity</h2>
                        <div class="meter-label">
                            <span>Low</span>
                            <span>Medium</span>
                            <span>High</span>
                        </div>
                        <div class="meter">
                            <div class="meter-fill" id="moisture-meter" style="width: 0%; background-color: #2196f3;"></div>
                        </div>
                        <p>Water retention capacity: <strong id="moisture-value">0%</strong> - <span id="moisture-status" class="status"></span></p>
                        <p id="moisture-description"></p>
                    </div>
                    
                    <div class="panel">
                        <h2 class="panel-title">Soil Composition</h2>
                        <div id="composition-chart" class="chart-container">
                            <svg width="100%" height="100%" viewBox="0 0 400 300">
                                <g transform="translate(200, 150)">
                                    <!-- Clay slice -->
                                    <path d="M0,0 L150,0 A150,150 0 0,1 75,-129.9 L0,0" fill="#8d6e63" />
                                    <text x="75" y="-50" fill="black" text-anchor="middle">Clay 45%</text>
                                    
                                    <!-- Silt slice -->
                                    <path d="M0,0 L75,-129.9 A150,150 0 0,1 -129.9,-75 L0,0" fill="#a1887f" />
                                    <text x="-40" y="-70" fill="black" text-anchor="middle">Silt 30%</text>
                                    
                                    <!-- Sand slice -->
                                    <path d="M0,0 L-129.9,-75 A150,150 0 0,1 -75,129.9 L0,0" fill="#bcaaa4" />
                                    <text x="-70" y="20" fill="black" text-anchor="middle">Sand 15%</text>
                                    
                                    <!-- Organic Matter slice -->
                                    <path d="M0,0 L-75,129.9 A150,150 0 0,1 150,0 L0,0" fill="#5d4037" />
                                    <text x="20" y="70" fill="black" text-anchor="middle">Organic 10%</text>
                                </g>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="panel recommendation">
                        <h2 class="panel-title">Recommendations</h2>
                        <ul class="recommendations-list" id="recommendations-list">
                            <!-- Recommendations will be inserted here -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set current date as default
            const today = new Date();
            const dateInput = document.getElementById('sample-date');
            dateInput.value = today.toISOString().split('T')[0];
            
            // Generate random sample ID
            const sampleId = document.getElementById('sample-id');
            const randomId = 'S' + today.getFullYear().toString().substr(-2) + '-' + 
                             Math.floor(1000 + Math.random() * 9000);
            sampleId.value = randomId;
            
            // Analyze button click handler
            const analyzeBtn = document.getElementById('analyze-btn');
            analyzeBtn.addEventListener('click', function() {
                // Show loading spinner
                document.querySelector('.loading').style.display = 'block';
                document.querySelector('.results').style.display = 'none';
                
                // Simulate analysis delay
                setTimeout(function() {
                    // Hide loading spinner and show results
                    document.querySelector('.loading').style.display = 'none';
                    document.querySelector('.results').style.display = 'block';
                    
                    // Generate and display random results
                    generateResults();
                }, 2000);
            });
            
            function generateResults() {
                // Get selected soil and crop types
                const soilType = document.getElementById('soil-type').value || 'loam';
                const cropType = document.getElementById('crop-type').value || 'corn';
                
                // Generate pH value based on soil type
                let phBase;
                switch(soilType) {
                    case 'clay': phBase = 6.5; break;
                    case 'silt': phBase = 6.2; break;
                    case 'sand': phBase = 5.8; break;
                    case 'loam': phBase = 6.8; break;
                    case 'clay-loam': phBase = 7.0; break;
                    case 'sandy-loam': phBase = 6.3; break;
                    case 'silt-loam': phBase = 6.7; break;
                    default: phBase = 6.5;
                }
                
                // Add some randomness
                const phValue = (phBase + (Math.random() * 1 - 0.5)).toFixed(1);
                const phPercent = (phValue / 14 * 100).toFixed(1);
                
                // Update pH meter
                document.getElementById('ph-value').textContent = phValue;
                document.getElementById('ph-meter').style.width = phPercent + '%';
                
                // Set pH status
                const phStatus = document.getElementById('ph-status');
                const phDescription = document.getElementById('ph-description');
                if (phValue < 6.0) {
                    phStatus.textContent = 'Acidic';
                    phStatus.className = 'status status-low';
                    phDescription.textContent = 'Your soil is acidic. Consider adding lime to raise pH for most crops.';
                } else if (phValue >= 6.0 && phValue <= 7.5) {
                    phStatus.textContent = 'Optimal';
                    phStatus.className = 'status status-optimal';
                    phDescription.textContent = 'Your soil pH is in the optimal range for most crops.';
                } else {
                    phStatus.textContent = 'Alkaline';
                    phStatus.className = 'status status-high';
                    phDescription.textContent = 'Your soil is alkaline. Consider adding sulfur or organic matter to lower pH for most crops.';
                }
                
                // Generate and set nutrient values
                const nutrients = {
                    nitrogen: {
                        base: soilType === 'loam' || soilType === 'clay-loam' ? 30 : 15,
                        unit: 'ppm',
                        low: 20,
                        high: 40
                    },
                    phosphorus: {
                        base: soilType === 'clay' || soilType === 'clay-loam' ? 25 : 18,
                        unit: 'ppm',
                        low: 15,
                        high: 30
                    },
                    potassium: {
                        base: soilType === 'clay' || soilType === 'loam' ? 200 : 150,
                        unit: 'ppm',
                        low: 150,
                        high: 250
                    },
                    calcium: {
                        base: phValue > 6.5 ? 2000 : 1500,
                        unit: 'ppm',
                        low: 1000,
                        high: 2000
                    },
                    magnesium: {
                        base: soilType === 'clay' ? 300 : 200,
                        unit: 'ppm',
                        low: 150,
                        high: 300
                    },
                    sulfur: {
                        base: 20,
                        unit: 'ppm',
                        low: 10,
                        high: 25
                    }
                };
                
                // Process each nutrient
                Object.keys(nutrients).forEach(nutrient => {
                    const data = nutrients[nutrient];
                    const value = Math.floor(data.base * (0.8 + Math.random() * 0.4));
                    const valueElement = document.getElementById(`${nutrient}-value`);
                    const statusElement = document.getElementById(`${nutrient}-status`);
                    
                    valueElement.textContent = `${value} ${data.unit}`;
                    
                    if (value < data.low) {
                        statusElement.textContent = 'Low';
                        statusElement.className = 'status status-low';
                    } else if (value > data.high) {
                        statusElement.textContent = 'High';
                        statusElement.className = 'status status-high';
                    } else {
                        statusElement.textContent = 'Optimal';
                        statusElement.className = 'status status-optimal';
                    }
                });
                
                // Generate moisture retention
                let moistureBase;
                switch(soilType) {
                    case 'clay': moistureBase = 80; break;
                    case 'silt': moistureBase = 65; break;
                    case 'sand': moistureBase = 35; break;
                    case 'loam': moistureBase = 60; break;
                    case 'clay-loam': moistureBase = 70; break;
                    case 'sandy-loam': moistureBase = 45; break;
                    case 'silt-loam': moistureBase = 65; break;
                    default: moistureBase = 55;
                }
                
                // Add randomness to moisture
                const moistureValue = moistureBase + Math.floor(Math.random() * 10) - 5;
                document.getElementById('moisture-value').textContent = moistureValue + '%';
                document.getElementById('moisture-meter').style.width = moistureValue + '%';
                
                // Set moisture status
                const moistureStatus = document.getElementById('moisture-status');
                const moistureDescription = document.getElementById('moisture-description');
                
                if (moistureValue < 40) {
                    moistureStatus.textContent = 'Low Retention';
                    moistureStatus.className = 'status status-low';
                    moistureDescription.textContent = 'Your soil has low water retention capacity. Consider adding organic matter to improve water holding capacity.';
                } else if (moistureValue >= 40 && moistureValue <= 70) {
                    moistureStatus.textContent = 'Medium Retention';
                    moistureStatus.className = 'status status-optimal';
                    moistureDescription.textContent = 'Your soil has good water retention capacity, balancing drainage and water availability.';
                } else {
                    moistureStatus.textContent = 'High Retention';
                    moistureStatus.className = 'status status-high';
                    moistureDescription.textContent = 'Your soil has high water retention capacity. May be prone to waterlogging during wet periods.';
                }
                
                // Generate recommendations based on analysis
                const recommendationsList = document.getElementById('recommendations-list');
                recommendationsList.innerHTML = '';
                
                const recommendations = generateRecommendations(
                    phValue, 
                    nutrients, 
                    moistureValue, 
                    soilType, 
                    cropType
                );
                
                recommendations.forEach(rec => {
                    const li = document.createElement('li');
                    li.textContent = rec;
                    recommendationsList.appendChild(li);
                });
            }
            
            function generateRecommendations(ph, nutrients, moisture, soilType, cropType) {
                const recommendations = [];
                
                // pH recommendations
                if (ph < 6.0) {
                    recommendations.push('Apply agricultural lime at a rate of 1-2 tons per acre to raise soil pH.');
                } else if (ph > 7.5) {
                    recommendations.push('Apply elemental sulfur or aluminum sulfate to lower soil pH gradually.');
                }
                
                // Nutrient recommendations
                const nitrogenValue = parseInt(document.getElementById('nitrogen-value').textContent);
                const phosphorusValue = parseInt(document.getElementById('phosphorus-value').textContent);
                const potassiumValue = parseInt(document.getElementById('potassium-value').textContent);
                
                if (nitrogenValue < nutrients.nitrogen.low) {
                    recommendations.push('Apply nitrogen fertilizer at a rate of 40-60 lbs per acre based on crop needs.');
                }
                
                if (phosphorusValue < nutrients.phosphorus.low) {
                    recommendations.push('Apply phosphate fertilizer at a rate of 20-30 lbs per acre.');
                }
                
                if (potassiumValue < nutrients.potassium.low) {
                    recommendations.push('Apply potash fertilizer at a rate of 30-40 lbs per acre.');
                }
                
                // Moisture recommendations
                if (moisture < 40) {
                    recommendations.push('Add compost or organic matter at a rate of 2-3 tons per acre to improve water retention.');
                    recommendations.push('Consider mulching or cover crops to reduce evaporation loss.');
                } else if (moisture > 70) {
                    recommendations.push('Improve drainage through installation of drainage tiles or creating raised beds.');
                    recommendations.push('Add coarse sand or perlite to heavy clay soils to improve drainage.');
                }
                
                // Soil type specific recommendations
                if (soilType === 'clay') {
                    recommendations.push('Add gypsum to improve soil structure and reduce compaction in clay soils.');
                } else if (soilType === 'sand') {
                    recommendations.push('Incorporate organic matter to improve nutrient retention in sandy soils.');
                }
                
                // Crop specific recommendations
                if (cropType === 'corn') {
                    recommendations.push('For corn production, ensure adequate nitrogen levels during V6-V8 growth stages.');
                } else if (cropType === 'soybean') {
                    recommendations.push('For soybeans, ensure adequate phosphorus and potassium for optimal nodulation.');
                } else if (cropType === 'vegetables') {
                    recommendations.push('For vegetable production, consider split applications of fertilizer throughout the growing season.');
                }
                
                // Ensure we have at least 3 recommendations
                if (recommendations.length < 3) {
                    recommendations.push('Conduct annual soil tests to monitor changes in soil health over time.');
                    recommendations.push('Consider crop rotation to improve soil structure and reduce pest pressure.');
                }
                
                return recommendations;
            }
        });
    </script>
</body>
</html>