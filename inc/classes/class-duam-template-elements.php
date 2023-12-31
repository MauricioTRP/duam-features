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
        // add_action( 'woocommerce_widget_shopping_cart_buttons', [ $this, 'duam_widget_shopping_cart_proceed_to_checkout'], 20 );
    }

    /**
     * Sticky button for "CURSOS SENCE"
     * 
     * @return void
     */
    public function duam_sticky_button() {
        $image_url = DUAM_FEATURES_URI . '/assets/img/SENCE.png';
        $outer_url = 'https://docs.google.com/spreadsheets/d/1LO43CkLXAlW2BvQgQTFPKaATZn_pTY7O/edit?usp=sharing&ouid=113616516354874432697&rtpof=true&sd=true';
        
        echo '
        <div class="duam-feature-btn">
            <a href="' . esc_url( $outer_url ) . '" target="_blank">
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
        $form_login = '
        <div class="content active">
            <form method="post" id="duam-login" class="duam-modal-form">
                <div>
                    <label for="username">' . esc_html__( 'Username or email address', 'woocommerce' ) .  '&nbsp;<span class="required">*</span></label>   
                    <input type="text" name="username" id="username" autocomplete="nombre de usuario" value"" />
                </div> 
                <div>
                    <label for="password">' . esc_html__( 'Password', 'woocommerce' ) .  '&nbsp;<span class="required">*</span></label>   
                    <input type="password" name="password" id="password" autocomplete="nombre de usuario" value"" />
                </div> 

                <div>
                    <label for="rememberme">
                        <input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span>' . esc_html__( 'Remember me', 'woocommerce' ) . '</span>
                    </label>
                    ' . wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ) . '
                    <button type="submit" class="button" name="duam-login" value="'. esc_attr( 'Log in', 'woocommerce' ) . '">' . esc_html__( 'Log in', 'woocommerce' ) . '</button>
                </div>

                <div>
                    <a href=' . esc_url( wp_lostpassword_url() ) . '>' . esc_html( '¿Perdiste tu clave?' ) . '</a>
                </div>
                
            </form>
        </div>
        ';

        /**
         * Register form
         * TODO apply css ordering to better presentation
         */
        $form_register = '
        <div class="content">
            <form method="post" id="duam-register" class="duam-modal-form">
                <div>
                    <label for="reg_email">
                        ' . esc_html__( 'Email address', 'woocommerce' ) . '&nbsp;<span class="required">*</span>
                    </label>
                    <input type="email" name="email" id="reg_email" autocomplete="email" />
                </div>
                <div>
                    <label for="reg_password">
                        ' . esc_html__( 'Password', 'woocommerce' ) . '&nbsp; <span class="required">*</span>
                    </label>
                    <input type="password" name="password" id="reg_password" autocomplete="new-password" />
                </div>
                <div>
                    ' . wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ) . '
                    <button class="button" type="submit" name="duam-register" value="' . esc_attr( 'Register', 'woocommerce' ) . '">
                        ' . esc_html__( 'Register', 'woocommerce' ) . '
                    </button>
                </div>
                <div>'
	                . wc_replace_policy_page_link_placeholders( wc_get_privacy_policy_text( 'registration' ) ) . 
                '</div>
            </form>
        </div>
        ';



        /**
         * Show login and register form within modals
         */
        echo '
            <div id="myModal" class="modal">
              <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Inicia Sesión o Registrate para Continuar</h2>
                <ul class="nav-tab">
                    <li class="nav-link active">Inicia Sesión</li>
                    <li class="nav-link">Registrate</li>
                </ul>
                <div class="nav-content">'.   $form_login  .  $form_register  . '</div>' . '
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
        echo '<button id="openModalBtnCart" class="duam-open-modal-btn checkout-button button alt wc-forward' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) . '">Finalizar Matrícula</button>';
    }

    public function duam_widget_proceed_trigger() {
        $wp_button_class = wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '';
        echo '<button id="openModalBtnWdCart" class="duam-open-modal-btn button checkout wc-forward' . esc_attr( $wp_button_class ) . '">Finalizar Matrícula</button>';
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

    /**
     * Output proceed to checkout widget button
     * 
     * not in use for JS problems (do not listen to click with JQuery)
     */
	function duam_widget_shopping_cart_proceed_to_checkout() {
		$wp_button_class = wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '';
        if ( is_user_logged_in() ) {
            wc_get_template( 'cart/proceed-to-checkout-button.php' );
        } else {
            $this->duam_widget_proceed_trigger();
        }
	}

    public function duam_remove_default_checkout_button() {
        remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
        remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
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


