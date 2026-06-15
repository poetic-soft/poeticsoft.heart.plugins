const { registerBlockType } = wp.blocks;
const { useBlockProps, InspectorControls, RichText } = wp.blockEditor;
const { PanelBody, SelectControl, __experimentalNumberControl: NumberControl } = wp.components;
const { useEffect, useState } = wp.element;
const { apiFetch } = wp;
const { __ } = wp.i18n;

import { HeadingSelector } from 'blockscommon/elementselector';
import { useUniqueId } from 'blockscommon/uniqueid';

import metadata from 'blocks/lastpublished/block.json';
import './editor.scss';

const modeOptions = [
    {
        label: __('Título, Imagen & Extracto', 'poeticsoft-heart-campus'),
        value: 'complete'
    },
    {
        label: __('Sólo título', 'poeticsoft-heart-campus'),
        value: 'compact'
    }
];

const visibilityOptions = [
    {
        label: __('Visible siempre', 'poeticsoft-heart-campus'),
        value: 'visiblealways'
    },
    {
        label: __('Sólo en contenedores', 'poeticsoft-heart-campus'),
        value: 'onlyincontainers'
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
    const { title, maxCount, sectionHeadingType, areaHeadingType, mode, visibility } = attributes;

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
                    <NumberControl
                        className="MaxCount"
                        label={__('Cuántas páginas se muestran', 'poeticsoft-heart-campus')}
                        value={maxCount}
                        min={0}
                        onChange={(value) =>
                            setAttributes({
                                maxCount: parseInt(value)
                            })
                        }
                        isDragEnabled={true}
                        isShiftStepEnabled={true}
                        shiftStep={5}
                    />
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
                        label={__('Modo', 'poeticsoft-heart-campus')}
                        value={mode}
                        options={modeOptions}
                        onChange={(value) =>
                            setAttributes({
                                mode: value
                            })
                        }
                    />
                    <SelectControl
                        label={__('Visibilidad', 'poeticsoft-heart-campus')}
                        value={visibility}
                        options={visibilityOptions}
                        onChange={(value) =>
                            setAttributes({
                                visibility: value
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
