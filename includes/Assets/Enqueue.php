<?php
/**
 * Handles the enqueuing of front-end scripts and styles.
 *
 * @package SCEvents
 */

namespace SCEvents\Assets;

class Enqueue {
    public function __construct() {
        // We now register the assets on every page load. They won't actually load unless called.
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
    }

    /**
     * Registers all plugin assets with WordPress.
     * This makes them available to be enqueued later, on demand.
     */
    public function register_assets() {
        // Register the main stylesheet.
        wp_register_style(
            'sc-events-main-style', // The unique handle for our style
            SC_EVENTS_URL . 'assets/css/sc-events-main.css',
            [],
            SC_EVENTS_VERSION
        );

        // Register the main JavaScript file.
        wp_register_script(
            'sc-events-main-script', // The unique handle for our script
            SC_EVENTS_URL . 'assets/js/sc-events-main.js',
            [ 'jquery' ],
            SC_EVENTS_VERSION,
            true // Load in footer
        );
    }
}