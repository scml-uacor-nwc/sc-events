<?php
/**
 * Handles the creation and rendering of plugin shortcodes.
 *
 * @package SCEvents
 */

namespace SCEvents\Frontend;

use SCEvents\Assets\Enqueue;

class Shortcodes {

    public function __construct() {
        add_action( 'init', [ $this, 'register_shortcodes' ] );
    }

    /**
     * Registers all the shortcodes for the plugin.
     */
    public function register_shortcodes() {
        add_shortcode( 'sc_events', [ $this, 'render_events_shortcode' ] );
    }

    /**
     * Renders the [sc_events] shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string The shortcode HTML output.
     */
    public function render_events_shortcode( $atts ) {
        // 1. Tell our asset loader that we need styles on this page.
        Enqueue::$load_assets = true;

        // 2. Set default attributes and merge them with user-provided ones.
        $atts = shortcode_atts(
            [
                'limit'    => 3,    // Default to showing 3 events
                'category' => '',   // Default to showing all categories
            ],
            $atts,
            'sc_events'
        );

        // 3. Set up the arguments for our database query.
        $query_args = [
            'post_type'      => 'event',
            'post_status'    => 'publish',
            'posts_per_page' => intval( $atts['limit'] ),
            'meta_key'       => '_event_start_date_time', // Order by start date
            'orderby'        => 'meta_value',
            'order'          => 'ASC', // Show upcoming events first
            'meta_query'     => [ // Only show events that haven't happened yet
                [
                    'key'     => '_event_start_date_time',
                    'value'   => date( 'Y-m-d H:i:s' ),
                    'compare' => '>=',
                    'type'    => 'DATETIME',
                ],
            ],
        ];

        // 4. If a category is specified, add it to the query.
        if ( ! empty( $atts['category'] ) ) {
            $query_args['tax_query'] = [
                [
                    'taxonomy' => 'event_category',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( $atts['category'] ),
                ],
            ];
        }

        // 5. Run the query.
        $events_query = new \WP_Query( $query_args );

        // 6. Generate the HTML using output buffering.
        ob_start();

        if ( $events_query->have_posts() ) {
            // We use the same class names as archive-event.php so the same CSS applies automatically.
            echo '<div class="sc-events-shortcode-wrapper">';
            echo '<div class="sc-events-archive__grid">';

            while ( $events_query->have_posts() ) {
                $events_query->the_post();
                
                // You can copy the exact card structure from archive-event.php
                $start_date = get_post_meta( get_the_ID(), '_event_start_date_time', true );
                $date_parts = sc_events_get_formatted_date( $start_date ); // We need to ensure this function is available or redefine it. See note below.
                $categories = get_the_terms( get_the_ID(), 'event_category' );
                ?>
                <a href="<?php the_permalink(); ?>" class="sc-events-card">
                    <?php if ( $date_parts ) : ?>
                        <div class="sc-events-card__date">
                            <span class="sc-events-card__day"><?php echo esc_html( $date_parts['day'] ); ?></span>
                            <span class="sc-events-card__month"><?php echo esc_html( $date_parts['month'] ); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="sc-events-card__details">
                        <h2 class="sc-events-card__title"><?php the_title(); ?></h2>
                        <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
                            <p class="sc-events-card__category"><?php echo esc_html( $categories[0]->name ); ?></p>
                        <?php endif; ?>
                    </div>
                </a>
                <?php
            }

            echo '</div>'; // end .sc-events-archive__grid
            echo '</div>'; // end .sc-events-shortcode-wrapper
        } else {
            echo '<p>' . __( 'There are no upcoming events scheduled at this time.', 'sc-events' ) . '</p>';
        }

        // Restore original Post Data
        wp_reset_postdata();

        return ob_get_clean();
    }
}

/**
 * Helper function to format date for shortcode.
 * This is duplicated from archive-event.php to make this file self-contained.
 */
if ( ! function_exists( __NAMESPACE__ . '\sc_events_get_formatted_date' ) ) {
    function sc_events_get_formatted_date( $date_string ) {
        if ( empty( $date_string ) ) return null;
        $timestamp = strtotime( $date_string );
        $day       = date( 'd', $timestamp );
        $month_num = date( 'n', $timestamp );
        $pt_months = [ '', 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ' ];
        return [ 'day' => $day, 'month' => $pt_months[ $month_num ] ];
    }
}