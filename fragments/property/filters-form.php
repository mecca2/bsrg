<?php

try {
	$filters = new \Crb\Properties_Filters();
} catch ( Exception $e ) {
	echo '<pre>';
	print_r( $e->getError() );
	echo '</pre>';
	return;
}

// Template Sectinos
$search_btn_title = __( 'Search', 'crb' );
$search_field_placeholder = __( 'Search by neighborhood, city or address', 'crb' );

// Template Properties
if ( is_page_template( 'templates/properties.php' ) ) {
	$search_btn_title = __( 'Search Properties', 'crb' );
	$search_field_placeholder = __( 'Search...', 'crb' );
}

?>

<div class="form-filter">
	<form action="<?php echo esc_url( $filters->get_action() ); ?>" method="GET">
		<input type="hidden" class="" name="order" value="<?php echo esc_attr( $filters->get_parameter( 'order' ) ); ?>">

		<div class="select select--bold select-meta-type">
			<select name="type" id="select-type">
				<?php foreach ( $filters->get_options( 'type' ) as $slug => $name ): ?>
					<option
						value="<?php echo esc_attr( $slug ); ?>"
						<?php echo $filters->get_current( $slug, 'type', 'selected' ); ?>
					>
						<?php echo apply_filters( 'the_title', $name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div><!-- /.select -->

		<div class="select select-meta-sub_type select-multiple">
			<select name="sub_type[]" id="select-sub-type"  multiple="multiple" placeholder="Year Built">
				<?php foreach ( $filters->get_options( 'sub_type' ) as $slug => $name ): ?>
					<option
						value="<?php echo esc_attr( $slug ); ?>"
						<?php echo $filters->get_current( $slug, 'sub_type', 'selected' ); ?>
					>
						<?php
						if ( $name == 'Condo/Townhouse' ) {
							$name = 'Condos & Villas';
						}

						echo apply_filters( 'the_title', $name );
						?>
					</option>
				<?php endforeach; ?>
			</select>
		</div><!-- /.select -->

		<input type="text" class="form__field search-field input-meta-search" id="search-field" name="search" placeholder="<?php echo esc_attr( $search_field_placeholder ); ?>" value="<?php echo esc_attr( $filters->get_parameter( 'search' ) ); ?>" />

		<div class="select select-meta-min-price">
			<select name="min_price" id="select-min-price">
				<?php foreach ( $filters->get_options( 'min_price' ) as $slug => $name ): ?>
					<option
						value="<?php echo esc_attr( $slug ); ?>"
						<?php echo $filters->get_current( $slug, 'min_price', 'selected' ); ?>
					>
						<?php echo apply_filters( 'the_title', $name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div><!-- /.select -->

		<div class="select select-meta-max-price">
			<select name="max_price" id="select-max-price">
				<?php foreach ( $filters->get_options( 'max_price' ) as $slug => $name ): ?>
					<option
						value="<?php echo esc_attr( $slug ); ?>"
						<?php echo $filters->get_current( $slug, 'max_price', 'selected' ); ?>
					>
						<?php echo apply_filters( 'the_title', $name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div><!-- /.select -->

		<div class="select select-meta-beds">
			<select name="bedrooms" id="select-beds">
				<?php foreach ( $filters->get_options( 'bedrooms' ) as $slug => $name ): ?>
					<option
						value="<?php echo esc_attr( $slug ); ?>"
						<?php echo $filters->get_current( $slug, 'bedrooms', 'selected' ); ?>
					>
						<?php echo apply_filters( 'the_title', $name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div><!-- /.select -->

		<div class="select select-meta-baths">
			<select name="bathrooms" id="select-baths">
				<?php foreach ( $filters->get_options( 'bathrooms' ) as $slug => $name ): ?>
					<option
						value="<?php echo esc_attr( $slug ); ?>"
						<?php echo $filters->get_current( $slug, 'bathrooms', 'selected' ); ?>
					>
						<?php echo apply_filters( 'the_title', $name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div><!-- /.select -->

		<div class="select select-meta-waterfronts select-multiple">
			<select name="waterfronts[]" id="select-waterfronts"  multiple="multiple" placeholder="Waterfronts">
				<?php foreach ( $filters->get_options( 'waterfronts' ) as $slug => $name ): ?>
					<option
						value="<?php echo esc_attr( $slug ); ?>"
						<?php echo $filters->get_current( $slug, 'waterfronts', 'selected' ); ?>
						<?php echo $filters->get_disabled( $slug, 'waterfronts', 'disabled' ); ?>
					>
						<?php echo apply_filters( 'the_title', $name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div><!-- /.select -->

		<div class="select select-meta-neighborhoods select-multiple">
			<select name="neighborhoods[]" id="select-neighborhoods"  multiple="multiple" placeholder="Neighborhoods">
				<?php foreach ( $filters->get_options( 'neighborhoods' ) as $slug => $name ): ?>
					<option
						value="<?php echo esc_attr( $slug ); ?>"
						<?php echo $filters->get_current( $slug, 'neighborhoods', 'selected' ); ?>
						<?php echo $filters->get_disabled( $slug, 'neighborhoods', 'disabled' ); ?>
					>
						<?php echo apply_filters( 'the_title', $name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div><!-- /.select -->

		<div class="select select-meta-community_amenities select-multiple">
			<select name="community_amenities[]" id="select-community-amenities"  multiple="multiple" placeholder="Community Amenities">
				<?php foreach ( $filters->get_options( 'community_amenities' ) as $slug => $name ): ?>
					<option
						value="<?php echo esc_attr( $slug ); ?>"
						<?php echo $filters->get_current( $slug, 'community_amenities', 'selected' ); ?>
						<?php echo $filters->get_disabled( $slug, 'community_amenities', 'disabled' ); ?>
					>
						<?php echo apply_filters( 'the_title', $name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div><!-- /.select -->

		<div class="select select-meta-year_built select-multiple">
			<select name="year_built[]" id="select-year-built"  multiple="multiple" placeholder="Year Built">
				<?php foreach ( $filters->get_options( 'year_built' ) as $slug => $name ): ?>
					<option
						value="<?php echo esc_attr( $slug ); ?>"
						<?php echo $filters->get_current( $slug, 'year_built', 'selected' ); ?>
					>
						<?php echo apply_filters( 'the_title', $name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div><!-- /.select -->

		<input type="submit" class="form__btn" value="<?php echo esc_attr( $search_btn_title ); ?>">
	</form>
</div><!-- /.form-filter -->
