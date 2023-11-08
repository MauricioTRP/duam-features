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

    echo '
            <div class="duam-feature-btn">
                <a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">
                    <span>Catálogo SENCE</span>
                </a>
            </div>
    ';
}


add_action( 'wp_footer', 'duam_sticky_button' );