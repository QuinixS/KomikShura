// Fungsi kirim pesan ke chatbot Flask
function sendMessage() {
    const input = document.getElementById('user-input');
    const message = input.value.trim();
    if (!message) return;

    appendMessage("Kamu", message);  // Tampilkan pesan user

    // Kirim ke backend Flask
    fetch('http://127.0.0.1:5002/chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        appendMessage("AshuraBot", data.reply);  // Tampilkan balasan
    })
    .catch(error => {
        console.error("Chatbot error:", error);
        appendMessage("AshuraBot", "⚠️ Bot sedang tidak bisa dihubungi.");
    });

    input.value = '';  // Kosongkan input setelah kirim
}

// Fungsi untuk menambahkan pesan ke tampilan chat
function appendMessage(sender, message) {
    const chatMessages = document.getElementById('chat-messages');
    const formattedMessage = sender === "AshuraBot" ? message : message.replace(/</g, "&lt;").replace(/>/g, "&gt;");

    chatMessages.innerHTML += `<div><strong>${sender}:</strong> ${formattedMessage}</div>`;
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Fungsi untuk membuka/menutup chatbox
function toggleChat() {
    const chatBox = document.getElementById('chat-box');
    chatBox.style.display = chatBox.style.display === 'block' ? 'none' : 'block';
}
