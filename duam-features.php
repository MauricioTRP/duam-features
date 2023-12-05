<?php
/**
 * This is the plugin bootstrap file
 * 
 * Plugin Name:     Duam Features
 * Plugin URI:      https://github.com/MauricioTRP/duam-features
 * Description:     Plugin para modificar el comportamiento WooCommerce, integra con LMSACE para venta de cursos online
 * Version:         1.0
 * Author:          Mauricio Fuentes
 * Author URI:      https://github.com/MauricioTRP
 * License:         GNU General Public License v3.0
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:     duam-features-adds
 */

// Defines root directory for plugin
if ( ! defined( 'ABSPATH' )) {
    die(); // die if accesed directly
}

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