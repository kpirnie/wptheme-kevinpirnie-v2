<?php
/** 
 * Header template
 * 
 * @since 8.5
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @package Kevin's Theme v2
 * 
 */

// no direct access fool!
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

?>
<!DOCTYPE html>
<html <?php language_attributes( ); ?> class="scroll-smooth">
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script>
            window.dataLayer = window.dataLayer || [];
            window.gtag = window.gtag || function () { window.dataLayer.push( arguments ); };
            ( function () {
                var accepted = /(?:^|;\s*)kp_cookie_consent=accepted(?:;|$)/.test( document.cookie );
                var state = accepted ? 'granted' : 'denied';
                window.gtag( 'consent', 'default', {
                    ad_storage: state,
                    ad_user_data: state,
                    ad_personalization: state,
                    analytics_storage: state,
                    functionality_storage: 'granted',
                    security_storage: 'granted',
                    wait_for_update: 500
                } );
                if ( ! accepted ) {
                    window.a2a_config = window.a2a_config || {};
                    window.a2a_config.no_3p = 1;
                }
            } )( );
        </script>
        <?php wp_head( ); ?>
    </head>
    <body <?php body_class( 'bg-kp-background text-kp-text font-sans antialiased' ); ?>>
        <?php wp_body_open( ); ?>

        <a href="#content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:bg-kp-orange focus:p-3 focus:text-kp-background"><?php esc_html_e( 'Skip to content', 'kevinpirnie-v2' ); ?></a>
        
        <?php 
        
        // pull in the main nav template
        get_template_part( 'partials/navigation/main' ); 
        ?>
        <main id="content" class="min-h-screen">