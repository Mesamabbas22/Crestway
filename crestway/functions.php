<?php
/**
 * Crestway theme bootstrap.
 *
 * @package Crestway
 */

defined( 'ABSPATH' ) || exit;

define( 'DH_VERSION', '1.0.0' );
define( 'DH_DIR', get_template_directory() );
define( 'DH_URI', get_template_directory_uri() );

require DH_DIR . '/inc/helpers.php';
require DH_DIR . '/inc/post-types.php';
require DH_DIR . '/inc/car-meta.php';
require DH_DIR . '/inc/booking.php';
require DH_DIR . '/inc/customizer.php';
require DH_DIR . '/inc/demo-content.php';

/**
 * Theme setup.
 */
function dh_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 88, 'width' => 260, 'flex-width' => true, 'flex-height' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );

	add_image_size( 'dh-card', 800, 500, true );
	add_image_size( 'dh-hero', 1600, 950, true );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'crestway' ),
			'footer'  => __( 'Footer Menu', 'crestway' ),
		)
	);
}
add_action( 'after_setup_theme', 'dh_setup' );

/**
 * Assets.
 */
function dh_assets() {
	wp_enqueue_style( 'dh-fonts', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap', array(), null );
	wp_enqueue_style( 'dh-style', get_stylesheet_uri(), array( 'dh-fonts' ), DH_VERSION );
	wp_enqueue_script( 'dh-main', DH_URI . '/assets/js/main.js', array(), DH_VERSION, true );
	wp_localize_script(
		'dh-main',
		'DH',
		array(
			'currency' => dh_currency(),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'dh_assets' );

/**
 * Preconnect to Google Fonts.
 */
function dh_resource_hints( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'dh_resource_hints', 10, 2 );

/**
 * Replace "no-js" with "js" as early as possible.
 */
function dh_js_class() {
	echo "<script>document.documentElement.classList.remove('no-js');</script>\n";
}
add_action( 'wp_head', 'dh_js_class', 0 );

/**
 * Fallback menu when no menu is assigned.
 */
function dh_menu_fallback() {
	$items = array(
		home_url( '/' )                     => __( 'Home', 'crestway' ),
		get_post_type_archive_link( 'car' ) => __( 'Our Fleet', 'crestway' ),
	);
	foreach ( array( 'about', 'contact' ) as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			$items[ get_permalink( $page ) ] = get_the_title( $page );
		}
	}
	echo '<ul>';
	foreach ( $items as $url => $label ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Filter + sort the fleet archive from query-string parameters.
 *
 * @param WP_Query $q Query.
 */
function dh_filter_fleet( $q ) {
	if ( is_admin() || ! $q->is_main_query() ) {
		return;
	}
	if ( ! ( $q->is_post_type_archive( 'car' ) || $q->is_tax( 'car_type' ) ) ) {
		return;
	}

	$q->set( 'posts_per_page', 8 );
	$f  = dh_fleet_filters();
	$mq = array( 'relation' => 'AND' );

	if ( $f['transmission'] ) {
		$mq[] = array( 'key' => '_dh_transmission', 'value' => $f['transmission'] );
	}
	if ( $f['fuel'] ) {
		$mq[] = array( 'key' => '_dh_fuel', 'value' => $f['fuel'] );
	}
	if ( $f['max_price'] ) {
		$mq[] = array( 'key' => '_dh_price', 'value' => $f['max_price'], 'type' => 'NUMERIC', 'compare' => '<=' );
	}
	if ( $f['seats'] ) {
		$mq[] = array( 'key' => '_dh_seats', 'value' => $f['seats'], 'type' => 'NUMERIC', 'compare' => '>=' );
	}
	if ( count( $mq ) > 1 ) {
		$q->set( 'meta_query', $mq );
	}

	if ( $f['ctype'] && ! $q->is_tax( 'car_type' ) ) {
		$q->set( 'tax_query', array( array( 'taxonomy' => 'car_type', 'field' => 'slug', 'terms' => $f['ctype'] ) ) );
	}

	switch ( $f['sort'] ) {
		case 'price_asc':
		case 'price_desc':
			$q->set( 'meta_key', '_dh_price' );
			$q->set( 'orderby', 'meta_value_num' );
			$q->set( 'order', 'price_asc' === $f['sort'] ? 'ASC' : 'DESC' );
			break;
		case 'rating':
			$q->set( 'meta_key', '_dh_rating' );
			$q->set( 'orderby', 'meta_value_num' );
			$q->set( 'order', 'DESC' );
			break;
	}
}
add_action( 'pre_get_posts', 'dh_filter_fleet' );

/**
 * Body classes.
 */
function dh_body_class( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'home';
	}
	return $classes;
}
add_filter( 'body_class', 'dh_body_class' );

/**
 * Shorter excerpts.
 */
add_filter( 'excerpt_length', fn() => 22 );
add_filter( 'excerpt_more', fn() => '…' );
