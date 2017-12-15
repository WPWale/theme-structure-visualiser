<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the
 * plugin admin area. This file also includes all of the dependencies used by
 * the plugin, registers the activation and deactivation functions, and defines
 * a function that starts the plugin.
 *
 * @since             0.0.1
 * @package           theme_structure_visualiser
 *
 * @wordpress-plugin
 * 
 * Plugin Name: Theme Structure Visualiser
 * Description: Helps visualise the template structure of a theme
 * Version: 1.0.1
 * Author: BaapWP
 * Author URI:  https://github.com/BaapWP
 * Text Domain: theme-structure-visualiser
 * Domain Path: /languages
 * License: GPL2
 */

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) exit();

if ( !defined( 'TSV_PATH' ) ) {
	/**
	 * Path to the plugin directory.
	 *
	 * @since 0.0.1
	 */
	define( 'TSV_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( !defined( 'TSV_URL' ) ) {
	/**
	 * URL to the plugin directory.
	 *
	 * @since 0.0.1
	 */
	define( 'TSV_URL', trailingslashit( plugin_dir_url(  __FILE__ ) ) );
}

/**
 * The core plugin class
 */
require_once TSV_PATH . 'includes/class-theme-structure-visualiser.php';

$tsv = new Theme_Structure_Visualiser();
$tsv->init();

/**
 * The admin settings class
 */
require_once TSV_PATH . 'admin/class-admin-settings.php';

$tsv_admin = new Admin_Settings();
$tsv_admin->init();