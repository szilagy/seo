<?php
if (!defined('ABSPATH')) exit;

function sdt_render_empresa_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sdt_save_empresa'])) {
        update_option('sdt_company_name', sanitize_text_field($_POST['sdt_company_name']));
        update_option('sdt_company_whatsapp', sanitize_text_field($_POST['sdt_company_whatsapp']));
        update_option('sdt_company_email', sanitize_email($_POST['sdt_company_email']));
        update_option('sdt_company_url', esc_url_raw($_POST['sdt_company_url']));
        update_option('sdt_company_city', sanitize_text_field($_POST['sdt_company_city']));
        update_option('sdt_company_state', sanitize_text_field($_POST['sdt_company_state']));
        update_option('sdt_company_country', sanitize_text_field($_POST['sdt_company_country']));
        update_option('sdt_company_description', sanitize_text_field($_POST['sdt_company_description']));
        echo '<div class="updated"><p>Dados da empresa salvos com sucesso.</p></div>';
    }

    $name = get_option('sdt_company_name', '');
    $whatsapp = get_option('sdt_company_whatsapp', '');
    $email = get_option('sdt_company_email', '');
    $url = get_option('sdt_company_url', '');
    $city = get_option('sdt_company_city', '');
    $state = get_option('sdt_company_state', 'BR');
    $country = get_option('sdt_company_country', 'BR');
    $description = get_option('sdt_company_description', '');
    ?>
    <div class="wrap">
        <h1>Dados da Empresa</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th><label for="sdt_company_name">Nome da empresa</label></th>
                    <td><input type="text" name="sdt_company_name" id="sdt_company_name" value="<?php echo esc_attr($name); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="sdt_company_whatsapp">WhatsApp (somente números, com DDD)</label></th>
                    <td><input type="text" name="sdt_company_whatsapp" id="sdt_company_whatsapp" value="<?php echo esc_attr($whatsapp); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="sdt_company_email">E-mail</label></th>
                    <td><input type="email" name="sdt_company_email" id="sdt_company_email" value="<?php echo esc_attr($email); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="sdt_company_url">Site</label></th>
                    <td><input type="url" name="sdt_company_url" id="sdt_company_url" value="<?php echo esc_url($url); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="sdt_company_city">Cidade</label></th>
                    <td><input type="text" name="sdt_company_city" id="sdt_company_city" value="<?php echo esc_attr($city); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="sdt_company_state">Estado (sigla, ex: SP)</label></th>
                    <td><input type="text" name="sdt_company_state" id="sdt_company_state" value="<?php echo esc_attr($state); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="sdt_company_country">País</label></th>
                    <td><input type="text" name="sdt_company_country" id="sdt_company_country" value="<?php echo esc_attr($country); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="sdt_company_description">Descrição curta</label></th>
                    <td><input type="text" name="sdt_company_description" id="sdt_company_description" value="<?php echo esc_attr($description); ?>" class="regular-text"></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="sdt_save_empresa" class="button button-primary" value="Salvar Dados">
            </p>
        </form>
    </div>
    <?php
}
