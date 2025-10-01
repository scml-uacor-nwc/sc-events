<?php
/**
 * Handles the creation and rendering of plugin shortcodes.
 *
 * @package SCEvents
 */

namespace SCEvents\Frontend;

use SCEvents\Core\Helpers;

class Shortcodes {

    public function __construct() {
        add_action( 'init', [ $this, 'register_shortcodes' ] );
    }

    public function register_shortcodes() {
        add_shortcode( 'sc_events', [ $this, 'render_events_shortcode' ] );
        add_shortcode( 'sc_event_details', [ $this, 'render_single_event_details' ] );
    }

    public function render_single_event_details() {
        if ( ! is_singular('event') ) return '';
        wp_enqueue_style('sc-events-main-style');
        wp_enqueue_script('sc-events-main-script');
        ob_start();
        while ( have_posts() ) : the_post();
            $template_path = SC_EVENTS_PATH . 'templates/single-event-content.php';
            if ( file_exists( $template_path ) ) {
                include( $template_path );
            }
        endwhile;
        return ob_get_clean();
    }
    
    public function render_events_shortcode( $atts ) {
        wp_enqueue_style('sc-events-main-style');
        wp_enqueue_script('sc-events-main-script');

        $atts = shortcode_atts(
            [
                'limit'          => 3,
                'category'       => '',
                'columns'        => '3',
                'excerpt_length' => 80,
                'hover'          => null,
            ],
            $atts,
            'sc_events'
        );
        
        // --- DEFINITIVE HOVER OVERRIDE LOGIC ---
        $sc_events_options = get_option( 'sc_events_options' );
        $global_hover_disabled = ! empty( $sc_events_options['disable_archive_hover'] );
        $is_hover_disabled = false;

        if ( is_null( $atts['hover'] ) ) {
            // If the 'hover' attribute was NOT used in the shortcode, use the global setting.
            $is_hover_disabled = $global_hover_disabled;
        } else {
            // If the 'hover' attribute WAS used, it overrides the global setting.
            $is_hover_disabled = ! filter_var( $atts['hover'], FILTER_VALIDATE_BOOLEAN );
        }

        // --- Class preparation logic ---
        $allowed_cols = ['1', '2', '3'];
        $columns = in_array( $atts['columns'], $allowed_cols ) ? $atts['columns'] : '3';
        $grid_class = 'sc-events-archive__grid sc-events-grid--cols-' . esc_attr( $columns );
        if ( $is_hover_disabled ) {
            $grid_class .= ' sc-events-hover-disabled';
        }

        // --- Query Arguments ---
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
                ]
            ];
        }

        $events_query = new \WP_Query( $query_args );

        ob_start();

        if ( $events_query->have_posts() ) {
            echo '<div class="' . $grid_class . '">';
            while ( $events_query->have_posts() ) {
                $events_query->the_post();
                
                $start_date = get_post_meta( get_the_ID(), '_event_start_date_time', true );
                $date_parts = Helpers::get_formatted_date( $start_date );
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
                                <?php echo esc_html( Helpers::get_trimmed_excerpt( intval($atts['excerpt_length']) ) ); ?>
                            </div>
                        </div>
                    </div>
                </a>
                <?php
            }
            echo '</div>';
            
            // Add agenda button if enabled in settings
            if ( ! empty( $sc_events_options['show_agenda_button'] ) ) {
                $agenda_button_style = isset( $sc_events_options['agenda_button_style'] ) ? $sc_events_options['agenda_button_style'] : 'default-blue';
                $agenda_button_classes = isset( $sc_events_options['agenda_button_classes'] ) ? $sc_events_options['agenda_button_classes'] : '';
                
                $button_class = 'sc-events-agenda-btn';
                if ( $agenda_button_style === 'theme' ) {
                    $button_class .= ' wp-element-button button btn';
                }
                if ( ! empty( $agenda_button_classes ) ) {
                    $button_class .= ' ' . esc_attr( $agenda_button_classes );
                }
                ?>
                <div class="sc-events-agenda-button-container">
                    <a href="/agenda" class="<?php echo esc_attr( $button_class ); ?>" data-style="<?php echo esc_attr( $agenda_button_style ); ?>">
                        <?php _e( 'Ver agenda completa', 'sc-events' ); ?>
                    </a>
                </div>
                <?php
            }
        } else {
            echo '<p>' . __( 'There are no upcoming events scheduled at this time.', 'sc-events' ) . '</p>';
        }

        wp_reset_postdata();
        return ob_get_clean();
    }
}