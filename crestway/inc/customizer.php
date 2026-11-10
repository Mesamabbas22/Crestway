<?php
/**
 * Customizer settings.
 *
 * @package Crestway
 */

defined( 'ABSPATH' ) || exit;

function dh_customize_register( $wp_customize ) {
	$wp_customize->add_panel( 'dh_panel', array( 'title' => __( 'Crestway Settings', 'crestway' ), 'priority' => 30 ) );

	$sections = array(
		'dh_hero'    => __( 'Homepage Hero', 'crestway' ),
		'dh_contact' => __( 'Contact Details', 'crestway' ),
		'dh_rental'  => __( 'Rental Settings', 'crestway' ),
	);
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section( $id, array( 'title' => $title, 'panel' => 'dh_panel' ) );
	}

	$fields = array(
		'dh_hero_badge'    => array( 'dh_hero', __( 'Badge text', 'crestway' ), 'text', 'Save up to 25% on weekly rentals' ),
		'dh_hero_title'    => array( 'dh_hero', __( 'Title', 'crestway' ), 'text', 'Drive the car you love, at a price you’ll like' ),
		'dh_hero_subtitle' => array( 'dh_hero', __( 'Subtitle', 'crestway' ), 'textarea', 'Premium, well-maintained cars with transparent pricing, free cancellation and 24/7 roadside support. Book in under 60 seconds.' ),
		'dh_hero_image'    => array( 'dh_hero', __( 'Background image URL', 'crestway' ), 'url', '' ),
		'dh_phone'         => array( 'dh_contact', __( 'Phone', 'crestway' ), 'text', '+1 (555) 123-4567' ),
		'dh_email'         => array( 'dh_contact', __( 'Email', 'crestway' ), 'text', 'hello@crestwayrentals.com' ),
		'dh_address'       => array( 'dh_contact', __( 'Address', 'crestway' ), 'text', '221 Market Street, San Francisco, CA' ),
		'dh_hours'         => array( 'dh_contact', __( 'Opening hours', 'crestway' ), 'text', 'Mon – Sun: 7:00 AM – 10:00 PM' ),
		'dh_credit'        => array( 'dh_contact', __( 'Footer credit (leave empty to hide)', 'crestway' ), 'text', 'Website by Mesam Abbas' ),
		'dh_currency'      => array( 'dh_rental', __( 'Currency symbol', 'crestway' ), 'text', '$' ),
		'dh_locations'     => array( 'dh_rental', __( 'Pickup locations (comma separated)', 'crestway' ), 'textarea', 'Downtown Office, Airport Terminal 1, Central Railway Station, City Mall Parking' ),
	);

	foreach ( $fields as $id => $f ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $f[3],
				'sanitize_callback' => 'url' === $f[2] ? 'esc_url_raw' : ( 'textarea' === $f[2] ? 'sanitize_textarea_field' : 'sanitize_text_field' ),
			)
		);
		$wp_customize->add_control( $id, array( 'label' => $f[1], 'section' => $f[0], 'type' => $f[2] ) );
	}
}
add_action( 'customize_register', 'dh_customize_register' );

/**
 * Theme mod with the default registered above.
 */
function dh_opt( $key ) {
	$defaults = array(
		'dh_hero_badge'    => 'Save up to 25% on weekly rentals',
		'dh_hero_title'    => 'Drive the car you love, at a price you’ll like',
		'dh_hero_subtitle' => 'Premium, well-maintained cars with transparent pricing, free cancellation and 24/7 roadside support. Book in under 60 seconds.',
		'dh_hero_image'    => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1920&q=80',
		'dh_phone'         => '+1 (555) 123-4567',
		'dh_email'         => 'hello@crestwayrentals.com',
		'dh_address'       => '221 Market Street, San Francisco, CA',
		'dh_hours'         => 'Mon – Sun: 7:00 AM – 10:00 PM',
	);
	if ( 'dh_credit' === $key ) {
		return get_theme_mod( 'dh_credit', 'Website by Mesam Abbas' ); // Empty string hides it.
	}
	$value = get_theme_mod( $key, $defaults[ $key ] ?? '' );
	return '' === $value ? ( $defaults[ $key ] ?? '' ) : $value;
}
