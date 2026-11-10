<?php
/**
 * Footer.
 *
 * @package Crestway
 */

$types = get_terms( array( 'taxonomy' => 'car_type', 'hide_empty' => true, 'number' => 5 ) );
?>
</main>

<footer class="site-footer">
	<div class="container">
		<div class="footer-grid">
			<div>
				<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<span class="logo-mark"><?php dh_the_icon( 'car', 22 ); ?></span><?php dh_the_logo_text(); ?>
				</a>
				<p><?php esc_html_e( 'Premium car rentals with transparent pricing, flexible pickup locations and 24/7 support. Your journey starts here.', 'crestway' ); ?></p>
				<div class="socials">
					<a href="#" aria-label="Facebook"><?php dh_the_icon( 'facebook', 18 ); ?></a>
					<a href="#" aria-label="Instagram"><?php dh_the_icon( 'instagram', 18 ); ?></a>
					<a href="#" aria-label="LinkedIn"><?php dh_the_icon( 'linkedin', 18 ); ?></a>
				</div>
			</div>

			<div>
				<h4><?php esc_html_e( 'Company', 'crestway' ); ?></h4>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'footer-links', 'depth' => 1 ) );
				} else {
					echo '<ul class="footer-links">';
					printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( '/' ) ), esc_html__( 'Home', 'crestway' ) );
					printf( '<li><a href="%s">%s</a></li>', esc_url( get_post_type_archive_link( 'car' ) ), esc_html__( 'Our Fleet', 'crestway' ) );
					foreach ( array( 'about', 'contact' ) as $slug ) {
						$p = get_page_by_path( $slug );
						if ( $p ) {
							printf( '<li><a href="%s">%s</a></li>', esc_url( get_permalink( $p ) ), esc_html( get_the_title( $p ) ) );
						}
					}
					echo '</ul>';
				}
				?>
			</div>

			<div>
				<h4><?php esc_html_e( 'Car Types', 'crestway' ); ?></h4>
				<ul class="footer-links">
					<?php if ( $types && ! is_wp_error( $types ) ) : ?>
						<?php foreach ( $types as $t ) : ?>
							<li><a href="<?php echo esc_url( get_term_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a></li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>

			<div>
				<h4><?php esc_html_e( 'Get in touch', 'crestway' ); ?></h4>
				<ul class="footer-links footer-contact">
					<li><?php dh_the_icon( 'pin', 16 ); ?><span><?php echo esc_html( dh_opt( 'dh_address' ) ); ?></span></li>
					<li><?php dh_the_icon( 'phone', 16 ); ?><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', dh_opt( 'dh_phone' ) ) ); ?>"><?php echo esc_html( dh_opt( 'dh_phone' ) ); ?></a></li>
					<li><?php dh_the_icon( 'mail', 16 ); ?><a href="mailto:<?php echo esc_attr( dh_opt( 'dh_email' ) ); ?>"><?php echo esc_html( dh_opt( 'dh_email' ) ); ?></a></li>
					<li><?php dh_the_icon( 'clock', 16 ); ?><span><?php echo esc_html( dh_opt( 'dh_hours' ) ); ?></span></li>
				</ul>
			</div>
		</div>

		<div class="footer-bottom">
			<span>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'crestway' ); ?></span>
			<?php if ( dh_opt( 'dh_credit' ) ) : ?>
				<span><?php echo esc_html( dh_opt( 'dh_credit' ) ); ?></span>
			<?php endif; ?>
		</div>
	</div>
</footer>

<button class="back-to-top" id="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'crestway' ); ?>"><?php dh_the_icon( 'arrow', 20 ); ?></button>

<?php wp_footer(); ?>
</body>
</html>
