<?php
/**
 * Custom post types & taxonomies.
 *
 * @package Crestway
 */

defined( 'ABSPATH' ) || exit;

function dh_register_post_types() {
	register_post_type(
		'car',
		array(
			'labels'        => array(
				'name'               => __( 'Cars', 'crestway' ),
				'singular_name'      => __( 'Car', 'crestway' ),
				'add_new_item'       => __( 'Add New Car', 'crestway' ),
				'edit_item'          => __( 'Edit Car', 'crestway' ),
				'all_items'          => __( 'All Cars', 'crestway' ),
				'menu_name'          => __( 'Fleet', 'crestway' ),
				'not_found'          => __( 'No cars found', 'crestway' ),
				'featured_image'     => __( 'Car photo', 'crestway' ),
				'set_featured_image' => __( 'Set car photo', 'crestway' ),
			),
			'public'        => true,
			'has_archive'   => 'cars',
			'rewrite'       => array( 'slug' => 'car', 'with_front' => false ),
			'menu_icon'     => 'dashicons-car',
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'show_in_rest'  => true,
		)
	);

	register_taxonomy(
		'car_type',
		'car',
		array(
			'labels'            => array(
				'name'          => __( 'Car Types', 'crestway' ),
				'singular_name' => __( 'Car Type', 'crestway' ),
				'add_new_item'  => __( 'Add New Type', 'crestway' ),
			),
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'cars/type', 'with_front' => false ),
		)
	);

	register_post_type(
		'dh_booking',
		array(
			'labels'          => array(
				'name'          => __( 'Bookings', 'crestway' ),
				'singular_name' => __( 'Booking', 'crestway' ),
				'edit_item'     => __( 'Booking Details', 'crestway' ),
				'all_items'     => __( 'Bookings', 'crestway' ),
				'not_found'     => __( 'No bookings yet', 'crestway' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => 'edit.php?post_type=car',
			'supports'        => array( 'title' ),
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'dh_register_post_types' );
