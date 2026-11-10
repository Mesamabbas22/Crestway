<?php
/**
 * Template helpers: icons, meta, prices, images.
 *
 * @package Crestway
 */

defined( 'ABSPATH' ) || exit;

/**
 * Inline SVG icon (stroke style).
 *
 * @param string $name Icon key.
 * @param int    $size Pixel size.
 * @return string
 */
function dh_icon( $name, $size = 20 ) {
	static $icons = array(
		'users'     => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
		'gear'      => '<circle cx="12" cy="12" r="3"/><path d="M12 1v3M12 20v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M1 12h3M20 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/>',
		'fuel'      => '<path d="M3 22h12M4 9h10"/><path d="M14 22V4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v18"/><path d="M14 13h2a2 2 0 0 1 2 2v2a2 2 0 0 0 4 0V9.83a2 2 0 0 0-.59-1.42L18 5"/>',
		'bag'       => '<rect width="20" height="14" x="2" y="7" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
		'door'      => '<path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/><path d="M3 21h18M15 12h.01"/>',
		'snow'      => '<path d="M2 12h20M12 2v20M20 16l-4-4 4-4M4 8l4 4-4 4M16 4l-4 4-4-4M8 20l4-4 4 4"/>',
		'gauge'     => '<path d="m12 14 4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/>',
		'star'      => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
		'pin'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
		'calendar'  => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
		'phone'     => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
		'mail'      => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-10 6L2 7"/>',
		'check'     => '<path d="M20 6 9 17l-5-5"/>',
		'arrow'     => '<path d="M5 12h14M12 5l7 7-7 7"/>',
		'shield'    => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
		'clock'     => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
		'wallet'    => '<path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/>',
		'headset'   => '<path d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"/>',
		'car'       => '<path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/>',
		'search'    => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
		'menu'      => '<path d="M4 6h16M4 12h16M4 18h16"/>',
		'x'         => '<path d="M18 6 6 18M6 6l12 12"/>',
		'plus'      => '<path d="M12 5v14M5 12h14"/>',
		'zap'       => '<path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/>',
		'key'       => '<circle cx="7.5" cy="15.5" r="5.5"/><path d="m21 2-9.6 9.6M15.5 7.5l3 3L22 7l-3-3"/>',
		'sliders'   => '<path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6"/>',
		'crown'     => '<path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zM5 20h14"/>',
		'flag'      => '<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><path d="M4 22v-7"/>',
		'truck'     => '<path d="M10 17h4V5H2v12h3M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5v8h1"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>',
		'lock'      => '<rect width="18" height="11" x="3" y="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
		'facebook'  => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
		'instagram' => '<rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><path d="M17.5 6.5h.01"/>',
		'linkedin'  => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/>',
		'twitter'   => '<path d="M4 4l16 16M20 4 4 20"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%2$s</svg>',
		(int) $size,
		$icons[ $name ]
	);
}

/**
 * Echo an icon (markup is static and trusted).
 */
function dh_the_icon( $name, $size = 20 ) {
	echo dh_icon( $name, $size ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Text logo: "Crest" + accent "way" for the default brand, otherwise the site name.
 */
function dh_the_logo_text() {
	$name = trim( wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) );
	if ( '' === $name || 0 === stripos( $name, 'Crestway' ) ) {
		echo '<span class="logo-text">Crest<span>way</span></span>';
		return;
	}
	echo '<span class="logo-text">' . esc_html( $name ) . '</span>';
}

/**
 * Car meta getter with default.
 */
function dh_meta( $post_id, $key, $default = '' ) {
	$value = get_post_meta( $post_id, '_dh_' . $key, true );
	return ( '' === $value || null === $value ) ? $default : $value;
}

/**
 * Currency symbol from Customizer.
 */
function dh_currency() {
	return get_theme_mod( 'dh_currency', '$' );
}

/**
 * Formatted price.
 */
function dh_price( $amount ) {
	return dh_currency() . number_format_i18n( (float) $amount, 0 );
}

/**
 * Inline SVG placeholder used when no image is available.
 */
function dh_placeholder_image() {
	$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 500"><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#e2e8f0"/><stop offset="1" stop-color="#cbd5e1"/></linearGradient></defs><rect width="800" height="500" fill="url(#g)"/><g transform="translate(300 150) scale(8.3)" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></g></svg>';
	return 'data:image/svg+xml;charset=utf-8,' . rawurlencode( $svg );
}

/**
 * Car image URL: featured image > external URL meta > placeholder.
 */
function dh_car_image( $post_id, $size = 'dh-card' ) {
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail_url( $post_id, $size );
	}
	$url = dh_meta( $post_id, 'image_url' );
	if ( $url ) {
		return 'dh-hero' === $size ? add_query_arg( 'w', 1600, $url ) : $url;
	}
	return dh_placeholder_image();
}

/**
 * <img> tag for a car with a graceful onerror fallback.
 */
function dh_car_img_tag( $post_id, $size = 'dh-card', $eager = false ) {
	return sprintf(
		'<img src="%1$s" alt="%2$s" %3$s decoding="async" onerror="this.onerror=null;this.src=\'%4$s\'">',
		esc_url( dh_car_image( $post_id, $size ), array( 'http', 'https', 'data' ) ),
		esc_attr( get_the_title( $post_id ) ),
		$eager ? 'fetchpriority="high"' : 'loading="lazy"',
		esc_attr( dh_placeholder_image() )
	);
}

/**
 * First car_type term name.
 */
function dh_car_type_name( $post_id ) {
	$terms = get_the_terms( $post_id, 'car_type' );
	return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
}

/**
 * Sanitised fleet filters from the query string.
 */
function dh_fleet_filters() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- public, read-only filtering.
	$get = function ( $key ) {
		return isset( $_GET[ $key ] ) ? sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) : '';
	};
	$sort = $get( 'sort' );
	return array(
		'ctype'        => sanitize_title( $get( 'ctype' ) ),
		'transmission' => in_array( $get( 'transmission' ), array( 'Automatic', 'Manual' ), true ) ? $get( 'transmission' ) : '',
		'fuel'         => in_array( $get( 'fuel' ), dh_fuel_options(), true ) ? $get( 'fuel' ) : '',
		'max_price'    => absint( $get( 'max_price' ) ),
		'seats'        => absint( $get( 'seats' ) ),
		'sort'         => in_array( $sort, array( 'price_asc', 'price_desc', 'rating' ), true ) ? $sort : '',
		'pickup'       => dh_valid_date( $get( 'pickup' ) ),
		'return'       => dh_valid_date( $get( 'return' ) ),
		'location'     => $get( 'location' ),
	);
	// phpcs:enable
}

/**
 * Return Y-m-d string if valid, else ''.
 */
function dh_valid_date( $value ) {
	$d = DateTime::createFromFormat( 'Y-m-d', (string) $value );
	return ( $d && $d->format( 'Y-m-d' ) === $value ) ? $value : '';
}

function dh_fuel_options() {
	return array( 'Petrol', 'Diesel', 'Hybrid', 'Electric' );
}

/**
 * Pickup locations (Customizer, comma separated).
 */
function dh_locations() {
	$raw = get_theme_mod( 'dh_locations', 'Downtown Office, Airport Terminal 1, Central Railway Station, City Mall Parking' );
	return array_values( array_filter( array_map( 'trim', explode( ',', $raw ) ) ) );
}

/**
 * Optional extras: key => [label, price per day].
 */
function dh_extras() {
	return array(
		'insurance' => array( __( 'Full insurance', 'crestway' ), 15 ),
		'gps'       => array( __( 'GPS navigation', 'crestway' ), 5 ),
		'child'     => array( __( 'Child seat', 'crestway' ), 4 ),
		'driver'    => array( __( 'Additional driver', 'crestway' ), 10 ),
	);
}

/**
 * Icon per car type slug (used on category cards).
 */
function dh_type_icon( $slug ) {
	$map = array(
		'suv'       => 'truck',
		'sedan'     => 'car',
		'luxury'    => 'crown',
		'electric'  => 'zap',
		'sports'    => 'gauge',
		'hatchback' => 'key',
	);
	return $map[ $slug ] ?? 'car';
}

/**
 * Page hero with breadcrumbs.
 */
function dh_page_hero( $title, $subtitle = '', $crumbs = array() ) {
	?>
	<section class="page-hero">
		<div class="container">
			<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'crestway' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'crestway' ); ?></a>
				<?php foreach ( $crumbs as $label => $url ) : ?>
					<span>/</span>
					<?php if ( $url ) : ?>
						<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a>
					<?php else : ?>
						<span><?php echo esc_html( $label ); ?></span>
					<?php endif; ?>
				<?php endforeach; ?>
			</nav>
			<h1><?php echo esc_html( $title ); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
}
