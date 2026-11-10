<?php
/**
 * Car details meta box.
 *
 * @package Crestway
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field definitions: key => [label, type, options/placeholder].
 */
function dh_car_fields() {
	return array(
		'price'        => array( __( 'Price per day', 'crestway' ), 'number', '79' ),
		'rating'       => array( __( 'Rating (0–5)', 'crestway' ), 'decimal', '4.8' ),
		'year'         => array( __( 'Model year', 'crestway' ), 'number', '2024' ),
		'seats'        => array( __( 'Seats', 'crestway' ), 'number', '5' ),
		'doors'        => array( __( 'Doors', 'crestway' ), 'number', '4' ),
		'bags'         => array( __( 'Luggage (bags)', 'crestway' ), 'number', '3' ),
		'transmission' => array( __( 'Transmission', 'crestway' ), 'select', array( 'Automatic', 'Manual' ) ),
		'fuel'         => array( __( 'Fuel', 'crestway' ), 'select', dh_fuel_options() ),
		'mileage'      => array( __( 'Mileage policy', 'crestway' ), 'text', 'Unlimited' ),
		'image_url'    => array( __( 'External image URL (used if no Car photo)', 'crestway' ), 'url', 'https://…' ),
		'features'     => array( __( 'Features (one per line)', 'crestway' ), 'textarea', "Bluetooth\nApple CarPlay\nRear camera" ),
		'featured'     => array( __( 'Show on homepage', 'crestway' ), 'checkbox', '' ),
	);
}

function dh_car_add_meta_box() {
	add_meta_box( 'dh_car_details', __( 'Car Details', 'crestway' ), 'dh_car_meta_box', 'car', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'dh_car_add_meta_box' );

function dh_car_meta_box( $post ) {
	wp_nonce_field( 'dh_save_car', 'dh_car_nonce' );
	echo '<style>.dh-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px 18px}.dh-grid p{margin:0}.dh-grid label{display:block;font-weight:600;margin-bottom:4px}.dh-grid input:not([type=checkbox]),.dh-grid select,.dh-grid textarea{width:100%}.dh-wide{grid-column:1/-1}@media(max-width:900px){.dh-grid{grid-template-columns:1fr}}</style><div class="dh-grid">';

	foreach ( dh_car_fields() as $key => $field ) {
		list( $label, $type, $extra ) = $field;
		$id    = 'dh_' . $key;
		$value = get_post_meta( $post->ID, '_dh_' . $key, true );
		$wide  = in_array( $type, array( 'textarea', 'url' ), true ) ? ' class="dh-wide"' : '';

		echo '<p' . $wide . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		switch ( $type ) {
			case 'select':
				printf( '<label for="%s">%s</label><select id="%1$s" name="%1$s">', esc_attr( $id ), esc_html( $label ) );
				foreach ( $extra as $opt ) {
					printf( '<option value="%1$s"%2$s>%1$s</option>', esc_attr( $opt ), selected( $value, $opt, false ) );
				}
				echo '</select>';
				break;
			case 'textarea':
				printf( '<label for="%1$s">%2$s</label><textarea id="%1$s" name="%1$s" rows="5" placeholder="%3$s">%4$s</textarea>', esc_attr( $id ), esc_html( $label ), esc_attr( $extra ), esc_textarea( $value ) );
				break;
			case 'checkbox':
				printf( '<label><input type="checkbox" name="%1$s" value="1"%2$s> %3$s</label>', esc_attr( $id ), checked( $value, '1', false ), esc_html( $label ) );
				break;
			default:
				$input_type = 'decimal' === $type ? 'number" step="0.1" min="0" max="5' : ( 'number' === $type ? 'number" min="0' : $type );
				printf( '<label for="%1$s">%2$s</label><input type="%3$s" id="%1$s" name="%1$s" value="%4$s" placeholder="%5$s">', esc_attr( $id ), esc_html( $label ), $input_type, esc_attr( $value ), esc_attr( $extra ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</p>';
	}
	echo '</div>';
}

function dh_car_save_meta( $post_id ) {
	if ( ! isset( $_POST['dh_car_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['dh_car_nonce'] ), 'dh_save_car' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( dh_car_fields() as $key => $field ) {
		$name = 'dh_' . $key;
		$raw  = isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		switch ( $field[1] ) {
			case 'number':
				$value = '' === $raw ? '' : absint( $raw );
				break;
			case 'decimal':
				$value = '' === $raw ? '' : min( 5, max( 0, round( (float) $raw, 1 ) ) );
				break;
			case 'select':
				$value = in_array( $raw, $field[2], true ) ? $raw : $field[2][0];
				break;
			case 'url':
				$value = esc_url_raw( $raw );
				break;
			case 'textarea':
				$value = sanitize_textarea_field( $raw );
				break;
			case 'checkbox':
				$value = $raw ? '1' : '';
				break;
			default:
				$value = sanitize_text_field( $raw );
		}
		update_post_meta( $post_id, '_dh_' . $key, $value );
	}
}
add_action( 'save_post_car', 'dh_car_save_meta' );

/**
 * Admin list columns for cars.
 */
add_filter(
	'manage_car_posts_columns',
	function ( $cols ) {
		$new = array();
		foreach ( $cols as $k => $v ) {
			if ( 'title' === $k ) {
				$new['dh_thumb'] = '';
			}
			$new[ $k ] = $v;
			if ( 'title' === $k ) {
				$new['dh_price'] = __( 'Price / day', 'crestway' );
				$new['dh_specs'] = __( 'Specs', 'crestway' );
			}
		}
		return $new;
	}
);

add_action(
	'manage_car_posts_custom_column',
	function ( $col, $post_id ) {
		if ( 'dh_thumb' === $col ) {
			printf( '<img src="%s" style="width:72px;height:46px;object-fit:cover;border-radius:6px" alt="">', esc_url( dh_car_image( $post_id, 'thumbnail' ), array( 'http', 'https', 'data' ) ) );
		} elseif ( 'dh_price' === $col ) {
			echo esc_html( dh_price( dh_meta( $post_id, 'price', 0 ) ) );
		} elseif ( 'dh_specs' === $col ) {
			echo esc_html( sprintf( '%s · %s · %s seats', dh_meta( $post_id, 'transmission', '—' ), dh_meta( $post_id, 'fuel', '—' ), dh_meta( $post_id, 'seats', '—' ) ) );
		}
	},
	10,
	2
);

add_action( 'admin_head', fn() => print( '<style>.column-dh_thumb{width:84px}</style>' ) );
