<?php
/**
 * Registers the custom post type (CPT) for Events and its associated taxonomies.
 *
 * @package SCEvents
 */

namespace SCEvents\PostTypes;

/**
 * Handles the registration of the 'event' custom post type and 'event_category' taxonomy.
 */
class Event {

    private $post_type = 'event'; // The slug for the custom post type.

    private $taxonomy_slug = 'event_category'; // The slug for the custom taxonomy.

    /**
     * Constructor. Hooks the registration functions to WordPress's 'init' action.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'register_taxonomy' ] );
    }

    /**
     * Registers the custom taxonomy.
     */
    public function register_taxonomy() {
        $labels = [
            'name'              => _x( 'Event Categories', 'taxonomy general name', 'sc-events' ),
            'singular_name'     => _x( 'Event Category', 'taxonomy singular name', 'sc-events' ),
            'search_items'      => __( 'Search Event Categories', 'sc-events' ),
            'all_items'         => __( 'All Event Categories', 'sc-events' ),
            'parent_item'       => __( 'Parent Event Category', 'sc-events' ),
            'parent_item_colon' => __( 'Parent Event Category:', 'sc-events' ),
            'edit_item'         => __( 'Edit Event Category', 'sc-events' ),
            'update_item'       => __( 'Update Event Category', 'sc-events' ),
            'add_new_item'      => __( 'Add New Event Category', 'sc-events' ),
            'new_item_name'     => __( 'New Event Category Name', 'sc-events' ),
            'menu_name'         => __( 'Categories', 'sc-events' ),
        ];

        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true, // Shows category in the All Events list view
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'events/category' ],
            'show_in_rest'      => true, // Important for the Block Editor
        ];

        register_taxonomy( $this->taxonomy_slug, [ $this->post_type ], $args );
    }

    /**
     * Registers the custom post type with all its labels and arguments.
     */
    public function register_post_type() {
        $labels = [
            'name'                  => _x( 'Events', 'Post Type General Name', 'sc-events' ),
            'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'sc-events' ),
            'menu_name'             => __( 'Events', 'sc-events' ),
            'name_admin_bar'        => __( 'Event', 'sc-events' ),
            'archives'              => __( 'Event Archives', 'sc-events' ),
            'attributes'            => __( 'Event Attributes', 'sc-events' ),
            'parent_item_colon'     => __( 'Parent Event:', 'sc-events' ),
            'all_items'             => __( 'All Events', 'sc-events' ),
            'add_new_item'          => __( 'Add New Event', 'sc-events' ),
            'add_new'               => __( 'Add New', 'sc-events' ),
            'new_item'              => __( 'New Event', 'sc-events' ),
            'edit_item'             => __( 'Edit Event', 'sc-events' ),
            'update_item'           => __( 'Update Event', 'sc-events' ),
            'view_item'             => __( 'View Event', 'sc-events' ),
            'view_items'            => __( 'View Events', 'sc-events' ),
            'search_items'          => __( 'Search Event', 'sc-events' ),
            'not_found'             => __( 'Not found', 'sc-events' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'sc-events' ),
            'featured_image'        => __( 'Event Image', 'sc-events' ),
            'set_featured_image'    => __( 'Set event image', 'sc-events' ),
            'remove_featured_image' => __( 'Remove event image', 'sc-events' ),
            'use_featured_image'    => __( 'Use as event image', 'sc-events' ),
            'insert_into_item'      => __( 'Insert into event', 'sc-events' ),
            'uploaded_to_this_item' => __( 'Uploaded to this event', 'sc-events' ),
            'items_list'            => __( 'Events list', 'sc-events' ),
            'items_list_navigation' => __( 'Events list navigation', 'sc-events' ),
            'filter_items_list'     => __( 'Filter events list', 'sc-events' ),
        ];

        $args = [
            'label'                 => __( 'Event', 'sc-events' ),
            'description'           => __( 'Post Type for creating and managing events.', 'sc-events' ),
            'labels'                => $labels,
            'supports'              => [ 'title', 'editor', 'thumbnail' ],
            'taxonomies'            => [ $this->taxonomy_slug ], // Link the taxonomy to the post type
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-calendar-alt',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => 'events',
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        ];

        register_post_type( $this->post_type, $args );
    }
}