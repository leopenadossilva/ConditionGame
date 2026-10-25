const API_BASE = 'http://localhost:8000/api';

const chatMessages = document.getElementById('chatMessages');
const chatInput = document.getElementById('chatInput');
const chatSend = document.getElementById('chatSend');
const btnEnemiesGet = document.getElementById('btnEnemiesGet');

function addMessage(text, type) {
    const msg = document.createElement('div');
    msg.className = `chat-message ${type}`;
    msg.textContent = text;
    chatMessages.appendChild(msg);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

chatSend.addEventListener('click', async () => {
    const message = chatInput.value.trim();
    if (!message) return;
    
    addMessage(message, 'user');
    chatInput.value = '';
    
    try {
        const res = await fetch(`${API_BASE}/agent/chat`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message })
        });
        const data = await res.json();
        addMessage(data.response || '...', 'helper');
    } catch (e) {
        addMessage('Error connecting to helper.', 'helper');
    }
});

chatInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') chatSend.click();
});

btnEnemiesGet.addEventListener('click', async () => {
    try {
        const res = await fetch(`${API_BASE}/game/enemies`);
        const data = await res.json();
        addMessage(`Tool enemies_get: ${JSON.stringify(data)}`, 'helper');
    } catch (e) {
        addMessage('Error calling enemies_get.', 'helper');
    }
});

addMessage('Helper online. Ready to assist.', 'helper');
