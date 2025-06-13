<?php
/**
 * Plugin Name: NEOs LinkedIn Share Button Shortcode
 * Plugin URI: https://github.com/NeoDesign/LinkedIn-Share
 * Description: Fügt einen Shortcode [linkedin_share_button] hinzu, der einen dynamischen LinkedIn-Share-Button mit SVG/PNG-Fallback ausgibt.
 * Version:     1.1.0
 * Author:      NEOs Onlinemarketing, Professor Dr. Alexander Lutz
 * Author URI:  https://die-neos.de/
 * License:     GPL2
 */


// Direkter Aufruf verhindern
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Shortcode-Handler: Gibt den Share-Button mit Fallback-Bild aus.
 */
function nso_linkedin_share_button_shortcode( $atts ) {
    $a = shortcode_atts(
        array(
            'alt'   => 'LinkedIn teilen',
            'class' => 'nso-linkedin-share-button',
        ),
        $atts
    );

    // Bild-URLs
    $svg_url = plugins_url( 'img/linkedin-share-button-icon.svg', __FILE__ );
    $png_url = plugins_url( 'img/linkedin-share-button-icon.png', __FILE__ );

    // HTML-Ausgabe
    return sprintf(
        '<a href="#" id="nso-linkedin-share-btn" class="%s" target="_blank" rel="noopener noreferrer">'
        . '<picture>'
        . '<source type="image/svg+xml" srcset="%s">'
        . '<img src="%s" alt="%s">'
        . '</picture>'
        . '</a>',
        esc_attr( $a['class'] ),
        esc_url( $svg_url ),
        esc_url( $png_url ),
        esc_attr( $a['alt'] )
    );
}
add_shortcode( 'linkedin_share_button', 'nso_linkedin_share_button_shortcode' );

/**
 * Stylesheet und Inline-Skript einreihen
 */
function nso_enqueue_assets() {
    // CSS
    wp_enqueue_style(
        'nso-linkedin-share-style',
        plugins_url( 'css/style.css', __FILE__ ),
        array(),
        '1.0'
    );

    // JS-Handle registrieren
    wp_register_script( 'nso-lnb-share-inline', '' , array(), '1.0', true );
    wp_enqueue_script( 'nso-lnb-share-inline' );

    $script = "(function(){
        function updateButton(){
            var url = encodeURIComponent(window.location.href);
            var btn = document.getElementById('nso-linkedin-share-btn');
            if(btn){ btn.href = 'https://www.linkedin.com/sharing/share-offsite/?url=' + url; }
        }
        document.addEventListener('DOMContentLoaded', function(){
            updateButton();
            window.addEventListener('hashchange', updateButton);
        });
    })();";

    wp_add_inline_script( 'nso-lnb-share-inline', $script );
}
add_action( 'wp_enqueue_scripts', 'nso_enqueue_assets' );

