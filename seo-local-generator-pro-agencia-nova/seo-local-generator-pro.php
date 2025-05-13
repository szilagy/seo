<?php
/*
Plugin Name: SEO Local Generator PRO – Agência Nova
Description: Gera páginas locais otimizadas para SEO automaticamente com base em produtos e cidades.
Version: 1.0
Author: Agência Nova
*/

if (!defined('ABSPATH')) exit;

define('SDT_PATH', plugin_dir_path(__FILE__));

// Includes
include_once SDT_PATH . 'admin/admin-menu.php';
include_once SDT_PATH . 'admin/admin-page.php';
include_once SDT_PATH . 'admin/admin-post.php';
include_once SDT_PATH . 'includes/page-generator.php';
include_once SDT_PATH . 'includes/helpers.php';
include_once SDT_PATH . 'includes/utils.php';
include_once SDT_PATH . 'includes/form-handler.php';
include_once SDT_PATH . 'includes/placeholder-parser.php';
