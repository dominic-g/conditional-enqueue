<?php

require_once ABSPATH . 'file-name.php';
add_action('wp_print_scripts', 'conditional_deregister_scripts', 100);

function conditional_deregister_scripts() {
    if (is_page('YOUR PAGE NAME')) {
        wp_deregister_script('script-handle');
    }
}


add_action('wp_print_styles', 'conditional_dequeue_styles', 100);

function conditional_dequeue_styles() {
    if (is_page('YOUR PAGE NAME')) {
        wp_dequeue_style('style-handle');
    }
}