<?php
if (!defined('ABSPATH')) exit;

function sdt_render_produtos_page() {
    $produtos = array_filter(array_map('trim', explode("\n", get_option('sdt_products_list'))));

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['sdt_save_produtos'])) {
            $novos = [];
            foreach ($_POST['produto'] as $i => $valor) {
                $valor = sanitize_text_field($valor);
                if (!empty($valor)) {
                    $novos[] = $valor;
                }
            }
            update_option('sdt_products_list', implode("\n", $novos));
            echo '<div class="updated"><p>Lista de produtos atualizada com sucesso.</p></div>';
            $produtos = $novos;
        } elseif (isset($_POST['sdt_delete_selected'])) {
            $restantes = [];
            foreach ($produtos as $i => $item) {
                if (!isset($_POST['delete'][$i])) {
                    $restantes[] = $item;
                }
            }
            update_option('sdt_products_list', implode("\n", $restantes));
            echo '<div class="updated"><p>Produtos selecionados foram removidos.</p></div>';
            $produtos = $restantes;
        } elseif (isset($_POST['sdt_add_produto'])) {
            $novos_itens = array_filter(array_map('trim', explode("\n", sanitize_textarea_field($_POST['novo_produto']))));
            foreach ($novos_itens as $item) {
                if (!in_array($item, $produtos)) {
                    $produtos[] = $item;
                }
            }
            update_option('sdt_products_list', implode("\n", $produtos));
            echo '<div class="updated"><p>Novos produtos adicionados com sucesso.</p></div>';
        }
    }
    ?>
    <div class="wrap">
        <h1>Gerenciar Produtos</h1>

        <h2>Adicionar Novos Produtos</h2>
        <form method="post" style="margin-bottom: 30px;">
            <textarea name="novo_produto" placeholder="item 1&#10;item 2" style="width: 50%;" rows="4"></textarea><br>
            <input type="submit" name="sdt_add_produto" class="button button-primary" value="Adicionar Produtos">
        </form>

        <form method="post">
            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th>Excluir</th>
                        <th>Produto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $i => $produto): ?>
                        <tr>
                            <td><input type="checkbox" name="delete[<?php echo $i; ?>]"></td>
                            <td><input type="text" name="produto[<?php echo $i; ?>]" value="<?php echo esc_attr($produto); ?>" style="width: 100%;" /></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <input type="submit" name="sdt_save_produtos" class="button button-primary" value="Salvar Tudo">
            <input type="submit" name="sdt_delete_selected" class="button button-secondary" value="Excluir Selecionados">
        </form>
    </div>
    <?php
}
