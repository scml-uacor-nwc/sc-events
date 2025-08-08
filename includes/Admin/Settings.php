<?php
/**
 * Handles the plugin's dedicated settings page.
 *
 * @package SCEvents
 */

namespace SCEvents\Admin;

class Settings {
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'wp_head', [ $this, 'output_custom_css' ] );
    }

    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=event',
            __( 'Custom Settings', 'sc-events' ),
            __( 'Custom Settings', 'sc-events' ),
            'manage_options',
            'sc-events-settings',
            [ $this, 'render_page' ]
        );
    }

    public function render_page() {
        ?>
        <div class="wrap">
            <h1><?php _e( 'SC Events: Custom Settings', 'sc-events' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                    settings_fields( 'sc_events_settings_group' );
                    do_settings_sections( 'sc_events_settings_section' );
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() {
        register_setting( 'sc_events_settings_group', 'sc_events_options', [ $this, 'sanitize_options' ] );

        add_settings_section(
            'sc_events_settings_section',
            __( 'General Settings', 'sc-events' ),
            null,
            'sc_events_settings_section'
        );

        add_settings_field(
            'disable_archive_hover',
            __( 'Global Hover Control', 'sc-events' ),
            [ $this, 'render_field_disable_hover' ],
            'sc_events_settings_section',
            'sc_events_settings_section'
        );
        
        add_settings_field(
            'custom_css',
            __( 'Custom CSS', 'sc-events' ),
            [ $this, 'render_field_custom_css' ],
            'sc_events_settings_section',
            'sc_events_settings_section',
            ['description_callback' => [ $this, 'render_css_guide' ] ]
        );
    }

    /**
     * Renders the expanded instructional guide for the CSS field.
     */
    public function render_css_guide() {
        ?>
        <div class="sc-events-css-guide" style="background: #f6f7f7; padding: 1px 20px; border: 1px solid #ddd; margin-top: 10px;">
            <h2><?php _e( 'Guia de Classes CSS', 'sc-events' ); ?></h2>
            <p><?php _e( 'Abaixo está uma lista das principais classes CSS que pode usar para estilizar os eventos. Copie e cole os seletores na caixa de "Custom CSS" acima e adicione as suas próprias regras.', 'sc-events' ); ?></p>
            
            <hr>
            <h3><?php _e( 'Cartões de Evento (Grelha)', 'sc-events' ); ?></h3>
            <p><strong><?php _e( 'O contentor da grelha de eventos:', 'sc-events' ); ?></strong> <code>.sc-events-archive__grid</code></p>
            <pre>/* Adiciona uma borda e fundo a toda a grelha de eventos */
.sc-events-archive__grid {
    background-color: #f9f9f9;
    border: 1px solid #e1e1e1;
    padding: 20px;
    border-radius: 8px;
}</pre>
            <p><strong><?php _e( 'A caixa da data:', 'sc-events' ); ?></strong> <code>.sc-events-card__date</code></p>
            <pre>.sc-events-card__date { background-color: #c0392b; }</pre>
            <p><strong><?php _e( 'O título do evento no cartão:', 'sc-events' ); ?></strong> <code>.sc-events-card__title</code></p>
            <pre>.sc-events-card__title { text-decoration: none; color: #3498db; }</pre>

            <hr>
            <h3><?php _e( 'Página de Evento Individual', 'sc-events' ); ?></h3>
             <p><strong><?php _e( 'O contentor principal da página do evento:', 'sc-events' ); ?></strong> <code>.sc-events-single-container</code></p>
            <pre>/* Adiciona um fundo e preenchimento a toda a área do evento */
.sc-events-single-container {
    background-color: #f9f9f9;
    padding: 30px;
}</pre>
            <p><strong><?php _e( 'A imagem principal do evento:', 'sc-events' ); ?></strong> <code>.sc-events-single__image</code></p>
            <pre>/* Adiciona uma sombra subtil à imagem */
.sc-events-single__image {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-radius: 8px;
}</pre>
            <p><strong><?php _e( 'O título principal do evento:', 'sc-events' ); ?></strong> <code>.sc-events-single__title</code></p>
            <pre>.sc-events-single__title { font-size: 48px; color: #2c3e50; }</pre>
            <p><strong><?php _e( 'Os títulos das caixas de detalhes:', 'sc-events' ); ?></strong> <code>.sc-events-single__detail-title</code></p>
            <pre>.sc-events-single__detail-title { background-color: #2ecc71; color: #fff; }</pre>
            <p><strong><?php _e( 'As caixas de conteúdo dos detalhes (área cinzenta):', 'sc-events' ); ?></strong> <code>.sc-events-single__detail-item p</code></p>
            <pre>/* Altera a cor de fundo das caixas de conteúdo */
.sc-events-single__detail-item p {
    background-color: #eaf2f8; /* Azul claro */
}</pre>
        </div>
        <?php
    }

    public function render_field_disable_hover() {
        $options = get_option( 'sc_events_options' );
        $value = isset( $options['disable_archive_hover'] ) ? $options['disable_archive_hover'] : 0;
        ?>
        <label for="sc_events_disable_archive_hover">
            <input type="checkbox" id="sc_events_disable_archive_hover" name="sc_events_options[disable_archive_hover]" value="1" <?php checked( $value, 1 ); ?> />
            <?php _e( 'Desactiva o efeito hover em TODOS os cartões de eventos (o atributo do shortcode vai substituir esta regra).', 'sc-events' ); ?>
        </label>
        <?php
    }
    
    public function render_field_custom_css( $args ) {
        $options = get_option( 'sc_events_options' );
        $value = isset( $options['custom_css'] ) ? $options['custom_css'] : '';
        ?>
        <textarea name="sc_events_options[custom_css]" rows="15" style="width: 100%; font-family: monospace;"><?php echo esc_textarea( $value ); ?></textarea>
        <?php
        if ( isset( $args['description_callback'] ) ) {
            call_user_func( $args['description_callback'] );
        }
    }

    public function sanitize_options( $input ) {
        $new_input = [];
        $new_input['disable_archive_hover'] = isset( $input['disable_archive_hover'] ) ? 1 : 0;
        $new_input['custom_css'] = isset( $input['custom_css'] ) ? wp_strip_all_tags( $input['custom_css'] ) : '';
        return $new_input;
    }

    public function output_custom_css() {
        $options = get_option( 'sc_events_options' );
        $custom_css = isset( $options['custom_css'] ) ? $options['custom_css'] : '';
        if ( ! empty( $custom_css ) ) {
            echo '<style type="text/css" id="sc-events-custom-css">' . $custom_css . '</style>';
        }
    }
}