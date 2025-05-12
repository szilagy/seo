<?php
if (!defined('ABSPATH')) exit;

function sdt_render_palavras_chave_page() {
    // Lógica para salvar, atualizar ou deletar palavras-chave
    if (isset($_POST['sdt_palavras_chave_nonce']) && wp_verify_nonce($_POST['sdt_palavras_chave_nonce'], 'sdt_save_palavras_chave')) {
        if (isset($_POST['sdt_add_palavra_chave']) && !empty(trim($_POST['nova_palavra_chave']))) {
            $nova_palavra = sanitize_text_field(trim($_POST['nova_palavra_chave']));
            $palavras_chave = get_option('sdt_palavras_chave_list', []);
            if (!in_array($nova_palavra, $palavras_chave)) {
                $palavras_chave[] = $nova_palavra;
                update_option('sdt_palavras_chave_list', $palavras_chave);
                echo '<div class="updated"><p>Palavra-chave adicionada com sucesso!</p></div>';
            } else {
                echo '<div class="error"><p>Erro: Palavra-chave já existe.</p></div>';
            }
        } elseif (isset($_POST['sdt_delete_palavra_chave']) && isset($_POST['palavra_chave_to_delete'])) {
            $palavra_a_deletar = sanitize_text_field(stripslashes($_POST['palavra_chave_to_delete']));
            $palavras_chave = get_option('sdt_palavras_chave_list', []);
            $index = array_search($palavra_a_deletar, $palavras_chave);
            if ($index !== false) {
                unset($palavras_chave[$index]);
                update_option('sdt_palavras_chave_list', array_values($palavras_chave)); // Reindexar array
                echo '<div class="updated"><p>Palavra-chave removida com sucesso!</p></div>';
            } else {
                 echo '<div class="error"><p>Erro: Palavra-chave não encontrada para remoção.</p></div>';
            }
        }
    }

    $palavras_chave_list = get_option('sdt_palavras_chave_list', []);
    ?>
    <div class="wrap">
        <h1>Gerenciar Palavras-chave</h1>
        <p>Adicione ou remova as palavras-chave que serão utilizadas na geração de conteúdo.</p>

        <h2>Adicionar Nova Palavra-chave</h2>
        <form method="POST" action="">
            <?php wp_nonce_field('sdt_save_palavras_chave', 'sdt_palavras_chave_nonce'); ?>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="nova_palavra_chave">Nova Palavra-chave</label></th>
                        <td><input name="nova_palavra_chave" type="text" id="nova_palavra_chave" value="" class="regular-text"></td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" name="sdt_add_palavra_chave" class="button button-primary" value="Adicionar Palavra-chave">
        </form>

        <h2>Palavras-chave Cadastradas</h2>
        <?php if (!empty($palavras_chave_list)): ?>
            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th>Palavra-chave</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($palavras_chave_list as $palavra): ?>
                        <tr>
                            <td><?php echo esc_html($palavra); ?></td>
                            <td>
                                <form method="POST" action="" style="display:inline;">
                                    <?php wp_nonce_field('sdt_save_palavras_chave', 'sdt_palavras_chave_nonce'); ?>
                                    <input type="hidden" name="palavra_chave_to_delete" value="<?php echo esc_attr($palavra); ?>">
                                    <input type="submit" name="sdt_delete_palavra_chave" class="button button-link-delete" value="Remover" onclick="return confirm('Tem certeza que deseja remover esta palavra-chave?');">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma palavra-chave cadastrada ainda.</p>
        <?php endif; ?>
    </div>
    <?php
}

