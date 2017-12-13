<?php
/**
 * Theme structure visualiser
 * 
 * @author Shantanu Desai <shantanu2846@gmail.com>
 * @since 0.0.1
 */

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) exit();

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
		private $template_part_identifiers = array();

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
		 * Template class
		 * 
		 * @var string
		 */
		private $template_class;

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

			$template_part_identifiers  = array( 'template_part');

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
		 * @since 0.0.1 
		 */
		function init() {
			
			/*
			 * The action 'all' is fired before every other action in the Wordpress core.
			 * This allows us to hook into actions such as get_header(), get_footer(), etc
			 */
			add_action( 'all', array( $this, 'get_templates' ) );
			
			/*
			 * The action 'all' is fired before every other action in the Wordpress core.
			 * This allows us to hook into actions such as get_template_part_{slug}
			 */
			add_action( 'all', array( $this, 'get_template_parts' ) );

			/*
			 * Hook into wp_enqueue_scripts to enqueue the javascript file.
			 */
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );	
		}

		/**
		 * Get templates
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

			$this->get_template_file_name( $current_hook_arguments, $hook_patterns, $current_hook_handle );

			// If the slug is not 'header'
			if ( 'header' !== $this->template_slug ) {

				//print the output
				$this->display_structure();
			}
		}

		/**
		 * Get file name
		 * 
		 * Gets the different parts of the file name of the template
		 *
		 * @param array $current_hook_arguments An array of current hook arguments
		 *  @param array $hook_patters An array of hook_patterns
		 * @param string $current_hook_handle Name of the current handle
		 * 
		 * @since 0.0.1
		 */
		public function get_template_file_name( $current_hook_arguments, $hook_patterns, $current_hook_handle ) {

			// Flip the keys and values of the pattern array
			$flipped_hook_patterns = array_flip( $hook_patterns );

			// The template 'class' is 'header' at 'get_header' key, for example
			$this->template_class = $flipped_hook_patterns[ $current_hook_handle ];

			// The template 'slug' is the same as 'template_class'
			$this->template_slug = $flipped_hook_patterns[ $current_hook_handle ];

			//  The template 'name' is the second arguement.
			$this->template_name = $current_hook_arguments[ 1 ];
			
//			print_r($current_hook_arguments);
		}

		/**
		 * Get Template Parts
		 * 
		 * @since 0.0.1
		 */
		function get_template_parts() {

			// Get the handle of the current hook
			$current_hook_handle = current_filter();

			// Initialise a variable to store the handles of template hooks
			$hook_patterns = array();

			// Loop through the identifiers 
			foreach ( $this->template_part_identifiers as $template_part_identifier ) {

				// Get the hook handle by prefixing 
				$hook_patterns[ $template_part_identifier ] = "get_$template_part_identifier";
			}

			foreach ( $hook_patterns as $key => $hook_pattern ) {

				if ( strstr( $current_hook_handle, $hook_pattern ) ) {

					$current_hook_arguments = func_get_args();
					$this->get_template_parts_file_name( $key, $current_hook_arguments );
				}
			}
		}

		/**
		 * Get template parts file name
		 * 
		 * @param string $class template class name
		 * @param array $$current_hook_arguments arguments passed to the hook
		 * 
		 * @since 0.0.1
		 */
		public function get_template_parts_file_name( $class, $current_hook_arguments  ) {

			// Get the arguements passed with the current hook

			$this->template_class	 = $class;
			$this->template_slug	 = $current_hook_arguments[ 1 ];
			$this->template_name	 = $current_hook_arguments[ 2 ];

			//print the output
			$this->display_structure();
		}

		/**
		 * Display the names of template files
		 * 
		 * @since 0.0.1
		 */
		function display_structure() {

			// Setup the required variables
			$path = $this->setup_template_variables();

			$class = $this->template_class;

			// Include the template that prints a div around the output
			include TSV_PATH . 'templates/display-structure.php';			
		}


		/**
		 * Setup the template variables
		 * 
		 * @return string Template name to be displayed
		 * 
		 * @since 0.0.1		 * 
		 */
		function setup_template_variables() {

			$slug = $this->template_slug;
			$name = $this->template_name;

			// If the 'slug' is empty, return
			if ( empty( $slug ) ) {
				return;
			}

			// Initialise a variable to hold the optional second part of the template name
			$second_part = '';

			// Check if the 'name' is empty
			if ( !empty( $name ) ) {
				// If it isn't empty, assign it to the variable
				$second_part = "-$name";
			}

			// Concatenate the two parts of the file name and the extension
			return ($slug . $second_part . '.php');
		}

		/**
		 * Enqueue the scripts
		 * 
		 * @since 0.0.1
		 */
		function enqueue() {

			// Enqueue the display header srcipt from the js directory
			wp_enqueue_script( 'display_header_object', TSV_URL . 'assets/js/theme-structure-visualiser.min.js', array( 'jquery' ) );

			// If the slug is not 'header' return early
			if ( 'header' !== $this->template_slug ) {
				return;
			}

			// Localize the script to send variables from PHP to Javascript
			wp_localize_script( 'display_header_object', 'tsv_header_filename', array(
				'slug'	 => $this->template_slug,
				'path'	 => $this->setup_template_variables(),
			) );
		}

	}// class

} //class_exists1