<?php
if (!defined("ABSPATH")) exit;

// Função para registrar as notices que serão exibidas
function sdt_admin_notices() {
    if (!isset($_GET["sdt_notice"])) {
        return;
    }

    $notice_type = sanitize_key($_GET["sdt_notice"]); // Sanitizar a chave do notice
    $message = "";
    $class = "notice-error"; // Default to error

    switch ($notice_type) {
        case "gerado_sucesso":
            $count = isset($_GET["sdt_count"]) ? intval($_GET["sdt_count"]) : 0;
            $message = sprintf(__("%d páginas foram geradas/atualizadas com sucesso!", "sdt-domain"), $count);
            $class = "notice-success is-dismissible";
            break;
        case "gerado_nada_novo":
            $attempted = isset($_GET["sdt_attempted"]) ? intval($_GET["sdt_attempted"]) : 0;
            $message = sprintf(__("Foram verificadas %d combinações, mas nenhuma página nova precisou ser criada ou atualizada.", "sdt-domain"), $attempted);
            $class = "notice-info is-dismissible";
            break;
        case "gerado_sem_tentativas":
            $message = __("Nenhuma página foi gerada pois não houve combinações válidas para processar (verifique suas listas de produtos e cidades).", "sdt-domain");
            $class = "notice-warning is-dismissible";
            break;
        case "listas_vazias":
            $message = __("As listas de produtos e/ou cidades estão vazias. Preencha-as para gerar as páginas.", "sdt-domain");
            $class = "notice-warning is-dismissible";
            break;
        case "gerado_falha_desconhecida":
            $message = __("Ocorreu uma falha desconhecida durante a geração das páginas.", "sdt-domain");
            $class = "notice-error is-dismissible";
            break;
        case "gerado_erro_retorno_invalido":
            $message = __("Ocorreu um erro no processamento do retorno da função de geração.", "sdt-domain");
            $class = "notice-error is-dismissible";
            break;
        case "config_salva": // Embora não usado no fluxo de geração, pode ser útil
            $message = __("Configurações salvas com sucesso!", "sdt-domain");
            $class = "notice-success is-dismissible";
            break;
    }

    if ($message) {
        printf("<div class=\"notice %s\"><p>%s</p></div>", esc_attr($class), esc_html($message));
    }
}
add_action("admin_notices", "sdt_admin_notices");


function sdt_render_settings_page() {
    // Função interna para carregar conteúdo de templates
    function sdt_get_template_content_for_js($filename) {
        $path = plugin_dir_path(__FILE__) . "../templates/" . $filename;
        if (file_exists($path)){
            $content = file_get_contents($path);
            // Remove tags PHP para evitar execução ou erros no JS
            return trim(preg_replace("/<\?php.*?\?>/s", "", $content));
        }
        return ""; // Retorna string vazia se o arquivo não existir
    }
    ?>
    <div class="wrap">
        <h1><?php _e("Configuração – SEO Local Generator", "sdt-domain"); ?></h1>

        <?php /* As notices serão exibidas aqui pela action 'admin_notices' */ ?>

        <form method="post" action="<?php echo esc_url(admin_url("admin-post.php")); ?>">
            <input type="hidden" name="action" value="sdt_generate"> <?php // Ação para admin-post.php ?>
            <?php wp_nonce_field("sdt_generate_action", "sdt_generate_nonce_field"); ?>
            
            <h3><?php _e("Formulário padrão (CF7 shortcode)", "sdt-domain"); ?></h3>
            <input type="text" name="sdt_default_form" value="<?php echo esc_attr(get_option("sdt_default_form")); ?>" size="50" />

            <h3><?php _e("Formulários por Produto (1 por linha: produto|shortcode)", "sdt-domain"); ?></h3>
            <p class="description"><?php _e("Este campo será substituído pela nova interface na aba 'Formulários por Produto' se você usar a seleção via dropdown.", "sdt-domain"); ?></p>
            <textarea name="sdt_product_forms" rows="5" cols="50"><?php echo esc_textarea(get_option("sdt_product_forms")); ?></textarea>

            <h3><?php _e("Modelo de Título", "sdt-domain"); ?></h3>
            <input type="text" name="sdt_template_title" id="sdt_template_title" value="<?php echo esc_attr(get_option("sdt_template_title")); ?>" size="100" />

            <h3><?php _e("Modelo de Conteúdo", "sdt-domain"); ?></h3>
            <?php
            wp_editor(
                get_option("sdt_template_content"),
                "sdt_template_content",
                [
                    "textarea_name" => "sdt_template_content",
                    "media_buttons" => false,
                    "textarea_rows" => 10,
                    "tinymce" => true,
                    "quicktags" => true,
                ]
            );
            ?>

            <h3><?php _e("Selecione o layout (modelo)", "sdt-domain"); ?></h3>
            <select name="sdt_layout_template" id="sdt_layout_template">
                <option value="padrao" <?php selected(get_option("sdt_layout_template"), "padrao"); ?>><?php _e("Padrão", "sdt-domain"); ?></option>
                <option value="clinica" <?php selected(get_option("sdt_layout_template"), "clinica"); ?>><?php _e("Clínica", "sdt-domain"); ?></option>
                <option value="industria" <?php selected(get_option("sdt_layout_template"), "industria"); ?>><?php _e("Indústria", "sdt-domain"); ?></option>
                <option value="escritorio" <?php selected(get_option("sdt_layout_template"), "escritorio"); ?>><?php _e("Escritório", "sdt-domain"); ?></option>
            </select>

            <br><br>
            <?php submit_button(__("Salvar Configurações e Gerar Páginas", "sdt-domain")); ?>
        </form>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const templateSelect = document.getElementById("sdt_layout_template");
        const titleField = document.getElementById("sdt_template_title");

        const templates = {
            padrao: {
                title: "<?php echo esc_js(__("Distribuidor de [produto] em [cidade] – [empresa]", "sdt-domain")); ?>",
                content: <?php echo json_encode(sdt_get_template_content_for_js("template-padrao.php")); ?>
            },
            clinica: {
                title: "<?php echo esc_js(__("Clínica especializada em [produto] em [cidade]", "sdt-domain")); ?>",
                content: <?php echo json_encode(sdt_get_template_content_for_js("template-clinica.php")); ?>
            },
            industria: {
                title: "<?php echo esc_js(__("Fornecedor industrial de [produto] em [cidade]", "sdt-domain")); ?>",
                content: <?php echo json_encode(sdt_get_template_content_for_js("template-industria.php")); ?>
            },
            escritorio: {
                title: "<?php echo esc_js(__("Distribuidor de [produto] para escritórios em [cidade]", "sdt-domain")); ?>",
                content: <?php echo json_encode(sdt_get_template_content_for_js("template-escritorio.php")); ?>
            }
        };

        if (templateSelect) {
            templateSelect.addEventListener("change", function () {
                const selectedValue = this.value;
                if (templates[selectedValue]) {
                    if(titleField) {
                        titleField.value = templates[selectedValue].title;
                    }
                    
                    if (typeof tinyMCE !== "undefined" && tinyMCE.get("sdt_template_content")) {
                        tinyMCE.get("sdt_template_content").setContent(templates[selectedValue].content);
                    } else {
                        const contentField = document.getElementById("sdt_template_content");
                        if (contentField) {
                            contentField.value = templates[selectedValue].content;
                        }
                    }
                }
            });
        }
    });
    </script>
    <?php
}

