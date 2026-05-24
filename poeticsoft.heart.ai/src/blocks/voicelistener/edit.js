import { v4 as uuidv4 } from 'uuid'
const { __ } = wp.i18n;
const { useBlockProps, InspectorControls } = wp.blockEditor;
const { PanelBody, TextControl } = wp.components;
const { Fragment, useEffect, useState } = wp.element;
const { registerBlockType } = wp.blocks;
import metadata from 'blocks/voicelistener/block.json';
import './edit.scss';

export default function Edit( { clientId, attributes, setAttributes } ) {
    const { blockId, refClientId, placeholder, buttonLabel } = attributes;
    const blockProps = useBlockProps();
    const [isListening, setIsListening] = useState(false);
    const [statusText, setStatusText] = useState('');
    const [transcript, setTranscript] = useState('');
  
    useEffect(() => {
        // IDs únicos
        if (!blockId || refClientId !== clientId) {
            setAttributes({ 
                blockId: uuidv4(),
                refClientId: clientId
            });
        }
    }, [clientId]);

    const toggleListening = () => {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

        if (!SpeechRecognition) {
            setStatusText(__( 'Speech API no soportada en este navegador.', 'poeticsoft-heart' ));
            return;
        }

        if (isListening) {
            window._pshRecognition?.stop();
            return;
        }

        const recognition = new SpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.lang = 'es-ES';

        recognition.onstart = () => {
            setIsListening(true);
            setStatusText(__( 'Escuchando...', 'poeticsoft-heart' ));
        };

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
            setTranscript(finalTranscript + interimTranscript);
        };

        recognition.onerror = (event) => {
            console.error('Editor Recognition error', event.error);
            setStatusText(__( 'Error: ', 'poeticsoft-heart' ) + event.error);
            setIsListening(false);
        };

        recognition.onend = () => {
            setIsListening(false);
            setStatusText('');
        };

        window._pshRecognition = recognition;
        recognition.start();
    };

    return (
        <Fragment>
            <InspectorControls>
                <PanelBody title={ __( 'Configuración de Voz', 'poeticsoft-heart' ) }>
                    <TextControl
                        label={ __( 'Texto del Botón', 'poeticsoft-heart' ) }
                        value={ buttonLabel }
                        onChange={ ( value ) => setAttributes( { buttonLabel: value } ) }
                    />
                    <TextControl
                        label={ __( 'Placeholder del Texto', 'poeticsoft-heart' ) }
                        value={ placeholder }
                        onChange={ ( value ) => setAttributes( { placeholder: value } ) }
                    />
                </PanelBody>
            </InspectorControls>

            <div { ...blockProps }>
                <div className="psh-voice-preview">
                    <div className="psh-voice-header">
                        <span className="psh-icon">🎙️</span>
                        <strong>{ __( 'Voice Listener (Editor Preview)', 'poeticsoft-heart' ) }</strong>
                    </div>
                    <div className="psh-voice-mockup">
                        <button 
                            className={`psh-voice-btn-preview ${isListening ? 'is-listening' : ''}`}
                            onClick={toggleListening}
                        >
                            { isListening ? '⏹️ Detener' : buttonLabel }
                        </button>
                        <div className="psh-voice-output-mock">
                            { transcript || placeholder }
                        </div>
                        { statusText && <div className="psh-voice-status-preview">{ statusText }</div> }
                    </div>
                    <div className="psh-block-id-badge">ID: { blockId }</div>
                </div>
            </div>
        </Fragment>
    );
}

const Save = () => null

registerBlockType(
  metadata.name,
  {
    edit: Edit,
    save: Save
  }
)
