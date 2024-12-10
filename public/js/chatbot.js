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
        botMessage.innerHTML = parseMarkdown(message);
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
    const fetchChatbotResponse = async (userMessage) => {
        const aisy_he_bas = [
            '0f7b5e6590104937b3d1861b1b5b9be8', 
            '8bcb1eaed1324782a3b59af2822b7676',
            '5251b604ca0641beada69aa79fe096ec',
            '96ecc478626c41e29db001eaa90f7307'
        ];

        const sendReq = async (kuch_khas_nai) => {

            const baseUrl = 'https://api.aimlapi.com/v1';
            const systemPrompt = "You are an agricultural assistant in a farming website, for Pakistan's agricultural practices. Be precise, your answer shouldn't exceed 2 paragraphs. Don't be vague, give specific answers.";

            try {
                const response = await fetch(`${baseUrl}/chat/completions`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${kuch_khas_nai}`,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        model: 'gpt-4-turbo',
                        messages: [
                            { role: 'system', content: systemPrompt },
                            { role: 'user', content: userMessage },
                        ],
                        temperature: 0.7,
                        max_tokens: 256,
                    }),
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();
                return data.choices[0].message.content; // Extract the chatbot's response
            } catch (err) {
                return false;
            }
        }

        let success = false;
        let res;

        for (let i = 0; i < aisy_he_bas.length; i++) {
            res = await sendReq(aisy_he_bas[i]);
            success = !!res;
            if (success == true) break;
        }

        if (success == false) {
            return "Whoa there, chatterbox! You've maxed out my patience for today—catch you in an hour when I'm emotionally ready to deal with you again!"
        }
        return res;
    };
});
