<?php
if (!defined('ABSPATH')) exit;

function sdt_render_cidades_page() {
    $cidades = array_filter(array_map('trim', explode("\n", get_option('sdt_cities_list'))));

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['sdt_save_cidades'])) {
            $novas = [];
            foreach ($_POST['cidade'] as $i => $valor) {
                $valor = sanitize_text_field($valor);
                if (!empty($valor)) {
                    $novas[] = $valor;
                }
            }
            update_option('sdt_cities_list', implode("\n", $novas));
            echo '<div class="updated"><p>Lista de cidades atualizada com sucesso.</p></div>';
            $cidades = $novas;
        } elseif (isset($_POST['sdt_delete_selected'])) {
            $restantes = [];
            foreach ($cidades as $i => $item) {
                if (!isset($_POST['delete'][$i])) {
                    $restantes[] = $item;
                }
            }
            update_option('sdt_cities_list', implode("\n", $restantes));
            echo '<div class="updated"><p>Cidades selecionadas foram removidas.</p></div>';
            $cidades = $restantes;
        } elseif (isset($_POST['sdt_add_cidade'])) {
            $novos_itens = array_filter(array_map('trim', explode("\n", sanitize_textarea_field($_POST['nova_cidade']))));
            foreach ($novos_itens as $item) {
                if (!in_array($item, $cidades)) {
                    $cidades[] = $item;
                }
            }
            update_option('sdt_cities_list', implode("\n", $cidades));
            echo '<div class="updated"><p>Novas cidades adicionadas com sucesso.</p></div>';
        }
    }
    ?>
    <div class="wrap">
        <h1>Gerenciar Cidades</h1>

        <h2>Adicionar Novas Cidades</h2>
        <form method="post" style="margin-bottom: 30px;">
            <textarea name="nova_cidade" placeholder="cidade 1&#10;cidade 2" style="width: 50%;" rows="4"></textarea><br>
            <input type="submit" name="sdt_add_cidade" class="button button-primary" value="Adicionar Cidades">
        </form>

        <form method="post">
            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th>Excluir</th>
                        <th>Cidade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cidades as $i => $cidade): ?>
                        <tr>
                            <td><input type="checkbox" name="delete[<?php echo $i; ?>]"></td>
                            <td><input type="text" name="cidade[<?php echo $i; ?>]" value="<?php echo esc_attr($cidade); ?>" style="width: 100%;" /></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <input type="submit" name="sdt_save_cidades" class="button button-primary" value="Salvar Tudo">
            <input type="submit" name="sdt_delete_selected" class="button button-secondary" value="Excluir Selecionadas">
        </form>
    </div>
    <?php
}
