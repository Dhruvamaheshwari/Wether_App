// script.js - SubhChintak AI
document.addEventListener('DOMContentLoaded', () => {
    const chatMessages = document.getElementById("chat-messages");
    const userInput = document.getElementById("user-input");
    const sendButton = document.getElementById("send-button");
    const clearButton = document.getElementById("clear-chat");
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Auto-resize textarea
    userInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Display message function
    function displayMessage(text, sender = "bot") {
        const messageDiv = document.createElement("div");
        messageDiv.classList.add(sender === "user" ? "user-message" : "bot-message");
        
        const now = new Date();
        const timeStr = now.getHours() + ":" + (now.getMinutes() < 10 ? "0" : "") + now.getMinutes();

        messageDiv.innerHTML = `
            <div class="message-bubble">
                <p>${text}</p>
            </div>
            <span class="message-time">${sender === "user" ? "Sent" : "AgriBot"} • ${timeStr}</span>
        `;

        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Typing indicator
    function showTyping() {
        const typingDiv = document.createElement("div");
        typingDiv.id = "typing-indicator";
        typingDiv.classList.add("bot-message");
        typingDiv.innerHTML = `
            <div class="message-bubble typing">
                <p>AgriBot is thinking...</p>
            </div>
        `;
        chatMessages.appendChild(typingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function removeTyping() {
        const indicator = document.getElementById("typing-indicator");
        if (indicator) indicator.remove();
    }

    // Handle Send Message
    async function handleSend() {
        const text = userInput.value.trim();
        if (!text) return;

        displayMessage(text, "user");
        userInput.value = "";
        userInput.style.height = 'auto';

        showTyping();

        try {
            const response = await fetch('/chatbot/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ message: text })
            });

            const data = await response.json();
            removeTyping();

            if (data.response) {
                displayMessage(data.response, "bot");
            } else if (data.error || data.message) {
                let errorText = data.error || data.message;
                if (typeof errorText !== 'string') {
                    errorText = JSON.stringify(errorText);
                }
                if (errorText.includes("API key not configured")) {
                    errorText = `<strong>Configuration Missing:</strong> Please add your OpenRouter API key to the .env file. <br><a href="https://openrouter.ai/keys" target="_blank" style="color: #4CAF50; text-decoration: underline;">Get a free key here</a>`;
                } else if (errorText.includes("Unauthenticated")) {
                    errorText = `<strong>Authentication Required:</strong> Please log in to use the chatbot. <a href="/login" style="color: #4CAF50; text-decoration: underline;">Login here</a>`;
                } else if (errorText.includes("not found") || response.status === 404) {
                    errorText = `<strong>Model Error:</strong> The selected AI model was not found. Please check your API key permissions and model availability.`;
                } else if (errorText.includes("quota") || response.status === 429) {
                    errorText = `<strong>Quota Exceeded:</strong> You have reached the free tier limit. Please try again in a few minutes or check your billing details.`;
                }
                displayMessage("Error: " + errorText, "bot");
            } else {
                displayMessage("Sorry, I am having trouble connecting right now.", "bot");
            }
        } catch (error) {
            removeTyping();
            displayMessage("Network error. Please try again.", "bot");
        }
    }

    sendButton.addEventListener("click", handleSend);
    userInput.addEventListener("keydown", (e) => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            handleSend();
        }
    });

    clearButton.addEventListener("click", () => {
        chatMessages.innerHTML = `
            <div class="bot-message initial-greeting">
                <div class="message-bubble">
                    <p>Chat cleared. How can I help you now?</p>
                </div>
                <span class="message-time">Just now</span>
            </div>
        `;
    });
});