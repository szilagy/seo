<?php
if (!defined('ABSPATH')) exit;

function sdt_parse_placeholders($template, $vars) {
    foreach ($vars as $key => $value) {
        $template = str_replace("[$key]", $value, $template);
    }
    return $template;
}
