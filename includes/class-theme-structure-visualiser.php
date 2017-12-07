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
		 * Template slug
		 * 
		 * @var string 
		 */
		private $template_slug;
		
		/**
		 * Template name
		 * 
		 * @var string
		 */
		private $template_name;

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
			
			// Get the handle of the current hook
			$current_hook_handle = current_filter();

			// Initialise a variable to store the handles of template hooks
			$hook_patterns = array();

			// Loop through the identifiers 
			foreach ( $this->template_identifiers as $template_identifier ) {
				
				// Get the hook handle by prefixing 
				$hook_patterns[ $template_identifier ] = "get_$template_identifier";
			}

			// Bail early if the current hook is not a template hook
			if ( !in_array( $current_hook_handle, $hook_patterns ) ) {
				return;
			}
			
			// Get the arguements passed with the current hook
			$current_hook_arguments = func_get_args();
			
			// Initialise template slug and name
			$template_slug = $template_name = '';

			// Flip the keys and values of the pattern array
			$flipped_hook_patterns = array_flip( $hook_patterns );
			
			// The template 'slug' is 'header' at 'get_header' key, for example
			$template_slug = $flipped_hook_patterns[ $current_hook_handle ];
			
			//  The template 'name' is the second arguement.
			$template_name = $current_hook_arguments[ 1 ];

			// If the slug is not 'header'
			if ( 'header' !== $template_slug ) {
				
				//print the output
				print_path( $template_slug, $template_name );
				
			/* 
			 * Otherwise if it is 'header' and we can't print into the template
			 * because outputting markup in the document header breaks the 
			 * things on the browser.
			 */		
			} else {
				
				$mm_header_slug	 = $template_slug;
				$mm_header_name	 = $template_name;
			}
		}

	}

}