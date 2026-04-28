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
    <link rel="stylesheet" href="styles.css">
    <!-- in php format some of the animation is not working so i added explicitly here -->
    <style> 
        .recommendation-text {
            display: inline-block;
            animation: scroll-up 35000ms ease-in-out infinite;
        }
    </style>
 
</head>
<body>
    <div class="container">
        <div class="weather-input">
              
            <div class="input-group">
                <input type="text" id="userLocation" placeholder="Search for places...">

                <select class="converter" name="" id="converter">
                    <option >°C</option>
                    <option >°F</option>
                </select>

                <i class="fa fa-search" onclick="findLocation()"></i>

            </div>
            <div class="weatherIcon"></div>
            <h2 class="temperature"></h2>
            <div class="feelsLike"></div>
            <div class="description"></div>
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

                <!-- <div class="uv-index">
                   UV Index
                    <i class="fa-solid fa-bacon"></i>
                    <h1 id="UValue"></h1>
                </div> -->

                <div class="pressure">
                    Pressure
                    <i class="fa-solid fa-volcano"></i>
                    <h1 id="PValue"></h1>
                </div>
            </div>
            <br>

            <h2 class="heading">This week</h2>
            <div class="Forecast"></div>

        <!-- for the farming recommendations -->

        <h2 class="heading">Farming Recommendations</h2>
        <div class="recommendation-container">
            <div class="recommendation-text">
                <p> Intense rainfall predicted over the next 24 hours. Postpone irrigation to prevent waterlogging and potential crop damage.</p>  
                <p> Extreme heat wave advisory. Utilize shade nets or mulching techniques to protect delicate plants from scorching temperatures.</p>  
                <p> Strong winds anticipated. Secure lightweight crops and greenhouse structures to avoid wind-related disturbances.</p>  
                <p> Soil moisture levels critically low. Immediate irrigation advised to maintain crop health and prevent drought stress.</p>  
                <p>Optimal climate conditions for sowing rice and maize. Utilize timely planting strategies to enhance yield potential.</p>  
                <p>Sudden temperature drop expected overnight. Consider protective measures to shield sensitive crops from frost damage.</p>  
                <p>Harvest-ready wheat fields. Ideal time for cutting and processing to maximize quality and market value.</p>  
                <p>Increased humidity may lead to pest infestations. Regular monitoring and preventive pest control applications recommended.</p>  
                <p>Heavy downpours expected over multiple days. Delay harvesting operations to prevent grain sprouting and quality degradation.</p>  
                <p>Prolonged high temperatures may lead to heat stress in livestock. Ensure adequate water supply and shaded resting areas.</p>  
                <p>Gusty winds could cause lodging in taller crops like sugarcane and maize. Strengthen support structures where necessary.</p>  
                <p>Favorable soil and climate conditions for transplanting paddy. Utilize recommended spacing techniques for better yield.</p>  
                <p>High moisture levels could increase fungal infections in crops. Apply preventive fungicide treatments where needed.</p>  
                <p>Unexpected cold spells might impact flowering in sensitive plants. Use protective coverings to safeguard against temperature shocks.</p>  
                <p>Fruit orchards nearing peak ripeness. Timely harvesting can enhance shelf life and market value.</p>  
                <p>Increased humidity may lead to weed proliferation. Regular weeding or herbicide application advised to prevent crop competition.</p>  
                <p>Pollination-friendly weather for crops like mustard and sunflower. Encourage bee activity to improve seed formation.</p>  
            </div>
        </div>
        
        </div>


        

        
    </div>
    

    <!-- <script src="script.js"></script> -->
     <script src="updatedScript.js"></script>
</body>
</html>