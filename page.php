<?php
/** 
 * Page template
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
            <div class="kp-prose max-w-4xl">
                <?php 
                the_content( ); 

                // paginated pages
                wp_link_pages( array( 
                    'before' => '<nav aria-label="Page navigation" class="mt-10 flex flex-wrap gap-3">', 
                    'after' => '</nav>' 
                ) ); 
                ?>
            </div>
        </div>
    </article>
    <?php 

// end the loop
endwhile;

// get the wp footer
get_footer( );
