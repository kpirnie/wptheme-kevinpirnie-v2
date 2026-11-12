
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

    }

}
