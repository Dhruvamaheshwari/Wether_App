<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Contact Us | FarmForecast</title>
  <link rel="stylesheet" href="S_style.css" />
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
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
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

    .office-btn {
      width: 100%;
      padding: 12px;
      background-color: #4caf50;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
      text-decoration: none;
      display: inline-block;
      text-align: center;
      box-sizing: border-box;
      transition: background-color 0.3s;
    }

    .office-btn:hover {
      background-color: #2e7d32;
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <!-- Background Video -->
  <video autoplay muted loop id="bg-video">
    <source src="Images\Vid.mp4" type="video/mp4">
  </video>

  <!-- Contact Form -->
  <form class="container" id="contactForm" action="{{ route('contact.send') }}" method="POST" onsubmit="return submitForm(event);">
    @csrf
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
    <a href="{{ route('office') }}" class="office-btn">Office Details →</a>
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

      fetch("{{ route('contact.send') }}", {
          method: "POST",
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          if (data.includes("success")) {
            document.getElementById("popup").style.display = "flex";
            setTimeout(() => {
              window.location.href = "{{ route('office') }}";
            }, 3000);
          } else {
            alert("Failed to send message: " + data);
            console.error("Server response:", data);
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
      window.location.href = "{{ route('home') }}";
    }
  </script>

</body>

</html>