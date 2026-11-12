
<?php

/**
 * Header Template
 *
 * Renders the document head, site header,
 * and opening main content container.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-kp-background text-kp-text font-sans antialiased'); ?>>

    <?php wp_body_open(); ?>

    <!-- Accessibility Skip Link -->
    <a
        href="#content"
        class="sr-only focus:not-sr-only focus:fixed
               focus:left-4 focus:top-4 focus:z-[100]
               focus:rounded-md focus:bg-kp-orange
               focus:px-5 focus:py-3
               focus:font-semibold focus:text-kp-background"
    >
        <?php esc_html_e('Skip to content', 'kevinpirnie-v2'); ?>
    </a>

    <?php
    // pull in the main navigation header
    get_template_part('partials/navigation/main');
    ?>

    <!-- Main Content -->
    <main id="content" class="min-h-screen">
