document.addEventListener('DOMContentLoaded', () => {
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotWindow = document.getElementById('chatbot-window');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotText = document.getElementById('chatbot-text');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const sendbutton = document.getElementById('chatbot-send-btn');

    // Toggle the chatbot window when the chat button is clicked
    if (chatbotToggle) {
        chatbotToggle.addEventListener('click', () => {
            chatbotToggle.style.display = 'none'; // Hide the toggle button
            chatbotWindow.style.display = 'flex'; // Show the chatbot window
        });
    }

    // Close the chatbot window when the close button is clicked
    if (chatbotClose) {
        chatbotClose.addEventListener('click', () => {
            chatbotWindow.style.display = 'none'; // Hide the chatbot window
            chatbotToggle.style.display = 'flex'; // Show the toggle button
        });
    }

    // Send a message when the user presses Enter or clicks the send button
    sendbutton.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent form submission
        tempMsgChatbotParent = document.getElementById('temp-msg-chatbot-parent');
        if(tempMsgChatbotParent){
            tempMsgChatbotParent.style.display = 'none';
        }
        chachaPura = document.getElementById('chacha-pura');
        if(chachaPura){
            chachaPura.style.display = 'block';
        }
        
        sendMessage();
    });

    chatbotText.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            tempMsgChatbotParent = document.getElementById('temp-msg-chatbot-parent');
            if(tempMsgChatbotParent){
            tempMsgChatbotParent.style.display = 'none';
        }
            chachaPura = document.getElementById('chacha-pura');
            if(chachaPura){
            chachaPura.style.display = 'block';
        }
            e.preventDefault(); // Prevent form submission
            sendMessage();
        }
    });

    // Function to send a message
    const sendMessage = () => {
        const message = chatbotText.value.trim();
        if (!message) return;
    
        // Add user message to the chat
        addUserMessage(message);
    
        chatbotText.value = ''; // Clear input field
    
        // Show typing animation from bot while waiting for the response
        addBotTyping();
    
        // Send API request
        fetchChatbotResponse(message)
            .then(botReply => {
                addBotMessage(botReply); // Replace typing animation with bot's response
            })
            .catch(err => {
                console.error('Error:', err);
                addBotMessage("Sorry, something went wrong. Please try again later.");
            });
    };
    
    // Function to add bot typing indicator
    const addBotTyping = () => {
        const botMessageOuter = document.createElement('div');
        const pfp = document.createElement('div');
        const botMessage = document.createElement('div');
        const typingIndicator = document.createElement('div');
        
        // Add classes for styling
        botMessage.classList.add("chatbot-bot-msg");
        botMessageOuter.classList.add("chatbot-bot-msg-outer");
        botMessageOuter.classList.add("typing-indicator-container"); // Add a specific class for easier selection
        typingIndicator.classList.add("chatbot-typing-indicator");
        pfp.classList.add("bot-pfp");
    
        // Set typing message
        typingIndicator.textContent = "Thinking...";  // Initial text
        botMessage.appendChild(typingIndicator);
    
        // Append elements
        botMessageOuter.appendChild(pfp);
        botMessageOuter.appendChild(botMessage);
        chatbotMessages.appendChild(botMessageOuter);
    
        // Scroll to the bottom
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    
        // Change text to "Compiling Data" after 2 seconds
        setTimeout(() => {
            if (typingIndicator) {
                typingIndicator.textContent = "Searching the Web...";
            }
        }, 3000);
    };
    

// Function to add bot message to the chat

    
    // Function to add user message to the chat
    const addUserMessage = (message) => {
        const userMessageOuter = document.createElement('div');
        const pfp = document.createElement('div');
        const userMessage = document.createElement('div');
        const timeElement = document.createElement('div');

        // Add classes for styling
        userMessage.classList.add("chatbot-user-msg");
        userMessageOuter.classList.add("chatbot-user-msg-outer");
        timeElement.classList.add("chatbot-msg-time");
        pfp.classList.add("user-pfp");

        // Set message text
        userMessage.textContent = message;
        userMessage.style.textAlign = 'left';

        // Set time text
        const now = new Date();
        const formattedTime = formatTime(now);
        timeElement.textContent = formattedTime;

        // Append elements
        userMessageOuter.appendChild(userMessage);
        userMessage.appendChild(timeElement);
        chatbotMessages.appendChild(userMessageOuter);
        userMessageOuter.appendChild(pfp);

        // Scroll to the bottom
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    };

    const parseMarkdown = (markdown) => {
        const markdownConverter = new showdown.Converter(); // Use a Markdown converter library like Showdown
        return markdownConverter.makeHtml(markdown);
    };

    // Function to add bot message to the chat with Speak and Translate buttons
    const addBotMessage = (message) => {
        // Remove the thinking/compiling indicator
        const typingIndicatorWrapper = document.querySelector(".typing-indicator-container");
        if (typingIndicatorWrapper) {
            typingIndicatorWrapper.remove();
        }
    
        // Create new message elements
        const botMessageOuter = document.createElement('div');
        const pfp = document.createElement('div');
        const botMessage = document.createElement('div');
        const messageContent = document.createElement('div');
        const timeElement = document.createElement('div');
        const speakButton = document.createElement('button'); // Create the Speak button
    
        // Add classes for styling
        botMessage.classList.add("chatbot-bot-msg");
        botMessageOuter.classList.add("chatbot-bot-msg-outer");
        timeElement.classList.add("chatbot-bot-time");
        pfp.classList.add("bot-pfp");
        messageContent.classList.add("chatbot-message-content");
        speakButton.classList.add("chatbot-speak-btn"); // Add a class for the Speak button
    
        // Set time text
        const now = new Date();
        const formattedTime = formatTime(now);
        timeElement.textContent = formattedTime;
    
        // Set Speak button text
        speakButton.textContent = "🔊 Speak";
        
        // Append elements but leave messageContent empty for now
        botMessageOuter.appendChild(pfp);
        botMessageOuter.appendChild(botMessage);
        botMessage.appendChild(messageContent);
        botMessage.appendChild(speakButton); // Append the Speak button
        botMessage.appendChild(timeElement);
        chatbotMessages.appendChild(botMessageOuter);
    
        // Scroll to the bottom
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    
        // Parse markdown
        const parsedMessage = parseMarkdown(message);
    
        // Insert the full HTML content immediately
        messageContent.style.textAlign = 'left';
        messageContent.innerHTML = parsedMessage;
    
        // Add event listener to the Speak button
        speakButton.addEventListener('click', () => {
            speak(message, speakButton); // Pass the button to toggle its text
        });
    
        // Commented out the animation logic
        /*
        const textNodes = [];
        const getTextNodes = (node) => {
            if (node.nodeType === 3 && node.nodeValue.trim() !== '') { // Text node with content
                textNodes.push(node);
            } else if (node.nodeType === 1) { // Element node
                for (let i = 0; i < node.childNodes.length; i++) {
                    getTextNodes(node.childNodes[i]);
                }
            }
        };
    
        getTextNodes(messageContent);
    
        // Hide all text initially
        textNodes.forEach(node => {
            node.originalText = node.nodeValue;
            node.nodeValue = '';
        });
    
        const typingSpeed = 250; // Higher number = faster typing (characters per second)
        let lastTypedTime = Date.now();
    
        const revealText = () => {
            const now = Date.now();
            const elapsed = now - lastTypedTime;
            const charsToType = Math.floor(elapsed * typingSpeed / 1000);
    
            if (charsToType <= 0) {
                requestAnimationFrame(revealText);
                return;
            }
    
            let charsTyped = 0;
            let allDone = true;
    
            for (let i = 0; i < textNodes.length; i++) {
                const node = textNodes[i];
                const remaining = node.originalText.length - node.nodeValue.length;
    
                if (remaining > 0) {
                    allDone = false;
                    const charsToAdd = Math.min(remaining, charsToType - charsTyped);
                    if (charsToAdd <= 0) continue;
    
                    const nextPortion = node.originalText.substr(
                        node.nodeValue.length,
                        charsToAdd
                    );
                    node.nodeValue += nextPortion;
                    charsTyped += charsToAdd;
    
                    if (charsTyped >= charsToType) break;
                }
            }
    
            lastTypedTime = now;
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    
            if (!allDone) {
                requestAnimationFrame(revealText);
            }
        };
    
        lastTypedTime = Date.now();
        requestAnimationFrame(revealText);
        */
    };

    // Function to format time
    const formatTime = (date) => {
        const hours = date.getHours();
        const minutes = date.getMinutes();
        const formattedHours = hours % 12 || 12; // Convert 24-hour to 12-hour format
        const formattedMinutes = minutes.toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'p.m.' : 'a.m.';
        return `${formattedHours}:${formattedMinutes} ${ampm}`;
    };

    // Function to fetch chatbot response
    const fetchChatbotResponse = async (userMessage) => {
        try {
            const response = await fetch('https://10.3.16.71:443/query', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ query: userMessage }),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            return data.response; // Ensure the backend returns a JSON object with a "response" key
        } catch (err) {
            console.error('Fetch Error:', err); // Improved error logging
            return `Sorry, something went wrong. Error: ${err.message}`;
        }
    };

    // Now expose `sendDefaultMessage` to global scope so it's accessible outside DOMContentLoaded
    window.sendDefaultMessage = (e) => {

        tempMsgChatbotParent = document.getElementById('temp-msg-chatbot-parent');
        if(tempMsgChatbotParent){
            tempMsgChatbotParent.style.display = 'none';
        }

        chachaPura = document.getElementById('chacha-pura');
        if(chachaPura){
            chachaPura.style.display = 'block';
        }
    
        let question = '';
        if (e === 1) { question = "What are some famous crops in Punjab?" }
        if (e === 2) { question = "Guide me about basics of farming techniques in Pakistan" }
        if (e === 3) { question = "Tell me a fun fact" }
    
        // Add user question to the chat
        addUserMessage(question);
    
        addBotTyping();

        // Fetch the chatbot response
        fetchChatbotResponse(question)
            .then(botReply => addBotMessage(botReply))
            .catch(err => {
                console.error('Error:', err);
                addBotMessage("Sorry, something went wrong. Please try again later.");
            });
    };
});


const speak = (text, button) => {
    const synth = window.speechSynthesis;

    // If already speaking, stop the speech and reset the button
    if (synth.speaking) {
        synth.cancel();
        button.textContent = "🔊 Speak";
        return;
    }

    const utter = new SpeechSynthesisUtterance(text);
    utter.lang = 'en-US';

    // Update button text to "Stop Speaking" while speaking
    button.textContent = "⏹ Stop Speaking";

    // Reset button text when speaking ends
    utter.onend = () => {
        button.textContent = "🔊 Speak";
    };

    // Handle errors during speech synthesis
    utter.onerror = () => {
        console.error("Speech synthesis error occurred.");
        button.textContent = "🔊 Speak";
    };

    synth.speak(utter);
};