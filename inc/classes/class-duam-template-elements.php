<?php
/**
 * Class for template elements used in plugin
 * 
 * @package duam-features
 */
namespace DUAM_FEATURES\Inc;

use DUAM_FEATURES\Inc\Traits\Singleton;

class Duam_Template_Elements {
    use Singleton;

    protected function __construct() {
        $this->setup_hooks();
    }

    protected function setup_hooks() {
        /**
         * Remove any default action
         */
        add_action( 'init', [ $this, 'duam_remove_default_checkout_button' ] );
    

        
        /**
         * Actions for template elements on plugin
         */
        add_action( 'wp_footer', [ $this, 'duam_sticky_button' ] );
        add_action( 'woocommerce_proceed_to_checkout', [ $this, 'duam_custom_proceed_to_checkout' ] , 20 );
    }

    /**
     * Sticky button for "CURSOS SENCE"
     * 
     * @return void
     */
    public function duam_sticky_button() {
        $image_url = DUAM_FEATURES_URI . '/assets/img/SENCE.png';
        
        echo '
        <div class="duam-feature-btn">
            <a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">
                Catálogo   <img src=' . esc_attr( $image_url ) . '" alt="SENCE" class="logo-btn">
            </a>
        </div>
        ';
    }

    /**
     * Login form modal before_checkout
     * 
     * @return void
     */
    public function duam_modal_form() {
        echo '
            <button id="openModalBtn" class="checkout-button button alt wc-forward' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) . '">Inicia Sesión para Seguir</button>
            
            <div id="myModal" class="modal">
              <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Modal Título</h2>
                <p>Contenido del modal...</p>
              </div>
            </div>
        ';
    }

    /**
     * Custom proceed to checkout button
     * This template removes the default WC action and setup
     * a custom button that can handles a modal form to login
     * 
     * @return void
     */
    public function duam_custom_proceed_to_checkout() {
        if ( is_user_logged_in() ) {
            wc_get_template( 'cart/proceed-to-checkout-button.php' );
        } else {
            $this->duam_modal_form();
        }
    }

    public function duam_remove_default_checkout_button() {
        remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
    }
}


