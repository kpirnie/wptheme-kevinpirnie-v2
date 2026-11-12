
/**
 * Contact Form Gutenberg Block
 *
 * Provides the editor interface for the
 * website contact form.
 *
 * @since 1.0.0
 */

(function (blocks, element, components) {

    const { registerBlockType } = blocks;
    const { createElement: el } = element;
    const { Placeholder } = components;

    registerBlockType('kpt/contact-form-block', {

        title: 'Contact Form',
        description: 'Display the website contact form.',
        icon: 'email',
        category: 'kpt-blocks',

        supports: {
            anchor: true,
            align: ['wide', 'full'],
        },

        edit: function () {

            return el(
                Placeholder,
                {
                    icon: 'email',
                    label: 'Contact Form',
                    instructions: 'The contact form will be displayed here on the frontend.',
                },
                el(
                    'p',
                    null,
                    'Includes contact fields, privacy consent, spam protection, and reCAPTCHA when configured.'
                )
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
    wp.components
);
