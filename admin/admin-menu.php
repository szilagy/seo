<?php
if (!defined('ABSPATH')) exit;

// Incluir arquivos das páginas do plugin
include_once plugin_dir_path(__FILE__) . 'admin-page.php';
include_once plugin_dir_path(__FILE__) . 'page-produtos.php';
include_once plugin_dir_path(__FILE__) . 'page-cidades.php';
include_once plugin_dir_path(__FILE__) . 'page-formularios.php';
include_once plugin_dir_path(__FILE__) . 'page-templates.php';
include_once plugin_dir_path(__FILE__) . 'page-shortcodes.php';
include_once plugin_dir_path(__FILE__) . 'page-empresa.php';
include_once plugin_dir_path(__FILE__) . 'page-whatsapp.php';
include_once plugin_dir_path(__FILE__) . 'page-relatorios.php';
include_once plugin_dir_path(__FILE__) . 'page-pixels.php';
include_once plugin_dir_path(__FILE__) . 'page-indexador.php';
include_once plugin_dir_path(__FILE__) . 'page-seo-avancado-settings.php'; // Mantido da v8 por enquanto

// Adicionando a primeira nova página da v9 para teste
include_once plugin_dir_path(__FILE__) . 'page-beneficios.php';
include_once plugin_dir_path(__FILE__) . 'page-faq.php';

include_once plugin_dir_path(__FILE__) . 'page-palavras-chave.php';

include_once plugin_dir_path(__FILE__) . '../includes/cf7-hook.php';

add_action('admin_menu', function () {
    add_menu_page(
        'SEO Local Generator',
        'SEO Local Generator',
        'manage_options',
        'sdt-main',
        'sdt_render_settings_page',
        'dashicons-location-alt',
        30
    );

    // Adicionando o submenu para Palavras-chave
    add_submenu_page(
        'sdt-main',
        'Palavras-chave',
        'Palavras-chave',
        'manage_options',
        'sdt-palavras-chave',
        'sdt_render_palavras_chave_page'
    );

    // Submenu original da v8 para SEO Avançado (será removido/substituído depois)
    add_submenu_page(
        'sdt-main',
        'Configurações SEO Avançado',
        'SEO Avançado',
        'manage_options',
        'sdt-seo-avancado-settings',
        'sdt_render_seo_avancado_settings_page'
    );

    add_submenu_page(
        'sdt-main',
        'Produtos',
        'Produtos',
        'manage_options',
        'sdt-produtos',
        'sdt_render_produtos_page'
    );

    add_submenu_page(
        'sdt-main',
        'Cidades',
        'Cidades',
        'manage_options',
        'sdt-cidades',
        'sdt_render_cidades_page'
    );

    add_submenu_page(
        'sdt-main',
        'Formulários por Produto',
        'Formulários por Produto',
        'manage_options',
        'sdt-formularios',
        'sdt_render_formularios_page'
    );

    add_submenu_page(
        'sdt-main',
        'Templates de Layout',
        'Templates de Layout',
        'manage_options',
        'sdt-templates',
        'sdt_render_templates_page'
    );

    add_submenu_page(
        'sdt-main',
        'Shortcodes Disponíveis',
        'Shortcodes Disponíveis',
        'manage_options',
        'sdt-shortcodes',
        'sdt_render_shortcodes_page'
    );

    add_submenu_page(
        'sdt-main',
        'Dados da Empresa',
        'Dados da Empresa',
        'manage_options',
        'sdt-empresa',
        'sdt_render_empresa_page'
    );

    add_submenu_page(
        'sdt-main',
        'Mensagem do WhatsApp',
        'Mensagem do WhatsApp',
        'manage_options',
        'sdt-whatsapp',
        'sdt_render_whatsapp_page'
    );

    add_submenu_page(
        'sdt-main',
        'Relatórios de Desempenho',
        'Relatórios de Desempenho',
        'manage_options',
        'sdt-relatorios',
        'sdt_render_relatorios_page'
    );

    add_submenu_page(
        'sdt-main',
        'Índice de Páginas SEO',
        'Índice de Páginas SEO',
        'manage_options',
        'sdt-indexador',
        'sdt_render_index_page'
    );

    add_submenu_page(
        'sdt-main',
        'Pixels de Conversão',
        'Pixels de Conversão',
        'manage_options',
        'sdt-pixels',
        'sdt_render_pixels_page'
    );

	add_submenu_page(
		'sdt-main',
		'Benefícios',
		'Benefícios',
		'manage_options',
		'sdt-beneficios',
		'sdt_render_beneficios_page'
	);

	add_submenu_page(
		'sdt-main',
		'Perguntas Frequentes',
		'FAQ',
		'manage_options',
		'sdt-faq',
		'sdt_render_faq_page'
	);

});
