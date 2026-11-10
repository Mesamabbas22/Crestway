<?php
/**
 * Booking + contact form handling and booking admin.
 *
 * @package Crestway
 */

defined( 'ABSPATH' ) || exit;

function dh_booking_statuses() {
	return array(
		'pending'   => __( 'Pending', 'crestway' ),
		'confirmed' => __( 'Confirmed', 'crestway' ),
		'completed' => __( 'Completed', 'crestway' ),
		'cancelled' => __( 'Cancelled', 'crestway' ),
	);
}

/**
 * Number of rental days between two Y-m-d dates (minimum 1).
 */
function dh_rental_days( $from, $to ) {
	$a = new DateTime( $from );
	$b = new DateTime( $to );
	return max( 1, (int) $a->diff( $b )->days );
}

/**
 * Handle booking form POST.
 */
function dh_handle_booking() {
	$car_id   = isset( $_POST['car_id'] ) ? absint( $_POST['car_id'] ) : 0;
	$back     = $car_id ? get_permalink( $car_id ) : home_url( '/' );
	$fail     = function ( $code ) use ( $back ) {
		wp_safe_redirect( add_query_arg( 'booking', $code, $back ) . '#book' );
		exit;
	};

	if ( ! isset( $_POST['dh_booking_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['dh_booking_nonce'] ), 'dh_booking' ) ) {
		$fail( 'expired' );
	}
	// Honeypot.
	if ( ! empty( $_POST['website'] ) ) {
		$fail( 'invalid' );
	}
	if ( ! $car_id || 'car' !== get_post_type( $car_id ) || 'publish' !== get_post_status( $car_id ) ) {
		$fail( 'invalid' );
	}

	$name     = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone    = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$location = isset( $_POST['location'] ) ? sanitize_text_field( wp_unslash( $_POST['location'] ) ) : '';
	$pickup   = isset( $_POST['pickup'] ) ? dh_valid_date( sanitize_text_field( wp_unslash( $_POST['pickup'] ) ) ) : '';
	$return   = isset( $_POST['return'] ) ? dh_valid_date( sanitize_text_field( wp_unslash( $_POST['return'] ) ) ) : '';
	$extras   = isset( $_POST['extras'] ) ? array_map( 'sanitize_key', (array) wp_unslash( $_POST['extras'] ) ) : array();
	$extras   = array_values( array_intersect( $extras, array_keys( dh_extras() ) ) );

	if ( ! $name || ! is_email( $email ) || ! $phone || ! $pickup || ! $return ) {
		$fail( 'missing' );
	}
	if ( ! in_array( $location, dh_locations(), true ) ) {
		$location = dh_locations()[0] ?? '';
	}
	$today = wp_date( 'Y-m-d' );
	if ( $pickup < $today || $return <= $pickup ) {
		$fail( 'dates' );
	}

	// Price is always calculated server-side.
	$days       = dh_rental_days( $pickup, $return );
	$rate       = (float) dh_meta( $car_id, 'price', 0 );
	$extras_day = 0;
	foreach ( $extras as $key ) {
		$extras_day += dh_extras()[ $key ][1];
	}
	$total = ( $rate + $extras_day ) * $days;

	$booking_id = wp_insert_post(
		array(
			'post_type'   => 'dh_booking',
			'post_status' => 'publish',
			'post_title'  => sprintf( '%s — %s', $name, get_the_title( $car_id ) ),
		),
		true
	);
	if ( is_wp_error( $booking_id ) ) {
		$fail( 'invalid' );
	}

	$meta = compact( 'car_id', 'name', 'email', 'phone', 'location', 'pickup', 'return', 'extras', 'days', 'total' );
	$meta['status'] = 'pending';
	foreach ( $meta as $k => $v ) {
		update_post_meta( $booking_id, '_dhb_' . $k, $v );
	}

	$ref = 'DH-' . str_pad( (string) $booking_id, 5, '0', STR_PAD_LEFT );

	// Notifications.
	$lines = array(
		sprintf( 'Reference: %s', $ref ),
		sprintf( 'Car: %s', get_the_title( $car_id ) ),
		sprintf( 'Customer: %s (%s, %s)', $name, $email, $phone ),
		sprintf( 'Pickup: %s at %s', $pickup, $location ),
		sprintf( 'Return: %s', $return ),
		sprintf( 'Duration: %d day(s)', $days ),
		sprintf( 'Total: %s', dh_price( $total ) ),
	);
	$site = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	wp_mail( get_option( 'admin_email' ), sprintf( '[%s] New booking %s', $site, $ref ), implode( "\n", $lines ) );
	wp_mail( $email, sprintf( '%s — booking request received (%s)', $site, $ref ), "Hi {$name},\n\nThanks for choosing us! We received your booking request and will confirm it shortly.\n\n" . implode( "\n", $lines ) );

	wp_safe_redirect( add_query_arg( array( 'booking' => 'success', 'ref' => $ref ), $back ) . '#book' );
	exit;
}
add_action( 'admin_post_dh_booking', 'dh_handle_booking' );
add_action( 'admin_post_nopriv_dh_booking', 'dh_handle_booking' );

/**
 * Handle contact form POST.
 */
function dh_handle_contact() {
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$back = remove_query_arg( 'sent', $back );

	if ( ! isset( $_POST['dh_contact_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['dh_contact_nonce'] ), 'dh_contact' ) || ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'sent', 'error', $back ) );
		exit;
	}
	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( ! $name || ! is_email( $email ) || ! $message ) {
		wp_safe_redirect( add_query_arg( 'sent', 'error', $back ) );
		exit;
	}

	wp_mail(
		get_option( 'admin_email' ),
		sprintf( '[Contact] %s', $subject ? $subject : $name ),
		"From: {$name} <{$email}>\n\n{$message}",
		array( 'Reply-To: ' . $name . ' <' . $email . '>' )
	);
	wp_safe_redirect( add_query_arg( 'sent', '1', $back ) );
	exit;
}
add_action( 'admin_post_dh_contact', 'dh_handle_contact' );
add_action( 'admin_post_nopriv_dh_contact', 'dh_handle_contact' );

/* ------------------------------------------------------------------
 * Booking admin UI
 * ------------------------------------------------------------------ */

add_filter(
	'manage_dh_booking_posts_columns',
	function () {
		return array(
			'cb'         => '<input type="checkbox">',
			'title'      => __( 'Booking', 'crestway' ),
			'dhb_car'    => __( 'Car', 'crestway' ),
			'dhb_dates'  => __( 'Dates', 'crestway' ),
			'dhb_total'  => __( 'Total', 'crestway' ),
			'dhb_status' => __( 'Status', 'crestway' ),
			'date'       => __( 'Received', 'crestway' ),
		);
	}
);

add_action(
	'manage_dh_booking_posts_custom_column',
	function ( $col, $id ) {
		switch ( $col ) {
			case 'dhb_car':
				$car = (int) get_post_meta( $id, '_dhb_car_id', true );
				echo $car ? '<a href="' . esc_url( get_edit_post_link( $car ) ) . '">' . esc_html( get_the_title( $car ) ) . '</a>' : '—';
				break;
			case 'dhb_dates':
				echo esc_html( get_post_meta( $id, '_dhb_pickup', true ) . ' → ' . get_post_meta( $id, '_dhb_return', true ) );
				break;
			case 'dhb_total':
				echo esc_html( dh_price( get_post_meta( $id, '_dhb_total', true ) ) );
				break;
			case 'dhb_status':
				$s      = get_post_meta( $id, '_dhb_status', true ) ?: 'pending';
				$colors = array( 'pending' => '#b45309', 'confirmed' => '#15803d', 'completed' => '#1d4ed8', 'cancelled' => '#b91c1c' );
				printf( '<span style="padding:3px 10px;border-radius:99px;font-weight:600;color:#fff;background:%s">%s</span>', esc_attr( $colors[ $s ] ?? '#64748b' ), esc_html( dh_booking_statuses()[ $s ] ?? $s ) );
				break;
		}
	},
	10,
	2
);

add_action(
	'add_meta_boxes_dh_booking',
	function () {
		add_meta_box( 'dhb_details', __( 'Booking Details', 'crestway' ), 'dh_booking_meta_box', 'dh_booking', 'normal', 'high' );
	}
);

function dh_booking_meta_box( $post ) {
	$m = function ( $k ) use ( $post ) {
		return get_post_meta( $post->ID, '_dhb_' . $k, true );
	};
	$extras = (array) $m( 'extras' );
	$labels = array();
	foreach ( $extras as $e ) {
		if ( isset( dh_extras()[ $e ] ) ) {
			$labels[] = dh_extras()[ $e ][0];
		}
	}
	$rows = array(
		__( 'Reference', 'crestway' ) => 'DH-' . str_pad( (string) $post->ID, 5, '0', STR_PAD_LEFT ),
		__( 'Car', 'crestway' )       => get_the_title( (int) $m( 'car_id' ) ),
		__( 'Name', 'crestway' )      => $m( 'name' ),
		__( 'Email', 'crestway' )     => $m( 'email' ),
		__( 'Phone', 'crestway' )     => $m( 'phone' ),
		__( 'Location', 'crestway' )  => $m( 'location' ),
		__( 'Pickup', 'crestway' )    => $m( 'pickup' ),
		__( 'Return', 'crestway' )    => $m( 'return' ),
		__( 'Days', 'crestway' )      => $m( 'days' ),
		__( 'Extras', 'crestway' )    => $labels ? implode( ', ', $labels ) : '—',
		__( 'Total', 'crestway' )     => dh_price( $m( 'total' ) ),
	);
	wp_nonce_field( 'dh_save_booking', 'dh_booking_admin_nonce' );
	echo '<table class="widefat striped" style="border:0"><tbody>';
	foreach ( $rows as $label => $value ) {
		printf( '<tr><th style="width:160px">%s</th><td>%s</td></tr>', esc_html( $label ), esc_html( $value ) );
	}
	echo '<tr><th>' . esc_html__( 'Status', 'crestway' ) . '</th><td><select name="dhb_status">';
	foreach ( dh_booking_statuses() as $key => $label ) {
		printf( '<option value="%s"%s>%s</option>', esc_attr( $key ), selected( $m( 'status' ), $key, false ), esc_html( $label ) );
	}
	echo '</select></td></tr></tbody></table>';
}

add_action(
	'save_post_dh_booking',
	function ( $post_id ) {
		if ( ! isset( $_POST['dh_booking_admin_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['dh_booking_admin_nonce'] ), 'dh_save_booking' ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$status = isset( $_POST['dhb_status'] ) ? sanitize_key( $_POST['dhb_status'] ) : 'pending';
		if ( isset( dh_booking_statuses()[ $status ] ) ) {
			update_post_meta( $post_id, '_dhb_status', $status );
		}
	}
);
