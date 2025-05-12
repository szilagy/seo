<?php
if (!defined('ABSPATH')) exit;

function sdt_render_whatsapp_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sdt_save_whatsapp_message'])) {
        update_option('sdt_whatsapp_message', sanitize_text_field($_POST['sdt_whatsapp_message']));
        echo '<div class="updated"><p>Mensagem do WhatsApp salva com sucesso.</p></div>';
    }

    $mensagem = get_option('sdt_whatsapp_message', 'Olá, tenho interesse em [produto] em [cidade]');

    ?>
    <div class="wrap">
        <h1>Mensagem do WhatsApp</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th><label for="sdt_whatsapp_message">Mensagem padrão</label></th>
                    <td>
                        <input type="text" name="sdt_whatsapp_message" id="sdt_whatsapp_message" value="<?php echo esc_attr($mensagem); ?>" class="regular-text" style="width: 80%;" />
                        <p class="description">Use os shortcodes: [produto], [cidade], [empresa]</p>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="sdt_save_whatsapp_message" class="button button-primary" value="Salvar Mensagem">
            </p>
        </form>
    </div>
    <?php
}
