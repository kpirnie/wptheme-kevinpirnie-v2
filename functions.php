<?php
/**
 * Theme Bootstrap
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */
defined('ABSPATH') || die('No direct script access allowed');
defined('KPT_PATH') || define('KPT_PATH', get_template_directory() . '/');
defined('KPT_VERSION') || define('KPT_VERSION', '1.0.0');

add_action('after_setup_theme', function () {
    $autoload = KPT_PATH . 'vendor/autoload.php';
    if (file_exists($autoload)) {
        require_once $autoload;
    } else {
        // Allow theme templates to load without Composer, but keep admin warning.
        foreach (glob(KPT_PATH . 'work/inc/*.php') ?: array() as $file) {
            require_once $file;
        }
        require_once KPT_PATH . 'work/kpt.php';
        add_action('admin_notices', function () {
            if (current_user_can('manage_options')) {
                echo '<div class="notice notice-warning"><p>' . esc_html__('Kevin Pirnie V2: Run composer install to enable the custom field framework.', 'kevinpirnie-v2') . '</p></div>';
            }
        });
    }
    if (class_exists('KPT')) {
        (new KPT())->init();
    }
}, 10);
