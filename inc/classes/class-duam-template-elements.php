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
         * Actions for template elements on plugin
         */
        add_action( 'wp_footer', [ $this, 'duam_sticky_button' ] );
        add_action( 'wp_footer', [ $this, 'duam_modal_form' ], 10 );
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
        if ( ! is_user_logged_in()  && is_checkout() ) {
        echo '
            <button id="openModalBtn">Abrir Modal</button>
            
            <div id="myModal" class="modal">
              <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Modal Título</h2>
                <p>Contenido del modal...</p>
              </div>
            </div>
        ';
        }
    }
}


