const { registerBlockType } = wp.blocks;
const { useBlockProps, InspectorControls } = wp.blockEditor;
const { PanelBody, ToggleControl, Dashicon } = wp.components;
const { __, sprintf } = wp.i18n;

import { useUniqueId } from 'blockscommon/uniqueid';
import metadata from 'blocks/columntools/block.json';
import './editor.scss';

const Edit = (props) => {
    const { clientId, attributes, setAttributes } = props;
    const { defaultOpen } = attributes;
    const blockProps = useBlockProps();

    useUniqueId(clientId, attributes, setAttributes);

    return (
        <>
            <InspectorControls>
                <PanelBody
                    className="Tools"
                    title={__('Opciones del Bloque', 'poeticsoft-heart-campus')}
                    initialOpen={true}
                >
                    <ToggleControl
                        label={sprintf(
                            __('Abierto: %s', 'poeticsoft-heart-campus'),
                            defaultOpen
                                ? __('SÍ', 'poeticsoft-heart-campus')
                                : __('NO', 'poeticsoft-heart-campus')
                        )}
                        checked={defaultOpen}
                        onChange={(value) =>
                            setAttributes({
                                defaultOpen: value
                            })
                        }
                    />
                </PanelBody>
            </InspectorControls>
            <div
                {...blockProps}
                onClick={() =>
                    setAttributes({
                        defaultOpen: !defaultOpen
                    })
                }
            >
                <Dashicon icon={defaultOpen ? 'arrow-left-alt2' : 'arrow-right-alt2'} />
            </div>
        </>
    );
};

const Save = () => null;

registerBlockType(metadata.name, {
    edit: Edit,
    save: Save
});
