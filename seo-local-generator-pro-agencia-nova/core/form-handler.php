<?php
if (!defined('ABSPATH')) exit;

// Este arquivo é um espaço reservado para lógica adicional de tratamento de formulários.
// Como o plugin já utiliza os shortcodes do Contact Form 7, essa estrutura pode servir para:
// - Integração futura com APIs externas
// - Registro de submissões em log local
// - Redirecionamentos customizados pós-envio
// - Validações extras, etc.

function sdt_log_form_submission($produto, $cidade) {
    $log = get_option('sdt_form_submissions_log', []);
    $log[] = [
        'produto' => $produto,
        'cidade' => $cidade,
        'data' => current_time('mysql')
    ];
    update_option('sdt_form_submissions_log', $log);
}
