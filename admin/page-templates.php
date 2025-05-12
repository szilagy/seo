<?php
if (!defined('ABSPATH')) exit;

function sdt_render_templates_page() {
    $template_dir = plugin_dir_path(__FILE__) . '../templates/';
    $template_files = glob($template_dir . '*.php');
    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sdt_criar_template'])) {
    $new_name = sanitize_file_name($_POST['novo_template_nome']);
    $new_content = stripslashes($_POST['novo_template_conteudo']);
    if (!empty($new_name) && !file_exists($template_dir . $new_name)) {
        file_put_contents($template_dir . $new_name, $new_content);
        $message = '<div class="updated"><p>Novo template criado com sucesso.</p></div>';
    } else {
        $message = '<div class="error"><p>Erro: nome inválido ou arquivo já existe.</p></div>';
    }
}


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sdt_save_template']) && isset($_POST['template_file'])) {
        $filename = basename($_POST['template_file']);
        $content = stripslashes($_POST['template_content']);
        file_put_contents($template_dir . $filename, $content);
        $message = '<div class="updated"><p>Template salvo com sucesso.</p></div>';
    }

    $selected_file = isset($_GET['template']) ? basename($_GET['template']) : null;
    $selected_content = $selected_file && file_exists($template_dir . $selected_file)
        ? file_get_contents($template_dir . $selected_file)
        : '';
    ?>
    <div class="wrap">
        <h1>Gerenciar Templates de Layout</h1>
        <?php echo $message; ?>

        <h2>Templates Disponíveis</h2>
        <ul>
            <?php foreach ($template_files as $file): 
                $base = basename($file); ?>
                <li>
                    <a href="?page=sdt-templates&template=<?php echo urlencode($base); ?>">
                        <?php echo esc_html($base); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
<hr>
<h2><?php echo $selected_file ? 'Editar Template' : 'Criar Novo Template'; ?></h2>
<form method="post">
    <table class="form-table">
        <tr>
            <th><label for="template_file">Nome do Arquivo (ex: template-exemplo.php)</label></th>
            <td><input type="text" name="template_file" id="template_file" class="regular-text" value="<?php echo esc_attr($selected_file); ?>" <?php echo $selected_file ? 'readonly' : ''; ?> /></td>
        </tr>
    </table>
    <?php
    wp_editor(
        $selected_content,
        'template_content',
        [
            'textarea_name' => 'template_content',
            'media_buttons' => false,
            'textarea_rows' => 15,
            'tinymce' => true,
            'quicktags' => true
        ]
    );
    ?>
    <br>
    <input type="submit" name="<?php echo $selected_file ? 'sdt_save_template' : 'sdt_criar_template'; ?>" class="button button-primary" value="<?php echo $selected_file ? 'Salvar Template' : 'Criar Template'; ?>">
</form>


        



        <?php if ($selected_file): ?>
            
        <?php endif; ?>
    </div>
    <?php
}
