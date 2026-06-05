const { 
  createHigherOrderComponent 
} = wp.compose;
const { 
  InspectorControls,
  RichText  
} = wp.blockEditor;
const { 
  PanelBody,  
  SelectControl
} = wp.components;
const { addFilter } = wp.hooks; 

import './main.scss'

const postContentVisibleOptions = [
  {
    label: 'Oculto siempre',
    value: 'hiddenalways'
  },
  {
    label: 'Visible siempre',
    value: 'visiblealways'
  },
  {
    label: 'Sólo en páginas de recursos',
    value: 'onlyincontents'
  }
] 

const withInspectorControls = createHigherOrderComponent(  
  BlockEdit => {

    return ( props ) => {
      
      if (props.name === 'core/post-content') {

        const { 
          attributes, 
          setAttributes 
        } = props;
        const {
          showRestrictedText,
          restrictedVisibleText,
          payVisibleText
        } = attributes

        return <>
          <BlockEdit { ...props } />
          <InspectorControls>
            <PanelBody 
              title="Advertencia contenido restringido" 
              initialOpen={ true }
              className="PostContentConfig"
            >        
              <SelectControl
                label="Donde ver advertencia"
                value={ showRestrictedText }
                options={ postContentVisibleOptions }
                onChange={ 
                  value => setAttributes({ 
                    showRestrictedText: value 
                  }) 
                }
              />
              <div className="Texts RestrictedText">
                <div className="EditTitle">
                  Texto contenido restringido            
                </div>
                <div className="EditText">
                  <RichText
                    __unstableOnFocus
                    tagName="div"
                    value={ restrictedVisibleText }
                    allowedFormats={[ 
                      'core/bold', 
                      'core/italic' 
                    ]} 
                    onChange={
                      value => setAttributes({
                        restrictedVisibleText: value
                      })
                    }
                    placeholder="Texto contenido restringido"
                  />
                </div>
              </div>              
            </PanelBody>
          </InspectorControls>
        </>
      }
      
      return <BlockEdit { ...props } />;
    };
  }, 
  'withInspectorControls' 
);

addFilter(
  'editor.BlockEdit',
  'poeticsoft/coreconfigs',
  withInspectorControls
);