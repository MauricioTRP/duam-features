<?php
/**
 * This is the plugin bootstrap file
 * 
 * Plugin Name:     Duam Features
 * Plugin URI:  
 * Descriptions:    Adds custom Gutenberg blocks and admin panel to WooCommerce for bulk coupons
 * Version:         1.0.0
 * Author:          Mauricio Fuentes
 * License:         GPL v2 or Later
 * Text Domain:     duam-features-adds
 */

// Defines root directory for plugin
if ( ! defined( 'DUAM_FEATURES_DIR_PATH' ) ) {
    define( 'DUAM_FEATURES_DIR_PATH', plugin_dir_path( __FILE__ ) );
};

if ( ! defined( 'DUAM_FEATURES_URI' ) ) {
    define( 'DUAM_FEATURES_URI', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
}

require_once DUAM_FEATURES_DIR_PATH . 'inc/helpers/autoloader.php';

function duam_get_plugin_instance() {
    \DUAM_FEATURES\Inc\DUAM_FEATURES::get_instance();
}

duam_get_plugin_instance();