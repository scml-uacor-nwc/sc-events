<?php
/**
 * Handles the meta fields for the Event post type.
 *
 * @package SCEvents
 */

namespace SCEvents\MetaFields;

/**
 * Creates the meta box and handles the saving of custom event data.
 */
class EventMeta {

    /**
     * The unique ID for the meta box.
     * @var string
     */
    private $meta_box_id = 'sc_events_details_meta_box';

    /**
     * The nonce name for security.
     * @var string
     */
    private $nonce_name = 'sc_events_meta_box_nonce';

    /**
     * The nonce action for security.
     * @var string
     */
    private $nonce_action = 'sc_events_save_meta_box_data';

    /**
     * Constructor. Hooks the necessary actions for meta box creation and saving.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
        // We use 'save_post_event' to ensure this only runs for our 'event' post type.
        add_action( 'save_post_event', [ $this, 'save_data' ] );
    }

    /**
     * Adds the meta box to the 'event' post type editor screen.
     */
    public function add_meta_box() {
        add_meta_box(
            $this->meta_box_id,
            __( 'Event Details', 'sc-events' ),
            [ $this, 'render_meta_box' ],
            'event', // The post type to show the meta box on.
            'normal', // The context (where on the screen).
            'high' // The priority.
        );
    }

    /**
     * Renders the HTML for the meta box fields.
     *
     * @param \WP_Post $post The current post object.
     */
    public function render_meta_box( $post ) {
        // Add a nonce field for security verification. This is critical.
        wp_nonce_field( $this->nonce_action, $this->nonce_name );

        // Get existing values from the database to populate the fields.
        $start_date = get_post_meta( $post->ID, '_event_start_date_time', true );
        $end_date   = get_post_meta( $post->ID, '_event_end_date_time', true );
        $place      = get_post_meta( $post->ID, '_event_place', true );
        $registry   = get_post_meta( $post->ID, '_event_registry', true );
        $contacts   = get_post_meta( $post->ID, '_event_contacts', true );
        ?>

        <table class="form-table">
            <tbody>
                <tr>
                    <th><label for="event_start_date_time"><?php _e( 'Start Date and Hour', 'sc-events' ); ?></label></th>
                    <td>
                        <input class="regular-text" type="datetime-local" id="event_start_date_time" name="event_start_date_time" value="<?php echo esc_attr( $start_date ); ?>" />
                    </td>
                </tr>
                 <tr>
                    <th><label for="event_end_date_time"><?php _e( 'End Date and Hour', 'sc-events' ); ?></label></th>
                    <td>
                        <input class="regular-text" type="datetime-local" id="event_end_date_time" name="event_end_date_time" value="<?php echo esc_attr( $end_date ); ?>" />
                        <p class="description"><?php _e('Leave empty if this is a single-day event or the end time is not applicable.', 'sc-events'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="event_place"><?php _e( 'Place', 'sc-events' ); ?></label></th>
                    <td>
                        <input class="large-text" type="text" id="event_place" name="event_place" value="<?php echo esc_attr( $place ); ?>" />
                    </td>
                </tr>
                <tr>
                    <th><label for="event_registry"><?php _e( 'Registry (URL)', 'sc-events' ); ?></label></th>
                    <td>
                        <input class="large-text" type="url" id="event_registry" name="event_registry" placeholder="https://example.com/register" value="<?php echo esc_attr( $registry ); ?>" />
                    </td>
                </tr>
                <tr>
                    <th><label for="event_contacts"><?php _e( 'Contacts', 'sc-events' ); ?></label></th>
                    <td>
                        <textarea id="event_contacts" name="event_contacts" rows="4" class="large-text"><?php echo esc_textarea( $contacts ); ?></textarea>
                        <p class="description"><?php _e('Enter contact information, one item per line.', 'sc-events'); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php
    }

    /**
     * Saves the custom meta data when a post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save_data( $post_id ) {
        // --- Security Checks ---

        // 1. Check if our nonce is set.
        if ( ! isset( $_POST[ $this->nonce_name ] ) ) {
            return;
        }

        // 2. Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST[ $this->nonce_name ], $this->nonce_action ) ) {
            return;
        }

        // 3. If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // 4. Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // --- Sanitize and Save Data ---

        // An array of our meta keys and their sanitization functions.
        $meta_fields = [
            '_event_start_date_time' => 'sanitize_text_field',
            '_event_end_date_time'   => 'sanitize_text_field',
            '_event_place'           => 'sanitize_text_field',
            '_event_registry'        => 'esc_url_raw',
            '_event_contacts'        => 'sanitize_textarea_field',
        ];

        foreach ( $meta_fields as $key => $sanitization_function ) {
            // The field name in the <form> is the key without the leading underscore.
            $form_field_name = ltrim( $key, '_' );

            if ( isset( $_POST[ $form_field_name ] ) ) {
                $value = call_user_func( $sanitization_function, $_POST[ $form_field_name ] );
                update_post_meta( $post_id, $key, $value );
            } else {
                // If a field is not submitted (e.g., an unchecked checkbox),
                // you might want to delete the meta key. For our text fields, this is less critical.
                delete_post_meta( $post_id, $key );
            }
        }
    }
}