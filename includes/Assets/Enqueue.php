<?php
/**
 * Handles the enqueuing of front-end scripts and styles.
 *
 * @package SCEvents
 */

namespace SCEvents\Assets;

class Enqueue {
    /**
     * A static flag that tells the class to load assets.
     * This is set to true by the shortcode when it is rendered.
     *
     * @var bool
     */
    public static $load_assets = false;

    /**
     * Constructor. Hooks the main enqueue method to WordPress.
     */
    public function __construct() {
        // We use a high priority (99) to ensure this check runs after the shortcode has been processed.
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ], 99 );
    }

    /**
     * Checks if assets should be loaded and enqueues them.
     */
    public function enqueue_assets() {
        // The condition is now expanded:
        // 1. Is it the main event archive page?
        // 2. Is it a single event page?
        // 3. OR has our shortcode set the static flag to true?
        if ( is_post_type_archive( 'event' ) || is_singular( 'event' ) || self::$load_assets ) {

            // Enqueue the main stylesheet.
            wp_enqueue_style(
                'sc-events-main-style',
                SC_EVENTS_URL . 'assets/css/sc-events-main.css',
                [], // No dependencies
                SC_EVENTS_VERSION // Version number for cache busting
            );

            // Enqueue the main JavaScript file (for future use with the hover effect).
            wp_enqueue_script(
                'sc-events-main-script',
                SC_EVENTS_URL . 'assets/js/sc-events-main.js',
                [ 'jquery' ], // Depends on jQuery
                SC_EVENTS_VERSION,
                true // Load in the footer
            );
        }
    }
}