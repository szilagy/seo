<?php
add_action('wp_enqueue_scripts', function () {
    if (is_single()) {
        wp_enqueue_script('sdt-view-counter', plugin_dir_url(__FILE__) . '../assets/ajax-view-counter.js', [], null, true);
        wp_localize_script('sdt-view-counter', 'sdt_ajax_view_counter', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'post_id' => get_the_ID()
        ]);
    }
});

add_action('wp_ajax_sdt_count_view', 'sdt_register_view');
add_action('wp_ajax_nopriv_sdt_count_view', 'sdt_register_view');
function sdt_register_view() {
    if (!isset($_POST['post_id'])) exit;
    $post_id = intval($_POST['post_id']);
    $views = get_post_meta($post_id, '_sdt_views', true);
    update_post_meta($post_id, '_sdt_views', intval($views) + 1);
    wp_die();
}
