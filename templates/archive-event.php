<?php
/**
 * The template for displaying the event archive.
 * @package SCEvents
 */

get_header();

// Get the plugin options from the DB.
$sc_events_options = get_option( 'sc_events_options' );
$is_hover_disabled = ! empty( $sc_events_options['disable_archive_hover'] );

// Prepare the CSS class for the grid.
$grid_classes = 'sc-events-archive__grid sc-events-grid--cols-3';
if ( $is_hover_disabled ) {
    $grid_classes .= ' sc-events-hover-disabled';
}

?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <header class="sc-events-archive__header">
            <p class="sc-events-archive__sub-title"><?php _e( 'AGENDA', 'sc-events' ); ?></p>
            <h1 class="sc-events-archive__title"><?php post_type_archive_title(); ?></h1>
        </header>

        <?php if ( have_posts() ) : ?>
            
            <div class="<?php echo esc_attr( $grid_classes ); ?>">
                
                <?php while ( have_posts() ) : the_post();
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
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php _e( 'No events found.', 'sc-events' ); ?></p>
        <?php endif; ?>
    </main>
</div>
<?php get_footer();