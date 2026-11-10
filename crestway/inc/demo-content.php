<?php
/**
 * One-click demo content: runs on theme activation, or from Appearance → Crestway Demo.
 *
 * @package Crestway
 */

defined( 'ABSPATH' ) || exit;

function dh_demo_cars() {
	$img = function ( $id ) {
		return 'https://images.unsplash.com/photo-' . $id . '?auto=format&fit=crop&w=900&q=80';
	};
	return array(
		array( 'Tesla Model 3', 'electric', 89, 4.9, 2024, 5, 4, 2, 'Automatic', 'Electric', $img( '1560958089-b8a1929cea89' ), true, 'Silent, instant torque and up to 500 km of range. Supercharger access included so you can road-trip with zero range anxiety.', "Autopilot\nPanoramic glass roof\n15\" touchscreen\nPremium audio\nHeated seats\nSupercharger access" ),
		array( 'BMW 5 Series', 'sedan', 99, 4.8, 2023, 5, 4, 3, 'Automatic', 'Petrol', $img( '1555215695-3004980ad54e' ), true, 'The executive sedan that balances comfort and sporty handling — ideal for business trips and long highway drives.', "Leather interior\nApple CarPlay\nAdaptive cruise control\nParking sensors\nDual-zone climate\nAmbient lighting" ),
		array( 'Ford Mustang GT', 'sports', 129, 4.7, 2023, 4, 2, 2, 'Automatic', 'Petrol', $img( '1494976388531-d1058494cdd8' ), true, 'A 5.0L V8 icon. Turn every drive into an event with the unmistakable rumble of the Mustang GT.', "5.0L V8 engine\nLaunch control\nSport exhaust\nDigital cockpit\nRear camera\nBluetooth" ),
		array( 'Range Rover Sport', 'suv', 159, 4.8, 2024, 5, 4, 5, 'Automatic', 'Diesel', $img( '1519641471654-76ce0107ad1b' ), true, 'Commanding presence, luxurious cabin and real off-road capability. Perfect for family trips and mountain escapes.', "All-wheel drive\nTerrain response\nMeridian sound\n360° camera\nHeated & cooled seats\nPower tailgate" ),
		array( 'Mercedes-Benz S-Class', 'luxury', 199, 5.0, 2024, 5, 4, 3, 'Automatic', 'Hybrid', $img( '1618843479313-40f8afb4b4d8' ), true, 'The benchmark of luxury. Massage seats, whisper-quiet cabin and cutting-edge driver assistance for VIP arrivals.', "Massage seats\nBurmester 3D audio\nRear entertainment\nHead-up display\nAir suspension\nChauffeur available" ),
		array( 'Porsche 911 Carrera', 'sports', 259, 4.9, 2023, 4, 2, 1, 'Automatic', 'Petrol', $img( '1503376780353-7e6692767b70' ), true, 'Precision-engineered driving pleasure. A weekend with the 911 is the kind of memory that lasts.', "Sport Chrono package\nPDK gearbox\nBose audio\nSport seats\nApple CarPlay\nLED matrix lights" ),
		array( 'Toyota RAV4 Hybrid', 'suv', 69, 4.6, 2024, 5, 4, 4, 'Automatic', 'Hybrid', $img( '1568605117036-5fe5e7bab0b7' ), false, 'Spacious, reliable and incredibly fuel-efficient. The smart choice for city and countryside.', "Hybrid efficiency\nLane assist\nAndroid Auto\nRoof rails\nRear camera\nKeyless entry" ),
		array( 'Volkswagen Golf', 'hatchback', 45, 4.5, 2023, 5, 4, 2, 'Manual', 'Petrol', $img( '1541899481282-d53bffe3c35d' ), false, 'Compact, nimble and easy to park — the perfect city companion at an unbeatable daily rate.', "Fuel efficient\nBluetooth\nCruise control\nParking sensors\nUSB-C charging\nAir conditioning" ),
		array( 'Audi e-tron GT', 'electric', 179, 4.9, 2024, 4, 4, 2, 'Automatic', 'Electric', $img( '1606664515524-ed2f786a0bd6' ), false, 'Grand-touring performance with zero emissions. Stunning design meets 0–100 km/h in just 4.1 seconds.', "Quattro AWD\nFast charging\nVirtual cockpit\nBang & Olufsen audio\nMatrix LED\nSport seats" ),
	);
}

function dh_import_demo() {
	$types = array(
		'suv'       => 'SUV',
		'sedan'     => 'Sedan',
		'luxury'    => 'Luxury',
		'electric'  => 'Electric',
		'sports'    => 'Sports',
		'hatchback' => 'Hatchback',
	);
	foreach ( $types as $slug => $name ) {
		if ( ! term_exists( $slug, 'car_type' ) ) {
			wp_insert_term( $name, 'car_type', array( 'slug' => $slug ) );
		}
	}

	foreach ( dh_demo_cars() as $c ) {
		list( $title, $type, $price, $rating, $year, $seats, $doors, $bags, $trans, $fuel, $image, $featured, $desc, $features ) = $c;
		if ( get_page_by_path( sanitize_title( $title ), OBJECT, 'car' ) ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'    => 'car',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_excerpt' => $desc,
				'post_content' => '<p>' . $desc . '</p><p>Every rental includes basic insurance, 24/7 roadside assistance and a full tank / full charge on pickup. The car is professionally cleaned and sanitised before every trip.</p>',
			)
		);
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}
		wp_set_object_terms( $id, $type, 'car_type' );
		$meta = array(
			'price'        => $price,
			'rating'       => $rating,
			'year'         => $year,
			'seats'        => $seats,
			'doors'        => $doors,
			'bags'         => $bags,
			'transmission' => $trans,
			'fuel'         => $fuel,
			'mileage'      => 'Unlimited',
			'image_url'    => $image,
			'features'     => $features,
			'featured'     => $featured ? '1' : '',
		);
		foreach ( $meta as $k => $v ) {
			update_post_meta( $id, '_dh_' . $k, $v );
		}
	}

	// Pages.
	$pages = array(
		'home'    => array( 'Home', '' ),
		'about'   => array( 'About Us', '<!-- wp:heading --><h2>Rental made simple since 2015</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Crestway started with a simple idea: renting a car should be as easy as ordering a coffee. No hidden fees, no paperwork headaches — just great cars and friendly people.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Today we serve thousands of happy drivers every month across city, airport and railway locations, with a fleet ranging from efficient hatchbacks to luxury sedans and electric grand-tourers.</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3>Our promise</h3><!-- /wp:heading --><!-- wp:list --><ul><li>Transparent pricing — the price you see is the price you pay</li><li>Free cancellation up to 24 hours before pickup</li><li>Every car cleaned, inspected and sanitised before each trip</li><li>24/7 roadside assistance wherever you go</li></ul><!-- /wp:list -->' ),
		'contact' => array( 'Contact', '' ),
	);
	$ids   = array();
	foreach ( $pages as $slug => $p ) {
		$existing = get_page_by_path( $slug );
		$ids[ $slug ] = $existing ? $existing->ID : wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_name'    => $slug,
				'post_title'   => $p[0],
				'post_content' => $p[1],
			)
		);
	}
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $ids['home'] );

	// Primary menu.
	if ( ! wp_get_nav_menu_object( 'Crestway Main' ) ) {
		$menu_id = wp_create_nav_menu( 'Crestway Main' );
		if ( ! is_wp_error( $menu_id ) ) {
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Home', 'menu-item-object' => 'page', 'menu-item-object-id' => $ids['home'], 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Our Fleet', 'menu-item-url' => home_url( '/cars/' ), 'menu-item-type' => 'custom', 'menu-item-status' => 'publish' ) );
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'About', 'menu-item-object' => 'page', 'menu-item-object-id' => $ids['about'], 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Contact', 'menu-item-object' => 'page', 'menu-item-object-id' => $ids['contact'], 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
			$locations            = get_theme_mod( 'nav_menu_locations', array() );
			$locations['primary'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	update_option( 'dh_demo_imported', 1 );
}

/**
 * On activation: register CPTs, import demo once, flush permalinks.
 */
function dh_on_activate() {
	dh_register_post_types();
	if ( ! get_option( 'dh_demo_imported' ) ) {
		dh_import_demo();
	}
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'dh_on_activate' );

/**
 * Appearance → Crestway Demo page (manual re-import).
 */
add_action(
	'admin_menu',
	function () {
		add_theme_page( __( 'Crestway Demo', 'crestway' ), __( 'Crestway Demo', 'crestway' ), 'manage_options', 'dh-demo', 'dh_demo_page' );
	}
);

function dh_demo_page() {
	if ( isset( $_POST['dh_demo_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['dh_demo_nonce'] ), 'dh_demo' ) && current_user_can( 'manage_options' ) ) {
		dh_import_demo();
		flush_rewrite_rules();
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Demo content imported. Existing items were skipped.', 'crestway' ) . '</p></div>';
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Crestway Demo Content', 'crestway' ); ?></h1>
		<p><?php esc_html_e( 'Creates sample cars, car types, Home / About / Contact pages and the main menu. Safe to run more than once.', 'crestway' ); ?></p>
		<form method="post">
			<?php wp_nonce_field( 'dh_demo', 'dh_demo_nonce' ); ?>
			<?php submit_button( __( 'Import demo content', 'crestway' ) ); ?>
		</form>
	</div>
	<?php
}
