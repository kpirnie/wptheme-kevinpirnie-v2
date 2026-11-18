<?php
/** 
 * Reusable Gutenberg blocks - retains the existing block identifiers
 * 
 * @since 8.5
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @package Kevin's Theme v2
 * 
 */

// no direct access fool!
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// make sure the class doesn't already exist
if ( ! class_exists( 'KPT_Blocks' ) ) {

    /** 
     * KPT_Blocks
     * 
     * Registers the theme's Gutenberg blocks
     * 
     * @since 8.5
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @package Kevin's Theme v2
     * 
     */
    class KPT_Blocks
    {

        /** 
         * register_blocks
         * 
         * Hook the block category and block registration into WordPress
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @return void This method does not return anything
         */
        public function register_blocks( ) : void
        {

            // add our block category
            add_filter( 'block_categories_all', function ( $categories ) {
                array_unshift( $categories, array( 'slug' => 'kpt-blocks', 'title' => __( 'KP Theme Blocks', 'kevinpirnie-v2' ) ) );
                return $categories;
            } );

            // register the blocks
            add_action( 'init', array( $this, 'register_all' ) );
        }

        /** 
         * register_all
         * 
         * Register the editor scripts and each block type
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @return void This method does not return anything
         */
        public function register_all( ) : void
        {

            // if blocks aren't supported, bail
            if ( ! function_exists( 'register_block_type' ) ) {
                return;
            }

            // register the editor scripts
            $this->editor( 'cta', array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-data', 'wp-server-side-render' ) );
            $this->editor( 'portfolio', array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render' ) );
            $this->editor( 'contact-form', array( 'wp-blocks', 'wp-element', 'wp-components' ) );

            // the cta block
            register_block_type( 'kpt/cta-block', array(
                'api_version' => 2,
                'title' => 'CTA Display',
                'category' => 'kpt-blocks',
                'icon' => 'megaphone',
                'attributes' => array( 'ctaId' => array( 'type' => 'number', 'default' => 0 ) ),
                'editor_script' => 'kpt-cta-editor',
                'render_callback' => array( $this, 'render_cta' ),
            ) );

            // the portfolio block
            register_block_type( 'kpt/portfolio-block', array(
                'api_version' => 2,
                'title' => 'Portfolio',
                'category' => 'kpt-blocks',
                'icon' => 'images-alt2',
                'attributes' => array( 'imageSize' => array( 'type' => 'string', 'default' => 'portfolio' ) ),
                'editor_script' => 'kpt-portfolio-editor',
                'render_callback' => array( $this, 'render_portfolio' ),
            ) );

            // the contact form block
            register_block_type( 'kpt/contact-form-block', array(
                'api_version' => 2,
                'title' => 'Contact Form',
                'category' => 'kpt-blocks',
                'icon' => 'email',
                'editor_script' => 'kpt-contact-form-editor',
                'render_callback' => array( $this, 'render_contact' ),
            ) );
        }

        /** 
         * editor
         * 
         * Register a block's editor script if the file exists
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @param string $name The block name
         * @param array $dependencies The script dependencies
         * 
         * @return void This method does not return anything
         */
        private function editor( string $name, array $dependencies ) : void
        {

            // the script file
            $file = 'assets/js/blocks/' . $name . '-block.js';

            // if it exists, register it
            if ( file_exists( KPT_PATH . $file ) ) {
                wp_register_script(
                    'kpt-' . $name . '-editor',
                    get_template_directory_uri( ) . '/' . $file,
                    $dependencies,
                    filemtime( KPT_PATH . $file ),
                    true
                );
            }
        }

        /** 
         * render_cta
         * 
         * Render the CTA block
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @param array $attributes The block attributes
         * 
         * @return string The rendered block markup
         */
        public function render_cta( array $attributes ) : string
        {

            // the cta id
            $id = absint( $attributes['ctaId'] ?? 0 );

            // if it's not a valid, published cta, bail
            if ( ! $id || 'kpt_cta' !== get_post_type( $id ) || 'publish' !== get_post_status( $id ) ) {
                return '';
            }

            // render it
            ob_start( );
            get_template_part( 'partials/cpts/cta', null, array( 'cta_id' => $id ) );
            return ob_get_clean( );
        }

        /** 
         * render_portfolio
         * 
         * Render the portfolio block
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @param array $attributes The block attributes
         * 
         * @return string The rendered block markup
         */
        public function render_portfolio( array $attributes ) : string
        {

            // the allowed image sizes
            $sizes = array(
                'portfolio' => 'kp-portfolio',
                'portfolio-featured' => 'kp-portfolio',
                'portfolio-grid' => 'kp-portfolio-card',
                'kp-portfolio' => 'kp-portfolio',
                'kp-portfolio-card' => 'kp-portfolio-card'
            );

            // the chosen size
            $size = $sizes[$attributes['imageSize'] ?? 'portfolio'] ?? 'kp-portfolio';

            // render it
            ob_start( );
            get_template_part( 'partials/cpts/portfolio', null, array( 'image_size' => $size ) );
            return ob_get_clean( );
        }

        /** 
         * render_contact
         * 
         * Render the contact form block
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @return string The rendered block markup
         */
        public function render_contact( ) : string
        {

            // in the editor, show a placeholder
            if ( defined( 'REST_REQUEST' ) && REST_REQUEST && is_user_logged_in( ) ) {
                return '<p>' . esc_html__( 'Contact form appears on the frontend.', 'kevinpirnie-v2' ) . '</p>';
            }

            // render it
            ob_start( );
            get_template_part( 'partials/contact' );
            return ob_get_clean( );
        }
    }
}