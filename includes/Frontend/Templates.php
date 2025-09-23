<?php
/**
 * Handles loading custom templates for the plugin.
 *
 * @package SCEvents
 */

namespace SCEvents\Frontend;

class Templates {
    public function __construct() {
        add_filter( 'template_include', [ $this, 'load_template' ] );
        add_action( 'init', [ $this, 'add_rewrite_rules' ] );
        add_filter( 'query_vars', [ $this, 'add_query_vars' ] );
    }

    public function load_template( $template ) {
        // Check for the agenda page (upcoming events)
        if ( get_query_var( 'agenda' ) ) {
            return $this->get_template_path( 'agenda.php' );
        }

        // Check for the event archive page (the list of all events)
        if ( is_post_type_archive( 'event' ) ) {
            return $this->get_template_path( 'archive-event.php' );
        }

        // Check for a single event page
        if ( is_singular( 'event' ) ) {
            return $this->get_template_path( 'single-event.php' );
        }

        return $template;
    }

    /**
     * Add rewrite rules for the agenda page.
     */
    public function add_rewrite_rules() {
        add_rewrite_rule( '^agenda/?$', 'index.php?agenda=1', 'top' );
    }

    /**
     * Add custom query vars.
     *
     * @param array $vars Existing query vars.
     * @return array Modified query vars.
     */
    public function add_query_vars( $vars ) {
        $vars[] = 'agenda';
        return $vars;
    }

    /**
     * Looks for a template file, allowing themes to override it.
     *
     * @param string $template_name The name of the template file.
     * @return string The path to the template file.
     */
    private function get_template_path( $template_name ) {
        // Check if the active theme has a custom version of the template.
        $theme_template = get_stylesheet_directory() . '/sc-events/' . $template_name;
        if ( file_exists( $theme_template ) ) {
            return $theme_template;
        }

        // If not, use the template included with the plugin.
        $plugin_template = SC_EVENTS_PATH . 'templates/' . $template_name;
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }

        // Fallback to a default template if something fails.
        return '';
    }
}