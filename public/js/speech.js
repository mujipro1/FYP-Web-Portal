const micBtn = document.getElementById('mic-btn');
const inputBox = document.getElementById('chatbot-text');
const chatbotsendbtn = document.getElementById('chatbot-send-btn');



const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
const recognition = new SpeechRecognition();

recognition.lang = 'en-US'; // or 'ur-PK' for Urdu etc.
recognition.interimResults = false;
recognition.maxAlternatives = 1;

micBtn.addEventListener('click', () => {
    console.log("clicked")
    recognition.start();
    micBtn.innerText = '🎙️ Listening...';
});

recognition.onresult = (event) => {
    const speechToText = event.results[0][0].transcript;
    inputBox.value = speechToText;
    chatbotsendbtn.click();
    micBtn.innerText = '🎤 Speak';
};

recognition.onerror = (event) => {
    console.error('Speech recognition error:', event.error);
    micBtn.innerText = '🎤 Speak';
};

recognition.onend = () => {
    micBtn.innerText = '🎤 Speak';
};
