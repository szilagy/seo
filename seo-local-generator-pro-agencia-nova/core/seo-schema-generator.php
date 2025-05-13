
<?php
if (!defined('ABSPATH')) exit; // Impede o acesso direto ao arquivo

// Função para gerar dados estruturados (JSON-LD) para SEO
function sdt_generate_schema($post_id, $product, $city) {
    // Dados do schema para produto e cidade
    $schema_data = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product,
        'description' => "Encontre " . $product . " na cidade de " . $city . " com as melhores ofertas e condições.",
        'url' => get_permalink($post_id),
        'offers' => [
            '@type' => 'Offer',
            'priceCurrency' => 'BRL',
            'price' => '100.00', // Valor do produto, você pode configurar dinamicamente
            'itemCondition' => 'https://schema.org/NewCondition',
            'availability' => 'https://schema.org/InStock'
        ],
        'brand' => [
            '@type' => 'Brand',
            'name' => 'Marca do Produto' // Substitua com a marca dinâmica, se necessário
        ]
    ];

    // Adicionando o schema ao cabeçalho da página
    add_action('wp_head', function() use ($schema_data) {
        echo '<script type="application/ld+json">' . json_encode($schema_data) . '</script>' . PHP_EOL;
    });

    // Atualizando o schema no banco de dados
    update_post_meta($post_id, '_seo_schema_data', json_encode($schema_data));
}
