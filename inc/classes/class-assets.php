<?php
/**
 * Enqueue plugin assets
 */

namespace DUAM_FEATURES\Inc;
use DUAM_FEATURES\Inc\Traits\Singleton;

class Assets {
    use Singleton;

	protected function __construct() {
		$this->setup_hooks();
    }

    protected function setup_hooks() {
        /**
         * Add actions
         */

         add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
         add_action( 'wp_enqueue_scripts', [ $this, 'register_styles'] );
    }

    public function register_scripts() {
        // Register Scripts
        wp_register_script( 'duam-js', DUAM_FEATURES_URI . '/assets/js/main.js', ['jquery'], filemtime( plugin_dir_url( __FILE__ ) . 'assets/js/main.js' ) ,true );

        // Enqueue Scripts
        wp_enqueue_script( 'duam-js' );
    }

    public function register_styles() {
        // Register Styles
        wp_register_style( 'duam-css', DUAM_FEATURES_URI . '/assets/css/style.css', [], false, 'all' );

        // Enqueue Styles
        wp_enqueue_style( 'duam-css' );
    }
}