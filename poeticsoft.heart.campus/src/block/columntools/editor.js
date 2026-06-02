const { 
  registerBlockType 
} = wp.blocks
const { 
  useBlockProps,
  InspectorControls 
} = wp.blockEditor
const {
  PanelBody,
  ToggleControl,
  Dashicon
} = wp.components

import { useUniqueId } from 'blockscommon/uniqueid'
import metadata from 'blocks/columntools/block.json'
import './editor.scss';

const Edit = props => {
  
  const {
    clientId,
    attributes, 
    setAttributes 
  } = props  
  const { 
    blockId,
    refClientId,
    defaultOpen
  } = attributes;
  const blockProps = useBlockProps()

  useUniqueId(clientId, attributes, setAttributes)
   
  return <>
    <InspectorControls>
      <PanelBody 
        className="Tools"
        title={ 'Opciones del Bloque' } 
        initialOpen={ true }
      >
        <ToggleControl        
          label={ `Abierto ${ defaultOpen ? 'SI' : 'NO' }` }
          checked={ defaultOpen }
          onChange={ 
            value => setAttributes({ 
              defaultOpen: value
            })
          }
        />
      </PanelBody>
    </InspectorControls>
    <div 
      { ...blockProps }
      onClick={ 
        () => setAttributes({ 
          defaultOpen: !defaultOpen
        })
      }
    >
      <Dashicon 
        icon={ 
          defaultOpen ? 
          'arrow-left-alt2'
          :  
          'arrow-right-alt2' 
        } 
      />
    </div>
  </>
}

const Save = () => null

registerBlockType(
  metadata.name,
  {
    edit: Edit,
    save: Save
  }
)