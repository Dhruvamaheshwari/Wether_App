<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | FarmForecast</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Global Styles */
        :root {
            --farm-green: #4CAF50;
            --dark-green: #2E7D32;
            --soil-brown: #8D6E63;
            --sun-yellow: #FFC107;
            --white: #FFFFFF;
            --off-white: #F5F7FA;
            --text-dark: #263238;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--off-white);
            color: var(--text-dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Section */
        .about-header {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(rgba(76, 175, 80, 0.8), rgba(46, 125, 50, 0.8)), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: var(--white);
            border-radius: 10px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .about-header h1 {
            font-size: 3.2rem;
            margin-bottom: 20px; 
            animation: fadeInDown 1s ease;
        }

        .about-header p {
            font-size: 1.3rem;
            max-width: 800px;
            margin: 0 auto;
            animation: fadeInUp 1s ease;
        }

        /* Mission & Tech */
        .tech-section {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 50px;
        }

        .tech-card {
            flex: 1 1 300px;
            background: var(--white);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            border-top: 4px solid var(--farm-green);
        }

        .tech-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .tech-card i {
            font-size: 2.8rem;
            color: var(--soil-brown);
            margin-bottom: 20px;
        }

        .tech-card h3 {
            color: var(--dark-green);
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        /* Dashboard Preview */
        .dashboard-section {
            background: var(--white);
            border-radius: 10px;
            padding: 40px;
            margin-bottom: 50px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .dashboard-section h2 {
            color: var(--dark-green);
            margin-bottom: 30px;
            font-size: 2.3rem;
        }

        .dashboard-img {
            max-width: 100%;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.5s ease;
        }

        .dashboard-img:hover {
            transform: scale(1.02);
        }

        /* Team Section */
        .team-section {
            margin-bottom: 60px;
        }

        .team-section h2 {
            text-align: center;
            margin-bottom: 40px;
            color: var(--dark-green);
            font-size: 2.3rem;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .team-member {
            background: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .team-member:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .team-img {
            height: 250px;
            overflow: hidden;
            background: var(--off-white);
        }

        .team-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .team-member:hover .team-img img {
            transform: scale(1.1);
        }

        .team-info {
            padding: 25px;
            text-align: center;
        }

        .team-info h3 {
            color: var(--dark-green);
            margin-bottom: 8px;
            font-size: 1.3rem;
        }

        .team-info p {
            color: var(--soil-brown);
            font-weight: 500;
        }

        /* CTA Section */
        .cta-section {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(rgba(142, 110, 99, 0.9), rgba(76, 175, 80, 0.9)), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: var(--white);
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            animation: fadeIn 1s ease;
        }

        /* Social Media Links */
        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .social-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--white);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            color: var(--sun-yellow);
            transform: translateY(-5px);
        }

        .social-link i {
            font-size: 1.5rem;
            margin-right: 10px;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .about-header h1 {
                font-size: 2.4rem;
            }
            
            .about-header p {
                font-size: 1.1rem;
            }
            
            .tech-section {
                flex-direction: column;
            }
            
            .social-links {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <section class="about-header">
            <h1><i class="fas fa-seedling"></i> About FarmForecast</h1>
            <p>Revolutionizing farming with smart agricultural technology that helps farmers monitor crops, soil, and weather conditions in real-time.</p>
        </section>

        <!-- Tech & Mission Section -->
        <section class="tech-section">
            <div class="tech-card">
                <i class="fas fa-tractor"></i>
                <h3>Smart Farming</h3>
                <p>AI-powered sensors and IoT networks collect hyper-local climate data, from soil moisture to micro-weather patterns.</p>
                <!-- <p>IoT sensors and drones collect precise field data on soil moisture, crop health, and microclimate conditions.</p> -->
            </div>
            <div class="tech-card">
                <i class="fas fa-cloud-sun-rain"></i>
                <h3>Weather Insights</h3>
                <p>Hyper-local weather predictions help you plan irrigation and protect crops from adverse conditions.</p>
            </div>
            <div class="tech-card">
                <i class="fas fa-leaf"></i>
                <h3>Sustainable Growth</h3>
                <p>Optimize water usage and reduce chemical inputs while increasing yields through precision agriculture.</p>
            </div>
        </section>

        <!-- Dashboard Preview -->
        <section class="dashboard-section">
            <h2><i class="fas fa-tablet-alt"></i> Farm Management Dashboard</h2>
            <img src="Images/snapedit_1743274261207.jpeg.png" alt="Farm Dashboard" class="dashboard-img">
        </section>

        <!-- Team Section -->
        <section class="team-section">
            <h2><i class="fas fa-users"></i> Our Team Experts</h2>
            <div class="team-grid">
                <div class="team-member">
                    <div class="team-img">
                        <img src="Images/ChatGPT Image Mar 30, 2025, 10_44_21 AM.png" alt="Img4">
                    </div>
                    <div class="team-info">
                        <h3>Arpit Kumar</h3>
                        <p>Developer</p>
                    </div>
                </div>
                <div class="team-member">
                    <div class="team-img">
                        <img src="Images\ChatGPT Image Apr 17, 2025, 09_03_55 PM.png" alt="Img4">
                    </div>
                    <div class="team-info">
                        <h3>Madhav Verma</h3>
                        <p>Developer</p>
                    </div>
                </div>
                <div class="team-member">
                    <div class="team-img">
                        <img src="Images\WhatsApp Image 2025-04-17 at 9.19.07 PM.jpeg" alt="Img4">
                    </div>
                    <div class="team-info">
                        <h3>Shivansh Dubey</h3>
                        <p>Developer</p>
                    </div>
                </div>
                <div class="team-member">
                    <div class="team-img">
                        <img src="Images\WhatsApp Image 2025-04-17 at 10.47.22 PM.jpeg" alt="Img2">
                    </div>
                    <div class="team-info">
                        <h3>Aishwarya Arya</h3>
                        <p>Developer</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <h2>Ready to Grow Smarter with FarmForecast?</h2>
            <p>Join thousands of farmers using our technology to increase yields and reduce costs.</p>
            <div class="social-links">
                <a href="#" target="_blank" class="social-link">
                    <i class="fab fa-instagram"></i>
                    Follow us on Instagram
                </a>
                <a href="#" target="_blank" class="social-link">
                    <i class="fab fa-twitter"></i>
                    Follow us on Twitter
                </a>
                <a href="#" target="_blank" class="social-link">
                    <i class="fab fa-facebook-f"></i>
                    Follow us on Facebook
                </a>
            </div>
        </section>
    </div>
</body>
</html>