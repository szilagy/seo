<?php
if (!defined('ABSPATH')) exit;

function sdt_slugify($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9-]+/', '-', $text);
    return trim($text, '-');
}

function sdt_format_whatsapp($number) {
    return preg_replace('/[^0-9]/', '', $number);
}

function sdt_log_event($message) {
    $log = get_option('sdt_log_events', []);
    $log[] = "[" . current_time('mysql') . "] " . $message;
    update_option('sdt_log_events', array_slice($log, -100));
}

function sdt_replace_placeholders($template, $replacements) {
    return str_replace(array_keys($replacements), array_values($replacements), $template);
}

function sdt_minify_html($content) {
    $content = preg_replace('/\s+/', ' ', $content);
    $content = preg_replace('/<!--.*?-->/', '', $content);
    return trim($content);
}
