<?php
/** 
 * Theme settings - preserves existing custom metadata fields and hero assignments
 * 
 * @since 8.5
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @package Kevin's Theme v2
 * 
 */

// no direct access fool!
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// make sure the class doesn't already exist
if ( ! class_exists( 'KPT_Settings' ) ) {

    /** 
     * KPT_Settings
     * 
     * Registers the theme options page and meta boxes
     * 
     * @since 8.5
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @package Kevin's Theme v2
     * 
     */
    class KPT_Settings
    {

        /** 
         * add_settings
         * 
         * Register the options page and meta boxes with the field framework
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @return void This method does not return anything
         */
        public function add_settings( ) : void
        {

            // if the framework isn't available, bail
            if ( ! class_exists( 'KP\\WPFieldFramework\\Loader' ) ) {
                return;
            }

            // initialize the framework
            $fw = \KP\WPFieldFramework\Loader::init( );

            // the theme options page
            $fw -> addOptionsPage( array(
                'option_key' => 'kptheme_settings',
                'page_title' => 'Theme Options',
                'menu_title' => 'Theme Options',
                'capability' => 'manage_options',
                'menu_slug' => 'theme-options',
                'icon_url' => 'dashicons-admin-customizer',
                'sections' => array( 
                    'general' => array( 
                        'title' => 'General', 
                        'fields' => array(
                            array( 'id' => 'kpt_recaptcha_site_key', 'type' => 'text', 'label' => 'reCAPTCHA Site Key' ),
                            array( 'id' => 'kpt_recaptcha_secret_key', 'type' => 'text', 'label' => 'reCAPTCHA Secret Key' ),
                        ) 
                    ) 
                ),
            ) );

            // build the page list
            $pages = array( );
            foreach ( get_pages( ) as $page ) {
                $pages[$page -> ID] = get_the_title( $page );
            }

            // hero display
            $fw -> addMetaBox( array(
                'id' => 'kpt_hero_display',
                'title' => 'Hero Display',
                'post_types' => array( 'kpt_hero' ),
                'context' => 'side',
                'priority' => 'high',
                'fields' => array(
                    array( 'id' => 'hero_page_ids', 'type' => 'checkboxes', 'label' => 'Page Assignment', 'options' => $pages ),
                    array( 
                        'id' => 'hero_on_home', 
                        'type' => 'select', 
                        'label' => 'On Home', 
                        'options' => array(
                            '' => 'Use existing assignment',
                            'yes' => 'Yes',
                            'no' => 'No',
                        ) 
                    ),
                ),
            ) );

            // page options
            $fw -> addMetaBox( array(
                'id' => 'kpt_hero_settings',
                'title' => 'Page Options',
                'post_types' => array( 'page' ),
                'context' => 'side',
                'priority' => 'high',
                'fields' => array(
                    array( 'id' => 'page_secondary_title', 'type' => 'text', 'label' => 'Secondary Title' ),
                ),
            ) );

            // portfolio options
            $fw -> addMetaBox( array(
                'id' => 'kpt_portfolio_settings',
                'title' => 'Portfolio Options',
                'post_types' => array( 'kpt_portfolio' ),
                'context' => 'side',
                'fields' => array(
                    array( 'id' => 'portfolio_url', 'type' => 'link', 'label' => 'Client Site URL' ),
                ),
            ) );

            // cta options
            $fw -> addMetaBox( array(
                'id' => 'kpt_cta_settings',
                'title' => 'CTA Options',
                'post_types' => array( 'kpt_cta' ),
                'context' => 'side',
                'fields' => array(
                    array( 
                        'id' => 'cta_buttons', 
                        'type' => 'repeater', 
                        'label' => 'Buttons',
                        'button_label' => 'Add Button', 
                        'row_label' => 'Button', 
                        'sortable' => true,
                        'fields' => array(
                            array( 
                                'id' => 'cta_button_type', 
                                'type' => 'select', 
                                'label' => 'Type', 
                                'options' => array( 1 => 'Primary', 2 => 'Secondary', 3 => 'Plain' ) 
                            ),
                            array( 'id' => 'cta_button_link', 'type' => 'link', 'label' => 'Link' ),
                        ),
                    ),
                ),
            ) );

            // post options
            $fw -> addMetaBox( array(
                'id' => 'kpt_post_settings',
                'title' => 'Post Options',
                'post_types' => array( 'post' ),
                'context' => 'side',
                'fields' => array(
                    array( 'id' => 'post_social_posted', 'type' => 'checkbox', 'label' => 'Posted on Social?' ),
                ),
            ) );
        }
    }
}