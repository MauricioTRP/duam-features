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
        // Duam_Admin_Menu::get_instance();
	}
}