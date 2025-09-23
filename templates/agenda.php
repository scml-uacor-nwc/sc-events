<?php
/**
 * The template for displaying upcoming events (agenda).
 * @package SCEvents
 */

get_header();

// Enqueue the plugin styles and scripts
wp_enqueue_style('sc-events-main-style');
wp_enqueue_script('sc-events-main-script');

// Get the plugin options from the DB.
$sc_events_options = get_option( 'sc_events_options' );
$is_hover_disabled = ! empty( $sc_events_options['disable_archive_hover'] );

// Prepare the CSS class for the grid.
$grid_classes = 'sc-events-archive__grid sc-events-grid--cols-3';
if ( $is_hover_disabled ) {
    $grid_classes .= ' sc-events-hover-disabled';
}

// Query upcoming events
$current_date = current_time( 'Y-m-d H:i:s' );
$upcoming_events = new WP_Query( [
    'post_type' => 'event',
    'posts_per_page' => -1,
    'meta_query' => [
        [
            'key' => '_event_start_date_time',
            'value' => $current_date,
            'compare' => '>',
            'type' => 'DATETIME'
        ]
    ],
    'meta_key' => '_event_start_date_time',
    'orderby' => 'meta_value',
    'order' => 'ASC'
] );

// Debug information (remove in production)
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
    error_log( 'Agenda Query - Current Date: ' . $current_date );
    error_log( 'Agenda Query - Found Events: ' . $upcoming_events->found_posts );
}

?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <header class="sc-events-archive__header">
            <p class="sc-events-archive__sub-title"><?php _e( 'PRÓXIMOS', 'sc-events' ); ?></p>
            <h1 class="sc-events-archive__title"><?php _e( 'Eventos', 'sc-events' ); ?></h1>
        </header>

        <?php if ( $upcoming_events->have_posts() ) : ?>
            
            <div class="<?php echo esc_attr( $grid_classes ); ?>">
                
                <?php while ( $upcoming_events->have_posts() ) : $upcoming_events->the_post();
                    $start_date = get_post_meta( get_the_ID(), '_event_start_date_time', true );
                    $date_parts = \SCEvents\Core\Helpers::get_formatted_date( $start_date );
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
                                    <?php echo esc_html( \SCEvents\Core\Helpers::get_trimmed_excerpt( 80 ) ); ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p><?php _e( 'Não há eventos futuros agendados.', 'sc-events' ); ?></p>
        <?php endif; ?>
        
        <?php wp_reset_postdata(); ?>
    </main>
</div>
<?php get_footer();