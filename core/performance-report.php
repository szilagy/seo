
<?php
if (!defined('ABSPATH')) exit; // Impede o acesso direto ao arquivo

// Função para gerar o relatório de desempenho das páginas
function sdt_generate_performance_report() {
    // Pega todas as páginas geradas
    $args = [
        'post_type' => 'page', // Alterar para incluir apenas páginas geradas pelo plugin, se necessário
        'posts_per_page' => -1, // Pega todas as páginas
        'post_status' => 'publish',
    ];
    
    $posts = get_posts($args);
    
    // Inicializa o relatório
    $report = 'Relatório de Desempenho - SEO Local Generator' . PHP_EOL;
    $report .= '---------------------------------------' . PHP_EOL;

    // Loop pelas páginas para coletar dados de visualizações
    foreach ($posts as $post) {
        $views = get_post_meta($post->ID, '_views_count', true); // Exemplo de como pegar as visualizações (substitua conforme necessário)
        $views = $views ? $views : 0; // Se não houver visualizações, define como 0

        // Adiciona dados ao relatório
        $report .= 'Página: ' . get_permalink($post->ID) . PHP_EOL;
        $report .= 'Visualizações: ' . $views . PHP_EOL;
        $report .= 'Última Modificação: ' . get_the_modified_date('Y-m-d', $post->ID) . PHP_EOL;
        $report .= '---------------------------------------' . PHP_EOL;
    }

    // Salva o relatório em um arquivo de texto
    $report_path = ABSPATH . 'performance-report.txt';
    file_put_contents($report_path, $report);
    
    // Opcional: Exibir link para o relatório no painel de administração
    add_action('admin_notices', function() use ($report_path) {
        echo '<div class="updated"><p>Relatório de desempenho gerado: <a href="' . home_url('/performance-report.txt') . '" target="_blank">Clique aqui para visualizar</a></p></div>';
    });
}
