<?php
if (!defined('ABSPATH')) exit;

/**
 * Exemplo de função para logar submissões de formulário se necessário.
 */
function sdt_log_form_submission($produto, $cidade) {
    $log = get_option('sdt_form_submissions_log', []);
    $log[] = [
        'produto' => $produto,
        'cidade' => $cidade,
        'data' => current_time('mysql')
    ];
    update_option('sdt_form_submissions_log', $log);
}
