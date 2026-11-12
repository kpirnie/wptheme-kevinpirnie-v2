<?php
/** WordPress theme supports and image sizes. */
defined('ABSPATH') || die('No direct script access allowed');
if (! class_exists('KPT_Supports')) {
    class KPT_Supports
    {
        public function the_theme_supports(): void
        {
            add_theme_support('title-tag');
            add_theme_support('post-thumbnails');
            add_theme_support('automatic-feed-links');
            add_theme_support('responsive-embeds');
            add_theme_support('align-wide');
            add_theme_support('editor-styles');
            add_theme_support('custom-logo', array('height' => 242, 'width' => 890, 'flex-height' => true, 'flex-width' => true, 'unlink-homepage-logo' => true));
            add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
            add_theme_support('appearance-tools');
            add_theme_support('custom-spacing');
            add_theme_support('custom-units', array('px', 'em', 'rem', 'vh', 'vw'));
            add_image_size('kp-hero', 1920, 800, true);
            add_image_size('kp-page-hero', 1920, 480, true);
            add_image_size('kp-article', 960, 540, true);
            add_image_size('kp-article-card', 600, 400, true);
            add_image_size('kp-portfolio', 960, 720, true);
            add_image_size('kp-portfolio-card', 600, 450, true);
            add_filter('image_size_names_choose', function ($sizes) {
                return array_merge($sizes, array(
                    'kp-hero' => __('Homepage Hero (1920x800)', 'kevinpirnie-v2'),
                    'kp-page-hero' => __('Page Hero (1920x480)', 'kevinpirnie-v2'),
                    'kp-article' => __('Article Featured (960x540)', 'kevinpirnie-v2'),
                    'kp-article-card' => __('Article Card (600x400)', 'kevinpirnie-v2'),
                    'kp-portfolio' => __('Portfolio Featured (960x720)', 'kevinpirnie-v2'),
                    'kp-portfolio-card' => __('Portfolio Card (600x450)', 'kevinpirnie-v2'),
                ));
            });
        }
    }
}
