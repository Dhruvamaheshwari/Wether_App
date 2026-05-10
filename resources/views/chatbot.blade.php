<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriBot AI | Smart Agriculture</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('chatbot_app/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="app-wrapper">
        <!-- Back Button -->
        <a href="{{ url('/home') }}" class="back-home">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Dashboard</span>
        </a>

        <div class="chat-main-container">
            <!-- Sidebar for info -->
            <div class="chat-sidebar">
                <div class="sidebar-header">
                    <i class="fas fa-seedling"></i>
                    <h2>FarmForecast</h2>
                </div>
                <div class="sidebar-content">
                    <div class="info-card">
                        <h3><i class="fas fa-robot"></i> AgriBot AI</h3>
                        <p>Intelligent assistant for Smart Agriculture Climate Monitoring.</p>
                    </div>
                    <div class="tips-section">
                        <h4>Try asking about:</h4>
                        <ul>
                            <li><i class="fas fa-tint"></i> Should I water crops now?</li>
                            <li><i class="fas fa-wheat-awn"></i> Is temperature safe for wheat?</li>
                            <li><i class="fas fa-cloud-rain"></i> Will rain affect irrigation?</li>
                        </ul>
                    </div>
                </div>
                <div class="sidebar-footer">
                    <p>© 2025 FarmForecast</p>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="chat-interface">
                <header class="interface-header">
                    <div class="bot-info">
                        <div class="bot-avatar">
                            <i class="fas fa-robot"></i>
                            <span class="online-status"></span>
                        </div>
                        <div class="bot-text">
                            <h1>AgriBot</h1>
                            <p>Climate Monitoring Assistant • Online</p>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button id="clear-chat" title="Clear Chat"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </header>

                <div class="chat-messages-area" id="chat-messages">
                    <!-- Messages will appear here -->
                    <div class="bot-message initial-greeting">
                        <div class="message-bubble">
                            <p>Namaste! I am <strong>AgriBot</strong>, your Climate Monitoring AI. I can analyze soil moisture, weather, and sensor data to help you grow better. How can I help you today?</p>
                        </div>
                        <span class="message-time">Just now</span>
                    </div>
                </div>

                <div class="chat-input-wrapper">
                    <div class="input-container">
                        <textarea id="user-input" placeholder="Type your agricultural query here..." rows="1"></textarea>
                        <button id="send-button">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <p class="input-footer">SubhChintak can make mistakes. Verify important agricultural data.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('chatbot_app/script.js') }}"></script>
</body>
</html>