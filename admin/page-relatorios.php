<?php
if (!defined('ABSPATH')) exit;

function sdt_render_relatorios_page() {
    // Coleta de dados
    $total_posts = wp_count_posts('post')->publish;
    $categorias = get_categories(['hide_empty' => false]);
    $por_produto = [];
    foreach ($categorias as $cat) {
        $por_produto[$cat->name] = $cat->count;
    }

    $city_counts = [];
    foreach (get_posts(['post_type' => 'post', 'numberposts' => -1]) as $p) {
        if (preg_match('/em ([A-ZÁÉÍÓÚÂÊÔÃÕÇa-záéíóúâêôãõç\s]+)/', $p->post_title, $match)) {
            $cidade = trim($match[1]);
            $city_counts[$cidade] = isset($city_counts[$cidade]) ? $city_counts[$cidade] + 1 : 1;
        }
    }

    $form_logs = get_option('sdt_form_submissions_log', []);
    $form_counts = [];
    foreach ($form_logs as $item) {
        $key = $item['form'] ?? 'Desconhecido';
        $form_counts[$key] = isset($form_counts[$key]) ? $form_counts[$key] + 1 : 1;
    }

    ?>
    <div class="wrap">
        <h1>Relatórios de Desempenho</h1>
        <p><strong>Total de páginas geradas:</strong> <?php echo $total_posts; ?></p>

        <div id="chart_produtos" style="width: 100%; height: 400px;"></div>
        <div id="chart_cidades" style="width: 100%; height: 400px;"></div>
        <div id="chart_formularios" style="width: 100%; height: 400px;"></div>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script>
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // Produtos
            var dataProd = google.visualization.arrayToDataTable([
                ['Produto', 'Páginas'],
                <?php foreach ($por_produto as $prod => $qtd) {
                    echo "['" . esc_js($prod) . "', $qtd],";
                } ?>
            ]);
            var prodChart = new google.visualization.ColumnChart(document.getElementById('chart_produtos'));
            prodChart.draw(dataProd, {title: 'Páginas por Produto'});

            // Cidades
            var dataCid = google.visualization.arrayToDataTable([
                ['Cidade', 'Acessos'],
                <?php foreach ($city_counts as $cidade => $qtd) {
                    echo "['" . esc_js($cidade) . "', $qtd],";
                } ?>
            ]);
            var cidadeChart = new google.visualization.ColumnChart(document.getElementById('chart_cidades'));
            cidadeChart.draw(dataCid, {title: 'Cidades Mais Acessadas'});

            // Formulários
            var dataForms = google.visualization.arrayToDataTable([
                ['Formulário', 'Envios'],
                <?php foreach ($form_counts as $form => $qtd) {
                    echo "['" . esc_js($form) . "', $qtd],";
                } ?>
            ]);
            var formChart = new google.visualization.PieChart(document.getElementById('chart_formularios'));
            formChart.draw(dataForms, {title: 'Formulários Mais Preenchidos'});
        }
        </script>
    </div>
    <?php
}
