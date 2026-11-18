<?php
/**
 * Theme Bootstrap
 *
 * @since 8.5
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @package Kevin's Theme v2
 *  
 */
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// setup the theme path
defined( 'KPT_PATH' ) || define( 'KPT_PATH', get_template_directory( ) . '/' );
defined( 'KPT_VERSION' ) || define( 'KPT_VERSION', '1.0.0' );

// once the theme is setup...
add_action( 'after_setup_theme', function ( ) {

    // setup the autoloader
    $autoload = KPT_PATH . 'vendor/autoload.php';
    if ( file_exists( $autoload ) ) {
        require_once $autoload;
    } else {

        // Allow theme templates to load without Composer, but keep admin warning.
        foreach ( glob( KPT_PATH . 'work/inc/*.php' ) ?: array( ) as $file ) {
            require_once $file;
        }

        // require the main theme class, and show the notice to setup composer
        require_once KPT_PATH . 'work/kpt.php';
        add_action( 'admin_notices', function ( ) {
            if ( current_user_can( 'manage_options' ) ) {
                echo '<div class="notice notice-warning"><p>' . esc_html__( 'Kevin Pirnie v2: Run composer install to enable the custom field framework.', 'kevinpirnie-v2' ) . '</p></div>';
            }
        } );
    }

    // if the main theme class exists, initialize it
    if ( class_exists( 'KPT' ) ) {
        ( new KPT( ) ) -> init( );
    }
}, 10 );
