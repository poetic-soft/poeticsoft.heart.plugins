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
  ToggleControl,
  __experimentalNumberControl: NumberControl
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
    ignoreRoot,
    onlySubscriptions,
    maxDeep,
    showLegend
  } = attributes  
  const blockProps = useBlockProps()

  useUniqueId(clientId, attributes, setAttributes)
   
  return <>
    <InspectorControls>
      <PanelBody 
        title={ 'Opciones del Bloque' } 
        initialOpen={ true }
        className="TreeNavControls"
      >             
        <ToggleControl        
          label={ `
            Ignorar raíz 
            (${ ignoreRoot ? 'SI' : 'NO' })
          `}
          checked={ ignoreRoot }
          onChange={ 
            value => setAttributes({ 
              ignoreRoot: value
            })
          }
        />     
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
        <NumberControl
            className="MaxDeep"
            label="Máximo nivel (0 para todos)"
            value={ maxDeep }
            min={ 0 }
            onChange={ 
              value => setAttributes({ 
                maxDeep: value
              })
            }
            isDragEnabled={ true }
            isShiftStepEnabled={ true }
            shiftStep={ 5 }
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