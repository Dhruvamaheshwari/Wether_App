// script.js
// Display the initial bot greeting message when the script loads
document.addEventListener('DOMContentLoaded', () => {
    const initialMessage = `
        <div class="bot-greeting">
            <strong>SubhChintak:</strong> Namaste! I'm your agricultural assistant. How can I help you with farming, crops, or weather today?
        </div>
    `;
    displayMessage(initialMessage, "bot");
});

// Function to send a message to the Gemini Flash API and handle the response
async function sendMessageToGeminiAPI(message) {
    const apiKey = ""; // Replace with your actual API key
    const geminiUrl = "";
    const requestBody = {
        contents: [
            {
                parts: [
                    { text: message } // Use the actual user input here
                ]
            }
        ]
    };

    try {
        const response = await fetch(`${geminiUrl}?key=${apiKey}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(requestBody)
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error("API Error:", errorData);
            throw new Error(`API Error: ${response.status} - ${errorData?.error?.message || response.statusText}`);
        }

        const data = await response.json();
        console.log("API Response:", data); // Debugging step

        // Check if the response structure is valid
        const botResponse = data?.candidates?.[0]?.content?.parts?.map(part => part.text).join(" ") || "No response received.";

        // Enhanced formatting:
        let formattedResponse = botResponse
            .split('\n\n')
            .map(paragraph => `<p>${paragraph}</p>`)
            .join('');

        // Basic bolding (example: if the bot uses "**" for bold):
        formattedResponse = formattedResponse.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

        // Basic italicizing (example: if the bot uses "*" for italics):
        formattedResponse = formattedResponse.replace(/\*(.*?)\*/g, '<em>$1</em>');

        // Basic list handling (example: lines starting with "- "):
        formattedResponse = formattedResponse.replace(/^-\s(.*?)$/gm, '<li>$1</li>');
        if (formattedResponse.includes('<li>')) {
            formattedResponse = '<ul>' + formattedResponse + '</ul>';
        }

        // Basic ordered list handling (example: lines starting with "1. "):
        formattedResponse = formattedResponse.replace(/^(\d+)\.\s(.*?)$/gm, '<li>$2</li>');
        if (formattedResponse.includes('<ol>') || formattedResponse.includes('<li>')) {
            // Simple check to avoid wrapping if already in a list
            if (!formattedResponse.includes('<ol>')) {
                const listItems = formattedResponse.split('<li>');
                if (listItems.length > 2) { // More than just the initial split
                    formattedResponse = '<ol>' + formattedResponse + '</ol>';
                }
            }
        }

        // Basic link handling:
        const linkedResponse = formattedResponse.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>');

        return `<div class="bot-message-content">${linkedResponse}</div>`;

    } catch (error) {
        console.error("Error communicating with Gemini API:", error);
        return `<div class="bot-error">Sorry, something went wrong. Please try again later.</div>`;
    }
}

// Function to display a message in the chat window
function displayMessage(messageHTML, sender = "user") {
    const chatMessages = document.getElementById("chat-messages");
    const messageElement = document.createElement("div");
    messageElement.classList.add(sender === "user" ? "user-message" : "bot-message");
    messageElement.innerHTML = sender === "user" ? `<div class="user-message-content">${messageHTML}</div>` : messageHTML;
    chatMessages.appendChild(messageElement);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Function to handle user input and send it to the API
async function handleUserInput() {
    const userInput = document.getElementById("user-input");
    const message = userInput.value.trim();

    if (message === "") return; // Ignore empty input

    displayMessage(message, "user"); // Display user's message
    userInput.value = ""; // Clear input field

    const botResponseHTML = await sendMessageToGeminiAPI(message); // Fetch response from API
    displayMessage(botResponseHTML, "bot"); // Display bot's response
}

// Add event listeners to handle sending messages
document.getElementById("send-button").addEventListener("click", handleUserInput);

// Enable sending messages with the 'Enter' key
document.getElementById("user-input").addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
        handleUserInput();
    }
});