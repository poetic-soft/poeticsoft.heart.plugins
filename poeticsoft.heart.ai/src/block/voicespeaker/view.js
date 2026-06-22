import './view.scss';

document.addEventListener('DOMContentLoaded', () => {
    const containers = document.querySelectorAll('.wp-block-poeticsoft-heart-ai-voicespeaker');
    
    containers.forEach(container => {
        const button = container.querySelector('.pshai-speaker-btn');
        const contentDiv = container.querySelector('.pshai-speaker-content');
        
        let isSpeaking = false;
        let isStreaming = false;
        let speechQueue = [];
        
        if (!window.speechSynthesis) {
            if (button) button.disabled = true;
            return;
        }

        /**
         * Lógica de Síntesis de Voz
         */
        const speakNextInQueue = () => {
            if (isSpeaking || speechQueue.length === 0) return;

            const text = speechQueue.shift();
            const utterance = new SpeechSynthesisUtterance(text);
            
            // Configuración desde data-attributes
            const pitch = parseFloat(container.dataset.pitch);
            const rate = parseFloat(container.dataset.rate);
            const voiceURI = container.dataset.voiceuri;

            if (!isNaN(pitch)) utterance.pitch = pitch;
            if (!isNaN(rate)) utterance.rate = rate;

            if (voiceURI) {
                const voices = window.speechSynthesis.getVoices();
                const selectedVoice = voices.find(v => v.voiceURI === voiceURI);
                if (selectedVoice) utterance.voice = selectedVoice;
            } else {
                utterance.lang = 'es-ES';
            }

            utterance.onstart = () => {
                isSpeaking = true;
                button.classList.add('is-speaking');
            };

            utterance.onend = () => {
                isSpeaking = false;
                if (speechQueue.length > 0) {
                    speakNextInQueue();
                } else if (!isStreaming) {
                    button.classList.remove('is-speaking');
                }
            };

            window.speechSynthesis.speak(utterance);
        };

        /**
         * Consumo de Streaming (SSE)
         */
        const startStreaming = async (inputText) => {
            if (isStreaming) return;
            
            isStreaming = true;
            button.classList.add('is-speaking');
            contentDiv.innerText = ''; // Limpiar para la nueva respuesta
            
            let buffer = '';
            
            try {
                const response = await fetch('/wp-json/psh/v1/voice/stream', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ text: inputText })
                });

                const reader = response.body.getReader();
                const decoder = new TextDecoder();

                while (true) {
                    const { done, value } = await reader.read();
                    if (done) break;

                    const chunk = decoder.decode(value, { stream: true });
                    const lines = chunk.split('\n');

                    for (const line of lines) {
                        if (line.startsWith('data: ')) {
                            const data = line.slice(6);
                            if (data === '[DONE]') break;

                            try {
                                const parsed = JSON.parse(data);
                                const newText = parsed.text;
                                buffer += newText;
                                contentDiv.innerText += newText;

                                // Si encontramos un signo de puntuación, enviamos a la cola de voz
                                if (/[.!?\n]/.test(newText)) {
                                    speechQueue.push(buffer.trim());
                                    buffer = '';
                                    speakNextInQueue();
                                }
                            } catch (e) {
                                // Ignorar errores de parseo parcial
                            }
                        }
                    }
                }

                // Al terminar el stream, si queda algo en el buffer, lo añadimos
                if (buffer.trim()) {
                    speechQueue.push(buffer.trim());
                    speakNextInQueue();
                }

            } catch (error) {
                console.error('Streaming error:', error);
            } finally {
                isStreaming = false;
                if (!isSpeaking && speechQueue.length === 0) {
                    button.classList.remove('is-speaking');
                }
            }
        };

        /**
         * Escuchar eventos del VoiceListener
         */
        document.addEventListener('psh-voice-input', (e) => {
            const { text } = e.detail;
            startStreaming(text);
        });

        /**
         * Botón de reproducción manual (mantiene compatibilidad)
         */
        button.addEventListener('click', () => {
            if (isSpeaking || isStreaming) {
                window.speechSynthesis.cancel();
                isSpeaking = false;
                isStreaming = false;
                speechQueue = [];
                button.classList.remove('is-speaking');
                return;
            }

            const text = contentDiv.innerText || contentDiv.textContent;
            if (text.trim()) {
                speechQueue.push(text.trim());
                speakNextInQueue();
            }
        });
    });
});
