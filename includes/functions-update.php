<?php
/**
 * Update
 *
 * Seemless updates
 *
 * @package      ${PACKAGE}
 * @license      license.txt
 * @copyright    ${YEAR} ${COMPANY}
 * @since        ${VERSION}
 *
 * Please do not edit this file. This file is part of the ${PACKAGE} Framework and all modifications
 * should be made in a child theme.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
// @TODO Add functions needed for update

/*
 * Update page templete meta data
 *
 * E.g: Change from `full-width-page.php` to `page-templates/full-width-page.php`
 *
 * This function only needes to be run once but it does not mater when. after_setup_theme should be fine.
 *
 */
function responsive_update_page_template_meta(){

	$args = array (
		'post_type' => 'page',
	);

	$pages = get_pages( $args );

	foreach ( $pages as $page ) {

		$meta_value = get_post_meta( $page->ID, '_wp_page_template', true );
		$page_templates_dir = 'page-templates/';
		$pos = strpos( $meta_value, $page_templates_dir );

		if ( $pos !== false ) {
			$meta_value = $page_templates_dir . $meta_value;
			update_post_meta( $post_id, '_wp_page_template', $meta_value );
			//update_post_meta( $post_id, '_wp_page_template_responsive', $meta_value );
		}

	}

}
add_action( 'switch_theme', 'responsive_update_page_template_meta' );

/**
 * Part of the Portfolio Press upgrade routine.
 * The page template paths have changed, so let's update the template meta for the user.
 */
function portfoliopress_update_page_templates() {
	$args = array(
		'post_type'   => 'page',
		'post_status' => 'publish',
		'meta_query'  => array(
			array(
				'key'     => '_wp_page_template',
				'value'   => 'default',
				'compare' => '!='
			)
		)
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) :
		while ( $query->have_posts() ) : $query->the_post();
			$current_template = get_post_meta( get_the_ID(), '_wp_page_template', true );
			$new_template = false;
			switch ( $current_template ) {
				case 'archive-portfolio.php':
					$new_template = 'templates/portfolio.php';
					break;
				case 'full-width-page.php':
					$new_template = 'templates/full-width-page.php';
					break;
				case 'full-width-portfolio.php':
					$new_template = 'templates/full-width-portfolio.php';
					break;
			}
			if ( $new_template ) {
				update_post_meta( get_the_ID(), '_wp_page_template', true );
			}
		endwhile;
	endif;
}
