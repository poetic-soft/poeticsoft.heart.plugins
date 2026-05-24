import './view.scss';

document.addEventListener('DOMContentLoaded', () => {
    const containers = document.querySelectorAll('.psh-voice-listener');
    
    containers.forEach(container => {
        const button = container.querySelector('.psh-voice-btn');
        const output = container.querySelector('.psh-voice-output');
        const status = container.querySelector('.psh-voice-status');
        
        let isListening = false;
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        
        if (!SpeechRecognition) {
            if (status) status.textContent = 'Speech API no soportada.';
            if (button) button.disabled = true;
            return;
        }

        const recognition = new SpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.lang = 'es-ES'; 

        recognition.onresult = (event) => {
            let interimTranscript = '';
            let finalTranscript = '';

            for (let i = event.resultIndex; i < event.results.length; ++i) {
                if (event.results[i].isFinal) {
                    finalTranscript += event.results[i][0].transcript;
                } else {
                    interimTranscript += event.results[i][0].transcript;
                }
            }
            if (output) {
                output.value = finalTranscript + interimTranscript;
            }
        };

        recognition.onstart = () => {
            isListening = true;
            button.classList.add('is-listening');
            if (status) status.textContent = 'Escuchando...';
        };

        recognition.onend = () => {
            isListening = false;
            button.classList.remove('is-listening');
            if (status) status.textContent = '';

            // Al finalizar la escucha, emitimos el texto completo
            const finalText = output.value.trim();
            if (finalText) {
                document.dispatchEvent(new CustomEvent('psh-voice-input', {
                    detail: { 
                        text: finalText,
                        source: container.id 
                    }
                }));
            }
        };

        recognition.onerror = (event) => {
            console.error('Speech recognition error', event.error);
            if (status) status.textContent = 'Error: ' + event.error;
        };

        button.addEventListener('click', () => {
            if (isListening) {
                recognition.stop();
            } else {
                recognition.start();
            }
        });
    });
});
