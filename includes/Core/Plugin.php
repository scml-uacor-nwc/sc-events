<?php
/**
 * The main plugin class, responsible for loading modules.
 * @package SCEvents
 */

namespace SCEvents\Core;

use SCEvents\PostTypes;
use SCEvents\MetaFields;
use SCEvents\Admin;
use SCEvents\Assets;
use SCEvents\Frontend;

final class Plugin {

    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        $this->load_modules();
    }

    /**
     * Instantiate and load all the plugin modules.
     */
    private function load_modules() {
        new PostTypes\Event();
        new MetaFields\EventMeta();
        new Admin\Settings(); // Replaced CustomCss with Settings
        new Assets\Enqueue();
        new Frontend\Templates();
        new Frontend\Shortcodes();
    }
    
    public static function activate() {
        $post_type = new PostTypes\Event();
        $post_type->register_post_type();
        flush_rewrite_rules();
    }
}