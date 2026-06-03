function toggleChatbot() {
    const box = document.getElementById("chatbotBox");
    box.classList.toggle("active");
}

function sendChatbotMessage() {
    const input = document.getElementById("chatbotInput");
    const body = document.getElementById("chatbotBody");
    const message = input.value.trim();

    if (message === "") return;

    body.innerHTML += `
        <div class="chat-message user">${message}</div>
    `;

    input.value = "";
    body.scrollTop = body.scrollHeight;

    setTimeout(() => {
        body.innerHTML += `
            <div class="chat-message bot">
                Pesan kamu sudah diterima. Fitur balasan chatbot akan dikembangkan oleh backend.
            </div>
        `;
        body.scrollTop = body.scrollHeight;
    }, 600);
}

document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("chatbotInput");

    if (input) {
        input.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                sendChatbotMessage();
            }
        });
    }
});
