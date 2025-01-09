( function ( blocks, element, serverSideRender, blockEditor ) {
    var el = element.createElement,
    registerBlockType = blocks.registerBlockType,
    ServerSideRender = serverSideRender,
    useBlockProps = blockEditor.useBlockProps;

registerBlockType( 'whatsapp-blocks/whatsapp-inquiry-block', {

    edit: function ( props ) {
        var blockProps = useBlockProps();
        return el(
            'div',
            blockProps,
            el( ServerSideRender, {
                block: 'whatsapp-blocks/whatsapp-inquiry-block',
                attributes: props.attributes,
            } )
        );
    },
} );
} )(
window.wp.blocks,
window.wp.element,
window.wp.serverSideRender,
window.wp.blockEditor
);