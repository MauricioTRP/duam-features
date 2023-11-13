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
        // add_action( 'wp_footer', [ $this, 'listar_hooks_activos'] );
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
        // Verifie if cart total is 0
        if ( $order->get_total() == 0 && $old_status !== 'completed' ) {
            // Update order status to "completed"
            $order->update_status('completed');
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

