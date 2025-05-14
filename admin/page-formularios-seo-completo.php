<?php
if (!defined('ABSPATH')) exit;

function sdt_render_formularios_seo_completo() {
	$layout_atual = get_option('sdt_seo_avancado_template', [])['sdt_seo_template_layout'] ?? '';
	$settings = get_option('sdt_seo_avancado_template', []);

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
				'sdt_seo_template_layout' => sanitize_text_field($_POST['sdt_seo_template_layout'] ?? ''),
				'sdt_default_form' => sanitize_text_field($_POST['sdt_default_form'] ?? ''),
				'sdt_manual_form_map' => sanitize_textarea_field($_POST['sdt_manual_form_map'] ?? ''),
				'sdt_template_title' => sanitize_text_field($_POST['sdt_template_title'] ?? ''),
				'sdt_template_content' => wp_kses_post($_POST['sdt_template_content'] ?? ''),
				'sdt_main_keyword' => sanitize_text_field($_POST['sdt_main_keyword'] ?? ''),
				'sdt_audience' => sanitize_text_field($_POST['sdt_audience'] ?? ''),
				'sdt_article_title' => sanitize_text_field($_POST['sdt_article_title'] ?? ''),
				'sdt_intro' => sanitize_textarea_field($_POST['sdt_intro'] ?? ''),
				'sdt_what_is' => sanitize_textarea_field($_POST['sdt_what_is'] ?? ''),
				'sdt_main_benefits' => sanitize_textarea_field($_POST['sdt_main_benefits'] ?? '')
			]);

			echo '<div class="notice notice-success is-dismissible"><p>Configurações salvas com sucesso.</p></div>';
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
		<h1>Formulários + Configurações SEO</h1>
		<form method="post">
			<?php wp_nonce_field("sdt_save_forms_action", "sdt_save_forms_nonce"); ?>

			<h2>Formulário padrão (CF7 shortcode)</h2>
			<input type="text" name="sdt_default_form" class="regular-text" value="<?php echo esc_attr($settings['sdt_default_form'] ?? ''); ?>">

			<h2>Formulários por Produto (1 por linha: produto|shortcode)</h2>
			<textarea name="sdt_manual_form_map" rows="5" class="large-text"><?php echo esc_textarea($settings['sdt_manual_form_map'] ?? ''); ?></textarea>

			<h2>Modelo de Título</h2>
			<input type="text" name="sdt_template_title" class="large-text" value="<?php echo esc_attr($settings['sdt_template_title'] ?? ''); ?>">

			<h2>Modelo de Conteúdo</h2>
			<?php
			wp_editor(
				$settings['sdt_template_content'] ?? '',
				'sdt_template_content',
				[
					'textarea_name' => 'sdt_template_content',
					'textarea_rows' => 8,
					'media_buttons' => false,
					'teeny' => true,
					'quicktags' => true
				]
			);
			?>

			<h2>Campos para Geração de Conteúdo</h2>
			<table class="form-table">
				<tr>
					<th><label for="sdt_main_keyword">Palavra-chave principal</label></th>
					<td><input type="text" name="sdt_main_keyword" class="regular-text" value="<?php echo esc_attr($settings['sdt_main_keyword'] ?? ''); ?>"></td>
				</tr>
				<tr>
					<th><label for="sdt_audience">Público-alvo</label></th>
					<td><input type="text" name="sdt_audience" class="regular-text" value="<?php echo esc_attr($settings['sdt_audience'] ?? ''); ?>"></td>
				</tr>
				<tr>
					<th><label for="sdt_article_title">Título do artigo</label></th>
					<td><input type="text" name="sdt_article_title" class="regular-text" value="<?php echo esc_attr($settings['sdt_article_title'] ?? ''); ?>"></td>
				</tr>
				<tr>
					<th><label for="sdt_intro">Introdução</label></th>
					<td><textarea name="sdt_intro" rows="3" class="large-text"><?php echo esc_textarea($settings['sdt_intro'] ?? ''); ?></textarea></td>
				</tr>
				<tr>
					<th><label for="sdt_what_is">O que é (explicação)</label></th>
					<td><textarea name="sdt_what_is" rows="3" class="large-text"><?php echo esc_textarea($settings['sdt_what_is'] ?? ''); ?></textarea></td>
				</tr>
				<tr>
					<th><label for="sdt_main_benefits">Benefícios principais</label></th>
					<td><textarea name="sdt_main_benefits" rows="4" class="large-text"><?php echo esc_textarea($settings['sdt_main_benefits'] ?? ''); ?></textarea></td>
				</tr>
			</table>
			<h2>Formulários por Produto</h2>
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

			<?php submit_button('Salvar Tudo'); ?>
		</form>
	</div>
<?php } ?>
