<?php
/**
 * Plugin Name: NEOs LinkedIn Share Button Shortcode
 * Plugin URI: https://github.com/NeoDesign/LinkedIn-Share
 * Description: Fügt einen Shortcode [linkedin_share_button] hinzu, der einen dynamischen LinkedIn-Share-Button mit SVG/PNG-Fallback ausgibt und separate Ausrichtung für Desktop und Mobil unterstützt. Der Shortcode-Parameter "align_desktop" bestimmt die Ausrichtung ab einer Bildschirmbreite ≥ 768 px. Der Shortcode-Parameter "align_mobile" gilt für Breiten < 768 px. Beide Parameter akzeptieren nur left, center oder right. Beispiel also: [linkedin_share_button align_desktop=left align_mobile=center]. Arbeitet klassenbasiert, kann mehrfach in einer Seite verwendet werden.
 * Version:     1.4.0
 * Author:      NEOs Onlinemarketing, Professor Dr. Alexander Lutz
 * Author URI:  https://die-neos.de/
 * License:     GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Shortcode-Handler: Ausrichtung Desktop/Mobil samt Klassen rendert.
 * Parameter:
 *   alt            - Alt-Text des Bildes (default 'LinkedIn teilen')
 *   class          - Zusätzliche CSS-Klassen
 *   align_desktop  - Ausrichtung auf Desktop: left|center|right (default left)
 *   align_mobile   - Ausrichtung auf Mobil: left|center|right (default left)
 */
function nso_linkedin_share_button_shortcode( $atts ) {
    $a = shortcode_atts(
        array(
            'alt'           => 'LinkedIn teilen',
            'class'         => '',
            'align_desktop' => 'left',
            'align_mobile'  => 'left',
        ),
        $atts
    );

    // Validierung
    $desktop = in_array( $a['align_desktop'], array( 'left', 'center', 'right' ) ) ? $a['align_desktop'] : 'left';
    $mobile  = in_array( $a['align_mobile'], array( 'left', 'center', 'right' ) ) ? $a['align_mobile']  : 'left';

    // Bildpfade
    $svg_url = plugins_url( 'img/linkedin-share-button-icon.svg', __FILE__ );
    $png_url = plugins_url( 'img/linkedin-share-button-icon.png', __FILE__ );

    // Wrapper-Klassen
    $wrapper_classes = sprintf(
        'nso-linkedin-share-wrapper desktop-%s mobile-%s %s',
        esc_attr( $desktop ),
        esc_attr( $mobile ),
        esc_attr( $a['class'] )
    );

    // HTML-Ausgabe
    $html  = '<div class="' . $wrapper_classes . '">';
    $html .= '<a class="nso-linkedin-share-button" target="_blank" rel="noopener noreferrer">';
    $html .= '<picture>';
    $html .= '<source type="image/svg+xml" srcset="' . esc_url( $svg_url ) . '">';
    $html .= '<img src="' . esc_url( $png_url ) . '" alt="' . esc_attr( $a['alt'] ) . '">';
    $html .= '</picture>';
    $html .= '</a>';
    $html .= '</div>';

    return $html;
}
add_shortcode( 'linkedin_share_button', 'nso_linkedin_share_button_shortcode' );

/**
 * Enqueue CSS und Inline-JS
 */
function nso_enqueue_assets() {
    // CSS
    wp_enqueue_style(
        'nso-linkedin-share-style',
        plugins_url( 'css/style.css', __FILE__ ),
        array(),
        '1.3'
    );

    // Inline JS für URL-Update
    wp_register_script( 'nso-lnb-share-inline', '', array(), '1.0', true );
    wp_enqueue_script( 'nso-lnb-share-inline' );
    $script = "(function(){
        function updateButton(){
            var url = encodeURIComponent(window.location.href);
            document.querySelectorAll('.nso-linkedin-share-button').forEach(function(btn){
              btn.href = 'https://www.linkedin.com/sharing/share-offsite/?url=' + url;
            });
        }
        document.addEventListener('DOMContentLoaded', function(){ updateButton(); window.addEventListener('hashchange', updateButton); });
    })();";
    wp_add_inline_script( 'nso-lnb-share-inline', $script );
}
add_action( 'wp_enqueue_scripts', 'nso_enqueue_assets' );
