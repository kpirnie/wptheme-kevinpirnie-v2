<?php
/** 
 * Custom post types - retained for permalink/content compatibility
 * 
 * @since 8.5
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @package Kevin's Theme v2
 * 
 */

// no direct access fool!
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// make sure the class doesn't already exist
if ( ! class_exists( 'KPT_CPTs' ) ) {

    /** 
     * KPT_CPTs
     * 
     * Registers the theme's custom post types
     * 
     * @since 8.5
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @package Kevin's Theme v2
     * 
     */
    class KPT_CPTs
    {

        /** 
         * add_cpts
         * 
         * Hook the custom post type registration into WordPress
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @return void This method does not return anything
         */
        public function add_cpts( ) : void
        {

            // register them on init
            add_action( 'init', function ( ) {

                // portfolio
                register_post_type( 'kpt_portfolio', array(
                    'labels' => array( 
                        'name' => __( 'Portfolio', 'kevinpirnie-v2' ), 
                        'singular_name' => __( 'Portfolio Item', 'kevinpirnie-v2' ) 
                    ),
                    'public' => true,
                    'show_ui' => true,
                    'menu_icon' => 'dashicons-portfolio',
                    'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
                    'has_archive' => false,
                    'show_in_rest' => true,
                    'show_in_nav_menus' => false,
                    'rewrite' => array( 'slug' => 'about-kevin-pirnie/enterprise-web-solutions-and-portfolio', 'with_front' => false ),
                ) );

                // page heroes
                register_post_type( 'kpt_hero', array(
                    'labels' => array( 
                        'name' => __( 'Page Heroes', 'kevinpirnie-v2' ), 
                        'singular_name' => __( 'Hero', 'kevinpirnie-v2' ) 
                    ),
                    'public' => false,
                    'show_ui' => true,
                    'menu_icon' => 'dashicons-format-gallery',
                    'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' ),
                    'has_archive' => false,
                    'rewrite' => false,
                    'publicly_queryable' => false,
                    'exclude_from_search' => true,
                    'show_in_nav_menus' => false,
                    'show_in_rest' => true,
                ) );

                // call to actions
                register_post_type( 'kpt_cta', array(
                    'labels' => array( 
                        'name' => __( 'Call To Actions', 'kevinpirnie-v2' ), 
                        'singular_name' => __( 'Call To Action', 'kevinpirnie-v2' ) 
                    ),
                    'public' => false,
                    'show_ui' => true,
                    'menu_icon' => 'dashicons-megaphone',
                    'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' ),
                    'has_archive' => false,
                    'rewrite' => false,
                    'publicly_queryable' => false,
                    'exclude_from_search' => true,
                    'show_in_nav_menus' => false,
                    'show_in_rest' => true,
                ) );
            } );
        }
    }
}