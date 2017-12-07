<?php

/**
 * Summary (no period for file headers)
 *
 * Description. (use period)
 *
 * @author  Shantanu Desai <shantanu2846@gmail.com>
 * @since 0.0.1
 */
/*
  Plugin Name: Theme Structure Visualiser
  Plugin URI	  :
  Description  : Visualise the basic structure of a website
  Version	  : 0.0.1
  Author	  : Shantanu Desai
  Author URI  : https://github.com/shantanu2704
  Text Domain: theme-structure-visualiser
  Domain Path: /languages
  License	  : GPL2
 */

// If this file is called directly, abort.
if ( !defined( ABSPATH ) ) {
	exit();
}

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
	define( 'TSV_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );
}

/**
 * The core plugin class
 */
require_once TSV_PATH . 'includes/class-theme-structure-visualiser.php';

$tsv = new Theme_Structure_Visualiser();
$tsv->init();