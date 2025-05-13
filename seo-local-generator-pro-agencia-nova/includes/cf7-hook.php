<?php
if (!defined('ABSPATH')) exit;

add_action('wpcf7_before_send_mail', function($cf7) {
    $title = $cf7->title();
    $log = get_option('sdt_form_submissions_log', []);
    $log[] = ['form' => $title, 'time' => current_time('mysql')];
    update_option('sdt_form_submissions_log', array_slice($log, -200));
});
