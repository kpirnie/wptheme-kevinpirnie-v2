<?php
/** 
 * CTA partial - reusable frosted-glass CTA
 * 
 * @since 8.5
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @package Kevin's Theme v2
 * 
 */

// no direct access fool!
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// grab the cta id
$id = absint( $args['cta_id'] ?? 0 );

// if it's not a valid, published cta, bail
if ( ! $id || 'kpt_cta' !== get_post_type( $id ) || 'publish' !== get_post_status( $id ) ) {
    return;
}

// grab the settings and buttons
$settings = get_post_meta( $id, 'kpt_cta_settings', true );
$buttons = is_array( $settings ) ? ( $settings['cta_buttons'] ?? array( ) ) : array( );

// the background image
$image_id = get_post_thumbnail_id( $id );
?>
<section class="my-12">
    <div class="kp-glass relative isolate overflow-hidden rounded-xl shadow-kp-card">
        <?php if ( $image_id ) : ?>
            <div class="absolute inset-0 -z-20">
                <?php echo wp_get_attachment_image( $image_id, 'kp-article', false, array( 
                    'class' => 'h-full w-full object-cover', 
                    'alt' => '', 
                    'loading' => 'lazy' 
                ) ); ?>
            </div>
            <div class="absolute inset-0 -z-10 bg-kp-background/85" aria-hidden="true"></div>
        <?php endif; ?>

        <div class="relative px-6 py-12 text-center md:px-12 md:py-16">
            <div class="mx-auto max-w-4xl">
                <h2 class="mb-5 text-3xl font-bold md:text-4xl"><?php echo esc_html( get_the_title( $id ) ); ?></h2>

                <div class="kp-prose mx-auto mb-8 max-w-2xl">
                    <?php echo wp_kses_post( apply_filters( 'the_content', get_post_field( 'post_content', $id ) ) ); ?>
                </div>

                <?php if ( is_array( $buttons ) ) : ?>
                    <div class="flex flex-wrap justify-center gap-4">
                        <?php 

                        // loop the buttons
                        foreach ( $buttons as $button ) :

                            // skip invalid buttons
                            if ( ! is_array( $button ) ) {
                                continue;
                            }

                            // the type and link
                            $type = absint( $button['cta_button_type'] ?? $button['cta_type'] ?? 1 );
                            $link = $button['cta_button_link'] ?? $button['cta_button'] ?? array( );

                            // skip invalid links
                            if ( ! is_array( $link ) ) {
                                continue;
                            }

                            // the url and text
                            $url = $link['url'] ?? '';
                            $text = $link['text'] ?? $link['title'] ?? '';

                            // skip if either is missing
                            if ( ! is_string( $url ) || ! is_string( $text ) || ! $url || ! $text ) {
                                continue;
                            }

                            // the target and classes
                            $target = '_blank' === ( $link['target'] ?? '' ) ? '_blank' : '_self';
                            $class = 2 === $type ? 'kp-btn kp-btn-secondary' : ( 3 === $type ? 'kp-btn text-kp-light-blue' : 'kp-btn kp-btn-primary' );
                            ?>
                            <a href="<?php echo esc_url( $url ); ?>" target="<?php echo esc_attr( $target ); ?>" <?php echo '_blank' === $target ? 'rel="noopener noreferrer"' : ''; ?> class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $text ); ?> &rarr;</a>
                            <?php 

                        // end the loop
                        endforeach; 
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>