<?php
/**
 * Header.
 *
 * @package Crestway
 */

?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'crestway' ); ?></a>

<header class="site-header" id="site-header">
	<div class="container header-inner">
		<?php if ( has_custom_logo() ) : ?>
			<?php the_custom_logo(); ?>
		<?php else : ?>
			<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<span class="logo-mark"><?php dh_the_icon( 'car', 22 ); ?></span>
				<?php dh_the_logo_text(); ?>
			</a>
		<?php endif; ?>

		<nav class="main-nav" id="main-nav" aria-label="<?php esc_attr_e( 'Primary', 'crestway' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => 'dh_menu_fallback',
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<a class="header-phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', dh_opt( 'dh_phone' ) ) ); ?>">
				<span class="icon-wrap"><?php dh_the_icon( 'phone', 18 ); ?></span>
				<span><small><?php esc_html_e( 'Need help?', 'crestway' ); ?></small><?php echo esc_html( dh_opt( 'dh_phone' ) ); ?></span>
			</a>
			<a class="btn btn--primary btn--sm" href="<?php echo esc_url( get_post_type_archive_link( 'car' ) ); ?>"><?php esc_html_e( 'Book a Car', 'crestway' ); ?></a>
			<button class="nav-toggle" id="nav-toggle" aria-controls="main-nav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle menu', 'crestway' ); ?>">
				<?php dh_the_icon( 'menu', 22 ); ?>
			</button>
		</div>
	</div>
</header>

<main id="main">
