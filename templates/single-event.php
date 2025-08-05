<?php
/**
 * The template for displaying a single event, based on the detailed design.
 *
 * @package SCEvents
 */

get_header();

/**
* Helper function to format date/time nicely.
*/
function sc_events_format_full_datetime( $date_string ) {
    if ( empty( $date_string ) ) return '';
    $timestamp = strtotime( $date_string );
    // This format will produce something like: 18 de setembro de 2025 | 15:30
    return date_i18n( get_option( 'date_format' ), $timestamp ) . ' | ' . date_i18n( get_option( 'time_format' ), $timestamp );
}
?>

<div id="primary" class="sc-events-single content-area">
    <main id="main" class="site-main sc-events-single-container">

        <?php
        while ( have_posts() ) :
            the_post();

            // Fetch all our data at once
            $start_date = get_post_meta( get_the_ID(), '_event_start_date_time', true );
            $end_date   = get_post_meta( get_the_ID(), '_event_end_date_time', true );
            $place      = get_post_meta( get_the_ID(), '_event_place', true );
            $registry   = get_post_meta( get_the_ID(), '_event_registry', true );
            $contacts   = get_post_meta( get_the_ID(), '_event_contacts', true );

            // Format the time range for the "Data / hora" box
            $time_range = '';
            if($start_date) {
                $time_range = date('H:i', strtotime($start_date));
            }
            if($end_date && date('H:i', strtotime($start_date)) != date('H:i', strtotime($end_date))) {
                 $time_range .= ' às ' . date('H:i', strtotime($end_date));
            }
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

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

                <?php 
                // This is where we display the main event image.
                if ( has_post_thumbnail() ) : ?>
                    <div class="sc-events-single__image-wrapper">
                        <?php the_post_thumbnail( 'large', ['class' => 'sc-events-single__image'] ); ?>
                    </div>
                <?php endif; ?>

                <div class="sc-events-single__content entry-content">
                    <?php 
                        // The main description and the "INFORMAÇÃO ADICIONAL" both come from the main editor.
                        // Just instruct the user to write both sections in the same editor.
                        the_content(); 
                    ?>
                </div>

                <div class="sc-events-single__details-grid">
                    <div class="sc-events-single__detail-item">
                        <h3 class="sc-events-single__detail-title"><?php _e( 'Data / hora', 'sc-events' ); ?></h3>
                        <p><?php echo esc_html( date_i18n( get_option('date_format'), strtotime($start_date) ) ); ?> | <?php echo esc_html( $time_range ); ?></p>
                    </div>
                    <div class="sc-events-single__detail-item">
                        <h3 class="sc-events-single__detail-title"><?php _e( 'Local', 'sc-events' ); ?></h3>
                        <p><?php echo esc_html( $place ); ?></p>
                    </div>
                    <div class="sc-events-single__detail-item">
                        <h3 class="sc-events-single__detail-title"><?php _e( 'Registo', 'sc-events' ); ?></h3>
                        <p>
                            <?php _e( 'O registo para esta ação deve ser feito através do seguinte link:', 'sc-events' ); ?>
                            <a href="<?php echo esc_url( $registry ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $registry ); ?></a>
                        </p>
                    </div>
                    <div class="sc-events-single__detail-item">
                        <h3 class="sc-events-single__detail-title"><?php _e( 'Contactos', 'sc-events' ); ?></h3>
                        <p><?php echo nl2br( esc_html( $contacts ) ); ?></p>
                    </div>
                </div>

            </article>
        <?php endwhile; ?>

    </main>
</div>

<?php
get_footer();