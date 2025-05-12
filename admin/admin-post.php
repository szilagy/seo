<?php
if (!defined("ABSPATH")) exit;

add_action("admin_post_sdt_generate", "sdt_handle_generate_action");

function sdt_handle_generate_action() {
    // 1. Verificar nonce para segurança
    if (!isset($_POST["sdt_generate_nonce_field"]) || !wp_verify_nonce($_POST["sdt_generate_nonce_field"], "sdt_generate_action")) {
        wp_die(__("Falha na verificação de segurança (nonce).", "sdt-domain"));
    }

    // 2. Verificar permissões do usuário
    if (!current_user_can("manage_options")) {
        wp_die(__("Você não tem permissão para executar esta ação.", "sdt-domain"));
    }

    // 3. Sanitizar e salvar os dados do POST do formulário principal (sdt-main)
    if (isset($_POST["sdt_default_form"])) {
        update_option("sdt_default_form", sanitize_text_field($_POST["sdt_default_form"]));
    }
    
    if (isset($_POST["sdt_product_forms"])) {
        $product_forms_data = wp_unslash($_POST["sdt_product_forms"]);
        update_option("sdt_product_forms", $product_forms_data);
    }

    if (isset($_POST["sdt_template_title"])) {
        update_option("sdt_template_title", sanitize_text_field($_POST["sdt_template_title"]));
    }
    if (isset($_POST["sdt_template_content"])) {
        update_option("sdt_template_content", wp_kses_post($_POST["sdt_template_content"]));
    }
    if (isset($_POST["sdt_layout_template"])) {
        update_option("sdt_layout_template", sanitize_text_field($_POST["sdt_layout_template"]));
    }

    // Incluir e chamar a função de geração de posts
    include_once plugin_dir_path(__FILE__) . "../includes/page-generator.php";
    $result = sdt_generate_seo_posts(); 

    // Construir URL de redirecionamento com base no resultado
    $redirect_url = admin_url("admin.php?page=sdt-main");
    if (is_array($result) && isset($result["status"])) {
        switch ($result["status"]) {
            case "ok":
                if ($result["generated"] > 0) {
                    $redirect_url = add_query_arg(["sdt_notice" => "gerado_sucesso", "sdt_count" => $result["generated"]], $redirect_url);
                } elseif ($result["attempted"] > 0) {
                    $redirect_url = add_query_arg(["sdt_notice" => "gerado_nada_novo", "sdt_attempted" => $result["attempted"]], $redirect_url);
                } else {
                     $redirect_url = add_query_arg(["sdt_notice" => "gerado_sem_tentativas"], $redirect_url); 
                }
                break;
            case "empty_lists":
                $redirect_url = add_query_arg("sdt_notice", "listas_vazias", $redirect_url);
                break;
            default: // unknown ou outros erros
                $redirect_url = add_query_arg("sdt_notice", "gerado_falha_desconhecida", $redirect_url);
                break;
        }
    } else {
        $redirect_url = add_query_arg("sdt_notice", "gerado_erro_retorno_invalido", $redirect_url);
    }

    wp_safe_redirect($redirect_url);
    exit;
}

