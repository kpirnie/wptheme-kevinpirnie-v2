
<?php

/**
 * Theme Gutenberg Blocks
 *
 * Registers custom Gutenberg blocks.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

if (! class_exists('KPT_Blocks')) {

    /**
     * KPT_Blocks
     *
     * Manage the theme's custom Gutenberg blocks.
     *
     * @since 1.0.0
     * @access public
     */
    class KPT_Blocks
    {

        /**
         * register_blocks
         *
         * Initialize custom Gutenberg blocks.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function register_blocks(): void
        {

            // register our block category
            add_filter('block_categories_all', array($this, 'add_block_category'), 10, 2);

            // register our blocks
            add_action('init', array($this, 'register_cta_block'));

            // register the portfolio block
            add_action('init', array($this, 'register_portfolio_block'));

            // register the contact form block
            add_action('init', array($this, 'register_contact_form_block'));

        }

        /**
         * add_block_category
         *
         * Register the custom block category.
         *
         * @since 1.0.0
         * @access public
         * @return array
         */
        public function add_block_category($categories, $context): array
        {

            return array_merge(
                array(
                    array(
                        'slug' => 'kpt-blocks',
                        'title' => __('KP Theme Blocks', 'kevinpirnie-v2'),
                        'icon' => 'admin-customizer',
                    ),
                ),
                $categories
            );

        }

        /**
         * register_cta_block
         *
         * Register the reusable CTA block.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function register_cta_block(): void
        {

            // make sure Gutenberg is available
            if (! function_exists('register_block_type')) {
                return;
            }

            // register the editor JavaScript when available
            $file = 'assets/js/blocks/cta-block.js';
            $path = KPT_PATH . $file;

            if (file_exists($path)) {

                wp_register_script(
                    'kpt-cta-block-editor',
                    get_template_directory_uri() . '/' . $file,
                    array(
                        'wp-blocks',
                        'wp-element',
                        'wp-block-editor',
                        'wp-components',
                        'wp-data',
                        'wp-server-side-render',
                    ),
                    filemtime($path),
                    true
                );

            }

            // register the CTA block
            register_block_type('kpt/cta-block', array(
                'api_version' => 2,
                'title' => __('CTA Display', 'kevinpirnie-v2'),
                'description' => __('Display a Call To Action', 'kevinpirnie-v2'),
                'category' => 'kpt-blocks',
                'icon' => 'megaphone',
                'keywords' => array(
                    'cta',
                    'call to action',
                    'banner',
                ),
                'supports' => array(
                    'anchor' => true,
                    'align' => array('wide', 'full'),
                ),
                'attributes' => array(
                    'ctaId' => array(
                        'type' => 'number',
                        'default' => 0,
                    ),
                ),
                'editor_script' => file_exists($path) ? 'kpt-cta-block-editor' : null,
                'render_callback' => array($this, 'render_cta_block'),
            ));

        }

        /**
         * render_cta_block
         *
         * Render the selected CTA.
         *
         * @since 1.0.0
         * @access public
         * @return string
         */
        public function render_cta_block($attributes): string
        {

            // get the selected CTA
            $cta_id = isset($attributes['ctaId'])
                ? absint($attributes['ctaId'])
                : 0;

            // make sure we have a published CTA
            if (
                ! $cta_id ||
                'kpt_cta' !== get_post_type($cta_id) ||
                'publish' !== get_post_status($cta_id)
            ) {
                return '';
            }

            // render the CTA template
            ob_start();

            get_template_part(
                'partials/cpts/cta',
                null,
                array('cta_id' => $cta_id)
            );

            return ob_get_clean();

        }

        /**
         * register_portfolio_block
         *
         * Register the portfolio Gutenberg block.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function register_portfolio_block(): void
        {

            // make sure Gutenberg is available
            if (! function_exists('register_block_type')) {
                return;
            }

            // register the editor JavaScript when available
            $file = 'assets/js/blocks/portfolio-block.js';
            $path = KPT_PATH . $file;

            if (file_exists($path)) {

                wp_register_script(
                    'kpt-portfolio-block-editor',
                    get_template_directory_uri() . '/' . $file,
                    array(
                        'wp-blocks',
                        'wp-element',
                        'wp-block-editor',
                        'wp-components',
                        'wp-server-side-render',
                    ),
                    filemtime($path),
                    true
                );

            }

            // register the portfolio block
            register_block_type('kpt/portfolio-block', array(
                'api_version' => 2,
                'title' => __('Portfolio Slideshow', 'kevinpirnie-v2'),
                'description' => __('Display portfolio projects.', 'kevinpirnie-v2'),
                'category' => 'kpt-blocks',
                'icon' => 'images-alt2',
                'keywords' => array(
                    'portfolio',
                    'projects',
                    'slideshow',
                    'gallery',
                ),
                'supports' => array(
                    'anchor' => true,
                    'align' => array('wide', 'full'),
                ),
                'attributes' => array(
                    'imageSize' => array(
                        'type' => 'string',
                        'default' => 'portfolio',
                    ),
                ),
                'editor_script' => file_exists($path) ? 'kpt-portfolio-block-editor' : null,
                'render_callback' => array($this, 'render_portfolio_block'),
            ));

        }

        /**
         * render_portfolio_block
         *
         * Render portfolio projects.
         *
         * @since 1.0.0
         * @access public
         * @return string
         */
        public function render_portfolio_block($attributes): string
        {

            // get the requested image size
            $image_size = $attributes['imageSize'] ?? 'portfolio';

            // map legacy image sizes to the new theme
            $image_sizes = array(
                'portfolio' => 'kp-portfolio',
                'portfolio-featured' => 'kp-portfolio',
                'portfolio-grid' => 'kp-portfolio-card',
                'kp-portfolio' => 'kp-portfolio',
                'kp-portfolio-card' => 'kp-portfolio-card',
            );

            // use a supported image size
            $image_size = is_string($image_size) && isset($image_sizes[$image_size])
                ? $image_sizes[$image_size]
                : 'kp-portfolio';

            // render the portfolio template
            ob_start();

            get_template_part(
                'partials/cpts/portfolio',
                null,
                array('image_size' => $image_size)
            );

            return ob_get_clean();

        }

        /**
         * register_contact_form_block
         *
         * Register the contact form Gutenberg block.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function register_contact_form_block(): void
        {

            // make sure Gutenberg is available
            if (! function_exists('register_block_type')) {
                return;
            }

            // register the editor JavaScript when available
            $file = 'assets/js/blocks/contact-form-block.js';
            $path = KPT_PATH . $file;

            if (file_exists($path)) {

                wp_register_script(
                    'kpt-contact-form-block-editor',
                    get_template_directory_uri() . '/' . $file,
                    array(
                        'wp-blocks',
                        'wp-element',
                        'wp-block-editor',
                        'wp-components',
                        'wp-server-side-render',
                    ),
                    filemtime($path),
                    true
                );

            }

            // register the contact form block
            register_block_type('kpt/contact-form-block', array(
                'api_version' => 2,
                'title' => __('Contact Form', 'kevinpirnie-v2'),
                'description' => __('Display the website contact form.', 'kevinpirnie-v2'),
                'category' => 'kpt-blocks',
                'icon' => 'email',
                'keywords' => array(
                    'contact',
                    'form',
                    'email',
                ),
                'supports' => array(
                    'anchor' => true,
                    'align' => array('wide', 'full'),
                ),
                'editor_script' => file_exists($path) ? 'kpt-contact-form-block-editor' : null,
                'render_callback' => array($this, 'render_contact_form_block'),
            ));

        }

        /**
         * render_contact_form_block
         *
         * Render the contact form.
         *
         * @since 1.0.0
         * @access public
         * @return string
         */
        public function render_contact_form_block($attributes): string
        {

            // don't render an interactive form inside the editor preview
            if (
                defined('REST_REQUEST') &&
                REST_REQUEST &&
                is_admin()
            ) {
                return '<p>' . esc_html__(
                    'Contact form preview is available on the frontend.',
                    'kevinpirnie-v2'
                ) . '</p>';
            }

            // render the contact form template
            ob_start();

            get_template_part('partials/contact');

            return ob_get_clean();

        }


    }

}
