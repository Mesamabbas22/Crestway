<?php
/**
 * Single car with booking form.
 *
 * @package Crestway
 */

get_header();

while ( have_posts() ) :
	the_post();
	$car_id   = get_the_ID();
	$price    = (float) dh_meta( $car_id, 'price', 0 );
	$rating   = dh_meta( $car_id, 'rating' );
	$type     = dh_car_type_name( $car_id );
	$features = array_filter( array_map( 'trim', explode( "\n", (string) dh_meta( $car_id, 'features' ) ) ) );
	$f        = dh_fleet_filters();
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	$status = isset( $_GET['booking'] ) ? sanitize_key( $_GET['booking'] ) : '';
	$ref    = isset( $_GET['ref'] ) ? sanitize_text_field( wp_unslash( $_GET['ref'] ) ) : '';
	// phpcs:enable
	$errors = array(
		'missing' => __( 'Please fill in all required fields with a valid email.', 'crestway' ),
		'dates'   => __( 'Please choose a pickup date from today and a return date after pickup.', 'crestway' ),
		'expired' => __( 'Your session expired. Please submit the form again.', 'crestway' ),
		'invalid' => __( 'Something went wrong. Please try again.', 'crestway' ),
	);

	dh_page_hero(
		get_the_title(),
		'',
		array(
			__( 'Fleet', 'crestway' ) => get_post_type_archive_link( 'car' ),
			get_the_title()           => '',
		)
	);
	?>

	<section class="section section--tight">
		<div class="container single-layout">

			<div>
				<div class="single-media">
					<?php echo dh_car_img_tag( $car_id, 'dh-hero', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>

				<div class="single-head">
					<div>
						<h2 style="margin-bottom:8px"><?php the_title(); ?></h2>
						<div class="meta-row">
							<?php if ( $type ) : ?>
								<span class="badge"><?php echo esc_html( $type ); ?></span>
							<?php endif; ?>
							<?php if ( $rating ) : ?>
								<span class="rating"><?php dh_the_icon( 'star', 16 ); ?><?php echo esc_html( number_format( (float) $rating, 1 ) ); ?> <span style="color:var(--c-muted);font-weight:500">(<?php echo esc_html( 40 + ( $car_id % 7 ) * 13 ); ?> <?php esc_html_e( 'reviews', 'crestway' ); ?>)</span></span>
							<?php endif; ?>
							<?php if ( dh_meta( $car_id, 'year' ) ) : ?>
								<span><?php dh_the_icon( 'calendar', 15 ); ?> <?php echo esc_html( dh_meta( $car_id, 'year' ) ); ?></span>
							<?php endif; ?>
						</div>
					</div>
					<div class="price"><?php echo esc_html( dh_price( $price ) ); ?><small> / <?php esc_html_e( 'day', 'crestway' ); ?></small></div>
				</div>

				<div class="spec-grid">
					<?php
					$specs = array(
						array( 'users', __( 'Seats', 'crestway' ), dh_meta( $car_id, 'seats', 5 ) ),
						array( 'gear', __( 'Transmission', 'crestway' ), dh_meta( $car_id, 'transmission', 'Automatic' ) ),
						array( 'Electric' === dh_meta( $car_id, 'fuel' ) ? 'zap' : 'fuel', __( 'Fuel', 'crestway' ), dh_meta( $car_id, 'fuel', 'Petrol' ) ),
						array( 'bag', __( 'Luggage', 'crestway' ), dh_meta( $car_id, 'bags', 2 ) . ' ' . __( 'bags', 'crestway' ) ),
						array( 'door', __( 'Doors', 'crestway' ), dh_meta( $car_id, 'doors', 4 ) ),
						array( 'gauge', __( 'Mileage', 'crestway' ), dh_meta( $car_id, 'mileage', 'Unlimited' ) ),
						array( 'snow', __( 'Air con', 'crestway' ), __( 'Climate', 'crestway' ) ),
						array( 'shield', __( 'Insurance', 'crestway' ), __( 'Included', 'crestway' ) ),
					);
					foreach ( $specs as $s ) :
						?>
						<div class="spec">
							<span class="icon-wrap"><?php dh_the_icon( $s[0], 20 ); ?></span>
							<div><small><?php echo esc_html( $s[1] ); ?></small><strong><?php echo esc_html( $s[2] ); ?></strong></div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="content-card">
					<h2><?php esc_html_e( 'About this car', 'crestway' ); ?></h2>
					<div class="car-description"><?php the_content(); ?></div>
				</div>

				<?php if ( $features ) : ?>
					<div class="content-card">
						<h2><?php esc_html_e( 'Features', 'crestway' ); ?></h2>
						<ul class="list-reset check-list">
							<?php foreach ( $features as $feat ) : ?>
								<li><span class="tick"><?php dh_the_icon( 'check', 14 ); ?></span><?php echo esc_html( $feat ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<div class="content-card">
					<h2><?php esc_html_e( 'Rental policies', 'crestway' ); ?></h2>
					<ul class="list-reset check-list">
						<li><span class="tick"><?php dh_the_icon( 'check', 14 ); ?></span><?php esc_html_e( 'Free cancellation up to 24h', 'crestway' ); ?></li>
						<li><span class="tick"><?php dh_the_icon( 'check', 14 ); ?></span><?php esc_html_e( 'Minimum driver age: 21', 'crestway' ); ?></li>
						<li><span class="tick"><?php dh_the_icon( 'check', 14 ); ?></span><?php esc_html_e( 'Full-to-full fuel policy', 'crestway' ); ?></li>
						<li><span class="tick"><?php dh_the_icon( 'check', 14 ); ?></span><?php esc_html_e( '24/7 roadside assistance', 'crestway' ); ?></li>
					</ul>
				</div>
			</div>

			<aside id="book">
				<div class="booking-card">
					<div class="booking-top">
						<div>
							<small><?php esc_html_e( 'Starting from', 'crestway' ); ?></small>
							<div class="price"><?php echo esc_html( dh_price( $price ) ); ?><small> / <?php esc_html_e( 'day', 'crestway' ); ?></small></div>
						</div>
						<span class="badge"><?php dh_the_icon( 'check', 14 ); ?> <?php esc_html_e( 'Available', 'crestway' ); ?></span>
					</div>

					<form class="booking-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-booking data-rate="<?php echo esc_attr( $price ); ?>">
						<?php if ( 'success' === $status ) : ?>
							<div class="notice notice--success" role="status"><?php dh_the_icon( 'check', 20 ); ?><div><strong><?php esc_html_e( 'Booking request received!', 'crestway' ); ?></strong><?php /* translators: %s: reference. */ echo esc_html( sprintf( __( 'Reference %s — we’ll confirm by email shortly.', 'crestway' ), $ref ) ); ?></div></div>
						<?php elseif ( isset( $errors[ $status ] ) ) : ?>
							<div class="notice notice--error" role="alert"><?php dh_the_icon( 'x', 20 ); ?><div><?php echo esc_html( $errors[ $status ] ); ?></div></div>
						<?php endif; ?>

						<input type="hidden" name="action" value="dh_booking">
						<input type="hidden" name="car_id" value="<?php echo esc_attr( $car_id ); ?>">
						<?php wp_nonce_field( 'dh_booking', 'dh_booking_nonce' ); ?>
						<div class="screen-reader-text" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

						<div class="field">
							<label for="b-location"><?php esc_html_e( 'Pickup location', 'crestway' ); ?></label>
							<div class="input-icon">
								<?php dh_the_icon( 'pin', 18 ); ?>
								<select id="b-location" name="location">
									<?php foreach ( dh_locations() as $loc ) : ?>
										<option value="<?php echo esc_attr( $loc ); ?>" <?php selected( $f['location'], $loc ); ?>><?php echo esc_html( $loc ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="grid-2">
							<div class="field">
								<label for="b-pickup"><?php esc_html_e( 'Pickup', 'crestway' ); ?></label>
								<input type="date" id="b-pickup" name="pickup" data-date="pickup" value="<?php echo esc_attr( $f['pickup'] ); ?>" required>
							</div>
							<div class="field">
								<label for="b-return"><?php esc_html_e( 'Return', 'crestway' ); ?></label>
								<input type="date" id="b-return" name="return" data-date="return" value="<?php echo esc_attr( $f['return'] ); ?>" required>
							</div>
						</div>

						<div class="field">
							<span class="field-label"><?php esc_html_e( 'Extras', 'crestway' ); ?></span>
							<div class="extras">
								<?php foreach ( dh_extras() as $key => $extra ) : ?>
									<label class="extra">
										<span class="lbl"><input type="checkbox" name="extras[]" value="<?php echo esc_attr( $key ); ?>" data-extra="<?php echo esc_attr( $extra[1] ); ?>"><?php echo esc_html( $extra[0] ); ?></span>
										<em>+<?php echo esc_html( dh_price( $extra[1] ) ); ?>/<?php esc_html_e( 'day', 'crestway' ); ?></em>
									</label>
								<?php endforeach; ?>
							</div>
						</div>

						<div class="field">
							<label for="b-name"><?php esc_html_e( 'Full name', 'crestway' ); ?></label>
							<input type="text" id="b-name" name="name" autocomplete="name" required>
						</div>
						<div class="grid-2">
							<div class="field">
								<label for="b-email"><?php esc_html_e( 'Email', 'crestway' ); ?></label>
								<input type="email" id="b-email" name="email" autocomplete="email" required>
							</div>
							<div class="field">
								<label for="b-phone"><?php esc_html_e( 'Phone', 'crestway' ); ?></label>
								<input type="tel" id="b-phone" name="phone" autocomplete="tel" required>
							</div>
						</div>

						<div class="summary" aria-live="polite">
							<div><span><?php echo esc_html( dh_price( $price ) ); ?> × <span data-days>1</span> <?php esc_html_e( 'day(s)', 'crestway' ); ?></span><span data-base><?php echo esc_html( dh_price( $price ) ); ?></span></div>
							<div><span><?php esc_html_e( 'Extras', 'crestway' ); ?></span><span data-extras><?php echo esc_html( dh_price( 0 ) ); ?></span></div>
							<div class="total"><span><?php esc_html_e( 'Total', 'crestway' ); ?></span><span data-total><?php echo esc_html( dh_price( $price ) ); ?></span></div>
						</div>

						<button class="btn btn--primary btn--block" type="submit"><?php esc_html_e( 'Request booking', 'crestway' ); ?> <?php dh_the_icon( 'arrow', 18 ); ?></button>
						<p class="booking-note"><?php dh_the_icon( 'lock', 14 ); ?> <?php esc_html_e( 'No payment now · Free cancellation', 'crestway' ); ?></p>
					</form>
				</div>
			</aside>
		</div>
	</section>

	<?php
	$related = new WP_Query(
		array(
			'post_type'      => 'car',
			'posts_per_page' => 3,
			'post__not_in'   => array( $car_id ), // phpcs:ignore WordPress.VIP.PostsPerPage
			'orderby'        => 'rand',
		)
	);
	if ( $related->have_posts() ) :
		?>
		<section class="section section--white">
			<div class="container">
				<div class="section-head reveal">
					<span class="eyebrow"><?php esc_html_e( 'You may also like', 'crestway' ); ?></span>
					<h2><?php esc_html_e( 'Similar cars', 'crestway' ); ?></h2>
				</div>
				<div class="car-grid">
					<?php
					while ( $related->have_posts() ) :
						$related->the_post();
						get_template_part( 'template-parts/car-card' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	endif;
endwhile;

get_footer();
