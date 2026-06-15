const { registerBlockType } = wp.blocks;
const { useBlockProps, InspectorControls, RichText } = wp.blockEditor;
const { PanelBody, SelectControl } = wp.components;
const { __ } = wp.i18n;

import { HeadingSelector } from 'blockscommon/elementselector';
import { useUniqueId } from 'blockscommon/uniqueid';

import metadata from 'blocks/containerchildren/block.json';
import './editor.scss';

const contentsOptions = [
    {
        label: __('Todo visible para todos', 'poeticsoft-heart-campus'),
        value: 'all'
    },
    {
        label: __('Todo visible para identificados', 'poeticsoft-heart-campus'),
        value: 'allidentified'
    },
    {
        label: __('Suscripciones & Abierto', 'poeticsoft-heart-campus'),
        value: 'subscriptionsandopen'
    }
];

const modeOptions = [
    {
        label: __('Título, Imagen & Extracto', 'poeticsoft-heart-campus'),
        value: 'complete'
    },
    {
        label: __('Título y contenidos', 'poeticsoft-heart-campus'),
        value: 'contents'
    },
    {
        label: __('Sólo título', 'poeticsoft-heart-campus'),
        value: 'compact'
    }
];

const hs = {
    h1: (title) => <h1 className="Title">{title}</h1>,
    h2: (title) => <h2 className="Title">{title}</h2>,
    h3: (title) => <h3 className="Title">{title}</h3>,
    h4: (title) => <h4 className="Title">{title}</h4>,
    h5: (title) => <h5 className="Title">{title}</h5>,
    h6: (title) => <h6 className="Title">{title}</h6>
};

const Edit = (props) => {
    const { clientId, attributes, setAttributes } = props;
    const { title, sectionHeadingType, areaHeadingType, contents, mode } = attributes;
    const blockProps = useBlockProps();

    useUniqueId(clientId, attributes, setAttributes);

    return (
        <>
            <InspectorControls>
                <PanelBody
                    title={__('Opciones del Bloque', 'poeticsoft-heart-campus')}
                    initialOpen={true}
                >
                    <div
                        className="
          containerchildren
          SeccionTitle
        "
                    >
                        <div className="EditTitle">
                            {__('Título de sección', 'poeticsoft-heart-campus')}
                        </div>
                        <div className="EditText">
                            <RichText
                                tagName="div"
                                value={title}
                                allowedFormats={['core/bold', 'core/italic']}
                                onChange={(value) =>
                                    setAttributes({
                                        title: value
                                    })
                                }
                                placeholder={__('Título', 'poeticsoft-heart-campus')}
                            />
                        </div>
                    </div>
                    <HeadingSelector
                        title={__('Elemento de título de sección', 'poeticsoft-heart-campus')}
                        value={sectionHeadingType}
                        onChange={(value) =>
                            setAttributes({
                                sectionHeadingType: value
                            })
                        }
                    />
                    <HeadingSelector
                        title={__('Elemento de título de área', 'poeticsoft-heart-campus')}
                        value={areaHeadingType}
                        onChange={(value) =>
                            setAttributes({
                                areaHeadingType: value
                            })
                        }
                    />
                    <SelectControl
                        label={__('Visualizar', 'poeticsoft-heart-campus')}
                        value={contents}
                        options={contentsOptions}
                        onChange={(value) =>
                            setAttributes({
                                contents: value
                            })
                        }
                    />
                    <SelectControl
                        label={__('Modo', 'poeticsoft-heart-campus')}
                        value={mode}
                        options={modeOptions}
                        onChange={(value) =>
                            setAttributes({
                                mode: value
                            })
                        }
                    />
                </PanelBody>
            </InspectorControls>
            <div {...blockProps}>
                {hs[sectionHeadingType](title)}
                <div className="Content">
                    {hs[areaHeadingType](__('Contenidos', 'poeticsoft-heart-campus'))}
                    {modeOptions.find((o) => o.value == mode).label}
                </div>
            </div>
        </>
    );
};

const Save = () => null;

registerBlockType(metadata.name, {
    edit: Edit,
    save: Save
});
