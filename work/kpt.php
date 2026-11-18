<?php
/** 
 * Primary theme initialization
 * 
 * @since 8.5
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @package Kevin's Theme v2
 * 
 */

// no direct access fool!
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// make sure the class doesn't already exist
if ( ! class_exists( 'KPT' ) ) {

    /** 
     * KPT
     * 
     * Boots the theme's component classes
     * 
     * @since 8.5
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @package Kevin's Theme v2
     * 
     */
    class KPT
    {

        /** 
         * init
         * 
         * Instantiate each theme class and run its setup method
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @return void This method does not return anything
         */
        public function init( ) : void
        {

            // the theme classes and their setup methods
            $theme_classes = array(
                array( 'class' => 'KPT_Supports', 'method' => 'the_theme_supports' ),
                array( 'class' => 'KPT_Assets', 'method' => 'enqueue' ),
                array( 'class' => 'KPT_Menus', 'method' => 'register_menus' ),
                array( 'class' => 'KPT_CPTs', 'method' => 'add_cpts' ),
                array( 'class' => 'KPT_Blocks', 'method' => 'register_blocks' ),
                array( 'class' => 'KPT_Settings', 'method' => 'add_settings' ),
                array( 'class' => 'KPT_Contact_Form', 'method' => 'init' ),
            );

            // loop them
            foreach ( $theme_classes as $item ) {

                // skip any that don't exist
                if ( ! class_exists( $item['class'] ) ) {
                    continue;
                }

                // instantiate it
                $instance = new $item['class']( );

                // run the setup method if it has one
                if ( method_exists( $instance, $item['method'] ) ) {
                    $instance -> {$item['method']}( );
                }
            }
        }
    }
}