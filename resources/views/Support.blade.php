<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Contact Us | FarmForecast</title>
  <link rel="stylesheet" href="S_style.css" />
</head>
<body>

  <!-- Background Video -->
  <video autoplay muted loop id="bg-video">
    <source src="Images\Vid.mp4" type="video/mp4">
  </video>

  <!-- Contact Form -->
  <form class="container" id="contactForm" action="contact.php" method="POST" onsubmit="return submitForm(event);">
    <h2>Contact Us</h2>
    <p class="subtext">Have questions about our<br>Automated Climate Monitoring System?</p>

    <label for="name">Name</label>
    <input type="text" id="name" name="name" placeholder="Name" required />

    <label for="email">Email</label>
    <input type="email" id="email" name="email" placeholder="xyz@gmail.com" required />

    <label for="subject">Subject</label>
    <input type="text" id="subject" name="subject" placeholder="Climate Monitoring Query" required />

    <label for="message">Message</label>
    <textarea id="message" name="message" placeholder="Write your message here..." maxlength="1000" required></textarea>

    <button type="submit">Send Message</button>

    <!-- Office Details Button -->
      <a href="office.php" class="back-btn">Office Details →</a>
  </form>

  <!-- Pop-up -->
  <div class="popup" id="popup">
    <div class="popup-content">
      <h3>Message Sent Successfully!✅</h3>
      <p>We’ll get back to you as soon as possible.</p>
      <button onclick="closePopup()">Close</button>
    </div>
  </div>

  <script>
    function submitForm(event) {
      event.preventDefault(); // Prevent normal form submission

      const form = document.getElementById("contactForm");
      const formData = new FormData(form);

      fetch("contact.php", {
        method: "POST",
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        if (data.includes("success")) {
          document.getElementById("popup").style.display = "flex";
          setTimeout(() => {
            window.location.href = "office.php";
          }, 3000);
        } else {
          alert("Failed to send message. Please try again.");
        }
      })
      .catch(error => {
        alert("Error submitting form.");
        console.error("Error:", error);
      });

      return false;
    }

    function closePopup() {
      document.getElementById("popup").style.display = "none";
      window.location.href = "homepage.php";
    }
  </script>

</body>
</html>
