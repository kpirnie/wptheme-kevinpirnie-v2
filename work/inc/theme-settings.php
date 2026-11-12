<?php
/** Preserve existing custom metadata fields and hero assignments. */
defined('ABSPATH') || die('No direct script access allowed');
if (! class_exists('KPT_Settings')) {
    class KPT_Settings
    {
        public function add_settings(): void
        {
            if (! class_exists('KP\\WPFieldFramework\\Loader')) {
                return;
            }
            $fw = \KP\WPFieldFramework\Loader::init();
            $fw->addOptionsPage(array(
                'option_key' => 'kptheme_settings', 'page_title' => 'Theme Options',
                'menu_title' => 'Theme Options', 'capability' => 'manage_options',
                'menu_slug' => 'theme-options', 'icon_url' => 'dashicons-admin-customizer',
                'sections' => array('general' => array('title' => 'General', 'fields' => array(
                    array('id' => 'kpt_recaptcha_site_key', 'type' => 'text', 'label' => 'reCAPTCHA Site Key'),
                    array('id' => 'kpt_recaptcha_secret_key', 'type' => 'text', 'label' => 'reCAPTCHA Secret Key'),
                ))),
            ));
            $heroes = array();
            foreach (get_posts(array('post_type' => 'kpt_hero', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids')) as $id) {
                $heroes[$id] = get_the_title($id);
            }
            $fw->addMetaBox(array(
                'id' => 'kpt_hero_settings', 'title' => 'Page Options', 'post_types' => array('page'),
                'context' => 'side', 'priority' => 'high', 'fields' => array(
                    array('id' => 'page_secondary_title', 'type' => 'text', 'label' => 'Secondary Title'),
                    array('id' => 'page_assignment', 'type' => 'checkboxes', 'label' => 'Hero Assignment', 'options' => $heroes),
                ),
            ));
            $fw->addMetaBox(array('id' => 'kpt_portfolio_settings', 'title' => 'Portfolio Options',
                'post_types' => array('kpt_portfolio'), 'context' => 'side', 'fields' => array(
                    array('id' => 'portfolio_url', 'type' => 'link', 'label' => 'Client Site URL'),
                ),
            ));
            $fw->addMetaBox(array('id' => 'kpt_cta_settings', 'title' => 'CTA Options',
                'post_types' => array('kpt_cta'), 'context' => 'side', 'fields' => array(
                    array('id' => 'cta_buttons', 'type' => 'repeater', 'label' => 'Buttons',
                        'button_label' => 'Add Button', 'row_label' => 'Button', 'sortable' => true,
                        'fields' => array(
                            array('id' => 'cta_button_type', 'type' => 'select', 'label' => 'Type', 'options' => array(1 => 'Primary', 2 => 'Secondary', 3 => 'Plain')),
                            array('id' => 'cta_button_link', 'type' => 'link', 'label' => 'Link'),
                        ),
                    ),
                ),
            ));
            $fw->addMetaBox(array('id' => 'kpt_post_settings', 'title' => 'Post Options',
                'post_types' => array('post'), 'context' => 'side', 'fields' => array(
                    array('id' => 'post_social_posted', 'type' => 'checkbox', 'label' => 'Posted on Social?'),
                ),
            ));
        }
    }
}
