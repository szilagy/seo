<?php
if (!defined("ABSPATH")) exit;

function sdt_render_formularios_page() {
    // Verificar se o Contact Form 7 está ativo
    if (!is_plugin_active("contact-form-7/wp-contact-form-7.php")) {
        echo 
        '<div class="notice notice-error"><p>'
         . __("O plugin Contact Form 7 não está ativo. Esta funcionalidade requer o Contact Form 7.", "sdt-domain") . 
        '</p></div>';
        return;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["sdt_save_forms_nonce"]) && wp_verify_nonce($_POST["sdt_save_forms_nonce"], "sdt_save_forms_action")) {
        if (current_user_can("manage_options")) {
            $entries = [];
            if (isset($_POST["sdt_product_cf7_id"]) && is_array($_POST["sdt_product_cf7_id"])){
                foreach ($_POST["sdt_product_cf7_id"] as $produto_key => $form_id) {
                    $produto = sanitize_text_field(wp_unslash($produto_key));
                    $form_id = intval($form_id);
                    
                    $usar = isset($_POST["sdt_product_use"][$produto]);

                    if ($form_id > 0 && $usar) {
                        $form_post = get_post($form_id);
                        if ($form_post && $form_post->post_type === "wpcf7_contact_form") {
                            $shortcode = sprintf("[contact-form-7 id=\"%d\" title=\"%s\"]", $form_id, esc_attr($form_post->post_title));
                            $entries[] = $produto . "|" . $shortcode;
                        }
                    }
                }
            }
            update_option("sdt_product_forms", implode("\n", $entries));
            // Adiciona um parâmetro para a notice de sucesso no redirecionamento ou exibe diretamente.
            // Para exibir diretamente (sem recarregar a página com GET param):
            echo '<div class="notice notice-success is-dismissible"><p>' . __("Formulários por produto salvos com sucesso!", "sdt-domain") . '</p></div>';

        } else {
            echo '<div class="notice notice-error"><p>' . __("Você não tem permissão para salvar estas configurações.", "sdt-domain") . '</p></div>';
        }
    }

    $produtos_raw = get_option("sdt_products_list");
    $produtos = array_filter(array_map("trim", explode("\n", strval($produtos_raw))));
    
    $form_map_raw = get_option("sdt_product_forms");
    $form_map_lines = array_filter(array_map("trim", explode("\n", strval($form_map_raw))));
    $form_map = [];
    foreach ($form_map_lines as $linha) {
        if (strpos($linha, "|") !== false) {
            list($prod, $shortcode_value) = explode("|", $linha, 2);
            if (preg_match("/id=\"(\d+)\"/", $shortcode_value, $matches)) {
                $form_map[trim($prod)] = intval($matches[1]);
            }
        }
    }

    $cf7_forms_query = new WP_Query([
        "post_type" => "wpcf7_contact_form",
        "posts_per_page" => -1,
        "orderby" => "title",
        "order" => "ASC",
    ]);
    $cf7_forms = $cf7_forms_query->get_posts();

    ?>
    <div class="wrap">
        <h1><?php _e("Formulários por Produto", "sdt-domain"); ?></h1>
        <p><?php _e("Associe um formulário do Contact Form 7 a cada produto. Se nenhum formulário específico for definido para um produto, o formulário padrão (configurado na página principal do plugin) será utilizado.", "sdt-domain"); ?></p>
        
        <form method="post">
            <?php wp_nonce_field("sdt_save_forms_action", "sdt_save_forms_nonce"); ?>
            <table class="widefat fixed striped sdt-form-table">
                <thead>
                    <tr>
                        <th class="sdt-col-checkbox"><?php _e("Usar?", "sdt-domain"); ?></th>
                        <th class="sdt-col-produto"><?php _e("Produto", "sdt-domain"); ?></th>
                        <th class="sdt-col-cf7"><?php _e("Formulário CF7 Associado", "sdt-domain"); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($produtos)): ?>
                        <?php foreach ($produtos as $produto_item): 
                            $produto_esc = esc_attr($produto_item);
                            $selected_form_id = $form_map[$produto_item] ?? 0;
                        ?>
                        <tr>
                            <td><input type="checkbox" name="sdt_product_use[<?php echo $produto_esc; ?>]" <?php checked(isset($form_map[$produto_item])); ?>></td>
                            <td><?php echo esc_html($produto_item); ?></td>
                            <td>
                                <?php if (!empty($cf7_forms)): ?>
                                    <select name="sdt_product_cf7_id[<?php echo $produto_esc; ?>]" class="regular-text">
                                        <option value="0" <?php selected($selected_form_id, 0); ?>><?php _e("-- Usar Formulário Padrão --", "sdt-domain"); ?></option>
                                        <?php foreach ($cf7_forms as $form): ?>
                                            <option value="<?php echo esc_attr($form->ID); ?>" <?php selected($selected_form_id, $form->ID); ?>><?php echo esc_html($form->post_title); ?> (ID: <?php echo esc_html($form->ID); ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <?php _e("Nenhum formulário do Contact Form 7 encontrado.", "sdt-domain"); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3"><?php _e("Nenhum produto cadastrado na lista de produtos. Adicione produtos na página principal do plugin para poder associar formulários.", "sdt-domain"); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <p class="submit">
                <?php submit_button(__("Salvar Associação de Formulários", "sdt-domain"), "primary", "sdt_save_forms_submit"); ?>
            </p>
        </form>
    </div>
    <style>
        .sdt-form-table .sdt-col-checkbox { width: 5%; }
        .sdt-form-table .sdt-col-produto { width: 35%; }
        .sdt-form-table .sdt-col-cf7 { width: 60%; }
    </style>
    <?php
}

