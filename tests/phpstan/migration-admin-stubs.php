<?php

function current_user_can($capability) {
    return true;
}

/** @return never */
function wp_die($message) {
    throw new RuntimeException((string) $message);
}

function add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback) {
    return '';
}

function check_admin_referer($action, $field) {
    return 1;
}

function sanitize_key($value) {
    return '';
}

function esc_html__($text, $domain = 'default') {
    return '';
}

function esc_textarea($value) {
    return '';
}

function checked($checked, $current = true, $echo = true) {
    return '';
}

function wp_nonce_field($action, $name) {
}

function submit_button($text) {
}
