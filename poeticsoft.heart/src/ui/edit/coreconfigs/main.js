const { createHigherOrderComponent } = wp.compose;
const { InspectorControls } = wp.blockEditor;
const { PanelBody } = wp.components;
const { addFilter } = wp.hooks;

import './main.scss';

// https://thesheryar.com/how-to-extend-core-gutenberg-blocks-with-addfilter-and-blockedit/

const withInspectorControls = createHigherOrderComponent((BlockEdit) => {
    return function WithInspectorControls(props) {
        if (props.name === 'core/post-content') {
            const { attributes } = props;
            const { attr } = attributes; // classes/UI/Blocks.php -> register_block_type_args

            return (
                <>
                    <BlockEdit {...props} />
                    <InspectorControls>
                        <PanelBody
                            title="Atributos de Core Post Content"
                            initialOpen={true}
                            className="PostContentConfig"
                        >
                            <div className="value">{ attr }</div>    
                        </PanelBody>
                    </InspectorControls>
                </>
            );
        }

        return <BlockEdit {...props} />;
    };
}, 'withInspectorControls');

addFilter(
    'editor.BlockEdit', 
    'poeticsoft-heart/coreconfigs', 
    withInspectorControls
);
