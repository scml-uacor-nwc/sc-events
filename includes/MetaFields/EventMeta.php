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

    private $meta_box_id = 'sc_events_details_meta_box';
    private $nonce_name = 'sc_events_meta_box_nonce';
    private $nonce_action = 'sc_events_save_meta_box_data';

    public function __construct() {
        add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
        add_action( 'save_post_event', [ $this, 'save_data' ] );
    }

    public function add_meta_box() {
        add_meta_box(
            $this->meta_box_id,
            __( 'Event Details', 'sc-events' ),
            [ $this, 'render_meta_box' ],
            'event',
            'normal',
            'high'
        );
    }

    /**
     * Renders the HTML for the meta box fields, formatting dates correctly.
     */
    public function render_meta_box( $post ) {
        wp_nonce_field( $this->nonce_action, $this->nonce_name );
        
        // Get the values from the database
        $start_date_db = get_post_meta( $post->ID, '_event_start_date_time', true );
        $end_date_db   = get_post_meta( $post->ID, '_event_end_date_time', true );

        // **THE FIX IS HERE:** Convert DB format to the input field's required format.
        $start_date_value = ! empty( $start_date_db ) ? date( 'Y-m-d\TH:i', strtotime( $start_date_db ) ) : '';
        $end_date_value   = ! empty( $end_date_db ) ? date( 'Y-m-d\TH:i', strtotime( $end_date_db ) ) : '';

        // Get other fields
        $place      = get_post_meta( $post->ID, '_event_place', true );
        $registry   = get_post_meta( $post->ID, '_event_registry', true );
        $contacts   = get_post_meta( $post->ID, '_event_contacts', true );
        ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th><label for="event_start_date_time"><?php _e( 'Start Date and Hour', 'sc-events' ); ?></label></th>
                    <td>
                        <input class="regular-text" type="datetime-local" id="event_start_date_time" name="event_start_date_time" value="<?php echo esc_attr( $start_date_value ); ?>" />
                    </td>
                </tr>
                 <tr>
                    <th><label for="event_end_date_time"><?php _e( 'End Date and Hour', 'sc-events' ); ?></label></th>
                    <td>
                        <input class="regular-text" type="datetime-local" id="event_end_date_time" name="event_end_date_time" value="<?php echo esc_attr( $end_date_value ); ?>" />
                        <p class="description"><?php _e('Leave empty if this is a single-day event or the end time is not applicable.', 'sc-events'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="event_place"><?php _e( 'Place', 'sc-events' ); ?></label></th>
                    <td><input class="large-text" type="text" id="event_place" name="event_place" value="<?php echo esc_attr( $place ); ?>" /></td>
                </tr>
                <tr>
                    <th><label for="event_registry"><?php _e( 'Registry (URL)', 'sc-events' ); ?></label></th>
                    <td><input class="large-text" type="url" id="event_registry" name="event_registry" placeholder="https://example.com/register" value="<?php echo esc_attr( $registry ); ?>" /></td>
                </tr>
                <tr>
                    <th><label for="event_contacts"><?php _e( 'Contacts', 'sc-events' ); ?></label></th>
                    <td><textarea id="event_contacts" name="event_contacts" rows="4" class="large-text"><?php echo esc_textarea( $contacts ); ?></textarea></td>
                </tr>
            </tbody>
        </table>
        <?php
    }

    /**
     * Saves the custom meta data with standardized date formats.
     */
    public function save_data( $post_id ) {
        // --- Security Checks (no changes here) ---
        if (!isset($_POST[$this->nonce_name]) || !wp_verify_nonce($_POST[$this->nonce_name], $this->nonce_action) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
            return;
        }

        // --- Sanitize and Save Data (no changes here) ---
        if ( ! empty( $_POST['event_start_date_time'] ) ) {
            $raw_start_date = sanitize_text_field( $_POST['event_start_date_time'] );
            $formatted_start_date = date( 'Y-m-d H:i:s', strtotime( $raw_start_date ) );
            update_post_meta( $post_id, '_event_start_date_time', $formatted_start_date );
        } else {
            delete_post_meta( $post_id, '_event_start_date_time' );
        }

        if ( ! empty( $_POST['event_end_date_time'] ) ) {
            $raw_end_date = sanitize_text_field( $_POST['event_end_date_time'] );
            $formatted_end_date = date( 'Y-m-d H:i:s', strtotime( $raw_end_date ) );
            update_post_meta( $post_id, '_event_end_date_time', $formatted_end_date );
        } else {
            delete_post_meta( $post_id, '_event_end_date_time' );
        }

        // Handle other fields
        if ( isset( $_POST['event_place'] ) ) {
            update_post_meta( $post_id, '_event_place', sanitize_text_field( $_POST['event_place'] ) );
        }
        if ( isset( $_POST['event_registry'] ) ) {
            update_post_meta( $post_id, '_event_registry', esc_url_raw( $_POST['event_registry'] ) );
        }
        if ( isset( $_POST['event_contacts'] ) ) {
            update_post_meta( $post_id, '_event_contacts', sanitize_textarea_field( $_POST['event_contacts'] ) );
        }
    }
}