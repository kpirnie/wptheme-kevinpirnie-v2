<?php
/** 
 * Single post template
 * 
 * @since 8.5
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @package Kevin's Theme v2
 * 
 */

// no direct access fool!
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// get the wp header
get_header( );

// loop the content
while ( have_posts( ) ) : the_post( );

    // the hero
    get_template_part( 'partials/heroes/inner-page' ); 
    ?>
    <article id="post-<?php the_ID( ); ?>" <?php post_class( 'py-16 md:py-20 lg:py-24' ); ?>>
        <div class="kp-container">
            <div class="mx-auto max-w-4xl">
                <div class="mb-10 flex flex-wrap gap-4 border-b border-kp-border pb-7 text-sm text-kp-muted">
                    <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( ) ); ?></time>
                    <span>By <?php echo esc_html( get_the_author( ) ); ?></span>
                    <span><?php echo wp_kses_post( get_the_category_list( ', ' ) ); ?></span>
                </div>

                <div class="kp-prose">
                    <?php the_content( ); ?>
                </div>

                <?php 

                // paginated articles
                wp_link_pages( array( 
                    'before' => '<nav aria-label="Article pages" class="mt-10">', 
                    'after' => '</nav>' 
                ) ); 

                // grab the tags
                $tags = get_the_tags( ); 

                // if we have any
                if ( $tags ) : ?>
                    <div class="mt-12 flex flex-wrap gap-3 border-t border-kp-border pt-8">
                        <?php foreach ( $tags as $tag ) : ?>
                            <a class="kp-btn kp-btn-secondary" href="<?php echo esc_url( get_tag_link( $tag -> term_id ) ); ?>"><?php echo esc_html( $tag -> name ); ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <nav class="mt-12 flex flex-col justify-between gap-6 border-t border-kp-border pt-8 md:flex-row" aria-label="Article navigation">
                    <?php 
                    previous_post_link( '%link', '&larr; %title' ); 
                    next_post_link( '%link', '%title &rarr;' ); 
                    ?>
                </nav>
            </div>
        </div>
    </article>
    <?php 

// end the loop
endwhile;

// get the wp footer
get_footer( );