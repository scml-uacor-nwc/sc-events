<?php
/**
 * The template for displaying the event archive.
 * @package SCEvents
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <header class="sc-events-archive__header">
            <p class="sc-events-archive__sub-title"><?php _e( 'AGENDA', 'sc-events' ); ?></p>
            <h1 class="sc-events-archive__title"><?php post_type_archive_title(); ?></h1>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="sc-events-archive__grid">
                <?php while ( have_posts() ) : the_post();
                    // We rebuild the card here directly for simplicity in the main archive.
                    $start_date = get_post_meta( get_the_ID(), '_event_start_date_time', true );
                    $timestamp = strtotime( $start_date );
                    $day = date( 'd', $timestamp );
                    $month_num = date( 'n', $timestamp );
                    $pt_months = [ '', 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ' ];
                    $month = $pt_months[ $month_num ];
                    $categories = get_the_terms( get_the_ID(), 'event_category' );
                    ?>
                    <a href="<?php the_permalink(); ?>" class="sc-events-card">
                        <div class="sc-events-card__date">
                            <span class="sc-events-card__day"><?php echo esc_html( $day ); ?></span>
                            <span class="sc-events-card__month"><?php echo esc_html( $month ); ?></span>
                        </div>
                        <div class="sc-events-card__details">
                            <h2 class="sc-events-card__title"><?php the_title(); ?></h2>
                            <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
                                <p class="sc-events-card__category"><?php echo esc_html( $categories[0]->name ); ?></p>
                            <?php endif; ?>
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