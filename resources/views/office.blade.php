<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmForecast - Our Office</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="O_style.css">
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <div class="background"></div>

    <div class="content">
        <div class="container">
            <h1>Our Office | FarmForecast</h1>

            <div class="toggle-btns">
                <button onclick="toggleTheme()">Dark-mode</button>
                <button onclick="location.href='{{ route('support') }}'">Support Us</button>
            </div>

            <div class="office-info">
                <div class="contact-item">
                    <strong data-tooltip="Call us">📞 Contact Numbers:</strong>
                    <p>+91 12345 67890 | +91 98765 43210</p>
                </div>

                <div class="contact-item">
                    <strong data-tooltip="Our hours of operation">🕒 Working Hours:</strong>
                    <div id="workingHours">
                        <p>Monday to Friday: 9:00 AM - 6:00 PM</p>
                        <p>Saturday: 10:00 AM - 4:00 PM</p>
                        <p>Sunday: Closed</p>
                    </div>
                </div>

                <div class="contact-item">
                    <strong data-tooltip="Our head office">📍 Our Location:</strong>
                    <p>FarmForecast, Agriculture Hub, Plot No. 56, Green Acres Road,</p>
                    <p>FarmVille, Punjab – 144011, India</p>
                </div>
            </div>

            <div class="footer">
                <p>&copy; 2025 FarmForecast. All Rights Reserved.</p>
            </div>
        </div>
    </div>

    <script>
        function toggleTheme() {
            document.body.classList.toggle("dark-mode");
            var button = document.querySelector(".toggle-btns button");
            if (document.body.classList.contains("dark-mode")) {
                button.textContent = "Light Mode";
            } else {
                button.textContent = "Dark Mode";
            }
        }
    </script>

</body>

</html>