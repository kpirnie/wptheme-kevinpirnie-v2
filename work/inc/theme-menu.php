<?php
/** 
 * WordPress navigation menu locations
 * 
 * @since 8.5
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @package Kevin's Theme v2
 * 
 */

// no direct access fool!
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// make sure the class doesn't already exist
if ( ! class_exists( 'KPT_Menus' ) ) {

    /** 
     * KPT_Menus
     * 
     * Registers the theme's menus and social menu icons
     * 
     * @since 8.5
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @package Kevin's Theme v2
     * 
     */
    class KPT_Menus
    {

        /** 
         * social_menu_item_title
         * 
         * Swap social menu item titles for their icon
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @param string $title The menu item title
         * @param \WP_Post $item The menu item
         * @param \stdClass $args The menu arguments
         * 
         * @return string The icon markup, or the original title
         */
        public function social_menu_item_title( string $title, \WP_Post $item, \stdClass $args ) : string
        {

            // only the social menu
            if ( ( $args -> theme_location ?? '' ) !== 'social' ) {
                return $title;
            }

            // the host, minus www
            $host = strtolower( ( string ) wp_parse_url( $item -> url, PHP_URL_HOST ) );
            $host = preg_replace( '/^www\./', '', $host );

            // match the host to an icon
            $icon = match ( $host ) {
                'facebook.com' => 'facebook-f',
                'x.com', 'twitter.com' => 'x-twitter',
                'linkedin.com' => 'linkedin-in',
                'reddit.com' => 'reddit-alien',
                'g.dev' => 'google',
                'profiles.wordpress.org' => 'wordpress-simple',
                'flipboard.com' => 'flipboard',
                'stackoverflow.com' => 'stack-overflow',
                'github.com' => 'github',
                'gist.github.com' => 'github-alt',
                'packagist.org' => 'php',
                'hub.docker.com' => 'docker',
                'xdaforums.com' => 'android',
                default => '',
            };

            // the label
            $label = trim( wp_strip_all_tags( $title ) );

            // no icon or label, leave it alone
            if ( '' === $icon || '' === $label ) {
                return $title;
            }

            // the sprite url
            $sprite = get_theme_file_uri( 'assets/social-icons.svg' ) . '#' . $icon;

            // return the icon
            return '<svg class="inline-block h-4 w-4 fill-current align-middle" aria-hidden="true" focusable="false">'
                . '<use href="' . esc_url( $sprite ) . '"></use></svg>'
                . '<span class="sr-only">' . esc_html( $label ) . '</span>';
        }

        /** 
         * register_menus
         * 
         * Register the menu locations and the social title filter
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @return void This method does not return anything
         */
        public function register_menus( ) : void
        {

            // the menu locations
            register_nav_menus( array(
                'primary' => __( 'Primary Navigation', 'kevinpirnie-v2' ),
                'top' => __( 'Top Header Navigation', 'kevinpirnie-v2' ),
                'social' => __( 'Social Navigation', 'kevinpirnie-v2' ),
                'bottom' => __( 'Footer Navigation', 'kevinpirnie-v2' ),
            ) );

            // swap the social titles for icons
            add_filter( 'nav_menu_item_title', array( $this, 'social_menu_item_title' ), 10, 3 );
        }
    }
}