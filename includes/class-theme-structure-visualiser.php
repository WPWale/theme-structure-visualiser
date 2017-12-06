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

		/**
		 * Initialise the class
		 *
		 * Description.
		 *
		 * @since 0.0.1 
		 */
		function init() {
			add_action( 'all', array( $this, 'get_included_file_names' ) );
			add_action( 'all', array( $this, 'print_header' ) );
		}

	}

}