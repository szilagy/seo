
<?php
if (!defined('ABSPATH')) exit; // Impede o acesso direto ao arquivo

// Função para gerar meta tags dinâmicas para SEO
function sdt_generate_meta_tags($post_id, $product, $city) {
    // Definindo o título da página (title)
    $meta_title = "Comprar " . $product . " em " . $city . " | SEO Local Generator";
    
    // Definindo a descrição da página (description)
    $meta_description = "Encontre " . $product . " na cidade de " . $city . " com as melhores ofertas e condições.";
    
    // Definindo as palavras-chave da página (keywords)
    $meta_keywords = $product . ", " . $city . ", comprar " . $product . ", " . $product . " " . $city;

    // Adicionando as meta tags ao cabeçalho da página
    add_action('wp_head', function() use ($meta_title, $meta_description, $meta_keywords) {
        echo '<meta name="title" content="' . esc_attr($meta_title) . '">' . PHP_EOL;
        echo '<meta name="description" content="' . esc_attr($meta_description) . '">' . PHP_EOL;
        echo '<meta name="keywords" content="' . esc_attr($meta_keywords) . '">' . PHP_EOL;
    });

    // Atualizando as meta tags no banco de dados para que o título e a descrição possam ser utilizados para cada página gerada
    update_post_meta($post_id, '_seo_meta_title', $meta_title);
    update_post_meta($post_id, '_seo_meta_description', $meta_description);
    update_post_meta($post_id, '_seo_meta_keywords', $meta_keywords);
}
