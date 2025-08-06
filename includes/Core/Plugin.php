<?php
namespace SCEvents\Core;

use SCEvents\PostTypes;
use SCEvents\MetaFields;
use SCEvents\Admin;

/**
 * The main plugin class.
 * 
 * This class only job is to create a new instance of each of our module classes.
 * 
 */
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
     * Load all the plugin modules.
     */
    private function load_modules() {
        new PostTypes\Event();
        new MetaFields\EventMeta();
        new Admin\CustomCss();
        new \SCEvents\Assets\Enqueue();
        new \SCEvents\Frontend\Templates();
        new \SCEvents\Frontend\Shortcodes(); 
    }
    
    /**
     * Code to run on plugin activation.
     */
    public static function activate() {
        flush_rewrite_rules();
    }
}