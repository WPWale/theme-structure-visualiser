<?php

/**
 * Theme Structure Visualiser
 * 
 * This file declares the plugin constants and instantiates the two necessary classes.
 * 
 * Plugin Name: Theme Structure Visualiser
 * Description: Visualise the basic structure of a website
 * Version: 0.0.1
 * Author: Shantanu Desai
 * Description: Visualise the basic structure of a websiteAuthor URI  https://github.com/shantanu2704
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
	 * @since 3.2
	 */
	define( 'TSV_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( !defined( 'TSV_URL' ) ) {
	/**
	 * URL to the plugin directory.
	 *
	 * @since 3.2
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