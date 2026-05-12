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
                    <p>© 2026 FarmForecast</p>
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
                    <p class="input-footer">AgriBot can make mistakes. Verify important agricultural data.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('chatbot_app/script.js') }}"></script>
</body>
</html>