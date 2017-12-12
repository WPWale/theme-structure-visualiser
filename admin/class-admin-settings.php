<?php
/**
 * Admin Settings
 * 
 * @author Shantanu Desai <shantanu2846@gmail.com>
 * @since 0.0.1
 */

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) exit();

if ( !class_exists( 'Admin_Settings' ) ) {
	
	/**
	 * Admin Settings
	 *
	 * @since 0.0.1
	 */
	
	class Admin_Settings {
		/**
		* Style Options
		* 
		* @var array
		*/
		private $style_options;
		
		/**
		 * Constructor
		 */
		public function __construct() {}
		
		/**
		 * Initialise the class
		 *
		 * @since 0.0.1 
		 */
		public function init() {
			
			// Apply the color settings
			add_action( 'wp_head', array( $this, 'apply_colours' ) );

			// Add the page to the admin menu
			add_action( 'admin_menu', array( $this, 'add_page' ) );

			// Register page options
			add_action( 'admin_init', array( $this, 'register_page_options' ) );

			// Register javascript
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_js' ) );

			// Get registered option
			$this->style_options = get_option( 'tsv_settings_options' );
			
		}
		
		/**
		 * Add page
		 * 
		 * Add an options page under the settings menu.
		 * 
		 * @since 0.0.1
		 */
		public function add_page() {
			
			add_options_page( 'TSV Options', 'TSV Options', 'manage_options', __FILE__, array( $this, 'display_page' ) );
		}

		
		/**
		 * Display page
		 * 
		 * Display the options page.
		 * 
		 * @since 0.0.1
		 */
		public function display_page() {
			
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
		public function register_page_options() {
			
			// Add a section
			add_settings_section( 'tsv_section', 'Plugin Options', array( $this, 'display_section' ), __FILE__ );
			
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
		public function bg_colour_settings_field() {

			$val = ( isset( $this->style_options[ 'background_colour' ] ) ) ? 
				$this->style_options[ 'background_colour' ] : '';
			?>
			<input type="text" name="tsv_settings_options[background_colour]"
				value="<?php echo $val; ?>" data-default-color="#000000" class="tsv-color-picker">
			<?php
		}
		
		
		/**
		 * Font colour settings field
		 * 
		 * Sets the font colour for the template/template-part names.
		 * 
		 * @since 0.0.1		 * 
		 */
		public function font_colour_settings_field() {

			$val = ( isset( $this->style_options[ 'font_colour' ] ) ) ?
				$this->style_options[ 'font_colour' ] : '';
			?>
			<input type="text" name="tsv_settings_options[font_colour]" 
				value="<?php echo $val; ?>" data-default-color="#fff" class="tsv-color-picker">';
			<?php
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
		public function validate_options($fields) {
			
			print_r($fields);
			
			$valid_fields = array();

			// Validate background colour field
			$background_colour = trim( $fields[ 'background_colour' ] );
			$valid_fields[ 'background_colour' ] = strip_tags( stripslashes( $background_colour ) );

			// Validate font colour field
			$font_colour = trim( $fields[ 'font_colour' ] );
			$valid_fields[ 'font_colour' ]	 = strip_tags( stripslashes( $font_colour ) );

			// Check if the hex value is valid for bg colour
			if ( FALSE === $this->check_colour( $background_colour ) ) {

				// Set error message
				add_settings_error( 'tsv_settings_options', 'tsv_bg_colour_error', "Insert a valid colour for the background $background_colour", 'error' );

				// Get the previous valid value
				$valid_fields[ 'background_colour' ] = $this->style_options[ 'background_colour' ];
			}
			else {

				$valid_fields[ 'background_colour' ] = $background_colour;
			}

			// Check if the hex value is valid
			if ( FALSE === $this->check_colour( $font_colour ) ) {
				
				// Set error message
				add_settings_error( 'tsv_settings_options', 'tsv_font_colour_error', "Insert a valid colour for the font $font_colour", 'error' );

				// Get the previous valid value
				$valid_fields[ 'font_colour' ] = $this->style_options[ 'font_colour' ];
			}
			else {

				$valid_fields[ 'font_colour' ] = $font_colour;
			}

			return apply_filters( 'validate_options', $valid_fields, $fields );
		}
		
		
		/**
		 * Check colour
		 * 
		 * Matches the pattern of the string entered by the user.
		 * 
		 * @param string User entered hex value
		 *
		 *  @return boolean
		 * 
		 * @since 0.0.1
		 */
		public function check_colour( $value ) {
			
			echo $value;
					
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
		public function enqueue_admin_js() {
						
			// Css rules for Color Picker
			wp_enqueue_style( 'wp-color-picker' );
			
			wp_enqueue_script( 'tsv_custom_js', TSV_URL . 'assets/js/jquery.custom.js', array( 'jquery', 'wp-color-picker' ), '', true );
		}
		
		
		public function apply_colours(){
			
			?>
			<style>
				.tsv-template-path {
					background-color: <?php echo $this->style_options[ 'background_colour' ]; ?>;
					color: <?php echo $this->style_options[ 'font_colour' ]; ?>;
					padding: 1rem 1rem;
					border-radius: 10px 10px 10px 10px;
					margin-bottom: 1rem;
				}
			</style>
			<?php
		}

		public function display_section() {

			// Intentionally left blank
			
		}
		
	}		
}
