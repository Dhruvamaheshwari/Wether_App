<?php
session_start();
$showAlert = isset($_SESSION['show_login_alert']) && $_SESSION['show_login_alert'];
if ($showAlert) {
    unset($_SESSION['show_login_alert']); // Clear the flag so it doesn't show again
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>&#x1f33e;HomePage | FarmForecast</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Popup styles */
        .logout-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px 40px;
            border-radius: 10px;
            z-index: 1000;
            display: none;
            animation: fadeInOut 3s ease-in-out;
        }
        
        @keyframes fadeInOut {
            0% { opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; }
        }

        .custom-alert {
            position: fixed;
            top: 10%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #4CAF50;
            color: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: none;
            animation: fadeInOut 2.5s ease-in-out;
            text-align: center;
            max-width: 80%;
        }
        
        @keyframes fadeInOut {
            0% { opacity: 0; }
            15% { opacity: 1; }
            85% { opacity: 1; }
            100% { opacity: 0; }
        }
        
        .custom-alert i {
            font-size: 24px;
            margin-right: 10px;
        }
        
        /* Explore Section Styles */
        .explore-section {
            display: none;
            padding: 80px 20px;
            color: #333;
            text-align: center;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(242, 242, 242, 0.95) 0%, rgba(230, 247, 230, 0.95) 100%);
        }
        
        .explore-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><circle cx="20" cy="20" r="3" fill="%234CAF50" fill-opacity="0.1"/><circle cx="50" cy="30" r="2" fill="%234CAF50" fill-opacity="0.1"/><circle cx="80" cy="20" r="3" fill="%234CAF50" fill-opacity="0.1"/><circle cx="30" cy="70" r="2" fill="%234CAF50" fill-opacity="0.1"/><circle cx="70" cy="80" r="3" fill="%234CAF50" fill-opacity="0.1"/></svg>'),
                url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><path d="M20,100 Q50,50 80,100 T140,100" stroke="%234CAF50" stroke-width="0.5" fill="none" stroke-opacity="0.1"/></svg>');
            background-size: 100px 100px, 200px 200px;
            opacity: 0.6;
            z-index: -1;
        }
        
        .explore-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .explore-section h2 {
            font-size: 2.8rem;
            margin-bottom: 20px;
            color: #2e7d32;
            position: relative;
            display: inline-block;
        }
        
        .explore-section h2::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 25%;
            width: 50%;
            height: 3px;
            background: linear-gradient(to right, transparent, #4CAF50, transparent);
        }
        
        .explore-section p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto 40px;
            color: #555;
            line-height: 1.6;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .feature-card-link {
            display: block; /* Makes the entire div clickable */
            text-decoration: none; /* Removes the default underline */
            color: inherit; /* Keeps text colors the same */
        }

        .feature-card {
            cursor: pointer; /* Shows the pointer when hovering */
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.12);
        }
        
        .feature-card::before {
            content: "";
            position: absolute;
            top: -20px;
            right: -20px;
            width: 60px;
            height: 60px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M50,10 C60,30 80,30 90,50 C80,70 60,70 50,90 C40,70 20,70 10,50 C20,30 40,30 50,10 Z" fill="%234CAF50" fill-opacity="0.1"/></svg>');
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.8;
        }
        
        .feature-icon {
            font-size: 3rem;
            color: #4CAF50;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #2E7D32;
        }
        
        .feature-card p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        .data-preview {
            height: 200px;
            background: #f9f9f9;
            border-radius: 5px;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .data-preview::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                linear-gradient(to right, rgba(0,0,0,0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0,0,0,0.02) 1px, transparent 1px);
            background-size: 20px 20px;
        }
        
        .data-point {
            position: absolute;
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            border: 2px solid #2E7D32;
        }
        
        .close-explore {
            margin-top: 40px;
            padding: 12px 30px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .close-explore:hover {
            background: #388E3C;
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(0,0,0,0.15);
        }
        
        .close-explore i {
            margin-left: 8px;
        }

        /*Explore section div */
        .Monitoring-Image, .Soil-Image, .AI-Image, .Forecast-Image, .Alert-Image, .Trends-Image {
            width: 100%;
            height: 100%;
            overflow: hidden;
            border-radius: 5px;
        }
        .Monitoring-Image img, .Soil-Image img, .AI-Image img, .Forecast-Image img, .Alert-Image img, .Trends-Image img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            transition: transform 0.3s;
        }

    </style>
</head>
<body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showAlert('Login successful!');
    });
</script>
    <div class="hero">
        <video autoplay loop muted plays-inline class="background-video">
            <source src="Images\Video1.mp4" type="video/mp4">
        </video>
        <nav>
            <img class="logo" src="Images\Logo.png" alt="logo">
            <ul class="navbar">
                <li><a href="#" id="home" class="active">HOME</a></li>
                <li><a href="Weather\weather frontend\ref.php" id="climate-data">CLIMATE DATA</a></li>
                <li><a href="Chatbot\index.php" id="chat-bot">CHATBOT</a></li>
                <div class="burger-menu">
                    <label class="burger" for="burger">
                    <input class="line" type="checkbox" id="burger" />
                    </label>
                    <div id="slide-page" class="slide-page">
                        <div class="Section_Nav">
                            <h2>More</h2>
                            <a href="AboutUs.php"><i class="fas fa-info-circle"></i> About Us</a>
                            <a href="Support.php"><i class="fas fa-hands-helping"></i> Support</a>
                            <a href="index.php" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                            <button id="close-btn" class="close-btn">Back<i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </ul>
        </nav>
        <div class="content">
            <h1>Climate</h1>
            <a href="#" id="explore-btn">Explore</a>
        </div>
    </div>
    
    <!-- Explore Section -->
    <section class="explore-section" id="exploreSection">
        <div class="explore-container">
            <h2>Automated Climate Monitoring System</h2>
            <p>Our advanced agricultural solution combines IoT sensors, AI analytics, and weather forecasting to help farmers optimize crop yields through precise climate monitoring and actionable insights.</p>
            
            <div class="features-grid">
                <a href="Monitor.php" class="feature-card-link">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-temperature-low"></i>
                        </div>
                        <h3>Real-time Field Monitoring</h3>
                        <p>Track temperature, humidity, soil moisture, and solar radiation across your fields with our network of IoT sensors.</p>
                        <div class="data-preview">
                            <div class="Monitoring-Image">
                                <img src="Images\Monitoring.jpg" alt="img1">
                            </div>
                        </div>
                    </div>
                </a>
                
                <a href="SoilAna.php" class="feature-card-link">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <h3>Soil Health Analysis</h3>
                        <p>Detailed soil composition reports including pH levels, nutrient content, and moisture retention capacity.</p>
                        <div class="data-preview">
                            <div class="Soil-Image">
                                <img src="Images\Soil.jpg" alt="img2">
                            </div>
                        </div>
                    </div>
                </a>
                
                <a href="Chatbot\index.php" class="feature-card-link">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-robot"></i>
                        </div>
                        <h3>AI-Powered Analytics</h3>
                        <p>Machine learning algorithms analyze patterns to provide irrigation and planting recommendations.</p>
                        <div class="data-preview">
                            <div class="AI-Image">
                                <img src="Images/Ai_analysis.jpg" alt="img3">
                            </div>
                        </div>
                    </div>
                </a>
                
                <a href="Weather\weather frontend\ref.php" class="feature-card-link">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-cloud-sun-rain"></i>
                        </div>
                        <h3>Hyper-local Forecasts</h3>
                        <p>Precision weather predictions tailored to your specific location and crop requirements.</p>
                        <div class="data-preview">
                            <div class="Forecast-Image">
                                <img src="Images\wimg.jpg" alt="img4">
                            </div>
                        </div>
                    </div>
                </a>
                
                <a href="Alert.php" class="feature-card-link">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3>Smart Alerts</h3>
                        <p>Instant notifications about frost risks, drought conditions, or optimal planting windows.</p>
                        <div class="data-preview">
                            <div class="Alert-Image">
                                <img src="Images\Alerts.jpg" alt="img5">
                            </div>
                        </div>
                    </div>
                </a>
                <a href="HisTrend.php" class="feature-card-link">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Historical Trends</h3>
                        <p>Access years of climate data to identify patterns and optimize your farming strategy.</p>
                        <div class="data-preview">
                            <div class="Trends-Image">
                                <img src="Images\trend.jpg" alt="img6">
                            </div>
                        </div>
                    </div>
                </div>
                </a>
                
                <button class="close-explore" id="closeExplore">Back to Home <i class="fas fa-home"></i></button>
            </div>
        </section>
        
        <!-- Popup div -->
        <div id="logout-popup" class="logout-popup">
            Logging out...
        </div>
        <div id="customAlert" class="custom-alert">
            <i class="fas fa-check-circle"></i>
            <span id="alertMessage"></span>
        </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navLinks = document.querySelectorAll('.navbar li a');
            document.getElementById('home').classList.add('active');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function () {
                    navLinks.forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            const burgerIcon = document.querySelector('.burger .line');
            const slidePage = document.getElementById('slide-page');
            const closeBtn = document.getElementById('close-btn');
            const logoutLink = document.getElementById('logout-link');
            const logoutPopup = document.getElementById('logout-popup');
            const exploreBtn = document.getElementById('explore-btn');
            const exploreSection = document.getElementById('exploreSection');
            const closeExplore = document.getElementById('closeExplore');

            // Burger menu functionality
            burgerIcon.addEventListener('click', function () {
                slidePage.classList.toggle('show');
            });

            closeBtn.addEventListener('click', function () {
                slidePage.classList.remove('show');
            });
            
            // Logout functionality
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();
                logoutPopup.style.display = 'block';
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 1000);
            });
            
            // Explore button functionality
            exploreBtn.addEventListener('click', function(e) {
                e.preventDefault();
                exploreSection.style.display = 'block';
                // Smooth scroll to the explore section
                exploreSection.scrollIntoView({ behavior: 'smooth' });
            });
            
            // Close explore section
            closeExplore.addEventListener('click', function() {
                exploreSection.style.display = 'none';
                // Scroll back to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
        function showAlert(message, type = 'success') {
            const alertBox = document.getElementById('customAlert');
            const alertMessage = document.getElementById('alertMessage');
            const alertIcon = alertBox.querySelector('i');
            
            alertMessage.textContent = message;
            alertBox.style.display = 'block';
            
            setTimeout(function() {
                alertBox.style.display = 'none';
            }, 2000);
        }
    </script>
</body>
</html>