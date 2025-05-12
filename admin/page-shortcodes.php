<?php
if (!defined('ABSPATH')) exit;

function sdt_render_shortcodes_page() {
    ?>
    <div class="wrap">
        <h1>Referência de Shortcodes</h1>
        <p>Use os shortcodes abaixo nos campos de conteúdo, título ou templates. Eles serão substituídos automaticamente durante a geração das páginas.</p>
        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th>Shortcode</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>[empresa]</code></td>
                    <td>Nome da empresa definida na configuração do plugin.</td>
                </tr>
                <tr>
                    <td><code>[produto]</code></td>
                    <td>Produto específico da página gerada.</td>
                </tr>
                <tr>
                    <td><code>[cidade]</code></td>
                    <td>Cidade específica da página gerada.</td>
                </tr>
                <tr>
                    <td><code>[formulario]</code></td>
                    <td>Formulário CF7 correspondente ao produto ou o padrão.</td>
                </tr>
                <tr>
                    <td><code>[whatsapp]</code></td>
                    <td>Número do WhatsApp da empresa formatado para link.</td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
