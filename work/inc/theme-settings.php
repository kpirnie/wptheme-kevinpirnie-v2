
<?php

/**
 * Theme Settings
 *
 * Manages custom fields and theme options.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

use KP\WPFieldFramework\Loader;

if (! class_exists('KPT_Settings')) {

    /**
     * KPT_Settings
     *
     * Manage theme settings and custom fields.
     *
     * @since 1.0.0
     * @access public
     */
    class KPT_Settings
    {

        // hold our framework
        private $fw;

        /**
         * add_settings
         *
         * Initialize theme settings.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function add_settings(): void
        {

            // initialize the field framework
            $this->fw = Loader::init();

            // register our settings
            $this->create_theme_settings();
            $this->heroes_settings();
            $this->cta_settings();
            $this->portfolio_settings();
            $this->post_settings();

        }

        /**
         * create_theme_settings
         *
         * Register general theme options.
         *
         * @since 1.0.0
         * @access private
         * @return void
         */
        private function create_theme_settings(): void
        {

            $this->fw->addOptionsPage(array(
                'option_key' => 'kptheme_settings',
                'page_title' => 'Theme Options',
                'menu_title' => 'Theme Options',
                'capability' => 'manage_options',
                'menu_slug' => 'theme-options',
                'icon_url' => 'dashicons-admin-customizer',
                'position' => 2,
                'sections' => array(
                    'general' => array(
                        'title' => 'General',
                        'description' => 'General theme settings.',
                        'fields' => array(
                            array(
                                'id' => 'kpt_recaptcha_site_key',
                                'type' => 'text',
                                'label' => 'reCaptcha Site Key',
                                'default' => '',
                            ),
                            array(
                                'id' => 'kpt_recaptcha_secret_key',
                                'type' => 'text',
                                'label' => 'reCaptcha Secret Key',
                                'default' => '',
                            ),
                        ),
                    ),
                ),
            ));

        }

        /**
         * heroes_settings
         *
         * Register page hero assignments and subtitles.
         *
         * @since 1.0.0
         * @access private
         * @return void
         */
        private function heroes_settings(): void
        {

            $this->fw->addMetaBox(array(
                'id' => 'kpt_hero_settings',
                'title' => 'Page Options',
                'post_types' => array('page'),
                'context' => 'side',
                'priority' => 'high',
                'fields' => array(
                    array(
                        'id' => 'page_secondary_title',
                        'type' => 'text',
                        'label' => 'Secondary Title',
                        'description' => 'Optional subtitle displayed below the page heading.',
                    ),
                    array(
                        'id' => 'page_assignment',
                        'type' => 'checkboxes',
                        'label' => 'Hero Assignment',
                        'description' => 'Select the heroes to display on this page.',
                        'options' => $this->get_heroes(),
                    ),
                ),
            ));

        }

        /**
         * cta_settings
         *
         * Register CTA button settings.
         *
         * @since 1.0.0
         * @access private
         * @return void
         */
        private function cta_settings(): void
        {

            $this->fw->addMetaBox(array(
                'id' => 'kpt_cta_settings',
                'title' => 'CTA Options',
                'post_types' => array('kpt_cta'),
                'context' => 'side',
                'priority' => 'high',
                'fields' => array(
                    array(
                        'id' => 'cta_buttons',
                        'type' => 'repeater',
                        'label' => 'Buttons',
                        'button_label' => 'Add Button',
                        'collapsed' => true,
                        'sortable' => true,
                        'row_label' => 'Button',
                        'fields' => array(
                            array(
                                'id' => 'cta_button_type',
                                'label' => 'Type',
                                'type' => 'select',
                                'options' => array(
                                    1 => 'Primary',
                                    2 => 'Secondary',
                                    3 => 'Plain',
                                ),
                            ),
                            array(
                                'id' => 'cta_button_link',
                                'type' => 'link',
                                'label' => 'Link',
                            ),
                        ),
                    ),
                ),
            ));

        }

        /**
         * portfolio_settings
         *
         * Register portfolio project settings.
         *
         * @since 1.0.0
         * @access private
         * @return void
         */
        private function portfolio_settings(): void
        {

            $this->fw->addMetaBox(array(
                'id' => 'kpt_portfolio_settings',
                'title' => 'Portfolio Options',
                'post_types' => array('kpt_portfolio'),
                'context' => 'side',
                'priority' => 'high',
                'fields' => array(
                    array(
                        'id' => 'portfolio_url',
                        'type' => 'link',
                        'label' => 'Client Site URL',
                    ),
                ),
            ));

        }

        /**
         * post_settings
         *
         * Register article settings.
         *
         * @since 1.0.0
         * @access private
         * @return void
         */
        private function post_settings(): void
        {

            $this->fw->addMetaBox(array(
                'id' => 'kpt_post_settings',
                'title' => 'Post Options',
                'post_types' => array('post'),
                'context' => 'side',
                'priority' => 'high',
                'fields' => array(
                    array(
                        'id' => 'post_social_posted',
                        'type' => 'checkbox',
                        'label' => 'Posted on Social?',
                        'description' => 'Has this article been published to social networks?',
                    ),
                ),
            ));

        }

        /**
         * get_heroes
         *
         * Retrieve available hero assignments.
         *
         * @since 1.0.0
         * @access private
         * @return array
         */
        private function get_heroes(): array
        {

            // hold the available heroes
            $ret = array();

            // retrieve published heroes
            $heroes = get_posts(array(
                'post_type' => 'kpt_hero',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'fields' => 'ids',
            ));

            // return early if none exist
            if (empty($heroes)) {
                return $ret;
            }

            // populate the checkbox options
            foreach ($heroes as $hero_id) {

                $ret[$hero_id] = get_the_title($hero_id);

            }

            return $ret;

        }

    }

}
