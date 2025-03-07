document.addEventListener('DOMContentLoaded', () => {
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotWindow = document.getElementById('chatbot-window');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotText = document.getElementById('chatbot-text');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const sendbutton = document.getElementById('chatbot-send-btn');

    // Toggle the chatbot window when the chat button is clicked
    chatbotToggle.addEventListener('click', () => {
        chatbotToggle.style.display = 'none'; // Hide the toggle button
        chatbotWindow.style.display = 'flex'; // Show the chatbot window
    });

    // Close the chatbot window when the close button is clicked
    chatbotClose.addEventListener('click', () => {
        chatbotWindow.style.display = 'none'; // Hide the chatbot window
        chatbotToggle.style.display = 'block'; // Show the toggle button
    });

    // Send a message when the user presses Enter or clicks the send button
    sendbutton.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent form submission
        sendMessage();
    });

    chatbotText.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevent form submission
            sendMessage();
        }
    });

    // Function to send a message
    const sendMessage = () => {
        const message = chatbotText.value.trim();
        if (!message) return;

        // Add user message to chat
        addUserMessage(message);

        chatbotText.value = ''; // Clear input field

        // Send API request
        fetchChatbotResponse(message)
            .then(botReply => addBotMessage(botReply))
            .catch(err => {
                console.error('Error:', err);
                addBotMessage("Sorry, something went wrong. Please try again later.");
            });
    };

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

    // Function to add bot message to the chat
    const addBotMessage = (message) => {
        const botMessageOuter = document.createElement('div');
        const pfp = document.createElement('div');
        const botMessage = document.createElement('div');
        const timeElement = document.createElement('div');

        // Add classes for styling
        botMessage.classList.add("chatbot-bot-msg");
        botMessageOuter.classList.add("chatbot-bot-msg-outer");
        timeElement.classList.add("chatbot-bot-time");
        pfp.classList.add("bot-pfp");


        // Parse and render Markdown
        const sanitizedMessage = message.replace(/<think>.*?<\/think>/gs, '');
        botMessage.innerHTML = parseMarkdown(sanitizedMessage);

        botMessage.style.textAlign = 'left';

        // Set time text
        const now = new Date();
        const formattedTime = formatTime(now);
        timeElement.textContent = formattedTime;

        // Append elements
        botMessageOuter.appendChild(pfp);
        botMessageOuter.appendChild(botMessage);
        botMessage.appendChild(timeElement);
        chatbotMessages.appendChild(botMessageOuter);

        // Scroll to the bottom
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
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
    // Function to fetch chatbot response
const fetchChatbotResponse = async (userMessage) => {
    try {
        const response = await fetch('http://10.3.16.62:5000/query', {
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
        console.error('Error:', err);
        return "Sorry, something went wrong. Please try again later.";
    }
};

});
