const { registerBlockType } = wp.blocks;
const { useBlockProps, InspectorControls } = wp.blockEditor;
const { PanelBody, ToggleControl } = wp.components;
const { __, sprintf } = wp.i18n;

import { LinkSelector } from 'blockscommon/elementselector';
import { useUniqueId } from 'blockscommon/uniqueid';

import metadata from 'blocks/mytools/block.json';
import './editor.scss';

const Link = (props) => {
    switch (props.linkType) {
        case 'button':
            return (
                <button
                    className="
          wp-block-button__link 
          wp-element-button
        "
                >
                    <a href="#">{__('SALIR', 'poeticsoft-heart-campus')}</a>
                </button>
            );

        case 'link':
            return <a href="#">{__('SALIR', 'poeticsoft-heart-campus')}</a>;

        default:
            return <a href="#">{__('SALIR', 'poeticsoft-heart-campus')}</a>;
    }
};

const Edit = (props) => {
    const { clientId, attributes, setAttributes } = props;
    const { linkType, idVisible } = attributes;
    const blockProps = useBlockProps();

    const selectLinkType = (value) => {
        setAttributes({
            linkType: value
        });
    };

    useUniqueId(clientId, attributes, setAttributes);

    return (
        <>
            <InspectorControls>
                <PanelBody
                    className="MyTools"
                    title={__('Opciones del Bloque', 'poeticsoft-heart-campus')}
                    initialOpen={true}
                >
                    <ToggleControl
                        label={sprintf(
                            __('Identificación visible?: %s', 'poeticsoft-heart-campus'),
                            idVisible
                                ? __('SÍ', 'poeticsoft-heart-campus')
                                : __('NO', 'poeticsoft-heart-campus')
                        )}
                        checked={idVisible}
                        onChange={(value) =>
                            setAttributes({
                                idVisible: value
                            })
                        }
                    />
                    <LinkSelector value={linkType} onChange={selectLinkType} />
                </PanelBody>
            </InspectorControls>
            <div {...blockProps}>
                {idVisible ? (
                    <span className="Identify">
                        {__('identificación', 'poeticsoft-heart-campus')}
                    </span>
                ) : (
                    ''
                )}
                <Link linkType={linkType} />
            </div>
        </>
    );
};

const Save = () => null;

registerBlockType(metadata.name, {
    edit: Edit,
    save: Save
});
