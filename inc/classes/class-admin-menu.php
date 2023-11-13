<?php
/**
 * this class handles the admin menu interface to bulk create coupons
 * 
 * @package duam-features
 */
namespace DUAM_FEATURES\Inc;

use DUAM_FEATURES\Inc\Traits\Singleton;

class Duam_Admin_Menu {
    use Singleton;

    protected function __construct() {
        $this->setup_hooks();
    }

    protected function setup_hooks() {
        add_action( 'admin_menu', [ $this, 'duam_plugin_menu' ] );
    }

    /**
     * Admin Hook page
     */
    public function duam_plugin_menu(){
        
        // Add a new top level menu
        add_menu_page( 
            'Cupones venta masiva', 
            'Cupones para venta masiva', 
            'manage-options', 
            'duam_features', 
            'duam_admin_page', 
            99 
        );
    }

    /**
     * HTML for admin page
     */
    public function duam_admin_page() {
        $page =  '
        <div class="wrap">
            Página Administración
        </div>
        ';

        echo $page;
    }
}