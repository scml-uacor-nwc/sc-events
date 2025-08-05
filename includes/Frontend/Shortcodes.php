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

    public function register_shortcodes() {
        add_shortcode( 'sc_events', [ $this, 'render_events_shortcode' ] );
        add_shortcode( 'sc_event_details', [ $this, 'render_single_event_details' ] ); // Register the new shortcode
    }

    /**
     * Renders the [sc_event_details] shortcode for the single event view.
     * This is to ensure compatibility with page builders like Avada.
     */
    public function render_single_event_details() {
        // This shortcode should only work on a single event page.
        if ( ! is_singular('event') ) {
            return '';
        }

        // Ensure assets are loaded
        Enqueue::$load_assets = true;

        ob_start();
        // Start the loop to fetch the current post data.
        while ( have_posts() ) :
            the_post();

            $start_date = get_post_meta( get_the_ID(), '_event_start_date_time', true );
            $end_date   = get_post_meta( get_the_ID(), '_event_end_date_time', true );
            $place      = get_post_meta( get_the_ID(), '_event_place', true );
            $registry   = get_post_meta( get_the_ID(), '_event_registry', true );
            $contacts   = get_post_meta( get_the_ID(), '_event_contacts', true );
            $time_range = '';
            if($start_date) { $time_range = date('H:i', strtotime($start_date)); }
            if($end_date && date('H:i', strtotime($start_date)) != date('H:i', strtotime($end_date))) { $time_range .= ' às ' . date('H:i', strtotime($end_date)); }
            ?>
             <article id="post-<?php the_ID(); ?>" <?php post_class('sc-events-single-container'); ?>>
                
                <div class="sc-events-single__breadcrumbs">
                    <span><?php _e('Início', 'sc-events'); ?></span> | <span><?php _e('Agenda', 'sc-events'); ?></span> | <span class="current"><?php the_title(); ?></span>
                </div>

                <header class="sc-events-single__header">
                    <div class="sc-events-single__title-wrapper">
                        <?php the_title( '<h1 class="sc-events-single__title">', '</h1>' ); ?>
                    </div>
                    <div class="sc-events-single__dates-wrapper">
                        <?php if ( $start_date ) : ?>
                            <p><strong>DE:</strong> <?php echo date_i18n( get_option( 'date_format' ), strtotime( $start_date ) ); ?></p>
                        <?php endif; ?>
                         <?php if ( $end_date ) : ?>
                            <p><strong>A:</strong> <?php echo date_i18n( get_option( 'date_format' ), strtotime( $end_date ) ); ?></p>
                        <?php endif; ?>
                    </div>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="sc-events-single__image-wrapper">
                        <?php the_post_thumbnail( 'large', ['class' => 'sc-events-single__image'] ); ?>
                    </div>
                <?php endif; ?>

                <div class="sc-events-single__content entry-content">
                    <?php the_content(); ?>
                </div>

                <div class="sc-events-single__details-grid">
                    <div class="sc-events-single__detail-item"><h3 class="sc-events-single__detail-title"><?php _e( 'Data / hora', 'sc-events' ); ?></h3><p><?php echo esc_html( date_i18n( get_option('date_format'), strtotime($start_date) ) ); ?> | <?php echo esc_html( $time_range ); ?></p></div>
                    <div class="sc-events-single__detail-item"><h3 class="sc-events-single__detail-title"><?php _e( 'Local', 'sc-events' ); ?></h3><p><?php echo esc_html( $place ); ?></p></div>
                    <div class="sc-events-single__detail-item"><h3 class="sc-events-single__detail-title"><?php _e( 'Registo', 'sc-events' ); ?></h3><p><?php _e( 'O registo para esta ação deve ser feito através do seguinte link:', 'sc-events' ); ?> <a href="<?php echo esc_url( $registry ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $registry ); ?></a></p></div>
                    <div class="sc-events-single__detail-item"><h3 class="sc-events-single__detail-title"><?php _e( 'Contactos', 'sc-events' ); ?></h3><p><?php echo nl2br( esc_html( $contacts ) ); ?></p></div>
                </div>
            </article>
            <?php
        endwhile;

        return ob_get_clean();
    }
    
    /**
     * Renders the [sc_events] shortcode for the cards grid.
     */
    public function render_events_shortcode( $atts ) {
        Enqueue::$load_assets = true;
        $atts = shortcode_atts( [ 'limit' => 3, 'category' => '', ], $atts, 'sc_events' );
        $query_args = [];
        if ( ! empty( $atts['category'] ) ) {}
        $events_query = new \WP_Query( $query_args );
        ob_start();
        if ( $events_query->have_posts() ) {
            echo '<div class="sc-events-shortcode-wrapper"><div class="sc-events-archive__grid">';
            while ( $events_query->have_posts() ) {
                $events_query->the_post();
                // HTML for the card goes here...
            }
            echo '</div></div>';
        } else {
            echo '<p>' . __( 'There are no upcoming events scheduled.', 'sc-events' ) . '</p>';
        }
        wp_reset_postdata();
        return ob_get_clean();
    }
}

// The helper function remains the same...
if ( ! function_exists( __NAMESPACE__ . '\sc_events_get_formatted_date' ) ) {
    function sc_events_get_formatted_date( $date_string ) { /* ... */ }
}