<?php
if (!defined('ABSPATH')) exit;

function sdt_render_palavras_chave_page() {
	if (isset($_POST['sdt_palavras_chave_nonce']) && wp_verify_nonce($_POST['sdt_palavras_chave_nonce'], 'sdt_save_palavras_chave')) {

		// Adicionar nova palavra
		if (isset($_POST['sdt_add_palavra_chave']) && !empty(trim($_POST['nova_palavra_chave']))) {
			$nova_palavra = sanitize_text_field(trim($_POST['nova_palavra_chave']));
			$palavras_chave = get_option('sdt_palavras_chave_list', []);
			if (!in_array($nova_palavra, $palavras_chave)) {
				$palavras_chave[] = $nova_palavra;
				update_option('sdt_palavras_chave_list', $palavras_chave);
				echo '<div class="updated"><p>Palavra-chave adicionada com sucesso!</p></div>';
			} else {
				echo '<div class="error"><p>Erro: Palavra-chave já existe.</p></div>';
			}
		}

		// Remover palavra
		elseif (isset($_POST['sdt_delete_palavra_chave']) && isset($_POST['palavra_chave_to_delete'])) {
			$palavra_a_deletar = sanitize_text_field(stripslashes($_POST['palavra_chave_to_delete']));
			$palavras_chave = get_option('sdt_palavras_chave_list', []);
			$index = array_search($palavra_a_deletar, $palavras_chave);
			if ($index !== false) {
				unset($palavras_chave[$index]);
				update_option('sdt_palavras_chave_list', array_values($palavras_chave));
			}
		}

		// Salvar palavras ativas
		elseif (isset($_POST['sdt_salvar_ativas'])) {
			$ativas = isset($_POST['palavras_ativas']) ? array_map('sanitize_text_field', $_POST['palavras_ativas']) : [];
			update_option('sdt_palavras_chave_ativas', $ativas);
			echo '<div class="updated"><p>Palavras-chave ativas atualizadas!</p></div>';
		}
	}

	$palavras_chave = get_option('sdt_palavras_chave_list', []);
	$ativas = get_option('sdt_palavras_chave_ativas', []);

	echo '<div class="wrap">';
	echo '<h1>Gerenciar Palavras-chave</h1>';
	echo '<form method="post">';
	wp_nonce_field('sdt_save_palavras_chave', 'sdt_palavras_chave_nonce');
	echo '<table class="widefat fixed striped">';
	echo '<thead><tr><th>Palavra-chave</th><th>Usar</th><th>Ação</th></tr></thead><tbody>';

	foreach ($palavras_chave as $palavra) {
		$checked = in_array($palavra, $ativas) ? 'checked' : '';
		echo '<tr>';
		echo '<td>' . esc_html($palavra) . '</td>';
		echo '<td><input type="checkbox" name="palavras_ativas[]" value="' . esc_attr($palavra) . '" ' . $checked . '></td>';
		echo '<td>';
		echo '<button class="button" type="submit" name="sdt_delete_palavra_chave" value="1" onclick="return confirm(\'Tem certeza?\');">Remover</button>';
		echo '<input type="hidden" name="palavra_chave_to_delete" value="' . esc_attr($palavra) . '">';
		echo '</td>';
		echo '</tr>';
	}

	echo '</tbody></table>';
	echo '<p><input type="submit" class="button button-primary" name="sdt_salvar_ativas" value="Salvar Selecionadas"></p>';
	echo '</form>';

	echo '<hr><h2>Adicionar Nova Palavra-chave</h2>';
	echo '<form method="post">';
	wp_nonce_field('sdt_save_palavras_chave', 'sdt_palavras_chave_nonce');
	echo '<input type="text" name="nova_palavra_chave" value="" required>';
	echo '<input type="submit" class="button" name="sdt_add_palavra_chave" value="Adicionar">';
	echo '</form>';
	echo '</div>';
}
?>
