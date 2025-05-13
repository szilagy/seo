<?php
if (!defined('ABSPATH')) exit;

function sdt_render_pixels_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sdt_save_pixels'])) {
        update_option('sdt_pixel_meta', sanitize_text_field($_POST['sdt_pixel_meta']));
        update_option('sdt_pixel_google', sanitize_text_field($_POST['sdt_pixel_google']));
        echo '<div class="updated"><p>Pixels salvos com sucesso.</p></div>';
    }

    $meta_pixel = get_option('sdt_pixel_meta', '');
    $google_pixel = get_option('sdt_pixel_google', '');
    ?>
    <div class="wrap">
        <h1>Integração com Pixels</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th><label for="sdt_pixel_meta">Meta Pixel ID</label></th>
                    <td><input type="text" name="sdt_pixel_meta" id="sdt_pixel_meta" value="<?php echo esc_attr($meta_pixel); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="sdt_pixel_google">Google Ads Tag ID</label></th>
                    <td><input type="text" name="sdt_pixel_google" id="sdt_pixel_google" value="<?php echo esc_attr($google_pixel); ?>" class="regular-text" /></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="sdt_save_pixels" class="button button-primary" value="Salvar Pixels">
            </p>
        </form>
    </div>
    <?php
}