<?php
if (!defined('ABSPATH')) exit;

function sdt_render_seo_avancado_settings_page() {
    // Salvar os dados se o formulário for enviado
    if (isset($_POST['sdt_seo_avancado_nonce']) && wp_verify_nonce($_POST['sdt_seo_avancado_nonce'], 'sdt_seo_avancado_save_settings')) {
        $options_to_save = [
            'sdt_seo_palavra_chave_principal' => sanitize_text_field($_POST['sdt_seo_palavra_chave_principal'] ?? ''),
            'sdt_seo_publico_alvo'            => sanitize_text_field($_POST['sdt_seo_publico_alvo'] ?? ''),
            'sdt_seo_titulo_artigo'           => sanitize_text_field($_POST['sdt_seo_titulo_artigo'] ?? ''),
            'sdt_seo_introducao_artigo'       => wp_kses_post($_POST['sdt_seo_introducao_artigo'] ?? ''),
            'sdt_seo_o_que_e_explicacao'      => wp_kses_post($_POST['sdt_seo_o_que_e_explicacao'] ?? ''),
            'sdt_seo_beneficios_principais'   => sanitize_textarea_field($_POST['sdt_seo_beneficios_principais'] ?? ''),
            'sdt_seo_como_usar_aplicar'       => wp_kses_post($_POST['sdt_seo_como_usar_aplicar'] ?? ''),
            'sdt_seo_faq_lista'               => sanitize_textarea_field($_POST['sdt_seo_faq_lista'] ?? ''),
            'sdt_seo_conclusao_artigo'        => wp_kses_post($_POST['sdt_seo_conclusao_artigo'] ?? ''),
            'sdt_seo_cta_paragrafo'           => sanitize_text_field($_POST['sdt_seo_cta_paragrafo'] ?? ''),
            'sdt_seo_cta_link_ancora'         => sanitize_text_field($_POST['sdt_seo_cta_link_ancora'] ?? ''),
            'sdt_seo_cta_link_url'            => esc_url_raw($_POST['sdt_seo_cta_link_url'] ?? ''),
            'sdt_seo_categoria_conteudo'      => sanitize_text_field($_POST['sdt_seo_categoria_conteudo'] ?? ''),
        ];
        update_option('sdt_seo_avancado_data', $options_to_save);
        echo '<div class="updated"><p>Configurações salvas!</p></div>';
    }

    // Obter as configurações atuais
    $options = get_option('sdt_seo_avancado_data', []);
    $defaults = [
        'sdt_seo_palavra_chave_principal' => '',
        'sdt_seo_publico_alvo'            => '',
        'sdt_seo_titulo_artigo'           => '',
        'sdt_seo_introducao_artigo'       => '',
        'sdt_seo_o_que_e_explicacao'      => '',
        'sdt_seo_beneficios_principais'   => '',
        'sdt_seo_como_usar_aplicar'       => '',
        'sdt_seo_faq_lista'               => '',
        'sdt_seo_conclusao_artigo'        => '',
        'sdt_seo_cta_paragrafo'           => '',
        'sdt_seo_cta_link_ancora'         => '',
        'sdt_seo_cta_link_url'            => '',
        'sdt_seo_categoria_conteudo'      => '',
    ];
    $current_options = wp_parse_args($options, $defaults);
    ?>
    <div class="wrap">
        <h1>Configurações do Template SEO Avançado</h1>
        <p>Preencha os campos abaixo para definir o conteúdo que será usado no template SEO avançado. Estes campos correspondem aos shortcodes que você pode usar no template.</p>
        
        <form method="POST" action="">
            <?php wp_nonce_field('sdt_seo_avancado_save_settings', 'sdt_seo_avancado_nonce'); ?>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="sdt_seo_palavra_chave_principal">Palavra-chave Principal</label></th>
                        <td><input name="sdt_seo_palavra_chave_principal" type="text" id="sdt_seo_palavra_chave_principal" value="<?php echo esc_attr($current_options['sdt_seo_palavra_chave_principal']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sdt_seo_publico_alvo">Público-alvo</label></th>
                        <td><input name="sdt_seo_publico_alvo" type="text" id="sdt_seo_publico_alvo" value="<?php echo esc_attr($current_options['sdt_seo_publico_alvo']); ?>" class="regular-text">
                        <p class="description">Para quem o artigo é escrito.</p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sdt_seo_titulo_artigo">Título do Artigo (H1)</label></th>
                        <td><input name="sdt_seo_titulo_artigo" type="text" id="sdt_seo_titulo_artigo" value="<?php echo esc_attr($current_options['sdt_seo_titulo_artigo']); ?>" class="regular-text">
                        <p class="description">Ex: Como [benefício ou solução] com [palavra-chave principal]. Shortcode: <code>[sdt_titulo_artigo_seo]</code></p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sdt_seo_introducao_artigo">Introdução do Artigo</label></th>
                        <td><textarea name="sdt_seo_introducao_artigo" id="sdt_seo_introducao_artigo" rows="5" class="large-text"><?php echo esc_textarea($current_options['sdt_seo_introducao_artigo']); ?></textarea>
                        <p class="description">Parágrafo introdutório. Shortcode: <code>[sdt_introducao_artigo]</code></p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sdt_seo_o_que_e_explicacao">O que é [Palavra-chave Principal]?</label></th>
                        <td><textarea name="sdt_seo_o_que_e_explicacao" id="sdt_seo_o_que_e_explicacao" rows="5" class="large-text"><?php echo esc_textarea($current_options['sdt_seo_o_que_e_explicacao']); ?></textarea>
                        <p class="description">Explicação clara e didática sobre a palavra-chave. Shortcode: <code>[sdt_o_que_e_explicacao]</code></p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sdt_seo_beneficios_principais">Benefícios Principais</label></th>
                        <td><textarea name="sdt_seo_beneficios_principais" id="sdt_seo_beneficios_principais" rows="5" class="large-text"><?php echo esc_textarea($current_options['sdt_seo_beneficios_principais']); ?></textarea>
                        <p class="description">Liste os benefícios, um por linha. O template formatará como lista. Shortcode: <code>[sdt_beneficios_principais_lista]</code></p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sdt_seo_como_usar_aplicar">Como usar/aplicar [Palavra-chave Principal]</label></th>
                        <td><textarea name="sdt_seo_como_usar_aplicar" id="sdt_seo_como_usar_aplicar" rows="8" class="large-text"><?php echo esc_textarea($current_options['sdt_seo_como_usar_aplicar']); ?></textarea>
                        <p class="description">Passo a passo, tutorial ou guia. Shortcode: <code>[sdt_como_usar_aplicar]</code></p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sdt_seo_faq_lista">Perguntas Frequentes (FAQ)</label></th>
                        <td><textarea name="sdt_seo_faq_lista" id="sdt_seo_faq_lista" rows="8" class="large-text"><?php echo esc_textarea($current_options['sdt_seo_faq_lista']); ?></textarea>
                        <p class="description">Formato: Pergunta1?||Resposta1 (nova linha) Pergunta2?||Resposta2. Shortcode: <code>[sdt_faq_lista]</code></p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sdt_seo_conclusao_artigo">Conclusão do Artigo</label></th>
                        <td><textarea name="sdt_seo_conclusao_artigo" id="sdt_seo_conclusao_artigo" rows="5" class="large-text"><?php echo esc_textarea($current_options['sdt_seo_conclusao_artigo']); ?></textarea>
                        <p class="description">Resumo dos pontos principais e reforço do benefício. Shortcode: <code>[sdt_conclusao_artigo]</code></p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sdt_seo_cta_paragrafo">Texto do CTA Final</label></th>
                        <td><input name="sdt_seo_cta_paragrafo" type="text" id="sdt_seo_cta_paragrafo" value="<?php echo esc_attr($current_options['sdt_seo_cta_paragrafo']); ?>" class="regular-text">
                        <p class="description">Ex: Gostou do conteúdo? Entre em contato... Shortcode: <code>[sdt_cta_paragrafo]</code></p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sdt_seo_cta_link_ancora">Texto do Botão do CTA</label></th>
                        <td><input name="sdt_seo_cta_link_ancora" type="text" id="sdt_seo_cta_link_ancora" value="<?php echo esc_attr($current_options['sdt_seo_cta_link_ancora']); ?>" class="regular-text">
                        <p class="description">Ex: Fale com um especialista. Shortcode: <code>[sdt_cta_link_ancora]</code></p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sdt_seo_cta_link_url">URL do Link do CTA</label></th>
                        <td><input name="sdt_seo_cta_link_url" type="url" id="sdt_seo_cta_link_url" value="<?php echo esc_attr($current_options['sdt_seo_cta_link_url']); ?>" class="regular-text">
                        <p class="description">Ex: https://seusite.com.br/contato. Shortcode: <code>[sdt_cta_link_url]</code></p></td>
                    </tr>
                     <tr>
                        <th scope="row"><label for="sdt_seo_categoria_conteudo">Categoria do Conteúdo</label></th>
                        <td><input name="sdt_seo_categoria_conteudo" type="text" id="sdt_seo_categoria_conteudo" value="<?php echo esc_attr($current_options['sdt_seo_categoria_conteudo']); ?>" class="regular-text">
                        <p class="description">Ex: SEO, Marketing Digital, Finanças...</p></td>
                    </tr>
                </tbody>
            </table>
            <?php submit_button('Salvar Configurações'); ?>
        </form>
    </div>
    <?php
}

