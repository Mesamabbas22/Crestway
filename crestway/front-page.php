<?php
/**
 * Homepage.
 *
 * @package Crestway
 */

get_header();

$fleet_url = get_post_type_archive_link( 'car' );
$car_count = (int) wp_count_posts( 'car' )->publish;
$types     = get_terms( array( 'taxonomy' => 'car_type', 'hide_empty' => false, 'number' => 6 ) );
$featured  = new WP_Query(
	array(
		'post_type'      => 'car',
		'posts_per_page' => 6,
		'meta_key'       => '_dh_featured', // phpcs:ignore WordPress.DB.SlowDBQuery
		'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
	)
);
if ( ! $featured->have_posts() ) {
	$featured = new WP_Query( array( 'post_type' => 'car', 'posts_per_page' => 6 ) );
}
?>

<!-- HERO -->
<section class="hero">
	<div class="hero-bg" style="background-image:url('<?php echo esc_url( dh_opt( 'dh_hero_image' ) ); ?>')"></div>
	<div class="container">
		<div class="hero-content">
			<span class="hero-badge"><b><?php esc_html_e( 'NEW', 'crestway' ); ?></b><?php echo esc_html( dh_opt( 'dh_hero_badge' ) ); ?></span>
			<h1><?php echo esc_html( dh_opt( 'dh_hero_title' ) ); ?></h1>
			<p class="hero-lead"><?php echo esc_html( dh_opt( 'dh_hero_subtitle' ) ); ?></p>
			<div class="hero-cta">
				<a class="btn btn--primary" href="<?php echo esc_url( $fleet_url ); ?>"><?php esc_html_e( 'Explore Fleet', 'crestway' ); ?> <?php dh_the_icon( 'arrow', 18 ); ?></a>
				<a class="btn btn--light" href="#how-it-works"><?php esc_html_e( 'How it works', 'crestway' ); ?></a>
			</div>
			<div class="hero-trust">
				<div><strong><?php echo esc_html( max( $car_count, 1 ) * 25 ); ?>+</strong><span><?php esc_html_e( 'Cars available', 'crestway' ); ?></span></div>
				<div><strong>4.9★</strong><span><?php esc_html_e( 'Customer rating', 'crestway' ); ?></span></div>
				<div><strong>24/7</strong><span><?php esc_html_e( 'Roadside support', 'crestway' ); ?></span></div>
			</div>
		</div>
	</div>
</section>

<!-- SEARCH -->
<div class="container search-wrap">
	<form class="search-card" action="<?php echo esc_url( $fleet_url ); ?>" method="get">
		<div class="search-tabs" aria-hidden="true">
			<span class="is-active"><?php esc_html_e( 'Self drive', 'crestway' ); ?></span>
			<span><?php esc_html_e( 'Find your perfect car', 'crestway' ); ?></span>
		</div>
		<div class="search-grid">
			<div class="field">
				<label for="s-location"><?php esc_html_e( 'Pickup location', 'crestway' ); ?></label>
				<div class="input-icon">
					<?php dh_the_icon( 'pin', 18 ); ?>
					<select id="s-location" name="location">
						<?php foreach ( dh_locations() as $loc ) : ?>
							<option value="<?php echo esc_attr( $loc ); ?>"><?php echo esc_html( $loc ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="field">
				<label for="s-pickup"><?php esc_html_e( 'Pickup date', 'crestway' ); ?></label>
				<input type="date" id="s-pickup" name="pickup" data-date="pickup" required>
			</div>
			<div class="field">
				<label for="s-return"><?php esc_html_e( 'Return date', 'crestway' ); ?></label>
				<input type="date" id="s-return" name="return" data-date="return" required>
			</div>
			<div class="field">
				<label for="s-type"><?php esc_html_e( 'Car type', 'crestway' ); ?></label>
				<select id="s-type" name="ctype">
					<option value=""><?php esc_html_e( 'All types', 'crestway' ); ?></option>
					<?php if ( $types && ! is_wp_error( $types ) ) : ?>
						<?php foreach ( $types as $t ) : ?>
							<option value="<?php echo esc_attr( $t->slug ); ?>"><?php echo esc_html( $t->name ); ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<button class="btn btn--primary" type="submit"><?php dh_the_icon( 'search', 18 ); ?> <?php esc_html_e( 'Search cars', 'crestway' ); ?></button>
		</div>
	</form>
</div>

<!-- CATEGORIES -->
<?php if ( $types && ! is_wp_error( $types ) ) : ?>
<section class="section section--tight">
	<div class="container">
		<div class="section-head reveal">
			<span class="eyebrow"><?php esc_html_e( 'Browse by type', 'crestway' ); ?></span>
			<h2><?php esc_html_e( 'A car for every journey', 'crestway' ); ?></h2>
		</div>
		<div class="cat-grid">
			<?php foreach ( $types as $t ) : ?>
				<a class="cat-card reveal" href="<?php echo esc_url( get_term_link( $t ) ); ?>">
					<span class="cat-icon"><?php dh_the_icon( dh_type_icon( $t->slug ), 26 ); ?></span>
					<?php echo esc_html( $t->name ); ?>
					<?php /* translators: %d: number of cars. */ ?>
					<small><?php echo esc_html( sprintf( _n( '%d car', '%d cars', $t->count, 'crestway' ), $t->count ) ); ?></small>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- FEATURED FLEET -->
<section class="section section--white">
	<div class="container">
		<div class="fleet-toolbar reveal" style="margin-bottom:48px;align-items:flex-end">
			<div class="section-head section-head--left" style="margin-bottom:0">
				<span class="eyebrow"><?php esc_html_e( 'Popular picks', 'crestway' ); ?></span>
				<h2><?php esc_html_e( 'Our most booked cars', 'crestway' ); ?></h2>
				<p><?php esc_html_e( 'Hand-picked, recently serviced and ready for the road.', 'crestway' ); ?></p>
			</div>
			<a class="btn btn--ghost" href="<?php echo esc_url( $fleet_url ); ?>"><?php esc_html_e( 'View all cars', 'crestway' ); ?> <?php dh_the_icon( 'arrow', 18 ); ?></a>
		</div>

		<?php if ( $featured->have_posts() ) : ?>
			<div class="car-grid">
				<?php
				while ( $featured->have_posts() ) :
					$featured->the_post();
					get_template_part( 'template-parts/car-card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<div class="empty-state">
				<div class="icon-wrap"><?php dh_the_icon( 'car', 30 ); ?></div>
				<h3><?php esc_html_e( 'No cars yet', 'crestway' ); ?></h3>
				<p><?php esc_html_e( 'Add cars under Fleet → Add New, or import demo content from Appearance → Crestway Demo.', 'crestway' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- HOW IT WORKS -->
<section class="section" id="how-it-works">
	<div class="container">
		<div class="section-head reveal">
			<span class="eyebrow"><?php esc_html_e( 'How it works', 'crestway' ); ?></span>
			<h2><?php esc_html_e( 'Rent a car in 3 easy steps', 'crestway' ); ?></h2>
			<p><?php esc_html_e( 'No queues, no paperwork. Everything happens online in minutes.', 'crestway' ); ?></p>
		</div>
		<div class="steps">
			<div class="step reveal">
				<div class="step-icon"><?php dh_the_icon( 'search', 26 ); ?></div>
				<h3><?php esc_html_e( 'Choose your car', 'crestway' ); ?></h3>
				<p><?php esc_html_e( 'Filter by type, price, seats or fuel and pick the car that fits your trip.', 'crestway' ); ?></p>
			</div>
			<div class="step reveal">
				<div class="step-icon"><?php dh_the_icon( 'calendar', 26 ); ?></div>
				<h3><?php esc_html_e( 'Pick dates & book', 'crestway' ); ?></h3>
				<p><?php esc_html_e( 'Select pickup location and dates — see the full price instantly, no hidden fees.', 'crestway' ); ?></p>
			</div>
			<div class="step reveal">
				<div class="step-icon"><?php dh_the_icon( 'key', 26 ); ?></div>
				<h3><?php esc_html_e( 'Grab keys & go', 'crestway' ); ?></h3>
				<p><?php esc_html_e( 'Show your licence at pickup, get the keys and enjoy the open road.', 'crestway' ); ?></p>
			</div>
		</div>
	</div>
</section>

<!-- WHY US -->
<section class="section section--white">
	<div class="container why-grid">
		<div class="why-media reveal">
			<img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=1000&q=80" alt="<?php esc_attr_e( 'Car driving on an open road', 'crestway' ); ?>" loading="lazy" onerror="this.onerror=null;this.src='<?php echo esc_attr( dh_placeholder_image() ); ?>'">
			<div class="why-float">
				<span class="icon-wrap"><?php dh_the_icon( 'shield', 24 ); ?></span>
				<div><strong><?php esc_html_e( 'Fully insured', 'crestway' ); ?></strong><span><?php esc_html_e( 'Every rental, every time', 'crestway' ); ?></span></div>
			</div>
		</div>
		<div class="reveal">
			<span class="eyebrow"><?php esc_html_e( 'Why choose us', 'crestway' ); ?></span>
			<h2><?php esc_html_e( 'More than just a rental — a better way to travel', 'crestway' ); ?></h2>
			<p><?php esc_html_e( 'We obsess over the details so you can focus on the journey.', 'crestway' ); ?></p>
			<div class="feature-list">
				<div class="feature">
					<span class="icon-wrap"><?php dh_the_icon( 'wallet', 24 ); ?></span>
					<div><h3><?php esc_html_e( 'Best price guarantee', 'crestway' ); ?></h3><p><?php esc_html_e( 'Found it cheaper? We’ll match the price — no questions asked.', 'crestway' ); ?></p></div>
				</div>
				<div class="feature">
					<span class="icon-wrap"><?php dh_the_icon( 'clock', 24 ); ?></span>
					<div><h3><?php esc_html_e( 'Free cancellation', 'crestway' ); ?></h3><p><?php esc_html_e( 'Plans changed? Cancel for free up to 24 hours before pickup.', 'crestway' ); ?></p></div>
				</div>
				<div class="feature">
					<span class="icon-wrap"><?php dh_the_icon( 'headset', 24 ); ?></span>
					<div><h3><?php esc_html_e( '24/7 customer support', 'crestway' ); ?></h3><p><?php esc_html_e( 'Real humans ready to help, day or night, wherever you are.', 'crestway' ); ?></p></div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- STATS -->
<section class="section section--dark section--tight">
	<div class="container stats">
		<div class="stat reveal"><strong><span data-count="12">0</span><em>k+</em></strong><span><?php esc_html_e( 'Happy customers', 'crestway' ); ?></span></div>
		<div class="stat reveal"><strong><span data-count="<?php echo esc_attr( max( $car_count, 1 ) * 25 ); ?>">0</span><em>+</em></strong><span><?php esc_html_e( 'Cars in fleet', 'crestway' ); ?></span></div>
		<div class="stat reveal"><strong><span data-count="<?php echo esc_attr( count( dh_locations() ) ); ?>">0</span></strong><span><?php esc_html_e( 'Pickup locations', 'crestway' ); ?></span></div>
		<div class="stat reveal"><strong><span data-count="10">0</span><em>yrs</em></strong><span><?php esc_html_e( 'Of experience', 'crestway' ); ?></span></div>
	</div>
</section>

<!-- TESTIMONIALS -->
<section class="section">
	<div class="container">
		<div class="section-head reveal">
			<span class="eyebrow"><?php esc_html_e( 'Testimonials', 'crestway' ); ?></span>
			<h2><?php esc_html_e( 'Loved by thousands of drivers', 'crestway' ); ?></h2>
		</div>
		<div class="testi-grid">
			<?php
			$testimonials = array(
				array( 'Sarah Mitchell', 'Business traveller', 'Booking took less than a minute and the car was spotless. Pickup at the airport was seamless — this is now my go-to rental.' ),
				array( 'James Carter', 'Weekend road-tripper', 'Rented the Mustang for a coastal drive. Transparent price, no surprise charges at return. Absolutely loved it!' ),
				array( 'Aisha Khan', 'Family vacation', 'The SUV had plenty of room for our family of five. Support team helped us extend the booking at midnight. Brilliant service.' ),
			);
			foreach ( $testimonials as $t ) :
				$initials = implode( '', array_map( fn( $w ) => $w[0], explode( ' ', $t[0] ) ) );
				?>
				<figure class="testi reveal" style="margin:0">
					<div class="testi-stars"><?php echo str_repeat( dh_icon( 'star', 18 ), 5 ); // phpcs:ignore ?></div>
					<blockquote>“<?php echo esc_html( $t[2] ); ?>”</blockquote>
					<figcaption class="testi-author">
						<span class="avatar"><?php echo esc_html( $initials ); ?></span>
						<div><strong><?php echo esc_html( $t[0] ); ?></strong><span><?php echo esc_html( $t[1] ); ?></span></div>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- FAQ -->
<section class="section section--white">
	<div class="container faq-wrap">
		<div class="reveal">
			<span class="eyebrow"><?php esc_html_e( 'FAQ', 'crestway' ); ?></span>
			<h2><?php esc_html_e( 'Questions? We’ve got answers', 'crestway' ); ?></h2>
			<p><?php esc_html_e( 'Can’t find what you’re looking for? Our team is one call away.', 'crestway' ); ?></p>
			<a class="btn btn--dark" href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ?: home_url( '/' ) ); ?>"><?php dh_the_icon( 'headset', 18 ); ?> <?php esc_html_e( 'Contact support', 'crestway' ); ?></a>
		</div>
		<div class="faq reveal">
			<?php
			$faqs = array(
				array( 'What do I need to rent a car?', 'A valid driving licence held for at least 1 year, a national ID or passport, and a credit/debit card in the driver’s name.' ),
				array( 'Is insurance included?', 'Yes. Every rental includes basic insurance and 24/7 roadside assistance. You can add full insurance for zero excess during booking.' ),
				array( 'Can I cancel or change my booking?', 'Absolutely. Cancellation is free up to 24 hours before pickup, and you can change dates any time subject to availability.' ),
				array( 'Is there a mileage limit?', 'Most of our cars come with unlimited mileage. Any limit is clearly shown on the car’s page before you book.' ),
				array( 'Can someone else drive the car?', 'Yes — add an additional driver during booking. They’ll need to show their licence at pickup.' ),
			);
			foreach ( $faqs as $i => $faq ) :
				?>
				<details <?php echo 0 === $i ? 'open' : ''; ?>>
					<summary><?php echo esc_html( $faq[0] ); ?><span class="faq-plus"><?php dh_the_icon( 'plus', 16 ); ?></span></summary>
					<p><?php echo esc_html( $faq[1] ); ?></p>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- CTA -->
<section class="section">
	<div class="container">
		<div class="cta-band reveal">
			<div>
				<h2><?php esc_html_e( 'Ready to hit the road?', 'crestway' ); ?></h2>
				<p><?php esc_html_e( 'Book today and get 10% off your first rental. Free cancellation included.', 'crestway' ); ?></p>
			</div>
			<div class="cta-actions">
				<a class="btn btn--dark" href="<?php echo esc_url( $fleet_url ); ?>"><?php esc_html_e( 'Browse cars', 'crestway' ); ?> <?php dh_the_icon( 'arrow', 18 ); ?></a>
				<a class="btn btn--light" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', dh_opt( 'dh_phone' ) ) ); ?>"><?php dh_the_icon( 'phone', 18 ); ?> <?php esc_html_e( 'Call us', 'crestway' ); ?></a>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
