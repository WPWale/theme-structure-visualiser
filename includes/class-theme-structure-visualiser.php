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
		 * Style Options
		 * 
		 * @var array
		 */
		private $style_options;

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

			// Get template name
			add_action( 'all', array( $this, 'get_templates' ) );
			
			// Get template part name
			add_action( 'all', array( $this, 'get_template_parts' ) );
			
			// Enqueue jQuery
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

			// Add the page to the admin menu
			add_action( 'admin_menu', array( &$this, 'add_page' ) );

			// Register page options
			add_action( 'admin_init', array( &$this, 'register_page_options' ) );

			// Register javascript
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_js' ) );

			// Get registered option
			$this->options = get_option( 'cpa_settings_options' );
		}

		/**
		 * Get templates
		 *
		 * @sinc 0.0.1 
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

			// Flip the keys and values of the pattern array
			$flipped_hook_patterns = array_flip( $hook_patterns );

			// The template 'class' is 'header' at 'get_header' key, for example
			$this->template_class = $flipped_hook_patterns[ $current_hook_handle ];
			
			// The template 'slug' is the same as 'template_class'
			$this->template_slug = $flipped_hook_patterns[ $current_hook_handle ];

			//  The template 'name' is the second arguement.
			$this->template_name = $current_hook_arguments[ 1 ];

			// If the slug is not 'header'
			if ( 'header' !== $this->template_slug ) {

				//print the output
				$this->display_structure();

			}
		}
		
		
		/**
		 * 
		 */
		function get_template_parts(){

			// Get the handle of the current hook
			$current_hook_handle = current_filter();
			
			// Initialise a variable to store the handles of template hooks
			$hook_patterns = array();

			// Loop through the identifiers 
			foreach ( $this->template_part_identifiers as $template_part_identifier ) {

				// Get the hook handle by prefixing 
				$hook_patterns[ $template_part_identifier ] = "get_$template_part_identifier";
			}

			foreach ( $hook_patterns as $key => $hook_pattern) {
				
				if ( strstr( $current_hook_handle, $hook_pattern ) ){
					
					// Get the arguements passed with the current hook
					$current_hook_arguments = func_get_args();	
					
					$this->template_class = $key;
					$this->template_slug = $current_hook_arguments[ 1 ];
					$this->template_name = $current_hook_arguments[ 2 ];
					
					//print the output
					$this->display_structure();
				}
			}
			
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
		function enqueue(){

			// Enqueue the display header srcipt from the js directory
			wp_enqueue_script( 'display_header_object', TSV_URL . 'assets/js/display-header-object.js', array('jquery')  );
			
			
			// If the slug is not 'header' return early
			if ( 'header'!== $this->template_slug ) {

				return;
			}


			// Localize the script to send variables from PHP to Javascript
			wp_localize_script( 'display_header_object', 'tsv_header_filename', array(
				'slug'	 => $this->template_slug,
				'path'	 => $this->setup_template_variables(),
			) );
		}
		
		
		/**
		 * Add page
		 * 
		 * Add an options page under the settings menu.
		 * 
		 * @since 0.0.1
		 */
		function add_page() {
			
			add_options_page( 'TSV Options', 'TSV Options', 'manage_options', __FILE__, array( $this, 'display_page' ) );
		}

		
		/**
		 * Display page
		 * 
		 * Display the options page.
		 * 
		 * @since 0.0.1
		 */
		function display_page() {
			
			?>
			<div class="wrap">
				<h2> Theme Structure Visualiser OPtions </h2>
					<form method="post" action="options.php">
					<?php
					settings_fields( __FILE__ );
					do_settings_sections( __FILE__ );
					submit_button();

					?>
					</form>
			</div> <!-- wrap -->
			<?php
		}
		
		
		
		/**
		 * Register page options
		 * 
		 * Register admin page options.
		 * 
		 * @since 0.0.1
		 */
		function register_page_options() {
			
			// Add a section
			add_settings_section( 'tsv_section', 'Plugin OPtions', array( $this, 'display_section' ), __FILE__ );
			
			// Add the background coluor field
			add_settings_field( 'tsv_background_field', 'Background Color', array( $this, ('bg_colour_settings_field') ), __FILE__, 'tsv_section' );
			
			// Add a font colour field
			add_settings_field( 'tsv_font_field', 'Font Color', array( $this, ('font_colour_settings_field') ), __FILE__, 'tsv_section' );
			
			// Register the setting
			register_setting( __FILE__, 'tsv_settings_options', array( $this, ('validate_options') ) );
		}
		
		
		/**
		 * Background colour settings field
		 * 
		 * Set the background for the template/template-part names.
		 * 
		 * @since  0.0.1
		 */
		function bg_colour_settings_field() {

			$val = ( isset( $this->options[ 'background-colour' ] ) ) ? 
			$this->options[ 'background-colour' ] : '';
			echo '<input type="text" name="tsv_settings_options[background-colour]
				value=" ' . $val . ' " class="tsv-color-picker">';
		}
		
		
		/**
		 * Font colour settings field
		 * 
		 * Sets the font colour for the template/template-part names.
		 * 
		 * @since 0.0.1		 * 
		 */
		function font_colour_settings_field() {

			$val = ( isset( $this->options[ 'font-colour' ] ) ) ?
			$this->options[ 'font-colour' ] : '';
			echo '<input type="text" name="tsv_settings_options[font-colour] 
				value=" ' . $val . ' " class="tsv-color-picker">';
		}
		
		
		/**
		 * Validate options
		 * 
		 * Validate all fields
		 * 
		 * @var array Holds the fields
		 * 
		 * @since 	0.0.1
		 */
		function validate_options($fields) {
			
			$valid_fields = array();

			// Validate background colour field
			$background_colour = trim( $fields[ 'background_colour' ] );
			$valid_fields[ 'background_colour' ] = strip_tags( stripslashes( $background_colour ) );

			// Validate font colour field
			$font_colour = trim( $fields[ 'font_colour' ] );
			$valid_fields[ 'font_colour' ]	 = strip_tags( stripslashes( $font_colour ) );

			// Check if the hex value is valid for bg color
			if ( FALSE === $this->check_color( $background_colour ) ) {

				// Set error message
				add_settings_error( 'tsv_settings_options', 'tsv_bg_color_error', 'Insert a valid color for the background', 'error' );

				// Get the previous valid value
				$valid_fields[ 'background_colour' ] = $this->options[ 'background_colour' ];
			}
			else {

				$valid_fields[ 'background_colour' ] = $background_colour;
			}

			// Check if the hex value is valid
			if ( FALSE === $this->check_color( $font_colour ) ) {

				// Set error message
				add_settings_error( 'tsv_settings_options', 'tsv_font_color_error', 'Insert a valid color for the font', 'error' );

				// Get the previous valid value
				$valid_fields[ 'font_colour' ] = $this->options[ 'font_colour' ];
			}
			else {

				$valid_fields[ 'font_colour' ] = $font_colour;
			}
			
			wp_enqueue_script( 'custom_options_object', TSV_URL . 'assets/js/jquery.custom.js', array('jquery')  );
			
			wp_localize_script( 'custom_options_object', 'tsv_custom_options', $valid_fields);			

			return apply_filters( 'validate_options', $valid_fields, $fields );
		}
		
		
		
		/**
		 * Check color
		 * 
		 * Matches the pattern of the string entered by the user.
		 * 
		 * @param string User entered hex value
		 *
		 *  @return boolean
		 * 
		 * @since 0.0.1
		 */
		function check_color( $value ) {
		
			if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) {
				echo "Preg_match returned true";
			
				return true;
			}

			return false;
		}
				
		
		/**
		 * Enqueue admin javascript
		 * 
		 * Enqueue the javascript for the color picker
		 * 
		 * @since 0.0.1
		 */
		function enqueue_admin_js() {
						
			// Css rules for Color Picker
			wp_enqueue_style( 'wp-color-picker' );
			
			wp_enqueue_script( 'tsv_custom_js', TSV_URL . 'assets/js/jquery.custom.js', array( 'jquery', 'wp-color-picker' ), '', true );
		}
		
		function display_section() {

			// Intentionally left blank
			
		}

	}// class

} //class_exists1