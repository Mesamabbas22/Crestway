<?php
/**
 * Contact page (auto-applied to the page with slug "contact").
 *
 * @package Crestway
 */

get_header();
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$sent = isset( $_GET['sent'] ) ? sanitize_key( $_GET['sent'] ) : '';

dh_page_hero( get_the_title(), __( 'Questions about a booking, corporate rentals or long-term leases? We usually reply within an hour.', 'crestway' ), array( get_the_title() => '' ) );
?>

<section class="section section--tight">
	<div class="container contact-grid">
		<div class="info-card reveal">
			<h2><?php esc_html_e( 'Let’s talk', 'crestway' ); ?></h2>
			<div class="info-item"><span class="icon-wrap"><?php dh_the_icon( 'phone', 20 ); ?></span><div><strong><?php esc_html_e( 'Phone', 'crestway' ); ?></strong><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', dh_opt( 'dh_phone' ) ) ); ?>"><?php echo esc_html( dh_opt( 'dh_phone' ) ); ?></a></div></div>
			<div class="info-item"><span class="icon-wrap"><?php dh_the_icon( 'mail', 20 ); ?></span><div><strong><?php esc_html_e( 'Email', 'crestway' ); ?></strong><a href="mailto:<?php echo esc_attr( dh_opt( 'dh_email' ) ); ?>"><?php echo esc_html( dh_opt( 'dh_email' ) ); ?></a></div></div>
			<div class="info-item"><span class="icon-wrap"><?php dh_the_icon( 'pin', 20 ); ?></span><div><strong><?php esc_html_e( 'Head office', 'crestway' ); ?></strong><?php echo esc_html( dh_opt( 'dh_address' ) ); ?></div></div>
			<div class="info-item"><span class="icon-wrap"><?php dh_the_icon( 'clock', 20 ); ?></span><div><strong><?php esc_html_e( 'Opening hours', 'crestway' ); ?></strong><?php echo esc_html( dh_opt( 'dh_hours' ) ); ?></div></div>
		</div>

		<div class="form-card reveal">
			<h2 style="font-size:1.6rem"><?php esc_html_e( 'Send us a message', 'crestway' ); ?></h2>
			<p style="color:var(--c-muted)"><?php esc_html_e( 'Fill in the form and our team will get back to you shortly.', 'crestway' ); ?></p>

			<?php if ( '1' === $sent ) : ?>
				<div class="notice notice--success" role="status"><?php dh_the_icon( 'check', 20 ); ?><div><strong><?php esc_html_e( 'Message sent!', 'crestway' ); ?></strong><?php esc_html_e( 'Thanks for reaching out — we’ll reply soon.', 'crestway' ); ?></div></div>
			<?php elseif ( 'error' === $sent ) : ?>
				<div class="notice notice--error" role="alert"><?php dh_the_icon( 'x', 20 ); ?><div><?php esc_html_e( 'Please fill in your name, a valid email and a message.', 'crestway' ); ?></div></div>
			<?php endif; ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="dh_contact">
				<?php wp_nonce_field( 'dh_contact', 'dh_contact_nonce' ); ?>
				<div class="screen-reader-text" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
				<div class="grid-2">
					<div class="field"><label for="c-name"><?php esc_html_e( 'Your name', 'crestway' ); ?></label><input id="c-name" type="text" name="name" autocomplete="name" required></div>
					<div class="field"><label for="c-email"><?php esc_html_e( 'Email', 'crestway' ); ?></label><input id="c-email" type="email" name="email" autocomplete="email" required></div>
				</div>
				<div class="field"><label for="c-subject"><?php esc_html_e( 'Subject', 'crestway' ); ?></label><input id="c-subject" type="text" name="subject"></div>
				<div class="field"><label for="c-message"><?php esc_html_e( 'Message', 'crestway' ); ?></label><textarea id="c-message" name="message" required></textarea></div>
				<div><button class="btn btn--primary" type="submit"><?php esc_html_e( 'Send message', 'crestway' ); ?> <?php dh_the_icon( 'arrow', 18 ); ?></button></div>
			</form>
		</div>
	</div>
</section>

<?php
while ( have_posts() ) :
	the_post();
	if ( get_the_content() ) :
		?>
		<section class="section section--tight" style="padding-top:0"><div class="container narrow"><div class="entry-content"><?php the_content(); ?></div></div></section>
		<?php
	endif;
endwhile;

get_footer();
