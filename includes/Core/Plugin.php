<?php
/**
 * The main plugin class, responsible for loading modules.
 * @package SCEvents
 */

namespace SCEvents\Core;

use SCEvents\PostTypes;
use SCEvents\MetaFields;
use SCEvents\Admin;
use SCEvents\Assets;
use SCEvents\Frontend;

final class Plugin {

    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        $this->load_modules();
    }

    /**
     * Instantiate and load all the plugin modules.
     */
    private function load_modules() {
        new PostTypes\Event();
        new MetaFields\EventMeta();
        new Admin\Settings();
        new Assets\Enqueue();
        new Frontend\Templates();
        new Frontend\Shortcodes();
        
        // Handle ICS download requests
        add_action( 'init', [ $this, 'handle_ics_download' ] );
        
        // Add body classes for button styling
        add_filter( 'body_class', [ $this, 'add_calendar_button_body_class' ] );
    }
    
    /**
     * Handle ICS file download requests.
     */
    public function handle_ics_download() {
        if ( isset( $_GET['sc_events_ics'] ) && isset( $_GET['event_id'] ) ) {
            $event_id = intval( $_GET['event_id'] );
            if ( $event_id && get_post_type( $event_id ) === 'event' ) {
                ICSGenerator::serve_ics_download( $event_id );
            }
        }
    }
    
    /**
     * Add body class for calendar button styling.
     */
    public function add_calendar_button_body_class( $classes ) {
        $options = get_option( 'sc_events_options' );
        $calendar_button_style = isset( $options['calendar_button_style'] ) ? $options['calendar_button_style'] : 'default-blue';
        $agenda_button_style = isset( $options['agenda_button_style'] ) ? $options['agenda_button_style'] : 'default-blue';
        
        $classes[] = 'sc-events-btn-' . $calendar_button_style;
        $classes[] = 'sc-events-agenda-btn-' . $agenda_button_style;
        
        return $classes;
    }
    
    public static function activate() {
        $post_type = new PostTypes\Event();
        $post_type->register_post_type();
        
        // Register custom rewrite rules for agenda
        $templates = new Frontend\Templates();
        $templates->add_rewrite_rules();
        
        flush_rewrite_rules();
    }
}