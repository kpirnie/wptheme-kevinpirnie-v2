
<?php

/**
 * Theme Bootstrap
 *
 * This file initializes the WordPress theme.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// setup our path
defined('KPT_PATH') || define('KPT_PATH', get_template_directory() . '/');

// setup our version
defined('KPT_VERSION') || define('KPT_VERSION', '1.0.0');

// At our earliest point, fire this up
add_action('after_setup_theme', function () {

    // include our autoloader
    $autoload = KPT_PATH . 'vendor/autoload.php';

    if (! file_exists($autoload)) {
        return;
    }

    require_once $autoload;

    // make sure the theme class exists
    if (! class_exists('KPT')) {
        return;
    }

    // initialize the theme
    $_kpt = new KPT();

    $_kpt->init();

    // clean up
    unset($_kpt);

}, 10);
