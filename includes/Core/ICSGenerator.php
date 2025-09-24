<?php
/**
 * ICS Calendar file generator for events.
 *
 * @package SCEvents
 */

namespace SCEvents\Core;

class ICSGenerator {
    
    /**
     * Generate ICS file content for an event.
     *
     * @param int $event_id The event post ID.
     * @return string The ICS file content.
     */
    public static function generate_ics( $event_id ) {
        $event = get_post( $event_id );
        if ( ! $event || $event->post_type !== 'event' ) {
            return '';
        }

        // Get event meta data
        $start_date = get_post_meta( $event_id, '_event_start_date_time', true );
        $end_date = get_post_meta( $event_id, '_event_end_date_time', true );
        $location = get_post_meta( $event_id, '_event_location', true );
        
        // If no end date, make it 1 hour after start
        if ( ! $end_date ) {
            $end_date = date( 'Y-m-d H:i:s', strtotime( $start_date . ' +1 hour' ) );
        }

        // Convert to UTC and format for ICS
        $start_utc = self::format_date_for_ics( $start_date );
        $end_utc = self::format_date_for_ics( $end_date );
        
        // Generate unique ID
        $uid = md5( $event_id . $start_date ) . '@' . parse_url( home_url(), PHP_URL_HOST );
        
        // Get event URL
        $event_url = get_permalink( $event_id );
        
        // Build description
        $description = wp_strip_all_tags( get_the_content( null, false, $event_id ) );
        $description = self::escape_ics_text( $description );
        
        // Build location
        $location_text = $location ? self::escape_ics_text( $location ) : '';
        
        // Build ICS content
        $ics_content = "BEGIN:VCALENDAR\r\n";
        $ics_content .= "VERSION:2.0\r\n";
        $ics_content .= "PRODID:-//SC Events//SC Events Plugin//EN\r\n";
        $ics_content .= "CALSCALE:GREGORIAN\r\n";
        $ics_content .= "METHOD:PUBLISH\r\n";
        $ics_content .= "BEGIN:VEVENT\r\n";
        $ics_content .= "UID:" . $uid . "\r\n";
        $ics_content .= "DTSTAMP:" . gmdate( 'Ymd\THis\Z' ) . "\r\n";
        $ics_content .= "DTSTART:" . $start_utc . "\r\n";
        $ics_content .= "DTEND:" . $end_utc . "\r\n";
        $ics_content .= "SUMMARY:" . self::escape_ics_text( get_the_title( $event_id ) ) . "\r\n";
        
        if ( $description ) {
            $ics_content .= "DESCRIPTION:" . $description . "\r\n";
        }
        
        if ( $location_text ) {
            $ics_content .= "LOCATION:" . $location_text . "\r\n";
        }
        
        $ics_content .= "URL:" . $event_url . "\r\n";
        $ics_content .= "END:VEVENT\r\n";
        $ics_content .= "END:VCALENDAR\r\n";
        
        return $ics_content;
    }
    
    /**
     * Format date for ICS file.
     *
     * @param string $date_string The date string.
     * @return string Formatted date for ICS.
     */
    private static function format_date_for_ics( $date_string ) {
        $timestamp = strtotime( $date_string );
        return gmdate( 'Ymd\THis\Z', $timestamp );
    }
    
    /**
     * Escape text for ICS format.
     *
     * @param string $text The text to escape.
     * @return string Escaped text.
     */
    private static function escape_ics_text( $text ) {
        $text = str_replace( array( '\\', ';', ',', "\n", "\r" ), array( '\\\\', '\\;', '\\,', '\\n', '' ), $text );
        return $text;
    }
    
    /**
     * Generate and serve ICS file download.
     *
     * @param int $event_id The event post ID.
     */
    public static function serve_ics_download( $event_id ) {
        $ics_content = self::generate_ics( $event_id );
        
        if ( ! $ics_content ) {
            wp_die( __( 'Event not found.', 'sc-events' ) );
        }
        
        $filename = sanitize_file_name( get_the_title( $event_id ) ) . '.ics';
        
        // Set headers for file download
        header( 'Content-Type: text/calendar; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
        header( 'Content-Length: ' . strlen( $ics_content ) );
        header( 'Cache-Control: no-cache' );
        
        echo $ics_content;
        exit;
    }
}