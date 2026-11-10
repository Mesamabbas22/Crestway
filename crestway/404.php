<?php
/**
 * 404.
 *
 * @package Crestway
 */

get_header();
?>
<section class="section">
	<div class="container">
		<div class="empty-state narrow">
			<div class="icon-wrap"><?php dh_the_icon( 'flag', 30 ); ?></div>
			<h1 style="font-size:clamp(3rem,8vw,5rem);margin-bottom:0" class="text-gradient">404</h1>
			<h2 style="font-size:1.5rem"><?php esc_html_e( 'Looks like you took a wrong turn', 'crestway' ); ?></h2>
			<p style="color:var(--c-muted)"><?php esc_html_e( 'The page you’re looking for doesn’t exist or has moved.', 'crestway' ); ?></p>
			<div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
				<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'crestway' ); ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( get_post_type_archive_link( 'car' ) ); ?>"><?php esc_html_e( 'Browse cars', 'crestway' ); ?></a>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
