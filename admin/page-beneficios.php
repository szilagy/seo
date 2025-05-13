<?php
if (!defined('ABSPATH')) exit;

function sdt_render_beneficios_page() {
	if (isset($_POST['sdt_beneficios_nonce']) && wp_verify_nonce($_POST['sdt_beneficios_nonce'], 'sdt_save_beneficios')) {
		$lista = array_filter(array_map('sanitize_text_field', $_POST['sdt_beneficio'] ?? []));
		update_option('sdt_beneficios_list', $lista);
		echo '<div class="updated"><p>Lista de benefícios atualizada com sucesso!</p></div>';
	}

	$beneficios = get_option('sdt_beneficios_list', []);

	echo '<div class="wrap"><h1>Gerenciar Benefícios</h1>';
	echo '<form method="post">';
	wp_nonce_field('sdt_save_beneficios', 'sdt_beneficios_nonce');
	echo '<table class="widefat striped"><thead><tr><th>Benefício</th><th>Ação</th></tr></thead><tbody>';

	foreach ($beneficios as $index => $item) {
		echo '<tr>';
		echo '<td><input type="text" name="sdt_beneficio[]" value="' . esc_attr($item) . '" class="widefat" /></td>';
		echo '<td><button type="button" class="button remove-row">Remover</button></td>';
		echo '</tr>';
	}

	echo '</tbody></table>';
	echo '<p><button type="button" class="button" id="add-beneficio">Adicionar Benefício</button></p>';
	submit_button('Salvar Benefícios');
	echo '</form></div>';

	echo '<script>
document.getElementById("add-beneficio").addEventListener("click", function() {
	var table = document.querySelector("table tbody");
	var row = document.createElement("tr");
	row.innerHTML = \'<td><input type="text" name="sdt_beneficio[]" value="" class="widefat" /></td><td><button type="button" class="button remove-row">Remover</button></td>\';
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
