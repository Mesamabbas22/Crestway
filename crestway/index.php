<?php
/**
 * Fallback template (blog, search, archives).
 *
 * @package Crestway
 */

get_header();

if ( is_search() ) {
	/* translators: %s: search query. */
	$title = sprintf( __( 'Search: %s', 'crestway' ), get_search_query() );
} elseif ( is_archive() ) {
	$title = wp_strip_all_tags( get_the_archive_title() );
} else {
	$title = is_home() && get_option( 'page_for_posts' ) ? get_the_title( get_option( 'page_for_posts' ) ) : __( 'Latest News', 'crestway' );
}
dh_page_hero( $title, '', array( $title => '' ) );
?>

<section class="section section--tight">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="car-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article class="car-card reveal">
						<?php if ( has_post_thumbnail() ) : ?>
							<a class="car-media" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'dh-card' ); ?></a>
						<?php endif; ?>
						<div class="car-body">
							<div class="car-sub"><?php echo esc_html( get_the_date() ); ?></div>
							<h3 class="car-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p style="margin:0;color:var(--c-muted)"><?php echo esc_html( get_the_excerpt() ); ?></p>
							<a class="btn btn--ghost btn--sm" style="align-self:flex-start" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'crestway' ); ?> <?php dh_the_icon( 'arrow', 16 ); ?></a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination( array( 'prev_text' => '←', 'next_text' => '→' ) ); ?>
		<?php else : ?>
			<div class="empty-state">
				<div class="icon-wrap"><?php dh_the_icon( 'search', 30 ); ?></div>
				<h3><?php esc_html_e( 'Nothing found', 'crestway' ); ?></h3>
				<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'crestway' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
