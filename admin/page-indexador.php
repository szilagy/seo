<?php
if (!defined('ABSPATH')) exit;

function sdt_render_index_page() {
    $posts = get_posts([
        'post_type' => 'post',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ]);

    echo "<div class='wrap'><h1>Índice de Páginas Geradas</h1><ul>";
    foreach ($posts as $post) {
        echo "<li><a href='" . get_permalink($post) . "' target='_blank'>" . esc_html($post->post_title) . "</a></li>";
    }
    echo "</ul></div>";
}
