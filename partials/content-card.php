<?php
/** Reusable post/project card. */
defined('ABSPATH') || die('No direct script access allowed');
?><article id="post-<?php the_ID(); ?>" <?php post_class('kp-card flex flex-col overflow-hidden'); ?>>
<?php if (has_post_thumbnail()) : ?><a href="<?php the_permalink(); ?>" class="mb-6 block overflow-hidden rounded-lg"><?php the_post_thumbnail('kp-article-card', array('class' => 'aspect-[3/2] w-full object-cover', 'loading' => 'lazy', 'sizes' => '(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw')); ?></a><?php endif; ?>
<?php if ('post' === get_post_type()) : ?><time class="mb-3 text-sm text-kp-muted" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time><?php endif; ?>
<h2 class="mb-4 text-xl font-bold leading-snug text-kp-text"><a href="<?php the_permalink(); ?>" class="hover:text-kp-light-blue"><?php the_title(); ?></a></h2>
<div class="mb-6 flex-1 text-sm leading-7 text-kp-muted"><?php the_excerpt(); ?></div>
<a href="<?php the_permalink(); ?>" class="font-semibold text-kp-light-blue hover:text-kp-text">Read More &rarr;</a>
</article>
