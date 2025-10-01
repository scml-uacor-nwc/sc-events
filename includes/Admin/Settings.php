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

        // Substitui wp_head por enqueue moderno
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_custom_css' ] );
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
        register_setting(
            'sc_events_settings_group',
            'sc_events_options',
            [ $this, 'sanitize_options' ]
        );

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
            'calendar_button_style',
            __( 'Calendar Button Style', 'sc-events' ),
            [ $this, 'render_field_calendar_button_style' ],
            'sc_events_settings_section',
            'sc_events_settings_section'
        );
        
        add_settings_field(
            'calendar_button_classes',
            __( 'Calendar Button CSS Classes', 'sc-events' ),
            [ $this, 'render_field_calendar_button_classes' ],
            'sc_events_settings_section',
            'sc_events_settings_section'
        );
        
        add_settings_field(
            'calendar_button_show_icon',
            __( 'Calendar Button Icon', 'sc-events' ),
            [ $this, 'render_field_calendar_button_show_icon' ],
            'sc_events_settings_section',
            'sc_events_settings_section'
        );
        
        add_settings_field(
            'show_agenda_button',
            __( 'Show Agenda Button', 'sc-events' ),
            [ $this, 'render_field_show_agenda_button' ],
            'sc_events_settings_section',
            'sc_events_settings_section'
        );
        
        add_settings_field(
            'agenda_button_style',
            __( 'Agenda Button Style', 'sc-events' ),
            [ $this, 'render_field_agenda_button_style' ],
            'sc_events_settings_section',
            'sc_events_settings_section'
        );
        
        add_settings_field(
            'agenda_button_classes',
            __( 'Agenda Button CSS Classes', 'sc-events' ),
            [ $this, 'render_field_agenda_button_classes' ],
            'sc_events_settings_section',
            'sc_events_settings_section'
        );
        
        add_settings_field(
            'custom_css',
            __( 'Custom CSS', 'sc-events' ),
            [ $this, 'render_field_custom_css' ],
            'sc_events_settings_section',
            'sc_events_settings_section',
            [ 'description_callback' => [ $this, 'render_css_guide' ] ]
        );

        add_settings_field(
            'shortcode_instructions',
            __( 'Shortcode Instructions', 'sc-events' ),
            [ $this, 'render_shortcode_instructions' ],
            'sc_events_settings_section',
            'sc_events_settings_section'
        );
    }

    /**
     * Guia CSS expandido
     */
    public function render_css_guide() {
        ?>
        <div class="sc-events-css-guide" style="background: #f6f7f7; padding: 1px 20px; border: 1px solid #ddd; margin-top: 10px;">
            <h2><?php _e( 'Guia de Classes CSS', 'sc-events' ); ?></h2>
            <p><?php _e( 'Copie os seletores para a caixa "Custom CSS" e adicione as suas regras.', 'sc-events' ); ?></p>
            <hr>
            <h3><?php _e( 'Cartões de Evento (Grelha)', 'sc-events' ); ?></h3>
            <pre>.sc-events-archive__grid { background: #f9f9f9; padding: 20px; }</pre>
            <pre>.sc-events-card__date { background: #c0392b; }</pre>
            <pre>.sc-events-card__title { color: #3498db; }</pre>
            <hr>
            <h3><?php _e( 'Página de Evento Individual', 'sc-events' ); ?></h3>
            <pre>.sc-events-single-container { background: #f9f9f9; padding: 30px; }</pre>
            <pre>.sc-events-single__image { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }</pre>
            <pre>.sc-events-single__title { font-size: 48px; color: #2c3e50; }</pre>
            <pre>.sc-events-single__detail-title { background: #2ecc71; color: #fff; }</pre>
            <pre>.sc-events-single__detail-item p { background: #eaf2f8; }</pre>
            <hr>
            <h3><?php _e( 'Botão de Calendário (Download ICS)', 'sc-events' ); ?></h3>
            <p><?php _e( 'O botão "Adicionar ao calendário" adapta-se automaticamente ao tema, mas pode personalizá-lo:', 'sc-events' ); ?></p>
            <pre>.sc-events-calendar-btn { background: #e74c3c; color: #fff; }</pre>
            <pre>.sc-events-calendar-btn:hover { background: #c0392b; }</pre>
            <pre>.sc-events-calendar-icon { font-size: 20px; }</pre>
            <p><em><?php _e( 'Nota: O botão usa as classes do tema (wp-element-button, button, btn) para integração automática.', 'sc-events' ); ?></em></p>
        </div>
        <?php
    }

    public function render_shortcode_instructions() {
        
        ?>
        <div class="sc-events-shortcode-instructions" style="background: #f6f7f7; padding: 1px 20px; border: 1px solid #ddd; margin-top: 10px;">
            <h2><?php _e( 'Instruções do Shortcode', 'sc-events' ); ?></h2>
            <p><?php _e( 'A funcionalidade mais poderosa deste plugin é o shortcode <code>[sc_events]</code>, que lhe permite colocar uma grelha de cartões de eventos em qualquer parte do seu site.', 'sc-events' ); ?></p>
            <h3><?php _e( 'Utilização Básica', 'sc-events' ); ?></h3>
            <p><?php _e( 'Para exibir uma grelha padrão com os próximos 3 eventos, basta adicionar um Bloco de Shortcode (no Gutenberg) ou um Bloco de Texto/Código (num page builder) e insira o seguinte:', 'sc-events' ); ?></p>
            <pre><b>[sc_events]</b></pre>
            <h3><?php _e( 'Atributos do Shortcode', 'sc-events' ); ?></h3>
            <p><?php _e( 'Pode personalizar o shortcode adicionando "atributos" para controlar o layout e o que é exibido.', 'sc-events' ); ?></p>
            <ul>
                <li>
                    <details>
                        <summary><strong>limit</strong>:</summary>
                        <?php _e( 'O que faz: Controla o número máximo de eventos a serem exibidos.', 'sc-events' ); ?><br>
                        <?php _e( 'Exemplo: Para mostrar os próximos 6 eventos:', 'sc-events' ); ?><br>
                    </details>
                    <pre><b>[sc_events limit="6"]</b></pre>
                </li>
                <li>
                    <details>
                        <summary><strong>columns</strong>:</summary> 
                        <?php _e( 'O que faz: Define o número de colunas para a grelha em ecrãs de computador (é sempre 1 coluna em dispositivos móveis).', 'sc-events' ); ?><br>
                        <?php _e( 'Opções: 1, 2, ou 3.', 'sc-events' ); ?><br>
                        <?php _e( 'Exemplo: Para exibir eventos numa grelha de 2 colunas:', 'sc-events' ); ?><br>
                    </details>
                    <pre><b>[sc_events columns="2"]</b></pre>
                </li>
                <li>
                    <details>
                        <summary><strong>category</strong>:</summary> 
                        <?php _e( 'O que faz: Filtra a exibição para mostrar apenas eventos de uma categoria específica.', 'sc-events' ); ?><br>
                        <?php _e( 'Como encontrar o slug: Vá a Events > Categories (Categorias). O "slug" é o nome da categoria formatado para URL.', 'sc-events' ); ?><br>
                        <?php _e( 'Exemplo: Para mostrar apenas eventos da categoria com o slug "workshops":', 'sc-events' ); ?><br>
                    </details>
                    <pre><b>[sc_events category="workshops"]</b></pre>
                </li>
                <li>
                    <details>
                        <summary><strong>excerpt_length</strong>:</summary> 
                        <?php _e( 'O que faz: Controla o número de caracteres exibidos no texto do pop-up ao passar o rato.', 'sc-events' ); ?><br>
                        <?php _e( 'Exemplo: Para mostrar um excerto mais longo com 120 caracteres:', 'sc-events' ); ?><br>
                    </details>
                    <pre><b>[sc_events excerpt_length="120"]</b></pre>
                </li>
                <li>
                    <details>
                        <summary><strong>hover</strong>:</summary>
                        <?php _e( 'O que faz: Ativa ou desativa o efeito de pop-up ao passar o rato sobre os cartões de evento.', 'sc-events' ); ?><br>
                        <?php _e( 'Opções: true ou false.', 'sc-events' ); ?><br>
                        <?php _e( 'Exemplo: Para exibir cartões estáticos sem efeito de hover:', 'sc-events' ); ?><br>
                    </details>
                    <pre><b>[sc_events hover="false"]</b></pre>
                </li>
            </ul>
            <h3><?php _e( 'Exemplos Combinados', 'sc-events' ); ?></h3>
            <p><?php _e( 'Pode combinar múltiplos atributos para um controlo mais refinado. Aqui estão alguns exemplos:', 'sc-events' ); ?></p>
            <ul>
                <li><?php _e( 'Mostrar os próximos 4 eventos numa grelha de 2 colunas:', 'sc-events' ); ?><br>
                    <pre><b>[sc_events limit="4" columns="2"]</b></pre>
                </li>
                <li><?php _e( 'Exibir 5 eventos da categoria "concerts" sem efeito de hover:', 'sc-events' ); ?><br>
                    <pre><b>[sc_events limit="5" category="concerts" hover="false"]</b></pre>
                </li>
                <li><?php _e( 'Mostrar 6 eventos da categoria "workshops" com excertos mais longos:', 'sc-events' ); ?><br>
                    <pre><b>[sc_events limit="6" category="workshops" excerpt_length="120"]</b></pre>
                </li>
            </ul>
        </div>
        <?php
    }

    public function render_field_disable_hover() {
        $options = get_option( 'sc_events_options' );
        $value   = isset( $options['disable_archive_hover'] ) ? $options['disable_archive_hover'] : 0;
        ?>
        <label for="sc_events_disable_archive_hover">
            <input type="checkbox" id="sc_events_disable_archive_hover" name="sc_events_options[disable_archive_hover]" value="1" <?php checked( $value, 1 ); ?> />
            <?php _e( 'Desactiva o efeito hover em TODOS os cartões de eventos.', 'sc-events' ); ?>
        </label>
        <?php
    }
    
    public function render_field_calendar_button_style() {
        $options = get_option( 'sc_events_options' );
        $value   = isset( $options['calendar_button_style'] ) ? $options['calendar_button_style'] : 'default-blue';
        
        $button_styles = [
            'default-blue' => __( 'Default Blue', 'sc-events' ),
            'default-bw' => __( 'Default B&W', 'sc-events' ),
            'black-yellow' => __( 'Black & Yellow', 'sc-events' ),
            'white-yellow' => __( 'White & Yellow', 'sc-events' ),
            'theme' => __( 'Theme Integration - Integra-se com botões do tema', 'sc-events' )
        ];
        ?>
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e( 'Calendar Button Style', 'sc-events' ); ?></span></legend>
            <?php foreach ( $button_styles as $style_value => $style_label ) : ?>
                <label style="display: block; margin-bottom: 8px;">
                    <input type="radio" name="sc_events_options[calendar_button_style]" value="<?php echo esc_attr( $style_value ); ?>" <?php checked( $value, $style_value ); ?> />
                    <?php echo esc_html( $style_label ); ?>
                </label>
            <?php endforeach; ?>
        </fieldset>
        <p class="description">
            <?php _e( 'Escolha o estilo do botão "Adicionar ao calendário". "Theme Integration" adapta-se aos botões do seu tema.', 'sc-events' ); ?>
        </p>
        <?php
    }
    
    public function render_field_calendar_button_classes() {
        $options = get_option( 'sc_events_options' );
        $value   = isset( $options['calendar_button_classes'] ) ? $options['calendar_button_classes'] : '';
        ?>
        <input type="text" name="sc_events_options[calendar_button_classes]" value="<?php echo esc_attr( $value ); ?>" style="width: 100%; max-width: 400px;" placeholder="btn btn-primary custom-class" />
        <p class="description">
            <?php _e( 'Adicione classes CSS personalizadas para o botão "Adicionar ao calendário" (separadas por espaços). Exemplo: btn btn-primary custom-style', 'sc-events' ); ?>
        </p>
        <?php
    }
    
    public function render_field_calendar_button_show_icon() {
        $options = get_option( 'sc_events_options' );
        $value   = isset( $options['calendar_button_show_icon'] ) ? $options['calendar_button_show_icon'] : 1;
        ?>
        <label for="sc_events_calendar_button_show_icon">
            <input type="checkbox" id="sc_events_calendar_button_show_icon" name="sc_events_options[calendar_button_show_icon]" value="1" <?php checked( $value, 1 ); ?> />
            <?php _e( 'Mostrar ícone 📅 no botão de calendário', 'sc-events' ); ?>
        </label>
        <p class="description">
            <?php _e( 'Desmarque para ocultar o ícone do calendário.', 'sc-events' ); ?>
        </p>
        <?php
    }
    
    public function render_field_show_agenda_button() {
        $options = get_option( 'sc_events_options' );
        $value   = isset( $options['show_agenda_button'] ) ? $options['show_agenda_button'] : 1;
        ?>
        <label for="sc_events_show_agenda_button">
            <input type="checkbox" id="sc_events_show_agenda_button" name="sc_events_options[show_agenda_button]" value="1" <?php checked( $value, 1 ); ?> />
            <?php _e( 'Mostrar botão "Ver agenda completa" no shortcode [sc_events]', 'sc-events' ); ?>
        </label>
        <p class="description">
            <?php _e( 'Exibe um botão centralizado sob a grelha de eventos que leva à página /agenda.', 'sc-events' ); ?>
        </p>
        <?php
    }
    
    public function render_field_agenda_button_style() {
        $options = get_option( 'sc_events_options' );
        $value   = isset( $options['agenda_button_style'] ) ? $options['agenda_button_style'] : 'default-blue';
        
        $button_styles = [
            'default-blue' => __( 'Default Blue', 'sc-events' ),
            'default-bw' => __( 'Default B&W', 'sc-events' ),
            'black-yellow' => __( 'Black & Yellow', 'sc-events' ),
            'white-yellow' => __( 'White & Yellow', 'sc-events' ),
            'theme' => __( 'Theme Integration - Integra-se com botões do tema', 'sc-events' )
        ];
        ?>
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e( 'Agenda Button Style', 'sc-events' ); ?></span></legend>
            <?php foreach ( $button_styles as $style_value => $style_label ) : ?>
                <label style="display: block; margin-bottom: 8px;">
                    <input type="radio" name="sc_events_options[agenda_button_style]" value="<?php echo esc_attr( $style_value ); ?>" <?php checked( $value, $style_value ); ?> />
                    <?php echo esc_html( $style_label ); ?>
                </label>
            <?php endforeach; ?>
        </fieldset>
        <p class="description">
            <?php _e( 'Escolha o estilo do botão "Ver agenda completa". "Theme Integration" adapta-se aos botões do seu tema.', 'sc-events' ); ?>
        </p>
        <?php
    }
    
    public function render_field_agenda_button_classes() {
        $options = get_option( 'sc_events_options' );
        $value   = isset( $options['agenda_button_classes'] ) ? $options['agenda_button_classes'] : '';
        ?>
        <input type="text" name="sc_events_options[agenda_button_classes]" value="<?php echo esc_attr( $value ); ?>" style="width: 100%; max-width: 400px;" placeholder="btn btn-primary custom-class" />
        <p class="description">
            <?php _e( 'Adicione classes CSS personalizadas para o botão "Ver agenda completa" (separadas por espaços). Exemplo: btn btn-primary custom-style', 'sc-events' ); ?>
        </p>
        <?php
    }
    
    public function render_field_custom_css( $args ) {
        $options = get_option( 'sc_events_options' );
        $value   = isset( $options['custom_css'] ) ? $options['custom_css'] : '';
        ?>
        <textarea name="sc_events_options[custom_css]" rows="15" style="width: 100%; font-family: monospace;"><?php echo esc_textarea( $value ); ?></textarea>
        <?php
        if ( isset( $args['description_callback'] ) ) {
            call_user_func( $args['description_callback'] );
        }
    }

    public function sanitize_options( $input ) {
        $allowed_button_styles = [ 'default-blue', 'default-bw', 'black-yellow', 'white-yellow', 'theme' ];
        
        $new_input = [];
        $new_input['disable_archive_hover'] = isset( $input['disable_archive_hover'] ) ? 1 : 0;
        $new_input['calendar_button_style'] = isset( $input['calendar_button_style'] ) && in_array( $input['calendar_button_style'], $allowed_button_styles ) ? $input['calendar_button_style'] : 'default-blue';
        $new_input['calendar_button_classes'] = isset( $input['calendar_button_classes'] ) ? sanitize_text_field( $input['calendar_button_classes'] ) : '';
        $new_input['calendar_button_show_icon'] = isset( $input['calendar_button_show_icon'] ) ? 1 : 0;
        $new_input['show_agenda_button'] = isset( $input['show_agenda_button'] ) ? 1 : 0;
        $new_input['agenda_button_style'] = isset( $input['agenda_button_style'] ) && in_array( $input['agenda_button_style'], $allowed_button_styles ) ? $input['agenda_button_style'] : 'default-blue';
        $new_input['agenda_button_classes'] = isset( $input['agenda_button_classes'] ) ? sanitize_text_field( $input['agenda_button_classes'] ) : '';
        $new_input['custom_css'] = isset( $input['custom_css'] ) ? wp_kses( $input['custom_css'], [] ) : '';
        return $new_input;
    }

    /**
     * Adiciona CSS inline ao handle principal do plugin ou via <style> se não houver handle
     */
    public function enqueue_custom_css() {
        $options    = get_option( 'sc_events_options' );
        $custom_css = isset( $options['custom_css'] ) ? trim( $options['custom_css'] ) : '';

        if ( ! empty( $custom_css ) ) {
            // Se o plugin tem um handle registado:
            if ( wp_style_is( 'sc-events-style', 'registered' ) ) {
                wp_add_inline_style( 'sc-events-style', $custom_css );
            } else {
                // fallback
                echo '<style id="sc-events-custom-css">' . esc_html( $custom_css ) . '</style>';
            }
        }
    }
}
