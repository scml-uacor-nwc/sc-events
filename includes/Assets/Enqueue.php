<?php
/**
 * Handles the enqueuing of front-end scripts and styles.
 *
 * @package SCEvents
 */

namespace SCEvents\Assets;

class Enqueue {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'conditionally_enqueue_assets' ] );
    }

    /**
     * Registers the main styles and scripts for the plugin.
     */
    public function register_assets() {
        wp_register_style(
            'sc-events-main-style',
            SC_EVENTS_URL . 'assets/css/sc-events-main.css',
            [],
            SC_EVENTS_VERSION
        );
        wp_register_script(
            'sc-events-main-script',
            SC_EVENTS_URL . 'assets/js/sc-events-main.js',
            [ 'jquery' ],
            SC_EVENTS_VERSION,
            true
        );
    }

    /**
     * Enqueues assets if we are on the main event archive or a single event page.
     * The shortcode will handle enqueuing on its own for other pages.
     */
    public function conditionally_enqueue_assets() {
        if ( is_post_type_archive( 'event' ) || is_singular( 'event' ) ) {
            wp_enqueue_style( 'sc-events-main-style' );
            wp_enqueue_script( 'sc-events-main-script' );
        }
    }
}