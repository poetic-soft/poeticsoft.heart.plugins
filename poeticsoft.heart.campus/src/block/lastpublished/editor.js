const { 
  registerBlockType 
} = wp.blocks
const { 
  useBlockProps,
  InspectorControls,
  RichText 
} = wp.blockEditor
const {
  PanelBody,
  SelectControl,
  __experimentalNumberControl: NumberControl
} = wp.components
const {
  useEffect,
  useState
} = wp.element
const {
  apiFetch
} = wp
import {
  HeadingSelector
 } from 'blockscommon/elementselector'
import { useUniqueId } from 'blockscommon/uniqueid'

import metadata from 'blocks/lastpublished/block.json'
import './editor.scss';

const modeOptions = [
  {
    label: 'Título, Imagen & Extracto',
    value: 'complete'
  },
  {
    label: 'Sólo título',
    value: 'compact'
  }
] 

const visibilityOptions = [
  {
    label: 'Visible siempre',
    value: 'visiblealways'
  },
  {
    label: 'Sólo en contenedores',
    value: 'onlyincontainers'
  }
]

const hs = {
  h1: title => <h1 className="Title">{ title }</h1>,
  h2: title => <h2 className="Title">{ title }</h2>,
  h3: title => <h3 className="Title">{ title }</h3>,
  h4: title => <h4 className="Title">{ title }</h4>,
  h5: title => <h5 className="Title">{ title }</h5>,
  h6: title => <h6 className="Title">{ title }</h6>
}

const Edit = props => {
  
  const {
    clientId,
    attributes, 
    setAttributes 
  } = props  
  const { 
    blockId,
    refClientId,
    title,
    maxCount,
    sectionHeadingType,
    areaHeadingType,
    mode,
    visibility
  } = attributes;

  const blockProps = useBlockProps()

  useUniqueId(clientId, attributes, setAttributes)
   
  return <>
    <InspectorControls>
      <PanelBody
        title="Opciones del Bloque"
        initialOpen={ true }
      > 
        <div className="
          containerchildren
          SeccionTitle
        ">
          <div className="EditTitle">
            Titulo de sección            
          </div>
          <div className="EditText">
            <RichText
              tagName="div"
              value={ title }
              allowedFormats={[ 
                'core/bold', 
                'core/italic' 
              ]} 
              onChange={
                value => setAttributes({
                  title: value
                })
              }
              placeholder="Título"
            />
          </div>
        </div>   
        <NumberControl
          className="MaxCount"
          label="Cuántas páginas se muestran"
          value={ maxCount }
          min={ 0 }
          onChange={ 
            value => setAttributes({ 
              maxCount: value
            })
          }
          isDragEnabled={ true }
          isShiftStepEnabled={ true }
          shiftStep={ 5 }
        />    
        <HeadingSelector
          title="Elemento de título de sección"
          value={ sectionHeadingType }
          onChange={
            value => setAttributes({
              sectionHeadingType: value
            })
          }
        />
        <HeadingSelector
          title="Elemento de título de área"
          value={ areaHeadingType }
          onChange={
            value => setAttributes({
              areaHeadingType: value
            })
          }
        />
        <SelectControl
          label="Modo"
          value={ mode }
          options={ modeOptions }
          onChange={ 
            value => setAttributes({ 
              mode: value
            })
          }
        />
        <SelectControl
          label="Visibilidad"
          value={ visibility }
          options={ visibilityOptions }
          onChange={ 
            value => setAttributes({ 
              visibility: value 
            }) 
          }
        />
      </PanelBody>
    </InspectorControls>
    <div { ...blockProps}>
      {
        hs[sectionHeadingType](title)
      }
      <div className="Content">
        {
          hs[areaHeadingType]('Contenidos')
        } 
        { 
          modeOptions.find(o => o.value == mode).label
        }
      </div>
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