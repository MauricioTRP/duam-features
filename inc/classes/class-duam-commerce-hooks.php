<?php
/**
 * Class for custom WC hooks for DUAM Needs
 * 
 * @package duam-features
 */

namespace DUAM_FEATURES\Inc;
use DUAM_FEATURES\Inc\Traits\Singleton;

class Duam_Commerce_Hooks {
    use Singleton;

    protected function __construct() {
        $this->setup_hooks();
    }

    protected function setup_hooks() {
        /**
         * Add actions
         */
        add_action('woocommerce_order_status_changed', [ $this, 'change_order_state_if_coupon' ], 10, 2);
        add_filter( 'woocommerce_registration_auth_new_customer', '__return_true' ); // force login after registration
        add_action( 'init', [ $this, 'duam_hide_coupon_on_cart' ] );
        // add_filter( 'woocommerce_registration_redirect', [ $this, 'duam_customer_redirection' ], 10, 1 );
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

    /**
     * Hook to redirect after registration of user
     */
    function duam_customer_redirection( $redirection_url ) {
        $redirection_url = wc_get_checkout_url();

        return $redirection_url;
    }

    /**
     * Wants to hide coupon on cart
     */
    public function duam_hide_coupon_on_cart() {
        // remueve el formulario cupon sólo en proceed_to_checkout
        // remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
    }
}
