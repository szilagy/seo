
<?php
if (!defined('ABSPATH')) exit; // Impede o acesso direto ao arquivo

// Função para gerar o Sitemap XML para SEO
function sdt_generate_sitemap() {
    // Filtra apenas as páginas geradas pelo plugin, usando um meta campo '_is_seo_local_page' para identificar
    $args = [
        'post_type' => 'page',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_key' => '_is_seo_local_page', // Meta chave para identificar páginas geradas pelo plugin
    ];
    
    $posts = get_posts($args);
    
    // Cabeçalho do arquivo XML
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

    // Loop pelas páginas para adicionar as URLs
    foreach ($posts as $post) {
        $sitemap .= '<url>' . PHP_EOL;
        $sitemap .= '<loc>' . get_permalink($post->ID) . '</loc>' . PHP_EOL;
        $sitemap .= '<lastmod>' . get_the_modified_date('Y-m-d', $post->ID) . '</lastmod>' . PHP_EOL;
        $sitemap .= '<changefreq>monthly</changefreq>' . PHP_EOL;
        $sitemap .= '<priority>0.5</priority>' . PHP_EOL;
        $sitemap .= '</url>' . PHP_EOL;
    }

    // Fechar tag do URL set
    $sitemap .= '</urlset>' . PHP_EOL;

    // Salvar o sitemap em um arquivo
    $sitemap_path = ABSPATH . 'sitemap.xml';
    file_put_contents($sitemap_path, $sitemap);
    
    // Opcional: Exibir o link para o sitemap no painel de administração
    add_action('admin_notices', function() use ($sitemap_path) {
        echo '<div class="updated"><p>Sitemap gerado: <a href="' . home_url('/sitemap.xml') . '" target="_blank">Clique aqui para visualizar</a></p></div>';
    });
}
