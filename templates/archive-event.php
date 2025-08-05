<?php
/**
 * The template for displaying the event archive (the cards grid).
 *
 * @package SCEvents
 */

get_header();

/**
 * Helper function to format date and translate month to Portuguese abbreviation.
 */
function sc_events_get_formatted_date( $date_string ) {
    if ( empty( $date_string ) ) {
        return null;
    }
    $timestamp = strtotime( $date_string );
    $day       = date( 'd', $timestamp );
    $month_num = date( 'n', $timestamp );
    $pt_months = [ '', 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ' ];

    return [
        'day'   => $day,
        'month' => $pt_months[ $month_num ],
    ];
}
?>

<div id="primary" class="sc-events-archive content-area">
    <main id="main" class="site-main">

        <header class="sc-events-archive__header">
            <p class="sc-events-archive__sub-title"><?php _e( 'AGENDA', 'sc-events' ); ?></p>
            <h1 class="sc-events-archive__title"><?php post_type_archive_title(); ?></h1>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="sc-events-archive__grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    $start_date = get_post_meta( get_the_ID(), '_event_start_date_time', true );
                    $date_parts = sc_events_get_formatted_date( $start_date );
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
                <?php endwhile; ?>
            </div>

            <?php the_posts_pagination(); // For navigating to more pages of events ?>

        <?php else : ?>
            <p><?php _e( 'No events found.', 'sc-events' ); ?></p>
        <?php endif; ?>

    </main>
</div>

<?php
get_footer();