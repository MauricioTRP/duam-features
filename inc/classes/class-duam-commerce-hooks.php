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
        // Verificar si el total del pedido es igual a 0
        if ( $order->get_total() == 0 && $old_status !== 'completed' ) {
            // Actualizar el estado del pedido a "Completado"
            $order->update_status('completed');
        }
    }
}

