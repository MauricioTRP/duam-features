<?php
/**
 * Bootstraps the plugin elements
 */

namespace DUAM_FEATURES\Inc;

use DUAM_FEATURES\Inc\Traits\Singleton;

class DUAM_FEATURES {
    use Singleton;

	protected function __construct() {
        // Load classes
        Assets::get_instance();
        Duam_Commerce_Hooks::get_instance();
        Duam_Template_Elements::get_instance();
        Duam_Custom_Forms_Handler::get_instance();
        Duam_User_Functions::get_instance(); // class used to generate users on WP
        // Duam_Admin_Menu::get_instance();
	}
}