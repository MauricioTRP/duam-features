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
        // add_action( 'wp_footer', [ $this, 'listar_hooks_activos'] );
        add_action( 'wp_footer', [ $this, 'duam_sticky_button' ] );
        add_action( 'wp_footer', [ $this, 'duam_modal_form' ] ); // form modal hidden on footer
        add_action( 'woocommerce_proceed_to_checkout', [ $this, 'duam_custom_proceed_to_checkout' ] , 20 ); // unhook custom proceed to checkout button and hook a custom one
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
        $form = '
            <form method="post" id="duam-login">
                <p>
                    <label for="username">' . esc_html( 'Nombre de usuario o correo', 'woocommerce' ) .  '&nbsp;<span class="required">*</span></label>   
                    <input type="text" name="username" id="username" autocomplete="nombre de usuario" value"" />
                </p> 
                <p>
                    <label for="password">' . esc_html( 'Password', 'woocommerce' ) .  '&nbsp;<span class="required">*</span></label>   
                    <input type="password" name="password" id="password" autocomplete="nombre de usuario" value"" />
                </p> 

                <p>
                    <label>
                        <input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span>' . esc_html( 'Remember me', 'woocommerce' ) . '</span>
                    </label>
                    ' . wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ) . '
                    <button type="submit" class="button" name="login" value="'. esc_attr( 'Log in', 'woocommerce' ) . '">' . esc_html( 'Log in', 'woocommerce' ) . '</button>
                </p>

                <p>
                    <a href=' . esc_url( wp_lostpassword_url() ) . '>' . esc_html( '¿Perdiste tu clave?' ) . '</a>
                </p>
                
            </form>
        ';

        // Muestra el contenido dentro del modal junto con el formulario
        echo '
            
            
            <div id="myModal" class="modal">
              <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Inicia Sesión o Registrate para Continuar</h2>
                ' .   $form  . '
              </div>
            </div>
        ';
    }

    /**
     * Modal button trigger
     * 
     * @return void
     */
    public function duam_modal_form_button_trigger() {
        echo '<button id="openModalBtn" class="checkout-button button alt wc-forward' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) . '">Finalizar Matrícula</button>';
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
            $this->duam_modal_form_button_trigger();
        }
    }

    public function duam_remove_default_checkout_button() {
        remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
    }

    /**
     * List all active hooks on footer
     * For debugging porpurses
     * 
     * @return void
     */
    public function listar_hooks_activos() {
        global $wp_filter;
        echo '<pre>';
        foreach ($wp_filter as $tag => $hook) {
            echo '<strong>' . $tag . '</strong>' . ': ';
            foreach ($hook as $priority => $functions) {
                echo $priority . ' ';
            }
            echo '<br>';
        }
        echo '</pre>';
    }
}


