<?php
if (!defined('ABSPATH')) exit;

include_once plugin_dir_path(__FILE__) . 'utils.php';
include_once plugin_dir_path(__FILE__) . 'sitemap.php';

function sdt_generate_seo_posts() {
    $return_data = ['status' => 'unknown', 'attempted' => 0, 'generated' => 0];

    $products_raw = get_option('sdt_products_list');
    $cities_raw = get_option('sdt_cities_list');

    $produtos = array_filter(array_map('trim', explode("\n", strval($products_raw))));
    $cidades = array_filter(array_map('trim', explode("\n", strval($cities_raw))));

    if (empty($produtos) || empty($cidades)) {
        $return_data['status'] = 'empty_lists';
        return $return_data;
    }

    // Dados da empresa e padrões
    $empresa = get_option('sdt_company_name', 'Sua Empresa');
    $whatsapp_number_raw = get_option('sdt_company_whatsapp', '');
    $whatsapp_number = sdt_format_whatsapp($whatsapp_number_raw); // sdt_format_whatsapp is assumed to exist from original plugin
    $form_default = get_option('sdt_default_form', '[contact-form-7 id="123" title="Formulário padrão"]');
    $whatsapp_msg_template = get_option('sdt_whatsapp_message', 'Olá, tenho interesse em [produto] em [cidade]');
    $company_logo_url = get_option('sdt_company_logo', '');
    $company_url = get_option('sdt_company_url', home_url());

    // Carregar dados das configurações SEO Avançado
    $seo_avancado_options_raw = get_option('sdt_seo_avancado_data', []);
    $seo_defaults = [
        'sdt_seo_palavra_chave_principal' => '',
        'sdt_seo_publico_alvo'            => '',
        'sdt_seo_titulo_artigo'           => 'Como [benefício ou solução] com [sdt_palavra_chave_principal]', // Default title structure
        'sdt_seo_introducao_artigo'       => 'Você está procurando maneiras de [resolver problema ou alcançar benefício]? Neste artigo, você vai entender como [sdt_palavra_chave_principal] pode ajudar você a [resultado]. Vamos abordar passo a passo tudo o que você precisa saber.',
        'sdt_seo_o_que_e_explicacao'      => 'Explique de forma clara e didática o que é a palavra-chave. Traga contexto, exemplos e dados se possível.',
        'sdt_seo_beneficios_principais'   => "Benefício 1\nBenefício 2\nBenefício 3",
        'sdt_seo_como_usar_aplicar'       => 'Descreva o passo a passo de aplicação, uso ou implementação do assunto. Pode ser um tutorial, checklist ou guia.',
        'sdt_seo_faq_lista'               => "Pergunta comum 1?||Resposta direta e objetiva com boas práticas\nPergunta comum 2?||Resposta objetiva e que agrega valor",
        'sdt_seo_conclusao_artigo'        => 'Resumo dos principais pontos, reforço do benefício e convite à ação.',
        'sdt_seo_cta_paragrafo'           => '<strong>Gostou do conteúdo?</strong> Entre em contato conosco para saber como aplicar [solução] no seu negócio e gerar resultados reais!',
        'sdt_seo_cta_link_ancora'         => '👉 Fale com um especialista',
        'sdt_seo_cta_link_url'            => 'https://seusite.com.br/contato',
        'sdt_seo_categoria_conteudo'      => '',
    ];
    $current_seo_options = wp_parse_args($seo_avancado_options_raw, $seo_defaults);

    // Carregar o conteúdo do template SEO Avançado
    $template_path = plugin_dir_path(__FILE__) . '../templates/template-seo-avancado.php';
    $conteudo_template_seo_avancado = '';
    if (file_exists($template_path)) {
        $conteudo_template_seo_avancado = file_get_contents($template_path);
    } else {
        // Se o template não for encontrado, podemos registrar um erro ou usar um fallback.
        // Por enquanto, a geração prosseguirá com conteúdo vazio se o template faltar.
    }

    // Título do template original (fallback se o título SEO avançado não for suficiente)
    $titulo_template_original = get_option('sdt_template_title', '[produto] em [cidade]');

    foreach ($produtos as $produto) {
        foreach ($cidades as $cidade) {
            $return_data['attempted']++;

            $formulario_shortcode = $form_default;
            $form_map_raw = get_option('sdt_product_forms');
            $form_map = array_filter(array_map('trim', explode("\n", strval($form_map_raw))));
            foreach ($form_map as $linha) {
                if (strpos($linha, '|') !== false) {
                    list($prod_form_key, $shortcode_val) = explode('|', $linha, 2);
                    if (trim($prod_form_key) === $produto) {
                        $formulario_shortcode = trim($shortcode_val);
                        break;
                    }
                }
            }

            // Montar array de placeholders
            $placeholders = [
                '[empresa]'    => esc_html($empresa),
                '[produto]'    => esc_html($produto),
                '[cidade]'     => esc_html($cidade),
                '[whatsapp]'   => esc_html($whatsapp_number_raw), // Usar o número raw para o link wa.me
                '[formulario]' => $formulario_shortcode, // Shortcodes são complexos para escapar globalmente
                
                // Novos placeholders SEO
                '[sdt_palavra_chave_principal]' => esc_html($current_seo_options['sdt_seo_palavra_chave_principal']),
                '[sdt_publico_alvo]'            => esc_html($current_seo_options['sdt_seo_publico_alvo']),
                '[sdt_titulo_artigo_seo]'       => esc_html($current_seo_options['sdt_seo_titulo_artigo']),
                '[sdt_introducao_artigo]'       => wp_kses_post($current_seo_options['sdt_seo_introducao_artigo']),
                '[sdt_o_que_e_explicacao]'      => wp_kses_post($current_seo_options['sdt_seo_o_que_e_explicacao']),
                '[sdt_como_usar_aplicar]'       => wp_kses_post($current_seo_options['sdt_seo_como_usar_aplicar']),
                '[sdt_conclusao_artigo]'        => wp_kses_post($current_seo_options['sdt_seo_conclusao_artigo']),
                '[sdt_cta_paragrafo]'           => wp_kses_post($current_seo_options['sdt_seo_cta_paragrafo']),
                '[sdt_cta_link_ancora]'         => esc_html($current_seo_options['sdt_seo_cta_link_ancora']),
                '[sdt_cta_link_url]'            => esc_url($current_seo_options['sdt_seo_cta_link_url']),
            ];

            // Processar lista de benefícios
            $beneficios_raw_list = trim($current_seo_options['sdt_seo_beneficios_principais']);
            $beneficios_html = '';
            if (!empty($beneficios_raw_list)) {
                $beneficios_items = explode("\n", $beneficios_raw_list);
                $beneficios_html = "<ul>\n";
                foreach ($beneficios_items as $item) {
                    $beneficios_html .= "  <li>✅ " . esc_html(trim($item)) . "</li>\n";
                }
                $beneficios_html .= "</ul>";
            }
            $placeholders['[sdt_beneficios_principais_lista]'] = $beneficios_html; // Já é HTML seguro

            // Processar lista de FAQ
            $faq_raw_list = trim($current_seo_options['sdt_seo_faq_lista']);
            $faq_html = '';
            if (!empty($faq_raw_list)) {
                $faq_pairs = explode("\n", $faq_raw_list);
                foreach ($faq_pairs as $pair) {
                    $parts = explode('||', $pair, 2);
                    if (count($parts) === 2) {
                        $question = trim($parts[0]);
                        $answer = trim($parts[1]);
                        $faq_html .= "<h3>" . esc_html($question) . "</h3>\n";
                        $faq_html .= "<p>" . wp_kses_post($answer) . "</p>\n";
                    }
                }
            }
            $placeholders['[sdt_faq_lista]'] = $faq_html; // Já é HTML seguro

            // Definir o título do post
            $post_title_final = sdt_replace_placeholders($current_seo_options['sdt_seo_titulo_artigo'], $placeholders);
            if (empty(trim($post_title_final))) {
                $post_title_final = sdt_replace_placeholders($titulo_template_original, $placeholders);
            }
            // Garantir que [produto] e [cidade] sejam substituídos no título final se ainda presentes
            $post_title_final = str_replace(['[produto]', '[cidade]'], [esc_html($produto), esc_html($cidade)], $post_title_final);

            // Gerar conteúdo base usando o template SEO Avançado
            $conteudo_base = sdt_replace_placeholders($conteudo_template_seo_avancado, $placeholders);

            // Adicionar Meta Tags (lógica original, verificar se é a melhor forma de adicionar no WP)
            $meta_description_content = sdt_replace_placeholders(sprintf('Distribuidor de %s em %s. %s oferece soluções em %s para sua região.', '[sdt_palavra_chave_principal]', esc_attr($cidade), esc_attr($empresa), '[sdt_palavra_chave_principal]'), $placeholders);
            $og_title_content = sdt_replace_placeholders(sprintf('%s – %s', '[sdt_titulo_artigo_seo]', esc_attr($empresa)), $placeholders);
            $current_page_slug = sdt_slugify($produto . '-' . $cidade); // Manter slug original por enquanto
            $current_page_url = site_url('/' . $current_page_slug);

            $meta_tags_html = "<meta name='description' content='" . esc_attr($meta_description_content) . "'>\n";
            $meta_tags_html .= "<meta property='og:url' content='" . esc_url($current_page_url) . "'>\n";
            if (!empty($company_logo_url)) {
                 $meta_tags_html .= "<meta property='og:image' content='" . esc_url($company_logo_url) . "'>\n";
            }
            $meta_tags_html .= "<meta name='robots' content='index, follow'>\n";
            $meta_tags_html .= "<meta property='og:title' content='" . esc_attr($og_title_content) . "'>\n";
            $meta_tags_html .= "<meta property='og:description' content='" . esc_attr($meta_description_content) . "'>\n";
            $meta_tags_html .= "<meta property='og:type' content='article'>\n";
            
            $conteudo_final = $meta_tags_html . $conteudo_base; // Prepend meta tags as per original logic

            // Botão WhatsApp (lógica original)
            $whatsapp_message_processed = sdt_replace_placeholders($whatsapp_msg_template, $placeholders);
            $whatsapp_url_final = "https://wa.me/" . esc_attr($whatsapp_number) . "?text=" . urlencode($whatsapp_message_processed); // Use $whatsapp_number (formatted)
            $btn_whatsapp = "<p><a href='" . esc_url($whatsapp_url_final) . "' class='button' target='_blank'>Fale pelo WhatsApp</a></p>";
            $conteudo_final .= "\n" . $btn_whatsapp;

            // Cross Links (lógica original)
            $cities_linked = array_diff($cidades, [$cidade]);
            $cross_links_html = '';
            if (!empty($cities_linked)) {
                $cross_links_html = '<p><strong>Atendemos também em:</strong> ';
                $city_links_array = [];
                $count = 0;
                foreach ($cities_linked as $c_link) {
                    if ($count >= 5) break;
                    $slug_link = sdt_slugify($produto . '-' . $c_link);
                    $url_link = site_url('/' . $slug_link);
                    $city_links_array[] = '<a href="' . esc_url($url_link) . '">' . esc_html($c_link) . '</a>';
                    $count++;
                }
                $cross_links_html .= implode(' | ', $city_links_array) . '</p>';
            }
            $conteudo_final .= "\n" . $cross_links_html;

            // JSON-LD (lógica original, adaptada)
            $json_ld_data = [
                "@context" => "https://schema.org",
                "@type" => ["LocalBusiness", "Product"],
                "name" => esc_html($empresa),
                "url" => esc_url($company_url),
                "telephone" => esc_html($whatsapp_number_raw),
                "address" => [
                    "@type" => "PostalAddress",
                    "addressLocality" => esc_html($cidade),
                    "addressRegion" => "BR", // Assuming BR, can be made configurable
                    "addressCountry" => "BR"
                ],
                "areaServed" => esc_html($cidade),
                "description" => sdt_replace_placeholders('Distribuidor de [sdt_palavra_chave_principal] em ' . esc_html($cidade), $placeholders)
            ];
            if (!empty($company_logo_url)) {
                $json_ld_data["image"] = esc_url($company_logo_url);
            }
            $json_script = '<script type="application/ld+json">' . wp_json_encode($json_ld_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
            $conteudo_final .= "\n" . $json_script;

            // Lazy load images (lógica original)
            $conteudo_final = preg_replace('/<img(.*?)>/i', '<img$1 loading="lazy">', $conteudo_final);

            // Definir categoria do post
            $categoria_nome_final = trim($current_seo_options['sdt_seo_categoria_conteudo']);
            if (empty($categoria_nome_final)) {
                $categoria_nome_final = $produto; // Fallback para nome do produto
            }
            $categoria_id = get_cat_ID($categoria_nome_final);
            if ($categoria_id == 0) {
                $term_id_or_error = wp_create_category($categoria_nome_final);
                if (is_wp_error($term_id_or_error)) {
                    $categoria_id = 0; 
                } else {
                    $categoria_id = $term_id_or_error;
                }
            }

            $post_args = [
                'post_title'    => $post_title_final,
                'post_content'  => $conteudo_final,
                'post_status'   => 'publish',
                'post_type'     => 'post', // Originalmente 'post', pode ser 'page' se preferir
                'post_name'     => $current_page_slug,
            ];
            if ($categoria_id > 0) {
                $post_args['post_category'] = [$categoria_id];
            }

            $post_id = wp_insert_post($post_args);

            if ($post_id && !is_wp_error($post_id)) {
                $return_data['generated']++;
            } else {
                // error_log('Falha ao inserir post para: ' . $produto . ' - ' . $cidade . ' Erro: ' . ($post_id ? $post_id->get_error_message() : 'unknown'));
            }
        }
    }

    sdt_generate_sitemap(); // Regenerar sitemap

    // Criar página de índice se não existir (lógica original)
    if (!get_page_by_path('index-seo-local')) {
        wp_insert_post([
            'post_title'   => 'Índice SEO Local',
            'post_name'    => 'index-seo-local',
            'post_content' => '[sdt_indexador]', // Shortcode para o indexador
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ]);
    }
    
    $return_data['status'] = 'ok';
    return $return_data;
}

// A função sdt_generate_pages() parece ser uma versão mais antiga ou alternativa.
// As modificações foram focadas em sdt_generate_seo_posts() que parece ser a principal usada.


