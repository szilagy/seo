<?php
if (!defined("ABSPATH")) exit;

function sdt_render_formularios_page() {
    $layout_atual = get_option('sdt_seo_avancado_template', [])['sdt_seo_template_layout'] ?? '';

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["sdt_save_forms_nonce"]) && wp_verify_nonce($_POST["sdt_save_forms_nonce"], "sdt_save_forms_action")) {
        if (current_user_can("manage_options")) {
            $entries = [];
            if (isset($_POST["sdt_product_cf7_id"]) && is_array($_POST["sdt_product_cf7_id"])) {
                foreach ($_POST["sdt_product_cf7_id"] as $produto_key => $form_id) {
                    $produto = sanitize_text_field(wp_unslash($produto_key));
                    $form_id = intval($form_id);
                    $usar = isset($_POST["sdt_product_use"][$produto]);

                    if ($form_id > 0 && $usar) {
                        $form_post = get_post($form_id);
                        if ($form_post && $form_post->post_type === "wpcf7_contact_form") {
                            $shortcode = sprintf('[contact-form-7 id="%d" title="%s"]', $form_id, esc_attr($form_post->post_title));
                            $entries[] = $produto . "|" . $shortcode;
                        }
                    }
                }
            }
            update_option("sdt_product_forms", implode("\n", $entries));
            update_option('sdt_seo_avancado_template', [
                'sdt_seo_template_layout' => sanitize_text_field($_POST['sdt_seo_template_layout'] ?? '')
            ]);
            echo '<div class="notice notice-success is-dismissible"><p>Formulários salvos com sucesso.</p></div>';
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
            if (preg_match('/id="(\d+)"/', $shortcode_value, $matches)) {
                $form_map[trim($prod)] = intval($matches[1]);
            }
        }
    }

    $cf7_forms = get_posts([
        'post_type' => 'wpcf7_contact_form',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ]);
    ?>

    <div class="wrap">
        <h1>Formulários por Produto</h1>
        <form method="post">
            <?php wp_nonce_field("sdt_save_forms_action", "sdt_save_forms_nonce"); ?>

            <table class="form-table">
                <?php foreach ($produtos as $produto): ?>
                    <?php
                    $produto_esc = esc_attr($produto);
                    $selected_form_id = $form_map[$produto] ?? 0;
                    ?>
                    <tr>
                        <th scope="row"><?php echo esc_html($produto); ?></th>
                        <td>
                            <input type="checkbox" name="sdt_product_use[<?php echo $produto_esc; ?>]" <?php checked(isset($form_map[$produto])); ?>>
                            <select name="sdt_product_cf7_id[<?php echo $produto_esc; ?>]">
                                <option value="0">-- Usar padrão --</option>
                                <?php foreach ($cf7_forms as $form): ?>
                                    <option value="<?php echo esc_attr($form->ID); ?>" <?php selected($form->ID, $selected_form_id); ?>>
                                        <?php echo esc_html($form->post_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <h2>Selecione o layout (modelo)</h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="sdt_seo_template_layout">Selecione o layout (modelo)</label></th>
                    <td>
                        <select name="sdt_seo_template_layout" id="sdt_seo_template_layout">
                            <option value="">— Selecione —</option>
                            <?php
                            $templates_dir = plugin_dir_path(__FILE__) . '../templates/';
                            $template_files = glob($templates_dir . '*.php');
                            if ($template_files) {
                                foreach ($template_files as $template_path) {
                                    $template_file = basename($template_path);
                                    $selected = selected($template_file, $layout_atual, false);
                                    echo '<option value="' . esc_attr($template_file) . '" ' . $selected . '>' . esc_html($template_file) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                
            </table>

            <p class="submit">
                <?php submit_button('Salvar'); ?>
            </p>
        </form>
    </div>
<?php } ?>
