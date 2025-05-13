<?php
if (!defined('ABSPATH')) exit;

function sdt_render_faq_page() {
	if (isset($_POST['sdt_faq_nonce']) && wp_verify_nonce($_POST['sdt_faq_nonce'], 'sdt_save_faq')) {
		$perguntas = $_POST['faq_pergunta'] ?? [];
		$respostas = $_POST['faq_resposta'] ?? [];
		$faq_list = [];

		foreach ($perguntas as $i => $pergunta) {
			if (!empty(trim($pergunta)) && isset($respostas[$i])) {
				$faq_list[] = [
					'pergunta' => sanitize_text_field($pergunta),
					'resposta' => sanitize_textarea_field($respostas[$i]),
				];
			}
		}

		update_option('sdt_faq_list', $faq_list);
		echo '<div class="updated"><p>FAQ atualizado com sucesso!</p></div>';
	}

	$faqs = get_option('sdt_faq_list', []);

	echo '<div class="wrap"><h1>Perguntas Frequentes (FAQ)</h1>';
	echo '<form method="post">';
	wp_nonce_field('sdt_save_faq', 'sdt_faq_nonce');
	echo '<table class="widefat striped"><thead><tr><th>Pergunta</th><th>Resposta</th><th>Ação</th></tr></thead><tbody>';

	foreach ($faqs as $item) {
		echo '<tr>';
		echo '<td><input type="text" name="faq_pergunta[]" value="' . esc_attr($item['pergunta']) . '" class="widefat" /></td>';
		echo '<td><textarea name="faq_resposta[]" class="widefat">' . esc_textarea($item['resposta']) . '</textarea></td>';
		echo '<td><button type="button" class="button remove-row">Remover</button></td>';
		echo '</tr>';
	}

	echo '</tbody></table>';
	echo '<p><button type="button" class="button" id="add-faq">Adicionar FAQ</button></p>';
	submit_button('Salvar FAQs');
	echo '</form></div>';

	echo '<script>
document.getElementById("add-faq").addEventListener("click", function() {
	var table = document.querySelector("table tbody");
	var row = document.createElement("tr");
	row.innerHTML = \'<td><input type="text" name="faq_pergunta[]" value="" class="widefat" /></td>\' +
					 \'<td><textarea name="faq_resposta[]" class="widefat"></textarea></td>\' +
					 \'<td><button type="button" class="button remove-row">Remover</button></td>\';
	table.appendChild(row);
});
document.addEventListener("click", function(e) {
	if (e.target && e.target.classList.contains("remove-row")) {
		e.target.closest("tr").remove();
	}
});
</script>';
}
?>
