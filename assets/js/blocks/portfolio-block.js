
/**
 * Portfolio Gutenberg Block
 *
 * Provides an editor interface for displaying
 * portfolio projects with configurable image sizes.
 *
 * @since 1.0.0
 */

(function (blocks, element, blockEditor, components, serverSideRender) {

    const { registerBlockType } = blocks;
    const { createElement: el } = element;
    const { InspectorControls } = blockEditor;
    const { PanelBody, SelectControl } = components;
    const ServerSideRender = serverSideRender;

    registerBlockType('kpt/portfolio-block', {

        title: 'Portfolio Slideshow',
        description: 'Display portfolio projects in a responsive grid.',
        icon: 'images-alt2',
        category: 'kpt-blocks',

        attributes: {
            imageSize: {
                type: 'string',
                default: 'portfolio',
            },
        },

        edit: function (props) {

            const { attributes, setAttributes } = props;

            // available image sizes
            const imageSizes = [
                {
                    label: 'Portfolio Featured (960x720)',
                    value: 'kp-portfolio',
                },
                {
                    label: 'Portfolio Card (600x450)',
                    value: 'kp-portfolio-card',
                },
            ];

            // image size selection
            const imageSizeControl = el(SelectControl, {
                label: 'Image Size',
                value: attributes.imageSize,
                options: imageSizes,
                onChange: (value) => {

                    setAttributes({
                        imageSize: value,
                    });

                },
                help: 'Choose the image size used for portfolio thumbnails.',
            });

            // editor sidebar
            const inspector = el(
                InspectorControls,
                null,
                el(
                    PanelBody,
                    {
                        title: 'Portfolio Settings',
                        initialOpen: true,
                    },
                    imageSizeControl
                )
            );

            // server-rendered portfolio preview
            return el(
                element.Fragment,
                null,
                inspector,
                el(ServerSideRender, {
                    block: 'kpt/portfolio-block',
                    attributes: attributes,
                })
            );

        },

        // dynamic block rendered by PHP
        save: function () {
            return null;
        },

    });

})(
    wp.blocks,
    wp.element,
    wp.blockEditor,
    wp.components,
    wp.serverSideRender
);
