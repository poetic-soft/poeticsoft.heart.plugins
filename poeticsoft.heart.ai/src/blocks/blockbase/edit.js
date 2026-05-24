import { v4 as uuidv4 } from 'uuid'
const { __ } = wp.i18n;
const { useBlockProps, InspectorControls, RichText } = wp.blockEditor;
const { PanelBody, ToggleControl } = wp.components;
const { Fragment, useEffect } = wp.element;
const { registerBlockType } = wp.blocks;
import metadata from 'blocks/blockbase/block.json';
import './edit.scss';

export default function Edit( { clientId, attributes, setAttributes } ) {
    const { message, showIcon, blockId, refClientId } = attributes;

    const blockProps = useBlockProps();
  
    useEffect(() => {
        // Si no hay ID o el bloque ha sido duplicado (clientId != refClientId)
        if (!blockId || refClientId !== clientId) {
            setAttributes({ 
                blockId: uuidv4(),
                refClientId: clientId
            });
        }
    }, [clientId]);

    return (
        <Fragment>
            <InspectorControls>
                <PanelBody title={ __( 'Configuración Base', 'poeticsoft-heart' ) }>
                    <ToggleControl
                        label={ __( 'Mostrar Icono', 'poeticsoft-heart' ) }
                        checked={ showIcon }
                        onChange={ ( value ) => setAttributes( { showIcon: value } ) }
                    />
                </PanelBody>
            </InspectorControls>

            <div { ...blockProps }>
                <div className="blockid">
                  { blockId }
                </div>
                <div className="refClientId">
                  { refClientId }
                </div>
                <div className="psh-block-content">
                    { showIcon && <span className="psh-icon">🚀</span> }
                    <RichText
                        tagName="p"
                        value={ message }
                        onChange={ ( value ) => setAttributes( { message: value } ) }
                        placeholder={ __( 'Escribe un mensaje...', 'poeticsoft-heart-theme' ) }
                    />
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
