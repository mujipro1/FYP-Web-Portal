<div id="chatbot-container">
    <button id="chatbot-toggle">
        <img src="{{ asset('images/chatbot.png') }}" class="img-fluid"/>
    </button>
    <div id="chatbot-window">
        <div id="chatbot-header">
            <span>Virtual Assistant</span>
            <button id="chatbot-close">✖</button>
        </div>
        <div id="chatbot-messages">
            <!-- Chat messages will appear here -->
        </div>
        <div class="labelcontainer mb-3">
            <input type="text" class="form-control" id="chatbot-text" placeholder="Type a Message..." required>
            <button class="btn-orange or-width mx-3" id="chatbot-send-btn">Send</button>
        </div>
    </div>
</div>




