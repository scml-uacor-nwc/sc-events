<?php
/**
 * A central class for helper and utility functions.
 *
 * @package SCEvents
 */

namespace SCEvents\Core;

class Helpers {
    /**
     * Creates a custom trimmed excerpt by character count.
     *
     * @param int $char_limit The number of characters to limit the text to.
     * @return string The trimmed excerpt.
     */
    public static function get_trimmed_excerpt( $char_limit = 80 ) {
        $content = get_the_content();
        $content = strip_tags( strip_shortcodes( $content ) );
        
        if ( mb_strlen( $content ) <= $char_limit ) {
            return $content;
        }
        
        $excerpt = mb_substr( $content, 0, $char_limit );
        $last_space = mb_strrpos( $excerpt, ' ' );
        
        if ( $last_space !== false ) {
            $excerpt = mb_substr( $excerpt, 0, $last_space );
        }
        
        return $excerpt . '...';
    }

    /**
     * Formats a date string for the event cards.
     *
     * @param string $date_string The date/time string from the database.
     * @return array|null An array with 'day' and 'month' or null.
     */
    public static function get_formatted_date( $date_string ) {
        if ( empty( $date_string ) ) return null;
        $timestamp = strtotime( $date_string );
        $day       = date( 'd', $timestamp );
        $month_num = date( 'n', $timestamp );
        $pt_months = [ '', 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ' ];
        return [ 'day' => $day, 'month' => $pt_months[ $month_num ] ];
    }
}