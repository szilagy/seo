
<?php
if (!defined('ABSPATH')) exit; // Impede o acesso direto ao arquivo

// Função principal para gerar as páginas de produto e cidade
function sdt_generate_pages() {
    // Obtém as listas de produtos e cidades a partir das opções do painel
    $products = get_option('sdt_products', []);
    $cities = get_option('sdt_cities', []);
    
    // Verifica se as listas de produtos e cidades não estão vazias
    if (empty($products) || empty($cities)) {
        return;
    }

    // Gera uma página para cada combinação de produto e cidade
    foreach ($products as $product) {
        foreach ($cities as $city) {
            $post_title = $product . ' em ' . $city;
            $post_content = 'Esta página foi gerada automaticamente para ' . $product . ' na cidade de ' . $city . '.';

            // Criação de uma nova página
            $post_data = [
                'post_title'   => $post_title,
                'post_content' => $post_content,
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ];

            // Insere a página no WordPress
            $post_id = wp_insert_post($post_data);

            // Adiciona meta dados para identificar a página gerada
            update_post_meta($post_id, '_is_seo_local_page', true);
        }
    }
}
