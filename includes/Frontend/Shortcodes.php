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
        add_shortcode( 'sc_event_details', [ $this, 'render_single_event_details' ] );
    }

    /**
     * Renders the [sc_event_details] shortcode for the single event view.
     */
    public function render_single_event_details() {
        // This function remains the same from our previous step to ensure compatibility.
        if ( ! is_singular('event') ) return '';
        Enqueue::$load_assets = true;
        ob_start();
        while ( have_posts() ) :
            the_post();
            // Include the content part for the single event view
            $template_path = SC_EVENTS_PATH . 'templates/single-event-content.php';
            if ( file_exists( $template_path ) ) {
                include( $template_path );
            }
        endwhile;
        return ob_get_clean();
    }
    
    /**
     * Renders the [sc_events] shortcode with the new 'columns' attribute.
     */
    public function render_events_shortcode( $atts ) {
        Enqueue::$load_assets = true;

        // Add 'columns' to the list of attributes, with a default of '3'.
        $atts = shortcode_atts(
            [
                'limit'    => 3,
                'category' => '',
                'columns'  => '3',
                'excerpt_length' => 80,
                'hover'          => 'true',
            ],
            $atts,
            'sc_events'
        );
        
        // Columns logic
        $allowed_cols = ['1', '2', '3'];
        $columns = in_array( $atts['columns'], $allowed_cols ) ? $atts['columns'] : '3';
        $grid_class = 'sc-events-grid--cols-' . esc_attr( $columns );

        // HOVER LOGIC
        // If the user sets hover="false", add disabling class.
        $hover_enabled = filter_var($atts['hover'], FILTER_VALIDATE_BOOLEAN);
        if ( !$hover_enabled ) {
            $grid_class .= ' sc-events-hover-disabled';
        }

        $query_args = [
            'post_type'      => 'event',
            'post_status'    => 'publish',
            'posts_per_page' => intval( $atts['limit'] ),
            'meta_key'       => '_event_start_date_time',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'meta_query'     => [
                [
                    'key'     => '_event_start_date_time',
                    'value'   => date('Y-m-d H:i:s'),
                    'compare' => '>=',
                    'type'    => 'DATETIME',
                ],
            ],
        ];

        if ( ! empty( $atts['category'] ) ) {
            $query_args['tax_query'] = [
                [
                    'taxonomy' => 'event_category',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( $atts['category'] ),
                ],
            ];
        }

        $events_query = new \WP_Query( $query_args );

        ob_start();

        if ( $events_query->have_posts() ) {
            // Adding the dynamic $grid_class to our div.
            echo '<div class="sc-events-shortcode-wrapper">';
            echo '<div class="sc-events-archive__grid ' . $grid_class . '">';

            while ( $events_query->have_posts() ) {
                $events_query->the_post();
                
                $start_date = get_post_meta( get_the_ID(), '_event_start_date_time', true );
                $date_parts = sc_events_get_formatted_date( $start_date );
                $categories = get_the_terms( get_the_ID(), 'event_category' );
                ?>

                <a href="<?php the_permalink(); ?>" class="sc-events-card">
                    <div class="sc-events-card__inner">
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
                            <div class="sc-events-card__excerpt">
                                <?php echo esc_html( \SCEvents\sc_events_get_trimmed_excerpt( intval($atts['excerpt_length']) ) ); ?>
                            </div>
                        </div>
                    </div>
                </a>
                <?php
            }

            echo '</div>'; // end .sc-events-archive__grid
            echo '</div>'; // end .sc-events-shortcode-wrapper
        } else {
            echo '<p>' . __( 'There are no upcoming events scheduled at this time.', 'sc-events' ) . '</p>';
        }

        wp_reset_postdata();

        return ob_get_clean();
    }
}

/**
 * Helper function to format date for cards.
 * This is placed outside the class.
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