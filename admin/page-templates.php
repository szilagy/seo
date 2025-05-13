<?php
if ( ! defined('ABSPATH') ) {
    exit;
}

function sdt_render_templates_page() {
    $template_dir = plugin_dir_path(__FILE__) . '../templates/';
    $editing_template = isset($_GET['template']) ? sanitize_file_name($_GET['template']) : '';
    $template_path = $editing_template ? $template_dir . $editing_template : '';

    $novo_template_nome = $editing_template;
    $novo_template_conteudo = '';

    // Carrega conteúdo do template se estiver editando
    if ( $editing_template && file_exists($template_path) ) {
        $novo_template_conteudo = file_get_contents($template_path);
    }

    // Tratamento do POST
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
        $novo_template_nome = isset($_POST['novo_template_nome']) ? sanitize_file_name($_POST['novo_template_nome']) : '';
        $novo_template_conteudo = isset($_POST['novo_template_conteudo']) ? wp_kses_post($_POST['novo_template_conteudo']) : '';
        $template_path = $template_dir . $novo_template_nome;

        if ( ! preg_match('/^template-[a-zA-Z0-9\-_]+\.php$/', $novo_template_nome) ) {
            echo '<div class="notice notice-error"><p><strong>Erro:</strong> nome inválido de arquivo.</p></div>';
        } else {
            if ( ! file_exists($template_dir) ) {
                wp_mkdir_p($template_dir);
            }
            file_put_contents($template_path, $novo_template_conteudo);
            echo '<div class="notice notice-success"><p>Template ' . ($editing_template ? 'atualizado' : 'criado') . ' com sucesso!</p></div>';
            $editing_template = $novo_template_nome;
        }
    }

    $template_files = glob($template_dir . '*.php');
    ?>

    <div class="wrap">
        <h1>Gerenciar Templates de Layout</h1>

        <h2>Templates Disponíveis</h2>
        <ul>
            <?php if ( ! empty($template_files) ): ?>
                <?php foreach ( $template_files as $file_path ): ?>
                    <?php $file = basename($file_path); ?>
                    <li>
                        <a href="?page=sdt-templates&template=<?php echo esc_attr($file); ?>">
                            <?php echo esc_html($file); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Nenhum template encontrado.</li>
            <?php endif; ?>
        </ul>

        <h2><?php echo $editing_template ? 'Editar Template' : 'Criar Novo Template'; ?></h2>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th><label for="novo_template_nome">Nome do Arquivo<br><small>(ex: template-exemplo.php)</small></label></th>
                    <td>
                        <input type="text" name="novo_template_nome" id="novo_template_nome" class="regular-text"
                               value="<?php echo esc_attr($novo_template_nome); ?>" required
                               <?php echo $editing_template ? 'readonly' : ''; ?>>
                    </td>
                </tr>
                <tr>
                    <th><label for="novo_template_conteudo">Conteúdo do Template</label></th>
                    <td>
                        <?php
                        wp_editor(
                            $novo_template_conteudo,
                            'novo_template_conteudo',
                            array(
                                'textarea_name' => 'novo_template_conteudo',
                                'textarea_rows' => 15,
                                'media_buttons' => false,
                                'teeny'         => false,
                                'quicktags'     => true,
                            )
                        );
                        ?>
                    </td>
                </tr>
            </table>

            <?php
            if ( function_exists('submit_button') ) {
                submit_button($editing_template ? 'Salvar Alterações' : 'Criar Template');
            } else {
                echo '<p><input type="submit" class="button button-primary" value="' . ($editing_template ? 'Salvar Alterações' : 'Criar Template') . '"></p>';
            }
            ?>
        </form>
    </div>
    <?php
}
