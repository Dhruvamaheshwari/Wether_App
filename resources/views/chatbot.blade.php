<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SubhChintak | FarmForecast</title>
    <link rel="stylesheet" href="{{ asset('Chatbot/style.css') }}">
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h1>SubhChintak</h1>
            <h4>Powered by Gemini Ai</h4>
        </div>
        <div class="chat-messages" id="chat-messages"></div>
        <div class="chat-input">
            <input type="text" id="user-input" placeholder="Ask me anything...">
            <button id="send-button">Send</button>
        </div>
    </div>
    <script src="{{ asset('Chatbot/script.js') }}"></script>
</body>
</html>