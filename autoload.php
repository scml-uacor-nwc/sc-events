<?php
/**
 * Plugin Autoloader
 * 
 * This is the central orchestrator. It will load all our other modules (PostTypes, MetaFields, etc.). It uses the Singleton pattern.
 *
 * @package SCEvents
 */

namespace SCEvents;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

spl_autoload_register(function ( $class ) {
    // Only autoload classes from this namespace.
    if ( strpos( $class, __NAMESPACE__ . '\\' ) !== 0 ) {
        return;
    }

    // Get the path to the class file.
    $file = str_replace(
        [ '\\', __NAMESPACE__ ],
        [ '/', 'includes' ],
        $class
    );

    $file_path = SC_EVENTS_PATH . $file . '.php';

    if ( file_exists( $file_path ) ) {
        require_once $file_path;
    }
});