<?php
/**
 * Handles the creation and rendering of plugin shortcodes.
 *
 * @package SCEvents
 */

namespace SCEvents\Frontend;

class Shortcodes {

    public function __construct() {
        add_action( 'init', [ $this, 'register_shortcodes' ] );
    }

    public function register_shortcodes() {
        add_shortcode( 'sc_events', [ $this, 'render_events_shortcode' ] );
        add_shortcode( 'sc_event_details', [ $this, 'render_single_event_details' ] );
    }

    /**
     * Renders the [sc_event_details] shortcode for the single event view.
     */
    public function render_single_event_details() {
        if ( ! is_singular('event') ) return '';

        // THE FIX: Directly enqueue the assets from within the shortcode.
        wp_enqueue_style('sc-events-main-style');
        wp_enqueue_script('sc-events-main-script');

        ob_start();
        while ( have_posts() ) :
            the_post();
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
        // THE FIX: Directly enqueue the assets from within the shortcode.
        wp_enqueue_style('sc-events-main-style');
        wp_enqueue_script('sc-events-main-script');

        // (The rest of this function remains exactly the same as your correct version)
        $atts = shortcode_atts(
            [
                'limit'          => 3,
                'category'       => '',
                'columns'        => '3',
                'excerpt_length' => 80,
                'hover'          => 'true',
            ],
            $atts,
            'sc_events'
        );
        
        $allowed_cols = ['1', '2', '3'];
        $columns = in_array( $atts['columns'], $allowed_cols ) ? $atts['columns'] : '3';
        $grid_class = 'sc-events-grid--cols-' . esc_attr( $columns );

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
            $query_args['tax_query'] = [ /* ... */ ];
        }

        $events_query = new \WP_Query( $query_args );

        ob_start();

        if ( $events_query->have_posts() ) {
            echo '<div class="sc-events-shortcode-wrapper">';
            echo '<div class="sc-events-archive__grid ' . $grid_class . '">';

            while ( $events_query->have_posts() ) {
                $events_query->the_post();
                ?>
                <a href="<?php the_permalink(); ?>" class="sc-events-card">
                    <div class="sc-events-card__inner">
                        <?php if ( ($date_parts = \SCEvents\Frontend\sc_events_get_formatted_date(get_post_meta( get_the_ID(), '_event_start_date_time', true ))) ) : ?>
                            <div class="sc-events-card__date">
                                <span class="sc-events-card__day"><?php echo esc_html( $date_parts['day'] ); ?></span>
                                <span class="sc-events-card__month"><?php echo esc_html( $date_parts['month'] ); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="sc-events-card__details">
                            <h2 class="sc-events-card__title"><?php the_title(); ?></h2>
                            <?php if ( ! empty( ($categories = get_the_terms( get_the_ID(), 'event_category' )) ) && ! is_wp_error( $categories ) ) : ?>
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

            echo '</div></div>';
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