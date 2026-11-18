<?php
/** 
 * Top navigation partial - top header navigation
 * 
 * @since 8.5
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @package Kevin's Theme v2
 * 
 */

// no direct access fool!
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// if neither menu is assigned, bail
if ( ! has_nav_menu( 'social' ) && ! has_nav_menu( 'top' ) ) {
    return;
}
?>
<div class="border-b border-kp-border">
    <div class="kp-container flex flex-wrap items-center justify-between gap-x-4 gap-y-1 py-1 text-xs text-kp-muted">
        <?php

        // the social menu
        wp_nav_menu( array(
            'theme_location' => 'social',
            'container' => 'nav',
            'container_aria_label' => __( 'Header Social Navigation', 'kevinpirnie-v2' ),
            'menu_id' => 'header-social-menu',
            'menu_class' => 'flex flex-wrap items-center gap-x-3 gap-y-1',
            'fallback_cb' => false,
            'depth' => 1,
        ) );

        // the top menu
        wp_nav_menu( array(
            'theme_location' => 'top',
            'container' => 'nav',
            'container_class' => 'ml-auto',
            'container_aria_label' => __( 'Top Header Navigation', 'kevinpirnie-v2' ),
            'menu_class' => 'flex flex-wrap items-center justify-end gap-x-4 gap-y-1',
            'fallback_cb' => false,
            'depth' => 1,
        ) );
        ?>
    </div>
</div>