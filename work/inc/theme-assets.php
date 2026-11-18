<?php
/** 
 * Frontend asset registration
 * 
 * @since 8.5
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @package Kevin's Theme v2
 * 
 */

// no direct access fool!
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// make sure the class doesn't already exist
if ( ! class_exists( 'KPT_Assets' ) ) {

    /** 
     * KPT_Assets
     * 
     * Registers the theme's frontend assets
     * 
     * @since 8.5
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @package Kevin's Theme v2
     * 
     */
    class KPT_Assets
    {

        /** 
         * enqueue
         * 
         * Hook the frontend assets into WordPress
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @return void This method does not return anything
         */
        public function enqueue( ) : void
        {

            // hook the frontend assets
            add_action( 'wp_enqueue_scripts', array( $this, 'frontend_assets' ) );
        }

        /** 
         * frontend_assets
         * 
         * Enqueue the theme stylesheet and script
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @return void This method does not return anything
         */
        public function frontend_assets( ) : void
        {

            // loop the assets
            foreach ( array( 'css' => 'assets/css/theme.min.css', 'js' => 'assets/js/theme.min.js' ) as $type => $file ) {

                // the file path
                $path = KPT_PATH . $file;

                // skip it if it doesn't exist
                if ( ! file_exists( $path ) ) {
                    continue;
                }

                // the url and version
                $url = get_template_directory_uri( ) . '/' . $file;
                $version = filemtime( $path );

                // enqueue the style
                if ( 'css' === $type ) {
                    wp_enqueue_style( 'kpt-theme', $url, array( ), $version );

                // otherwise, enqueue the script
                } else {
                    wp_enqueue_script( 'kpt-theme', $url, array( ), $version, array( 'strategy' => 'defer', 'in_footer' => true ) );
                }
            }
        }
    }
}