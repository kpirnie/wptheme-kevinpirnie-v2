
<?php

/**
 * CTA Display Template
 *
 * Renders reusable CTA content with glassmorphism,
 * optional background imagery, and action buttons.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// get the CTA ID
$cta_id = isset($args['cta_id']) ? absint($args['cta_id']) : 0;

// make sure we have a valid CTA
if (! $cta_id || 'kpt_cta' !== get_post_type($cta_id) || 'publish' !== get_post_status($cta_id)) {
    return;
}

// get the CTA settings
$cta_settings = get_post_meta($cta_id, 'kpt_cta_settings', true);
$cta_settings = is_array($cta_settings) ? $cta_settings : array();

// get the CTA buttons
$cta_buttons = $cta_settings['cta_buttons'] ?? array();

// get the CTA content
$cta_title = get_the_title($cta_id);
$cta_content = get_post_field('post_content', $cta_id);

// get the featured image
$image_id = get_post_thumbnail_id($cta_id);

?>

<!-- CTA Container -->
<section class="my-12 w-full">

    <div class="kp-glass relative isolate overflow-hidden rounded-xl shadow-kp-card">

        <?php if ($image_id) : ?>

            <!-- CTA Background Image -->
            <div class="absolute inset-0 -z-20">

                <?php
                echo wp_get_attachment_image(
                    $image_id,
                    'kp-article',
                    false,
                    array(
                        'class' => 'h-full w-full object-cover',
                        'alt' => '',
                        'loading' => 'lazy',
                        'sizes' => '100vw',
                    )
                );
                ?>

            </div>

            <!-- Background Overlay -->
            <div
                class="absolute inset-0 -z-10 bg-kp-background/85"
                aria-hidden="true"
            ></div>

        <?php endif; ?>

        <!-- CTA Content -->
        <div class="relative z-10 px-6 py-12 text-center md:px-12 md:py-16">

            <div class="mx-auto max-w-4xl">

                <?php if ($cta_title) : ?>

                    <h2 class="mb-5 text-3xl font-bold leading-tight tracking-tight text-kp-text md:text-4xl">
                        <?php echo esc_html($cta_title); ?>
                    </h2>

                <?php endif; ?>

                <?php if ($cta_content) : ?>

                    <div class="kp-prose mx-auto mb-8 max-w-2xl text-lg leading-8 text-kp-muted">
                        <?php echo wp_kses_post(apply_filters('the_content', $cta_content)); ?>
                    </div>

                <?php endif; ?>

                <!-- CTA Buttons -->
                <?php if (is_array($cta_buttons) && ! empty($cta_buttons)) : ?>

                    <div class="flex flex-wrap items-center justify-center gap-4">

                        <?php foreach ($cta_buttons as $button) : ?>

                            <?php

                            // make sure the button is valid
                            if (! is_array($button)) {
                                continue;
                            }

                            // get the button type
                            $button_type = absint($button['cta_button_type'] ?? $button['cta_type'] ?? 1);

                            // get the button link
                            $button_link = $button['cta_button_link'] ?? $button['cta_button'] ?? array();
                            $button_link = is_array($button_link) ? $button_link : array();

                            // get the link details
                            $button_url = $button_link['url'] ?? '';
                            $button_text = $button_link['text'] ?? $button_link['title'] ?? '';
                            $button_target = $button_link['target'] ?? '_self';

                            // skip incomplete buttons
                            if (! is_string($button_url) || ! is_string($button_text) || '' === $button_url || '' === $button_text) {
                                continue;
                            }

                            // only allow recognized targets
                            $button_target = '_blank' === $button_target ? '_blank' : '_self';

                            // determine the button appearance
                            $button_class = 'kp-btn kp-btn-primary';

                            if (2 === $button_type) {
                                $button_class = 'kp-btn kp-btn-secondary';
                            } elseif (3 === $button_type) {
                                $button_class = 'kp-btn border border-transparent bg-transparent text-kp-light-blue hover:text-kp-text';
                            }

                            ?>

                            <a
                                href="<?php echo esc_url($button_url); ?>"
                                target="<?php echo esc_attr($button_target); ?>"
                                <?php echo '_blank' === $button_target ? 'rel="noopener noreferrer"' : ''; ?>
                                class="<?php echo esc_attr($button_class); ?>"
                            >
                                <span>
                                    <?php echo esc_html($button_text); ?>
                                </span>

                                <span aria-hidden="true">
                                    &rarr;
                                </span>
                            </a>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>
