<?php
if (!defined('ABSPATH')) exit;

function sdt_render_seo_avancado_settings_page() {
    if (isset($_POST['sdt_seo_avancado_nonce']) && wp_verify_nonce($_POST['sdt_seo_avancado_nonce'], 'sdt_seo_avancado_save_settings')) {
        $options_to_save = [
            'sdt_seo_palavra_chave_principal' => sanitize_text_field($_POST['sdt_seo_palavra_chave_principal'] ?? ''),
            'sdt_seo_publico_alvo' => sanitize_text_field($_POST['sdt_seo_publico_alvo'] ?? ''),
            'sdt_seo_titulo_artigo' => sanitize_text_field($_POST['sdt_seo_titulo_artigo'] ?? ''),
            'sdt_seo_introducao_artigo' => wp_kses_post($_POST['sdt_seo_introducao_artigo'] ?? ''),
            'sdt_seo_o_que_e_explicacao' => wp_kses_post($_POST['sdt_seo_o_que_e_explicacao'] ?? ''),
            'sdt_seo_beneficios_principais' => sanitize_textarea_field($_POST['sdt_seo_beneficios_principais'] ?? ''),
            'sdt_seo_template_layout' => sanitize_text_field($_POST['sdt_seo_template_layout'] ?? '')
        ];
        update_option('sdt_seo_avancado_template', $options_to_save);
        echo '<div class="updated"><p>Configurações salvas com sucesso!</p></div>';
    }

    $options = get_option('sdt_seo_avancado_template', []);
    $template_dir = plugin_dir_path(__FILE__) . '../templates/';
    $template_files = glob($template_dir . '*.php');
    ?>

    <div class="wrap">
        <h1>Configuração – SEO Local Generator</h1>
        <form method="post">
            <?php wp_nonce_field('sdt_seo_avancado_save_settings', 'sdt_seo_avancado_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="sdt_seo_palavra_chave_principal">Palavra-chave principal</label></th>
                    <td><input type="text" name="sdt_seo_palavra_chave_principal" id="sdt_seo_palavra_chave_principal" value="<?php echo esc_attr($options['sdt_seo_palavra_chave_principal'] ?? ''); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sdt_seo_publico_alvo">Público-alvo</label></th>
                    <td><input type="text" name="sdt_seo_publico_alvo" id="sdt_seo_publico_alvo" value="<?php echo esc_attr($options['sdt_seo_publico_alvo'] ?? ''); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sdt_seo_titulo_artigo">Título do artigo</label></th>
                    <td><input type="text" name="sdt_seo_titulo_artigo" id="sdt_seo_titulo_artigo" value="<?php echo esc_attr($options['sdt_seo_titulo_artigo'] ?? ''); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sdt_seo_introducao_artigo">Introdução</label></th>
                    <td><textarea name="sdt_seo_introducao_artigo" rows="4" class="large-text"><?php echo esc_textarea($options['sdt_seo_introducao_artigo'] ?? ''); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sdt_seo_o_que_e_explicacao">O que é (explicação)</label></th>
                    <td><textarea name="sdt_seo_o_que_e_explicacao" rows="4" class="large-text"><?php echo esc_textarea($options['sdt_seo_o_que_e_explicacao'] ?? ''); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sdt_seo_beneficios_principais">Benefícios principais</label></th>
                    <td><textarea name="sdt_seo_beneficios_principais" rows="4" class="large-text"><?php echo esc_textarea($options['sdt_seo_beneficios_principais'] ?? ''); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sdt_seo_template_layout">Selecione o layout (modelo)</label></th>
                    <td>
                        <select name="sdt_seo_template_layout" id="sdt_seo_template_layout">
                            <option value="">— Selecione —</option>
                            <?php
                            foreach ($template_files as $file_path) {
                                $file = basename($file_path);
                                $selected = selected($file, $options['sdt_seo_template_layout'] ?? '', false);
                                echo '<option value="' . esc_attr($file) . '" ' . $selected . '>' . esc_html($file) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button('Salvar Configurações'); ?>
        </form>
    </div>
<?php } ?>
