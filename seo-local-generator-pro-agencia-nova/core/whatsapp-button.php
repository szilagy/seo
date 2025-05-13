
<?php
if (!defined('ABSPATH')) exit; // Impede o acesso direto ao arquivo

// Função para gerar o botão do WhatsApp dinâmico
function sdt_generate_whatsapp_button($product, $city) {
    // Recupera o número do WhatsApp configurado nas opções do plugin
    $whatsapp_number = get_option('sdt_whatsapp_number'); // Recupera o número de WhatsApp configurado

    if (!$whatsapp_number) {
        return; // Se o número não estiver configurado, não exibe o botão
    }

    // Mensagem dinâmica com base no produto e cidade
    $message = 'Olá! Gostaria de saber mais sobre ' . $product . ' em ' . $city . '.';

    // URL do WhatsApp com a mensagem
    $whatsapp_url = 'https://wa.me/' . $whatsapp_number . '?text=' . urlencode($message);

    // HTML do botão do WhatsApp
    $button_html = '<a href="' . esc_url($whatsapp_url) . '" target="_blank" class="whatsapp-button">'
                . '<img src="' . plugins_url('assets/img/whatsapp-icon.png', __FILE__) . '" alt="WhatsApp" />'
                . 'Fale Conosco no WhatsApp</a>';

    // Adiciona o botão à página
    echo $button_html;
}

// Adiciona o botão no conteúdo da página gerada, por exemplo, após o conteúdo
add_action('the_content', function($content) {
    if (is_singular('page')) {
        // Obtém as variáveis de produto e cidade (substitua com dados reais)
        $product = get_post_meta(get_the_ID(), '_product', true);
        $city = get_post_meta(get_the_ID(), '_city', true);

        // Gera o botão do WhatsApp
        ob_start();
        sdt_generate_whatsapp_button($product, $city);
        $whatsapp_button = ob_get_clean();

        // Adiciona o botão após o conteúdo da página
        $content .= $whatsapp_button;
    }
    return $content;
});
