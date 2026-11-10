<?php
/**
 * Fleet archive with filters (also used for car_type taxonomy).
 *
 * @package Crestway
 */

get_header();

global $wp_query;
$f         = dh_fleet_filters();
$fleet_url = get_post_type_archive_link( 'car' );
$term      = is_tax( 'car_type' ) ? get_queried_object() : null;
$action    = $term ? get_term_link( $term ) : $fleet_url;
$types     = get_terms( array( 'taxonomy' => 'car_type', 'hide_empty' => false ) );
$max_cap   = 300;

if ( $term ) {
	/* translators: %s: car type. */
	dh_page_hero( sprintf( __( '%s Cars', 'crestway' ), $term->name ), $term->description ? $term->description : __( 'Browse our hand-picked selection and book in seconds.', 'crestway' ), array( __( 'Fleet', 'crestway' ) => $fleet_url, $term->name => '' ) );
} else {
	dh_page_hero( __( 'Our Fleet', 'crestway' ), __( 'From efficient city cars to luxury sedans — find the perfect ride and book in under a minute.', 'crestway' ), array( __( 'Fleet', 'crestway' ) => '' ) );
}
?>

<section class="section section--tight">
	<div class="container fleet-layout">

		<form class="filter-card" id="filter-card" action="<?php echo esc_url( $action ); ?>" method="get">
			<h3><?php dh_the_icon( 'sliders', 20 ); ?> <?php esc_html_e( 'Filters', 'crestway' ); ?></h3>

			<?php foreach ( array( 'pickup', 'return', 'location', 'sort' ) as $keep ) : ?>
				<?php if ( $f[ $keep ] ) : ?>
					<input type="hidden" name="<?php echo esc_attr( $keep ); ?>" value="<?php echo esc_attr( $f[ $keep ] ); ?>">
				<?php endif; ?>
			<?php endforeach; ?>

			<?php if ( ! $term && $types && ! is_wp_error( $types ) ) : ?>
				<div class="filter-group">
					<span class="field-label"><?php esc_html_e( 'Car type', 'crestway' ); ?></span>
					<div class="chip-row">
						<label class="chip"><input type="radio" name="ctype" value="" <?php checked( $f['ctype'], '' ); ?>><span><?php esc_html_e( 'All', 'crestway' ); ?></span></label>
						<?php foreach ( $types as $t ) : ?>
							<label class="chip"><input type="radio" name="ctype" value="<?php echo esc_attr( $t->slug ); ?>" <?php checked( $f['ctype'], $t->slug ); ?>><span><?php echo esc_html( $t->name ); ?></span></label>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="filter-group">
				<div class="range-row">
					<span class="field-label"><?php esc_html_e( 'Max price / day', 'crestway' ); ?></span>
					<output id="price-out"><?php echo esc_html( dh_currency() . ( $f['max_price'] ? $f['max_price'] : $max_cap ) ); ?></output>
				</div>
				<input type="range" name="max_price" id="max_price" min="30" max="<?php echo esc_attr( $max_cap ); ?>" step="10" value="<?php echo esc_attr( $f['max_price'] ? $f['max_price'] : $max_cap ); ?>">
			</div>

			<div class="filter-group">
				<span class="field-label"><?php esc_html_e( 'Transmission', 'crestway' ); ?></span>
				<div class="chip-row">
					<label class="chip"><input type="radio" name="transmission" value="" <?php checked( $f['transmission'], '' ); ?>><span><?php esc_html_e( 'Any', 'crestway' ); ?></span></label>
					<?php foreach ( array( 'Automatic', 'Manual' ) as $opt ) : ?>
						<label class="chip"><input type="radio" name="transmission" value="<?php echo esc_attr( $opt ); ?>" <?php checked( $f['transmission'], $opt ); ?>><span><?php echo esc_html( $opt ); ?></span></label>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="filter-group">
				<span class="field-label"><?php esc_html_e( 'Fuel', 'crestway' ); ?></span>
				<div class="chip-row">
					<label class="chip"><input type="radio" name="fuel" value="" <?php checked( $f['fuel'], '' ); ?>><span><?php esc_html_e( 'Any', 'crestway' ); ?></span></label>
					<?php foreach ( dh_fuel_options() as $opt ) : ?>
						<label class="chip"><input type="radio" name="fuel" value="<?php echo esc_attr( $opt ); ?>" <?php checked( $f['fuel'], $opt ); ?>><span><?php echo esc_html( $opt ); ?></span></label>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="filter-group">
				<span class="field-label"><?php esc_html_e( 'Minimum seats', 'crestway' ); ?></span>
				<div class="chip-row">
					<?php foreach ( array( 0 => __( 'Any', 'crestway' ), 2 => '2+', 4 => '4+', 5 => '5+', 7 => '7+' ) as $val => $label ) : ?>
						<label class="chip"><input type="radio" name="seats" value="<?php echo esc_attr( $val ? $val : '' ); ?>" <?php checked( $f['seats'], $val ); ?>><span><?php echo esc_html( $label ); ?></span></label>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="filter-actions">
				<a class="btn btn--ghost btn--sm" href="<?php echo esc_url( $action ); ?>"><?php esc_html_e( 'Reset', 'crestway' ); ?></a>
				<button class="btn btn--primary btn--sm" type="submit"><?php esc_html_e( 'Apply', 'crestway' ); ?></button>
			</div>
		</form>

		<div>
			<div class="fleet-toolbar">
				<p>
					<?php
					/* translators: %s: number of cars. */
					printf( esc_html( _n( '%s car available', '%s cars available', (int) $wp_query->found_posts, 'crestway' ) ), '<strong>' . esc_html( number_format_i18n( $wp_query->found_posts ) ) . '</strong>' );
					?>
				</p>
				<div class="fleet-tools">
					<button type="button" class="btn btn--ghost btn--sm filter-toggle" id="filter-toggle" aria-controls="filter-card" aria-expanded="false"><?php dh_the_icon( 'sliders', 16 ); ?> <?php esc_html_e( 'Filters', 'crestway' ); ?></button>
					<label class="screen-reader-text" for="sort"><?php esc_html_e( 'Sort by', 'crestway' ); ?></label>
					<select class="input" id="sort" data-sort>
						<?php
						$sorts = array(
							''           => __( 'Sort: Newest', 'crestway' ),
							'price_asc'  => __( 'Price: Low to High', 'crestway' ),
							'price_desc' => __( 'Price: High to Low', 'crestway' ),
							'rating'     => __( 'Top rated', 'crestway' ),
						);
						foreach ( $sorts as $val => $label ) :
							?>
							<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $f['sort'], $val ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<?php if ( have_posts() ) : ?>
				<div class="car-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/car-card' );
					endwhile;
					?>
				</div>
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 1,
						'prev_text' => '←',
						'next_text' => '→',
					)
				);
				?>
			<?php else : ?>
				<div class="empty-state">
					<div class="icon-wrap"><?php dh_the_icon( 'search', 30 ); ?></div>
					<h3><?php esc_html_e( 'No cars match your filters', 'crestway' ); ?></h3>
					<p><?php esc_html_e( 'Try widening your price range or removing a filter.', 'crestway' ); ?></p>
					<a class="btn btn--primary" href="<?php echo esc_url( $action ); ?>"><?php esc_html_e( 'Clear filters', 'crestway' ); ?></a>
				</div>
			<?php endif; ?>
		</div>

	</div>
</section>

<?php
get_footer();
