<div class="chatbot-widget">
    <button class="chatbot-toggle" onclick="toggleChatbot()">
        💬
    </button>

    <div class="chatbot-box" id="chatbotBox">
        <div class="chatbot-header">
            <div>
                <strong>Chatbot KosKita</strong>
                <p>Online - Siap membantu</p>
            </div>
            <button onclick="toggleChatbot()">×</button>
        </div>

        <div class="chatbot-body" id="chatbotBody">
            <div class="chat-message bot">
                Halo 👋 Ada yang bisa saya bantu?
            </div>
        </div>

        <div class="chatbot-input">
            <input type="text" id="chatbotInput" placeholder="Ketik pesan...">
            <button onclick="sendChatbotMessage()">Kirim</button>
        </div>
    </div>
</div>