const { registerBlockType } = wp.blocks;
const { useBlockProps } = wp.blockEditor;
const { __ } = wp.i18n;

import { useUniqueId } from 'blockscommon/uniqueid';
import metadata from 'blocks/breadcrumbs/block.json';
import './editor.scss';

const Edit = (props) => {
    const { clientId, attributes, setAttributes } = props;
    const { blockId, refClientId } = attributes;

    const blockProps = useBlockProps();

    useUniqueId(clientId, attributes, setAttributes);

    return <div {...blockProps}>{__('Breadcrumbs', 'poeticsoft-heart-campus')}</div>;
};

const Save = () => null;

registerBlockType(metadata.name, {
    edit: Edit,
    save: Save
});
