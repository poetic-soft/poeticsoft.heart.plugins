const { registerBlockType } = wp.blocks;

import metadata from 'blocks/base/block.json';
import './editor.scss';

const Edit = (props) => {

    return (
        <>BASE</>
    );
};

const Save = () => null;

registerBlockType(
    metadata.name, 
    {
        edit: Edit,
        save: Save
    }
);
