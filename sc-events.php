<?php
/**
 * Plugin Name:       SC Events
 * Plugin URI:        https://scml.pt
 * Description:       A modular plugin to create and manage events.
 * Version:           2.4.1
 * Author:            Pedro Matias
 * Author URI:        https://pedromatias.dev/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sc-events
 * Domain Path:       /languages
 */

namespace SCEvents;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Define plugin constants for easy access.
define( 'SC_EVENTS_VERSION', '2.4.1' );
define( 'SC_EVENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'SC_EVENTS_URL', plugin_dir_url( __FILE__ ) );

// Require the autoloader.
require_once SC_EVENTS_PATH . 'autoload.php';

/**
 * Begins execution of the plugin.
 *
 * The main plugin instance is stored in a static variable,
 * so it can be accessed from anywhere using `sc_events()`.
 */
function sc_events() {
    return Core\Plugin::instance();
}

// Kick off
sc_events();

// Register activation hook.
register_activation_hook( __FILE__, [ __NAMESPACE__ . '\Core\Plugin', 'activate' ] );


/**
 * Trimmed excerpt ( the_excerpt() ) by character count for events.
 */
function sc_events_get_trimmed_excerpt( $char_limit = 140 ) { 
    $content = get_the_content();
    $content = strip_tags( strip_shortcodes( $content ) );
    
    if ( mb_strlen( $content ) <= $char_limit ) {
        return $content;
    }
    
    // Trim the string to the character limit
    $excerpt = mb_substr( $content, 0, $char_limit );
    
    // Find the last space to avoid cutting a word in half
    $last_space = mb_strrpos( $excerpt, ' ' );
    
    if ( $last_space !== false ) {
        $excerpt = mb_substr( $excerpt, 0, $last_space );
    }
    
    return $excerpt . '...';
}