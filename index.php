<?php
/** 
 * Index template - fallback archive/search listing
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
?>
<section class="py-16 md:py-20 lg:py-24">
    <div class="kp-container">
        <header class="mb-12 border-b border-kp-border pb-8">
            <h1 class="text-4xl font-bold text-kp-text md:text-5xl">
                <?php 

                // search results
                if ( is_search( ) ) {
                    printf( esc_html__( 'Search Results for: %s', 'kevinpirnie-v2' ), esc_html( get_search_query( ) ) );

                // archives
                } elseif ( is_archive( ) ) {
                    the_archive_title( );

                // everything else
                } else {
                    esc_html_e( 'Latest Articles', 'kevinpirnie-v2' );
                }
                ?>
            </h1>
            <?php if ( is_archive( ) ) : ?>
                <div class="kp-prose mt-5">
                    <?php the_archive_description( ); ?>
                </div>
            <?php endif; ?>
        </header>

        <?php if ( have_posts( ) ) : ?>
            <div class="grid gap-7 md:grid-cols-2 lg:grid-cols-3">
                <?php while ( have_posts( ) ) : the_post( );
                    get_template_part( 'partials/content-card' );
                endwhile; ?>
            </div>
            <div class="mt-14">
                <?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
            </div>
        <?php else : ?>
            <div class="py-16 text-center">
                <h2 class="mb-5 text-2xl font-bold">Nothing Found</h2>
                <p class="mb-8 text-kp-muted">No content matched your request.</p>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="kp-btn kp-btn-primary">Return Home</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php 

// get the wp footer
get_footer( );
