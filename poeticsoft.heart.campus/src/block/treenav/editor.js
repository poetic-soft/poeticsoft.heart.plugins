import './editor.scss'

const { 
  registerBlockType 
} = wp.blocks
const { 
  useBlockProps,
  InspectorControls 
} = wp.blockEditor
const {
  PanelBody,
  ToggleControl 
} = wp.components

import metadata from 'blocks/treenav/block.json'
import { useUniqueId } from 'blockscommon/uniqueid'
import './editor.scss'

const Edit = props => {
  
  const {
    clientId,
    attributes, 
    setAttributes 
  } = props  
  const { 
    blockId,
    refClientId,
    onlySubscriptions,
    showLegend
  } = attributes  
  const blockProps = useBlockProps()

  useUniqueId(clientId, attributes, setAttributes)
   
  return <>
    <InspectorControls>
      <PanelBody 
        title={ 'Opciones del Bloque' } 
        initialOpen={ true }
      >        
        <ToggleControl        
          label={ `
            Ver sólo suscripciones 
            (${ onlySubscriptions ? 'SI' : 'NO' })
          `}
          checked={ onlySubscriptions }
          onChange={ 
            value => setAttributes({ 
              onlySubscriptions: value
            })
          }
        />        
        <ToggleControl        
          label={ `
            Ver leyenda 
            (${ showLegend ? 'SI' : 'NO' })
          `}
          checked={ showLegend }
          onChange={ 
            value => setAttributes({ 
              showLegend: value
            })
          }
        />
      </PanelBody>
    </InspectorControls>
    <div { ...blockProps }>
      Navegación del 
      { onlySubscriptions ? ' (Sólo suscripciones & Libre)' : '' }
      { showLegend ? ' (Con leyenda)' : '' }
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