<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InnoPark AI Assistant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
        }
        .glass {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .chat-container {
            height: calc(100vh - 180px);
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .typing-cursor {
            display: inline-block;
            width: 6px;
            height: 1.2em;
            background-color: #10b981;
            vertical-align: text-bottom;
            animation: blink 1s step-end infinite;
            margin-left: 2px;
        }
        @keyframes blink {
            50% { opacity: 0; }
        }
        
        /* Markdown / GPT Tarzı Tipografi Düzeltmeleri */
        .ai-message p, .user-message p { 
            margin-bottom: 1.25rem; 
            line-height: 1.75; 
            letter-spacing: 0.01em;
        }
        .ai-message p:last-child, .user-message p:last-child { 
            margin-bottom: 0; 
        }
        .ai-message h1, .ai-message h2, .ai-message h3 { 
            margin-top: 1.5rem; 
            margin-bottom: 1rem; 
            font-weight: 600; 
            color: #10b981; /* GPT tarzı başlık vurgusu */
        }
        .ai-message h1 { font-size: 1.5rem; }
        .ai-message h2 { font-size: 1.25rem; }
        .ai-message h3 { font-size: 1.1rem; }
        .ai-message ul { 
            list-style-type: disc; 
            margin-left: 1.5rem; 
            margin-bottom: 1.25rem; 
        }
        .ai-message ol { 
            list-style-type: decimal; 
            margin-left: 1.5rem; 
            margin-bottom: 1.25rem; 
        }
        .ai-message li { 
            margin-bottom: 0.5rem; 
            line-height: 1.6;
            padding-left: 0.25rem;
        }
        .ai-message strong { 
            color: #f8fafc; 
            font-weight: 600; 
        }

        .gradient-text {
            background: linear-gradient(90deg, #10b981, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .ai-message {
            background: rgba(51, 65, 85, 0.5);
            border-radius: 1.5rem 1.5rem 1.5rem 0;
        }
        .user-message {
            background: linear-gradient(135deg, #059669, #047857);
            border-radius: 1.5rem 1.5rem 0 1.5rem;
        }
        pre {
            background: #1e293b;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 0.5rem 0;
        }
    </style>
</head>
<body class="overflow-hidden">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 glass hidden md:flex flex-col p-4 border-r border-slate-800">
            <div class="flex items-center gap-3 mb-8 px-2">
                <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                </div>
                <h1 class="text-xl font-bold tracking-tight">InnoPark <span class="text-emerald-400">AI</span></h1>
            </div>
            
            <button onclick="window.location.reload()" class="w-full py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all border border-slate-700 flex items-center gap-3 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Yeni Sohbet
            </button>

            <div class="flex-1 overflow-y-auto space-y-2 scrollbar-hide">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 px-2">Hızlı Sorgular</div>
                <button onclick="askQuestion('InnoPark ekibinde kimler var?')" class="w-full text-left p-2 rounded-lg hover:bg-slate-800 transition-colors text-sm text-slate-300">👥 Ekip Üyeleri</button>
                <button onclick="askQuestion('InnoPark\'taki firmaların listesini ver.')" class="w-full text-left p-2 rounded-lg hover:bg-slate-800 transition-colors text-sm text-slate-300">🏢 Firmalar</button>
                <button onclick="askQuestion('Girişimciler için ne gibi destekler var?')" class="w-full text-left p-2 rounded-lg hover:bg-slate-800 transition-colors text-sm text-slate-300">🚀 Girişimci Desteği</button>
                <button onclick="askQuestion('Çevre ve Kalite politikaları nedir?')" class="w-full text-left p-2 rounded-lg hover:bg-slate-800 transition-colors text-sm text-slate-300">📜 Standartlar</button>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="flex-1 flex flex-col relative">
            <!-- Header -->
            <div class="h-16 border-b border-slate-800 flex items-center justify-between px-6 glass sticky top-0 z-10">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-medium text-slate-400">Çevrimiçi | NotebookLM Bağlantısı Aktif</span>
                </div>
            </div>

            <!-- Messages -->
            <div id="chatWindow" class="flex-1 overflow-y-auto p-6 space-y-6 scrollbar-hide chat-container">
                <!-- Welcome Message -->
                <div class="flex flex-col items-center justify-center h-full text-center animate__animated animate__fadeIn">
                    <div class="w-20 h-20 bg-emerald-500/10 rounded-3xl flex items-center justify-center mb-6 border border-emerald-500/20">
                         <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                    </div>
                    <h2 class="text-3xl font-bold mb-2 gradient-text">Merhaba! Size nasıl yardımcı olabilirim?</h2>
                    <p class="text-slate-400 max-w-md">InnoPark kaynakları üzerinden tüm sorularınızı yanıtlamaya hazırım.</p>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-6 glass border-t border-slate-800">
                <div class="max-w-4xl mx-auto relative">
                    <input type="text" id="userInput" placeholder="InnoPark hakkında bir şey sorun..." 
                        class="w-full bg-slate-800/50 border border-slate-700 rounded-2xl py-4 pl-6 pr-16 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all text-slate-100 placeholder-slate-500 shadow-2xl">
                    <button id="sendBtn" onclick="handleSend()" 
                        class="absolute right-2 top-2 bottom-2 w-12 bg-emerald-500 hover:bg-emerald-600 rounded-xl flex items-center justify-center transition-all shadow-lg shadow-emerald-500/20 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                    </button>
                </div>
                <div class="text-center mt-3 text-[10px] text-slate-600 uppercase tracking-widest font-bold">
                    Powered by InnoPark AI & NotebookLM
                </div>
            </div>
        </div>
    </div>

    <script>
        const chatWindow = document.getElementById('chatWindow');
        const userInput = document.getElementById('userInput');
        const sendBtn = document.getElementById('sendBtn');

        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') handleSend();
        });

        async function handleSend() {
            const query = userInput.value.trim();
            if (!query) return;

            // Clear input and welcome screen
            userInput.value = '';
            if (chatWindow.querySelector('.text-center')) {
                chatWindow.innerHTML = '';
            }

            // Append User Message
            appendMessage('user', query);

            // Show AI Loading State (Düşünüyor efekti)
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'flex justify-start animate__animated animate__fadeIn';
            loadingDiv.innerHTML = `
                <div class="ai-message px-5 py-3 max-w-[80%] shadow-lg glass border border-emerald-500/10">
                    <div class="flex gap-2 items-center h-4">
                        <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce"></div>
                        <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce [animation-delay:-0.2s]"></div>
                        <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce [animation-delay:-0.4s]"></div>
                    </div>
                </div>
            `;
            chatWindow.appendChild(loadingDiv);
            scrollToBottom();

            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ query: query })
                });
                
                const data = await response.json();
                chatWindow.removeChild(loadingDiv);

                if (data.error) {
                    appendMessage('error', `❌ Bir hata oluştu: ${data.error}`);
                } else {
                    appendMessage('ai', data.response);
                }
            } catch (error) {
                chatWindow.removeChild(loadingDiv);
                appendMessage('error', `❌ Sunucuya ulaşılamadı. Lütfen PHP servisinin çalıştığından emin olun.`);
            }
        }

        function appendMessage(role, text) {
            const msgDiv = document.createElement('div');
            msgDiv.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'} animate__animated animate__fadeInUp mb-4`;
            
            const innerDiv = document.createElement('div');
            
            if (role === 'ai') {
                innerDiv.className = 'ai-message p-4 max-w-[85%] shadow-xl border border-white/5 text-slate-200';
                
                // Div'i DOM'a ekleyip scroll yapıyoruz
                msgDiv.appendChild(innerDiv);
                chatWindow.appendChild(msgDiv);
                scrollToBottom();

                // ChatGPT tarzı "Akan Yazı" (Streaming Typewriter) efekti
                let i = 0;
                let currentText = "";
                // Daha hızlı bir akış için tek seferde 3-5 karakter ekliyoruz
                const charsPerTick = 4; 
                
                const typingInterval = setInterval(() => {
                    currentText += text.substring(i, i + charsPerTick);
                    innerDiv.innerHTML = marked.parse(currentText) + '<span class="typing-cursor"></span>';
                    scrollToBottom();
                    i += charsPerTick;
                    
                    if (i >= text.length) {
                        clearInterval(typingInterval);
                        innerDiv.innerHTML = marked.parse(text); // İmleci kaldır ve son temiz hali bas
                        scrollToBottom();
                    }
                }, 15); // 15ms hız
                
                return; // Normal akışta aşağıda tekrar append edilmemesi için
                
            } else if (role === 'error') {
                innerDiv.className = 'bg-red-500/20 text-red-200 border border-red-500/30 p-4 rounded-xl max-w-[85%] shadow-xl';
                innerDiv.textContent = text;
            } else {
                innerDiv.className = 'user-message text-white p-4 max-w-[85%] shadow-xl border border-white/5';
                innerDiv.textContent = text;
            }

            msgDiv.appendChild(innerDiv);
            chatWindow.appendChild(msgDiv);
            scrollToBottom();
        }

        function scrollToBottom() {
            chatWindow.scrollTop = chatWindow.scrollHeight;
        }

        function askQuestion(q) {
            userInput.value = q;
            handleSend();
        }
    </script>
</body>
</html>
