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

    <div class="background"></div>

    <div class="content">
        <div class="container">
            <h1>Our Office | FarmForecast</h1>

            <div class="toggle-btns">
                <button onclick="toggleTheme()">Dark-mode</button>
                <button onclick="location.href='Support.php'">Support Us</button>
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
