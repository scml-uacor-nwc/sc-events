<?php
/**
 * Handles the Custom CSS admin page and output.
 *
 * @package SCEvents
 */

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
        ?>
        <div class="wrap">
            <h1><?php _e( 'Custom CSS for SC Events', 'sc-events' ); ?></h1>
            <p><?php _e( 'Adicione as suas próprias CSS aqui para alterar os estilos dos "cards" e das páginas de eventos.', 'sc-events' ); ?></p>

            <!-- CSS REFERENCE GUIDE -->
            <div class="sc-events-css-guide" style="background: #f6f7f7; padding: 1px 20px; border: 1px solid #ddd; margin-bottom: 20px;">
                <h2><?php _e( 'Guia de Classes CSS', 'sc-events' ); ?></h2>
                <p><?php _e( 'Abaixo está uma lista das principais classes CSS que pode usar para estilizar os eventos. Copie e cole os seletores e adicione as suas próprias regras.', 'sc-events' ); ?></p>

                <hr>

                <h3><?php _e( 'Cartões de Evento (Grelha)', 'sc-events' ); ?></h3>
                
                <p>
                    <strong><?php _e( 'O contentor da grelha de eventos:', 'sc-events' ); ?></strong>
                    <code>.sc-events-archive__grid</code>
                </p>
                <pre>/* Altera o espaçamento entre os cartões */
.sc-events-archive__grid {
    gap: 4rem;
}</pre>

                <p>
                    <strong><?php _e( 'A caixa preta da data:', 'sc-events' ); ?></strong>
                    <code>.sc-events-card__date</code>
                </p>
                <pre>/* Altera a cor de fundo da caixa da data */
.sc-events-card__date {
    background-color: #c0392b; /* Vermelho */
}</pre>

                <p>
                    <strong><?php _e( 'O título do evento no cartão:', 'sc-events' ); ?></strong>
                    <code>.sc-events-card__title</code>
                </p>
                <pre>/* Remove o sublinhado e altera a cor do título */
.sc-events-card__title {
    text-decoration: none;
    color: #3498db;
}</pre>

                <p>
                    <strong><?php _e( 'O pop-up de hover (o cartão branco):', 'sc-events' ); ?></strong>
                    <code>.sc-events-card:hover .sc-events-card__inner</code>
                </p>
                <pre>/* Adiciona uma borda azul ao pop-up de hover */
.sc-events-archive__grid:not(.sc-events-hover-disabled) .sc-events-card:hover .sc-events-card__inner {
    border: 2px solid #3498db;
}</pre>

                <hr>

                <h3><?php _e( 'Página de Evento Individual', 'sc-events' ); ?></h3>

                <p>
                    <strong><?php _e( 'O título principal do evento:', 'sc-events' ); ?></strong>
                    <code>.sc-events-single__title</code>
                </p>
                <pre>/* Altera o tamanho da fonte do título principal */
.sc-events-single__title {
    font-size: 48px;
}</pre>

                <p>
                    <strong><?php _e( 'Os títulos das caixas de detalhes (fundo dourado):', 'sc-events' ); ?></strong>
                    <code>.sc-events-single__detail-title</code>
                </p>
                <pre>/* Altera a cor de fundo dos títulos dos detalhes */
.sc-events-single__detail-title {
    background-color: #2ecc71; /* Verde */
}</pre>

                 <p>
                    <strong><?php _e( 'O contentor da grelha de detalhes:', 'sc-events' ); ?></strong>
                    <code>.sc-events-single__details-grid</code>
                </p>
                <pre>/* Altera a cor de fundo da área da grelha de detalhes */
.sc-events-single__details-grid {
    background-color: #ecf0f1;
}</pre>

            </div>
            <!-- CSS REFERENCE GUIDE ENDS -->


            <form method="post" action="options.php">
                <?php
                    settings_fields( 'sc_events_css_group' );
                    do_settings_sections( 'sc-events-css' );
                ?>
                <textarea name="sc_events_custom_css" rows="20" style="width: 100%; font-family: monospace;"><?php echo esc_textarea( get_option( 'sc_events_custom_css' ) ); ?></textarea>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() {
        register_setting( 'sc_events_css_group', 'sc_events_custom_css', 'wp_strip_all_tags' );
    }

    public function output_custom_css() {
        $custom_css = get_option( 'sc_events_custom_css' );
        if ( ! empty( $custom_css ) ) {
            echo '<style type="text/css" id="sc-events-custom-css">' . $custom_css . '</style>';
        }
    }
}