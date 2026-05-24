import { v4 as uuidv4 } from 'uuid'
const { __ } = wp.i18n;
const { useBlockProps, InspectorControls, RichText } = wp.blockEditor;
const { PanelBody, TextControl, RangeControl, SelectControl, Button } = wp.components;
const { Fragment, useEffect, useState } = wp.element;
const { registerBlockType } = wp.blocks;
import metadata from 'blocks/voicespeaker/block.json';
import './edit.scss';

export default function Edit( { clientId, attributes, setAttributes } ) {
    const { blockId, refClientId, placeholder, buttonLabel, content, pitch, rate, voiceURI } = attributes;
    const blockProps = useBlockProps();
    const [voices, setVoices] = useState([]);
    const [isPlaying, setIsPlaying] = useState(false);
  
    useEffect(() => {
        // IDs únicos
        if (!blockId || refClientId !== clientId) {
            setAttributes({ 
                blockId: uuidv4(),
                refClientId: clientId
            });
        }

        // Cargar voces disponibles del sistema
        const loadVoices = () => {
            setVoices(window.speechSynthesis.getVoices());
        };
        loadVoices(); // Intento inmediato
        
        // Algunos navegadores cargan las voces de forma asíncrona
        if (window.speechSynthesis.onvoiceschanged !== undefined) {
            window.speechSynthesis.onvoiceschanged = loadVoices;
        }
    }, [clientId]);

    const testVoice = () => {
        if (window.speechSynthesis.speaking) {
            window.speechSynthesis.cancel();
            setIsPlaying(false);
            return;
        }

        const textToRead = content || 'Este es un mensaje de prueba para comprobar la configuración de voz.';
        const utterance = new SpeechSynthesisUtterance(textToRead);
        
        utterance.pitch = pitch !== undefined ? pitch : 1;
        utterance.rate = rate !== undefined ? rate : 1;
        
        if (voiceURI) {
            const selectedVoice = voices.find(v => v.voiceURI === voiceURI);
            if (selectedVoice) {
                utterance.voice = selectedVoice;
            }
        } else {
            utterance.lang = 'es-ES'; // Default fallback
        }

        utterance.onend = () => setIsPlaying(false);
        utterance.onerror = () => setIsPlaying(false);

        setIsPlaying(true);
        window.speechSynthesis.speak(utterance);
    };

    // Preparar opciones para el SelectControl
    const voiceOptions = [
        { label: __( 'Voz por defecto del sistema', 'poeticsoft-heart' ), value: '' },
        ...voices.map(v => ({
            label: `${v.name} (${v.lang})`,
            value: v.voiceURI
        }))
    ];

    return (
        <Fragment>
            <InspectorControls>
                <PanelBody title={ __( 'Configuración de Altavoz', 'poeticsoft-heart' ) }>
                    <TextControl
                        label={ __( 'Texto del Botón', 'poeticsoft-heart' ) }
                        value={ buttonLabel }
                        onChange={ ( value ) => setAttributes( { buttonLabel: value } ) }
                    />
                    <SelectControl
                        label={ __( 'Voz', 'poeticsoft-heart' ) }
                        value={ voiceURI }
                        options={ voiceOptions }
                        onChange={ ( value ) => setAttributes( { voiceURI: value } ) }
                        help={ __( 'La disponibilidad de voces depende del navegador y sistema operativo del usuario final.', 'poeticsoft-heart' ) }
                    />
                    <RangeControl
                        label={ __( 'Tono (Pitch)', 'poeticsoft-heart' ) }
                        value={ pitch !== undefined ? pitch : 1 }
                        onChange={ ( value ) => setAttributes( { pitch: value } ) }
                        min={ 0 }
                        max={ 2 }
                        step={ 0.1 }
                    />
                    <RangeControl
                        label={ __( 'Velocidad (Rate)', 'poeticsoft-heart' ) }
                        value={ rate !== undefined ? rate : 1 }
                        onChange={ ( value ) => setAttributes( { rate: value } ) }
                        min={ 0.5 }
                        max={ 2 }
                        step={ 0.1 }
                    />
                    <div style={{ marginTop: '20px' }}>
                        <Button 
                            variant="primary" 
                            onClick={testVoice}
                            style={{ width: '100%', justifyContent: 'center' }}
                        >
                            { isPlaying ? '⏹️ Detener Prueba' : '▶️ Probar Configuración de Voz' }
                        </Button>
                    </div>
                </PanelBody>
            </InspectorControls>

            <div { ...blockProps }>
                <div className="psh-speaker-preview">
                    <div className="psh-speaker-header">
                        <span className="psh-icon">🔊</span>
                        <strong>{ __( 'Voice Speaker (Text to Speech)', 'poeticsoft-heart' ) }</strong>
                    </div>
                    <div className="psh-speaker-mockup">
                        <button disabled className="psh-speaker-btn-mock">{ buttonLabel }</button>
                        <RichText
                            tagName="div"
                            className="psh-speaker-content-edit"
                            value={ content }
                            onChange={ ( value ) => setAttributes( { content: value } ) }
                            placeholder={ __( 'Escribe el texto que será leído en voz alta...', 'poeticsoft-heart' ) }
                        />
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
