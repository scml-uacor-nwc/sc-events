<?php
namespace SCEvents\Admin;

class CustomCss {
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'wp_head', [ $this, 'output_custom_css' ] );
    }

    public function add_admin_menu() {
        add_submenu_page( 'edit.php?post_type=event', __( 'Custom CSS', 'sc-events' ), __( 'Custom CSS', 'sc-events' ), 'manage_options', 'sc-events-css', [ $this, 'render_page' ] );
    }

    public function render_page() {
        // Exact same HTML for the settings page as before
        ?>
        <div class="wrap">
            <h1><?php _e( 'Custom CSS for SC Events', 'sc-events' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                    settings_fields( 'sc_events_css_group' );
                    do_settings_sections( 'sc-events-css' );
                ?>
                <textarea name="sc_events_custom_css" rows="20" style="width: 100%;"><?php echo esc_textarea( get_option( 'sc_events_custom_css' ) ); ?></textarea>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() {
        register_setting( 'sc_events_css_group', 'sc_events_custom_css', 'sanitize_textarea_field' );
    }



    public function output_custom_css() {
        $custom_css = get_option( 'sc_events_custom_css' );
        if ( ! empty( $custom_css ) ) {
            echo '<style type="text/css" id="sc-events-custom-css">' . wp_strip_all_tags( $custom_css ) . '</style>';
        }
    }
}