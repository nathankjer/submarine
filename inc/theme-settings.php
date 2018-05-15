<?php
/**
 * Check and setup theme's default settings
 *
 * @package submarine
 *
 */

if ( ! function_exists ( 'submarine_setup_theme_default_settings' ) ) {
	function submarine_setup_theme_default_settings() {

		// check if settings are set, if not set defaults.
		// Caution: DO NOT check existence using === always check with == .
		// Latest blog posts style.
		$submarine_posts_index_style = get_theme_mod( 'submarine_posts_index_style' );
		if ( '' == $submarine_posts_index_style ) {
			set_theme_mod( 'submarine_posts_index_style', 'default' );
		}

		// Sidebar position.
		$submarine_sidebar_position = get_theme_mod( 'submarine_sidebar_position' );
		if ( '' == $submarine_sidebar_position ) {
			set_theme_mod( 'submarine_sidebar_position', 'right' );
		}

		// Container width.
		$submarine_container_type = get_theme_mod( 'submarine_container_type' );
		if ( '' == $submarine_container_type ) {
			set_theme_mod( 'submarine_container_type', 'container' );
		}
	}
}