<?php
/** Frontend asset registration. */
defined('ABSPATH') || die('No direct script access allowed');
if (! class_exists('KPT_Assets')) {
    class KPT_Assets
    {
        public function enqueue(): void
        {
            add_action('wp_enqueue_scripts', array($this, 'frontend_assets'));
        }
        public function frontend_assets(): void
        {
            foreach (array('css' => 'assets/css/theme.min.css', 'js' => 'assets/js/theme.min.js') as $type => $file) {
                $path = KPT_PATH . $file;
                if (! file_exists($path)) {
                    continue;
                }
                $url = get_template_directory_uri() . '/' . $file;
                $version = filemtime($path);
                if ('css' === $type) {
                    wp_enqueue_style('kpt-theme', $url, array(), $version);
                } else {
                    wp_enqueue_script('kpt-theme', $url, array(), $version, array('strategy' => 'defer', 'in_footer' => true));
                }
            }
        }
    }
}
