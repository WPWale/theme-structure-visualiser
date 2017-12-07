<?php
/**
 * Theme structure visualiser
 * 
 * @author Shantanu Desai <shantanu2846@gmail.com>
 * @since 0.0.1
 */

// If this file is called directly, abort.
if ( !defined( ABSPATH ) ) exit();

if ( !class_exists( 'Theme_Structure_Visualiser' ) ) {

	/**
	 * Theme structure visualiser
	 *
	 * @since 0.0.1
	 */
	class Theme_Structure_Visualiser {

		/**
		 * Identifiers for templates
		 * 
		 * @var array  
		 */
		private $template_identifiers = array();

		/**
		 * Identifiers for template parts
		 * 
		 * @var array
		 */
		private $template_part_identifiers = array( 'template_part');		

		/**
		 * Constructor
		 */
		public function __construct() {
			
			/*
			 * This array contains text that will be used for
			 * 
			 *  1. Identifying the template hook
			 *  For eg, 'get_header' hook from 'header'
			 * 
			 *  2. Identifying the template file
			 *  For eg, 'header.php' from 'header'
			 */
			$template_identifiers = array( 'header', 'footer', 'sidebar');
			
			/**
			 * Filters the template identifiers
			 * 
			 * @since 0.0.1
			 * 
			 * @param array $template_identifiers A list of templates, eg header, footer, etc.
			 */
			$this->template_identifiers = apply_filters( 'tsv_template_identifiers', $template_identifiers );
						
			$template_part_identifiers  = $this->template_part_identifiers ;

			/**
			 * Filters the template part identifiers
			 * 
			 * @since 0.0.1
			 * 
			 * @param array $template_part_identifiers A list of template parts, eg template_part.
			 */
			$this->template_part_identifiers = apply_filters( 'tsv_template_part_identifiers', $template_part_identifiers );
		}
		
		/**
		 * Initialise the class
		 *
		 * Description.
		 *
		 * @since 0.0.1 
		 */
		function init() {
			add_action( 'all', array( $this, 'get_templates' ) );
			add_action( 'wp_head', array( $this, 'print_header' ) );
		}

		/**
		 * Get templates
		 *
		 * Description.
		 *
		 * @since 0.0.1 
		 */
		function get_templates() {
			
			$current_filter = current_filter();

			$hook_patterns = array();

			foreach ( $this->template_identifiers as $template_identifier ) {
				$hook_patterns[ $template_identifier ] = "get_$template_identifier";
			}
					
			$hook_arguments = func_get_args();
			$slug = $name = '';

			if ( in_array( $current_filter, $hook_patterns ) ) {
				$flipped_hook_patterns = array_flip( $hook_patterns );
				$slug	= $flipped_hook_patterns[ $current_filter ];
				$name = $hook_arguments[ 1 ];
			}

			if ( 'header' !== $slug ) {
				print_path( $slug, $name );
			} else {
				global $mm_header_slug, $mm_header_name;
				$mm_header_slug	 = $slug;
				$mm_header_name	 = $name;
			}
		}

	}

}