<?php
/**
 * Car card used in grids.
 *
 * @package Crestway
 */

$car_id = get_the_ID();
$link   = get_permalink();
$f      = dh_fleet_filters();
if ( $f['pickup'] || $f['return'] || $f['location'] ) {
	$link = add_query_arg( array_filter( array( 'pickup' => $f['pickup'], 'return' => $f['return'], 'location' => $f['location'] ) ), $link );
}
$type   = dh_car_type_name( $car_id );
$rating = dh_meta( $car_id, 'rating' );
?>
<article class="car-card reveal">
	<a class="car-media" href="<?php echo esc_url( $link ); ?>" tabindex="-1" aria-hidden="true">
		<?php echo dh_car_img_tag( $car_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php if ( $type ) : ?>
			<span class="car-tag"><?php echo esc_html( $type ); ?></span>
		<?php endif; ?>
		<?php if ( $rating ) : ?>
			<span class="car-rating"><?php dh_the_icon( 'star', 13 ); ?><?php echo esc_html( number_format( (float) $rating, 1 ) ); ?></span>
		<?php endif; ?>
	</a>
	<div class="car-body">
		<div>
			<h3 class="car-title"><a href="<?php echo esc_url( $link ); ?>"><?php the_title(); ?></a></h3>
			<div class="car-sub"><?php echo esc_html( trim( dh_meta( $car_id, 'year' ) . ' · ' . dh_meta( $car_id, 'mileage', 'Unlimited' ) . ' ' . __( 'mileage', 'crestway' ), ' ·' ) ); ?></div>
		</div>
		<ul class="list-reset car-specs">
			<li><?php dh_the_icon( 'users', 17 ); ?><?php echo esc_html( dh_meta( $car_id, 'seats', 5 ) ); ?> <?php esc_html_e( 'Seats', 'crestway' ); ?></li>
			<li><?php dh_the_icon( 'gear', 17 ); ?><?php echo esc_html( dh_meta( $car_id, 'transmission', 'Automatic' ) ); ?></li>
			<li><?php dh_the_icon( 'Electric' === dh_meta( $car_id, 'fuel' ) ? 'zap' : 'fuel', 17 ); ?><?php echo esc_html( dh_meta( $car_id, 'fuel', 'Petrol' ) ); ?></li>
			<li><?php dh_the_icon( 'bag', 17 ); ?><?php echo esc_html( dh_meta( $car_id, 'bags', 2 ) ); ?> <?php esc_html_e( 'Bags', 'crestway' ); ?></li>
		</ul>
		<div class="car-foot">
			<div class="price"><?php echo esc_html( dh_price( dh_meta( $car_id, 'price', 0 ) ) ); ?><small> / <?php esc_html_e( 'day', 'crestway' ); ?></small></div>
			<a class="btn btn--dark btn--sm" href="<?php echo esc_url( $link ); ?>"><?php esc_html_e( 'Rent now', 'crestway' ); ?> <?php dh_the_icon( 'arrow', 16 ); ?></a>
		</div>
	</div>
</article>
