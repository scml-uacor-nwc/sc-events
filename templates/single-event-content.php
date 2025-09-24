<?php
/**
 * The content template for displaying a single event's details.
 * This is included by the [sc_event_details] shortcode.
 *
 * @package SCEvents
 */

// Fetch all our data at once
$start_date = get_post_meta( get_the_ID(), '_event_start_date_time', true );
$end_date   = get_post_meta( get_the_ID(), '_event_end_date_time', true );
$place      = get_post_meta( get_the_ID(), '_event_place', true );
$registry   = get_post_meta( get_the_ID(), '_event_registry', true );
$contacts   = get_post_meta( get_the_ID(), '_event_contacts', true );

// Format the time range for the "Data / hora" box
$time_range = '';
if($start_date) {
    $time_range = date_i18n( get_option('time_format'), strtotime($start_date) );
}
if($end_date && date('H:i', strtotime($start_date)) != date('H:i', strtotime($end_date))) {
     $time_range .= ' às ' . date_i18n( get_option('time_format'), strtotime($end_date) );
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('sc-events-single-container'); ?>>
    
    <div class="sc-events-single__breadcrumbs">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e('Início', 'sc-events'); ?></a> | 
        <a href="<?php echo esc_url( home_url( '/agenda/' ) ); ?>"><?php _e('Agenda', 'sc-events'); ?></a> | 
        <span class="current"><?php the_title(); ?></span>
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

    <div class="sc-events-single__calendar-download">
        <?php
        $download_url = add_query_arg( array(
            'sc_events_ics' => '1',
            'event_id' => get_the_ID()
        ), home_url() );
        ?>
        <?php
        // Get button style preference
        $sc_events_options = get_option( 'sc_events_options' );
        $button_style = isset( $sc_events_options['calendar_button_style'] ) ? $sc_events_options['calendar_button_style'] : 'plugin';
        $theme_classes = ( $button_style === 'theme' ) ? ' wp-element-button button btn' : '';
        ?>
        <a href="<?php echo esc_url( $download_url ); ?>" class="sc-events-calendar-btn<?php echo esc_attr( $theme_classes ); ?>" download>
            <span class="sc-events-calendar-icon">📅</span>
            <?php _e( 'Adicionar ao calendário', 'sc-events' ); ?>
        </a>
    </div>

</article>