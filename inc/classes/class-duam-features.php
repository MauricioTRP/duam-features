<?php
/**
 * Bootstraps the plugin elements
 */

namespace DUAM_FEATURES\Inc;

use DUAM_FEATURES\Inc\Traits\Singleton;

class DUAM_FEATURES {
    use Singleton;

	public function __construct() {
        // Load classes
        Assets::get_instance();
        Duam_Commerce_Hooks::get_instance();
	}
}