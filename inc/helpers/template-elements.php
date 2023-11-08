<?php
/**
 * This template renders a single button with SO classes for CSS styling
 * 
 * @package duam-features
 */


/**
 * Cursos SENCE btn
 * 
 * @return string
 */
function duam_sticky_button() {
    $image_url = DUAM_FEATURES_URI . '/assets/img/SENCE.png';
    
    echo '
            <div class="duam-feature-btn">
                <a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">
                    Catálogo   <img src=' . $image_url . '" alt="SENCE" class="logo-btn">
                </a>
            </div>
    ';
}


add_action( 'wp_footer', 'duam_sticky_button' );