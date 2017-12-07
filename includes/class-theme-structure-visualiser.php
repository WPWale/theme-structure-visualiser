<?php

/**
 * Theme structure visualiser
 * 
 * @author Shantanu Desai <shantanu2846@gmail.com>
 * @since 0.0.1
 */
// If this file is called directly, abort.
if ( !defined( ABSPATH ) ) {
	exit();
}

if ( !class_exists( 'Theme_Structure_Visualiser' ) ) {

	/**
	 * Theme structure visualiser
	 *
	 * @since 0.0.1
	 */
	class Theme_Structure_Visualiser {
		
		private $file_types = array( 'header', 'footer', 'sidebar', 'template_part' );
		
		/**
		 * Initialise the class
		 *
		 * Description.
		 *
		 * @since 0.0.1 
		 */
		function init() {
			add_action( 'all', array( $this, 'get_included_file_names' ) );
			add_action( 'wp_head', array( $this, 'print_header' ) );
		}

		/**
		 * Initialise the class
		 *
		 * Description.
		 *
		 * @since 0.0.1 
		 */
		function get_included_file_names() {
			$mm_current_filter = current_filter();

			$patterns = array();

			foreach ( $file_types as $type ) {
				$patterns[ $type ] = "get_$type";
			}

			if ( !in_array( $mm_current_filter, $patterns ) ) {
				if ( !strstr( $mm_current_filter, $patterns[ 'template_part' ] ) ) {
					return;
				}
			}
			$hook_arguments = func_get_args();
			$slug = $name = '';

			if ( in_array( $mm_current_filter, $patterns ) ) {
				$patterns_flip = array_flip( $patterns );
				$slug = $patterns_flip[ $mm_current_filter ];
				$name = $hook_arguments[ 1 ];
			}

			if ( strstr( $mm_current_filter, $patterns[ 'template_part' ] ) ) {
				$slug	 = $hook_arguments[ 1 ];
				$name = $hook_arguments[ 2 ];
			}

			if ( 'header' !== $slug ) {
				mm_print_path( $slug, $name );
			} else {
				global $mm_header_slug, $mm_header_name;
				$mm_header_slug	 = $slug;
				$mm_header_name	 = $name;
			}
		}

	}

}