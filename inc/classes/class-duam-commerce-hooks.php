<?php
/**
 * Class for custom WC hooks for DUAM Needs
 * 
 * @package duam-features
 */

namespace DUAM_FEATURES\Inc;
use DUAM_FEATURES\Inc\Traits\Singleton;
use \Exception;
use \WP_Error;

class Duam_Commerce_Hooks {
    use Singleton;

    protected function __construct() {
        $this->setup_hooks();
    }

    protected function setup_hooks() {
        /**
         * Add actions
         */
        // add_action( 'wp_footer', [ $this, 'listar_hooks_activos'] );
        add_action('woocommerce_order_status_changed', [ $this, 'change_order_state_if_coupon' ], 10, 2);
        add_action( 'init', [ $this, 'custom_login_redirect' ] );
    }

    /**
     * This functions checks if it has a 100% discount in one order
     * and set the status of order to completed to trigger enrollment
     * 
     * @params $string $order_id-> the ID of the current order ,  $old_status->the current status of the order
     * @return void
     */

    public function change_order_state_if_coupon( $order_id, $old_status ) {
        $order = wc_get_order( $order_id );
        // Verifie if cart total is 0
        if ( $order->get_total() == 0 && $old_status !== 'completed' ) {
            // Update order status to "completed"
            $order->update_status('completed');
        }
    }

    public function custom_login_redirect() {
       /**
        * Sign in / Register
        */
        static $valid_nonce = null;

        if ( null === $valid_nonce ) {
            $nonce_value = wc_get_var( $_REQUEST[ 'woocommerce-login-nonce' ] );

            $valid_nonce = wp_verify_nonce( $nonce_value, 'woocommerce-login' );
        }

        if ( isset( $_POST[ 'duam-login' ], $_POST[ 'username' ], $_POST[ 'password' ] ) && $valid_nonce ) {
            try {
                $credentials = array(
                    'user_login' => sanitize_text_field($_POST['username']),
                    'user_password' => sanitize_text_field($_POST['password']),
                    'remember' => isset( $_POST[ 'rememberme' ] )
                );

                echo '<pre>';
                echo '<h1> Existe WP_Error o Duam_WP_Error? </h1>';
                echo '<h2> WP_Error </h2>';
                print_r( class_exists( 'WP_Error' ) );
                echo '<h2> Exception </h2>';
                print_r( class_exists( 'Exception' ) );



                if ( empty( $credentials[ 'user_login' ] ) ) {
                    throw new Exception( '<strong>' . __( 'Error:', 'woocommerce' ) . '</strong>' . __( 'Username is required.', 'woocommerce' ) );
                }

                // On multisite, ensure user exists on current site, if not add them before allowing login.
				if ( is_multisite() ) {
					$user_data = get_user_by( is_email( $creds['user_login'] ) ? 'email' : 'login', $creds['user_login'] );

					if ( $user_data && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
						add_user_to_blog( get_current_blog_id(), $user_data->ID, 'customer' );
					}
				}

                // Perform the login
                $user = wp_signon( $credentials );

                if ( is_wp_error( $user ) ) {
                    throw new Exception( $user->get_error_message() );
                } else {
                    // redirect to checkout
                    if ( strpos( $_POST[ '_wp_http_referer' ], 'my-account' ) === false || strpos( $_POST[ '_wp_http_referer' ], 'mi-cuenta' === false ) ) {
                        $checkout_url = wc_get_checkout_url();
                        wp_safe_redirect( $checkout_url );
                    }
                }
            } catch ( Exception $e ) {
                wc_add_notice( apply_filters( 'login_errors', $e->getMessage() ), 'error' );
                do_action( 'woocommerce_login_failed' );
            }
        }
    }
}


    
    /**
     * Provisory funcion to list active hooks available on site
     * must uncomment on $this->setup_hooks()
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

