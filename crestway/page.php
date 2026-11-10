<?php
/**
 * Generic page.
 *
 * @package Crestway
 */

get_header();

while ( have_posts() ) :
	the_post();
	dh_page_hero( get_the_title(), has_excerpt() ? get_the_excerpt() : '', array( get_the_title() => '' ) );
	?>
	<section class="section section--tight">
		<div class="container narrow">
			<article class="entry-content reveal">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'dh-hero', array( 'style' => 'margin-bottom:28px' ) ); ?>
				<?php endif; ?>
				<?php the_content(); ?>
			</article>
		</div>
	</section>
	<?php
endwhile;

get_footer();
