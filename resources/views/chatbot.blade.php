<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Chatbot Manajemen Kos</title>
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
</head>
<body>

<div class="chatbot-container">
    <div class="chatbot-header">
        <div>
            <h3>Chatbot Kos</h3>
            <p>Online - Siap membantu</p>
        </div>
    </div>

    <div class="chatbot-body" id="chatBody">
        <div class="message bot-message">
            Halo 👋 Saya chatbot Manajemen Kos. Ada yang bisa saya bantu?
        </div>
    </div>

    <div class="chatbot-footer">
        <input type="text" id="chatInput" placeholder="Ketik pesan...">
        <button onclick="sendMessage()">Kirim</button>
    </div>
</div>

<script src="{{ asset('js/chatbot.js') }}"></script>
</body>
</html>