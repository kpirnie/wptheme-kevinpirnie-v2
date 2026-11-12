
/**
 * CTA Gutenberg Block
 *
 * Provides an editor interface for selecting and
 * displaying existing CTA custom post type entries.
 *
 * @since 1.0.0
 */

(function (blocks, element, blockEditor, components, data, serverSideRender) {

    const { registerBlockType } = blocks;
    const { createElement: el } = element;
    const { InspectorControls } = blockEditor;
    const { PanelBody, SelectControl, Placeholder, Spinner } = components;
    const { useSelect } = data;
    const ServerSideRender = serverSideRender;

    registerBlockType('kpt/cta-block', {

        title: 'CTA Display',
        description: 'Display a reusable Call To Action.',
        icon: 'megaphone',
        category: 'kpt-blocks',

        attributes: {
            ctaId: {
                type: 'number',
                default: 0,
            },
        },

        edit: function (props) {

            const { attributes, setAttributes } = props;
            const { ctaId } = attributes;

            // retrieve available CTA entries
            const ctas = useSelect((select) => {

                return select('core').getEntityRecords(
                    'postType',
                    'kpt_cta',
                    {
                        per_page: 100,
                        status: 'publish',
                        orderby: 'title',
                        order: 'asc',
                    }
                );

            }, []);

            // build the dropdown options
            const options = [
                {
                    label: 'Select a Call To Action',
                    value: 0,
                },
            ];

            if (Array.isArray(ctas)) {

                ctas.forEach((cta) => {

                    options.push({
                        label: cta.title.rendered || `CTA #${cta.id}`,
                        value: cta.id,
                    });

                });

            }

            // update the selected CTA
            const onChange = (value) => {

                setAttributes({
                    ctaId: parseInt(value, 10) || 0,
                });

            };

            // CTA selection control
            const selector = el(SelectControl, {
                label: 'Call To Action',
                value: ctaId,
                options: options,
                onChange: onChange,
                help: 'Select the CTA you want to display.',
            });

            // editor sidebar settings
            const inspector = el(
                InspectorControls,
                null,
                el(
                    PanelBody,
                    {
                        title: 'CTA Settings',
                        initialOpen: true,
                    },
                    selector
                )
            );

            // display a loading indicator
            if (ctas === null || ctas === undefined) {

                return el(
                    element.Fragment,
                    null,
                    inspector,
                    el(
                        Placeholder,
                        {
                            icon: 'megaphone',
                            label: 'Call To Action',
                        },
                        el(Spinner)
                    )
                );

            }

            // prompt for a CTA selection
            if (!ctaId) {

                return el(
                    element.Fragment,
                    null,
                    inspector,
                    el(
                        Placeholder,
                        {
                            icon: 'megaphone',
                            label: 'Call To Action',
                            instructions: 'Choose an existing CTA to display.',
                        },
                        selector
                    )
                );

            }

            // render the selected CTA
            return el(
                element.Fragment,
                null,
                inspector,
                el(ServerSideRender, {
                    block: 'kpt/cta-block',
                    attributes: attributes,
                })
            );

        },

        // dynamic block - rendered by PHP
        save: function () {
            return null;
        },

    });

})(
    wp.blocks,
    wp.element,
    wp.blockEditor,
    wp.components,
    wp.data,
    wp.serverSideRender
);
