
<?php
if (!defined('ABSPATH')) exit; // Impede o acesso direto ao arquivo

// Template específico para escritórios
function sdt_generate_escritorio_template($post_id, $product, $city) {
    // Título e conteúdo dinâmico a partir das opções do painel
    $page_title = get_option('sdt_escritorio_page_title', 'Escritório de ' . $product . ' em ' . $city);
    $page_content = get_option('sdt_escritorio_page_content', '<h1>Bem-vindo ao nosso escritório de ' . $product . ' em ' . $city . '</h1>');
    
    // Imagem configurável
    $image_url = get_option('sdt_escritorio_image_url', 'https://example.com/default-image.jpg');

    // Personalizando o conteúdo com base nas opções
    $page_content .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($product) . ' em ' . esc_attr($city) . '" />';
    $page_content .= '<p>Somos um escritório especializado em ' . $product . ' localizado em ' . $city . '. Oferecemos serviços de alta qualidade e soluções jurídicas/pessoais/empresariais.</p>';
    $page_content .= '<p>Entre em contato conosco para saber como podemos ajudar no seu processo de ' . $product . '.</p>';

    // Gerar e adicionar o conteúdo à página
    wp_update_post([
        'ID' => $post_id,
        'post_title' => $page_title,
        'post_content' => $page_content
    ]);

    // Atualizando o conteúdo dinâmico
    update_post_meta($post_id, '_escritorio_product', $product);
    update_post_meta($post_id, '_escritorio_city', $city);
}
