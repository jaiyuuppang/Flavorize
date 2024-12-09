<?php
session_start();
require 'database.php';

// Gemini API configuration
$geminiApiKey = getenv('GEMINI_API_KEY') ?: 'AIzaSyCZFH01hgDv8x2DBSHpjObi3hkixUZ17-w'; // Fallback to local key if env var not set
$geminiEndpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userMessage = $_POST['message'] ?? '';
    
    try {
        // Prepare the prompt with context about cooking and recipes
        $prompt = array(
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "You are a concise cooking assistant. Keep your responses brief and to the point.

Rules:
1. For recipes, use this exact format:
   **Recipe Name**
   • **Ingredients**: [3-4 main ingredients]
   • **Cooking time**: [time]
   • **Pro tip**: [one key tip]

2. Keep all responses under 3 lines
3. Use bullet points with • symbol
4. Use ** for bold text only for headers and labels
5. Don't provide detailed instructions
6. If asked about cooking techniques, give only 1-2 key tips

User message: " . $userMessage
                        ]
                    ]
                ]
            ]
        );

        // Setup cURL request to Gemini
        $ch = curl_init($geminiEndpoint . '?key=' . $geminiApiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($prompt));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        // Get response from Gemini
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            error_log("cURL Error: " . $err);
            throw new Exception("Failed to connect to Gemini API");
        }

        $aiResponse = json_decode($response, true);
        
        // Log the full response for debugging
        error_log("Gemini API Response: " . $response);

        if (isset($aiResponse['error'])) {
            error_log("Gemini API Error: " . json_encode($aiResponse['error']));
            throw new Exception($aiResponse['error']['message'] ?? 'Unknown API error');
        }

        // Extract the response text
        $responseText = $aiResponse['candidates'][0]['content']['parts'][0]['text'] ?? 'I apologize, I cannot process your request at the moment.';
        
        echo $responseText;
    } catch (Exception $e) {
        error_log("Chatbot Error: " . $e->getMessage());
        echo 'Sorry, I encountered an error: ' . $e->getMessage();
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Assistant</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f5f7fb;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-container {
            width: 400px;
            height: 600px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-header {
            background: rgb(253, 57, 8);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chat-header h2 {
            font-size: 1.2rem;
            font-weight: 500;
            color: white;
        }

        .chat-header i {
            font-size: 1.4rem;
            color: white;
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background-color: #f0f2f5;
        }

        .message {
            max-width: 80%;
            padding: 12px 16px;
            border-radius: 15px;
            font-size: 0.95rem;
            line-height: 1.4;
            position: relative;
            word-wrap: break-word;
        }

        .user-message {
            align-self: flex-end;
            background-color: #dcf8c6;
            color: #000;
            border-bottom-right-radius: 5px;
        }

        .bot-message {
            align-self: flex-start;
            background-color: white;
            color: #000;
            border-bottom-left-radius: 5px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .bot-message.loading {
            background-color: #e9ecef;
            color: #666;
            padding: 8px 16px;
        }

        .bot-message.error {
            background-color: #fee;
            color: #c00;
        }

        .message strong {
            color: rgb(253, 57, 8);
        }

        .chat-input {
            padding: 15px;
            background: white;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .chat-input input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 25px;
            outline: none;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        .chat-input input:focus {
            border-color: rgb(253, 57, 8);
        }

        .chat-input button {
            background: rgb(253, 57, 8);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 0.95rem;
            transition: background-color 0.2s;
        }

        .chat-input button:hover {
            background: #c51f1f;
        }

        .suggestions {
            padding: 10px 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            background: white;
            border-top: 1px solid #eee;
        }

        .suggestion-btn {
            white-space: nowrap;
            padding: 8px 15px;
            border: 1px solid rgb(253, 57, 8);
            border-radius: 20px;
            background: white;
            color: rgb(253, 57, 8);
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .suggestion-btn:hover {
            background-color: rgb(253, 57, 8);
            color: white;
        }

        /* Scrollbar Styling */
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }

        /* Message Time Styling */
        .message::after {
            content: attr(data-time);
            position: absolute;
            bottom: -20px;
            font-size: 0.75rem;
            color: #666;
            padding: 2px 0;
        }

        .user-message::after {
            right: 5px;
        }

        .bot-message::after {
            left: 5px;
        }

        /* Bullet Points Styling */
        .bot-message ul {
            margin: 5px 0;
            padding-left: 20px;
        }

        .bot-message li {
            margin: 5px 0;
        }

        .recipe-item {
            margin-bottom: 15px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
        }
        .recipe-title {
            font-size: 1.1em;
            margin-bottom: 10px;
        }
        .recipe-title strong {
            color: rgb(253, 57, 8);
        }
        .recipe-ingredients, .recipe-time, .recipe-instructions {
            margin: 8px 0;
            line-height: 1.5;
        }
        .recipe-ingredients strong, 
        .recipe-time strong, 
        .recipe-instructions strong {
            color: #333;
            margin-right: 5px;
        }

        .ai-response-block {
            margin-bottom: 15px;
            padding: 12px;
            background-color: #f0f4f8;
            border-radius: 8px;
            line-height: 1.6;
        }
        .recipe-item {
            border: 1px solid #e0e4e8;
        }
        .recipe-title {
            font-size: 1.1em;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        .recipe-title strong {
            color: rgb(253, 57, 8);
        }
        .recipe-details {
            color: #34495e;
        }
        .recipe-details strong {
            color: rgb(253, 57, 8);
            display: inline-block;
            margin-right: 5px;
        }
        .bot-message p {
            margin: 10px 0;
        }
        .bot-message strong {
            color: rgb(253, 57, 8);
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <i class="fas fa-robot"></i>
            <h2>Recipe Assistant</h2>
        </div>
        
        <div class="chat-messages" id="chatMessages">
            <div class="message bot-message">
                Hello! I'm your AI cooking assistant. Click a suggestion below or ask me anything about recipes! 🤖
            </div>
        </div>

        <div class="suggestions">
            <button class="suggestion-btn" onclick="sendSuggestion('Quick healthy breakfast ideas')">
                🍳 Quick Breakfast
            </button>
            <button class="suggestion-btn" onclick="sendSuggestion('Low-carb dinner recipes')">
                🥗 Low-Carb Meals
            </button>
            <button class="suggestion-btn" onclick="sendSuggestion('Easy chicken recipes')">
                🍗 Chicken Recipes
            </button>
            <button class="suggestion-btn" onclick="sendSuggestion('Vegetarian protein sources')">
                🥦 Vegetarian Options
            </button>
            <button class="suggestion-btn" onclick="sendSuggestion('30-minute recipes')">
                ⏱️ Quick Meals
            </button>
            <button class="suggestion-btn" onclick="sendSuggestion('Healthy snack ideas')">
                🍎 Healthy Snacks
            </button>
            <button class="suggestion-btn" onclick="sendSuggestion('Meal prep tips')">
                📋 Meal Prep Tips
            </button>
            <button class="suggestion-btn" onclick="sendSuggestion('Common ingredient substitutes')">
                🔄 Ingredient Swaps
            </button>
        </div>

        <div class="chat-input-container">
            <div class="chat-input">
                <input type="text" id="userInput" placeholder="Ask about recipes, cooking tips, or ingredients...">
                <button onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>

    <script>
        function processMarkdown(text) {
            // Split text into lines
            const lines = text.split('\n').filter(line => line.trim() !== '');
            
            // Process each line
            const processedLines = lines.map(line => {
                // Bold markdown
                line = line.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                
                // Special handling for recipe-like structures
                const recipeMatch = line.match(/•\s*(\d+)\.\)\s*([^:]+):\s*Ingredients:\s*([^•\n]+)\s*Prep time:\s*•\s*(\d+\s*min)\s*Instructions:\s*•\s*([^\n]+)/);
                if (recipeMatch) {
                    const [, number, recipeName, ingredients, prepTime, instructions] = recipeMatch;
                    return `
<div class="ai-response-block recipe-item">
    <div class="recipe-title"><strong>${number}.) ${recipeName}</strong></div>
    <div class="recipe-details">
        <strong>Ingredients:</strong> ${ingredients.trim()}<br>
        <strong>Prep time:</strong> ${prepTime}<br>
        <strong>Instructions:</strong> ${instructions.trim()}
    </div>
</div>`;
                }
                
                return `<p>${line}</p>`;
            });

            return processedLines.join('');
        }

        function sendMessage() {
            const userInput = document.getElementById('userInput');
            const message = userInput.value.trim();
            
            if (message === '') return;

            // Add user message
            const chatMessages = document.querySelector('.chat-messages');
            const userDiv = document.createElement('div');
            userDiv.className = 'message user-message';
            userDiv.textContent = message;
            chatMessages.appendChild(userDiv);

            // Clear input
            userInput.value = '';

            // Show loading message
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'message bot-message loading';
            loadingDiv.textContent = 'Typing...';
            chatMessages.appendChild(loadingDiv);

            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Send to PHP
            fetch('chatbot.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'message=' + encodeURIComponent(message)
            })
            .then(response => response.text())
            .then(response => {
                // Remove loading message
                chatMessages.removeChild(loadingDiv);

                // Add bot response with markdown processing
                const botDiv = document.createElement('div');
                botDiv.className = 'message bot-message';
                botDiv.innerHTML = processMarkdown(response);
                chatMessages.appendChild(botDiv);

                // Scroll to bottom
                chatMessages.scrollTop = chatMessages.scrollHeight;
            })
            .catch(error => {
                console.error('Error:', error);
                // Remove loading message
                chatMessages.removeChild(loadingDiv);
                
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'message bot-message error';
                errorDiv.textContent = 'Sorry, something went wrong. Please try again.';
                chatMessages.appendChild(errorDiv);
            });
        }

        function sendSuggestion(suggestion) {
            const input = document.getElementById('userInput');
            input.value = suggestion;
            sendMessage();
        }

        // Enter key to send message
        document.getElementById('userInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
</body>
</html>