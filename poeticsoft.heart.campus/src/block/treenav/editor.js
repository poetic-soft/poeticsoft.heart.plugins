import './editor.scss';

const { registerBlockType } = wp.blocks;
const { useBlockProps, InspectorControls } = wp.blockEditor;
const { PanelBody, ToggleControl, __experimentalNumberControl: NumberControl } = wp.components;
const { __, sprintf } = wp.i18n;

import metadata from 'blocks/treenav/block.json';
import { useUniqueId } from 'blockscommon/uniqueid';
import './editor.scss';

const Edit = (props) => {
    const { clientId, attributes, setAttributes } = props;
    const { ignoreRoot, onlySubscriptions, maxDeep, showLegend } = attributes;
    const blockProps = useBlockProps();

    useUniqueId(clientId, attributes, setAttributes);

    return (
        <>
            <InspectorControls>
                <PanelBody
                    title={__('Opciones del Bloque', 'poeticsoft-heart-campus')}
                    initialOpen={true}
                    className="TreeNavControls"
                >
                    <ToggleControl
                        label={sprintf(
                            __('Ignorar raíz (%s)', 'poeticsoft-heart-campus'),
                            ignoreRoot
                                ? __('SÍ', 'poeticsoft-heart-campus')
                                : __('NO', 'poeticsoft-heart-campus')
                        )}
                        checked={ignoreRoot}
                        onChange={(value) =>
                            setAttributes({
                                ignoreRoot: value
                            })
                        }
                    />
                    <ToggleControl
                        label={sprintf(
                            __('Ver sólo suscripciones (%s)', 'poeticsoft-heart-campus'),
                            onlySubscriptions
                                ? __('SÍ', 'poeticsoft-heart-campus')
                                : __('NO', 'poeticsoft-heart-campus')
                        )}
                        checked={onlySubscriptions}
                        onChange={(value) =>
                            setAttributes({
                                onlySubscriptions: value
                            })
                        }
                    />
                    <NumberControl
                        className="MaxDeep"
                        label={__('Máximo nivel (0 para todos)', 'poeticsoft-heart-campus')}
                        value={maxDeep}
                        min={0}
                        onChange={(value) =>
                            setAttributes({
                                maxDeep: parseInt(value)
                            })
                        }
                        isDragEnabled={true}
                        isShiftStepEnabled={true}
                        shiftStep={5}
                    />
                    <ToggleControl
                        label={sprintf(
                            __('Ver leyenda (%s)', 'poeticsoft-heart-campus'),
                            showLegend
                                ? __('SÍ', 'poeticsoft-heart-campus')
                                : __('NO', 'poeticsoft-heart-campus')
                        )}
                        checked={showLegend}
                        onChange={(value) =>
                            setAttributes({
                                showLegend: value
                            })
                        }
                    />
                </PanelBody>
            </InspectorControls>
            <div {...blockProps}>
                {__('Navegación del ', 'poeticsoft-heart-campus')}
                {onlySubscriptions
                    ? __(' (Sólo suscripciones & Libre)', 'poeticsoft-heart-campus')
                    : ''}
                {showLegend ? __(' (Con leyenda)', 'poeticsoft-heart-campus') : ''}
            </div>
        </>
    );
};

const Save = () => null;

registerBlockType(metadata.name, {
    edit: Edit,
    save: Save
});
