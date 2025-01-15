<?php
/**
 * Plugin Name: Fix HTML Issues
 * Description: Fixes trailing slashes on void elements and unquoted attribute values.
 * Version: 1.0
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

function fix_html_output($content) {
    // Remove trailing slashes from void elements
    $content = preg_replace('/<(\w+)([^>]*?)\/>/', '<$1$2>', $content);
    
    // Ensure all attributes are quoted
    $content = preg_replace('/(\w+)=([^\s>]+)/', '$1="$2"', $content);

    return $content;
}

add_filter('the_content', 'fix_html_output');
add_filter('wp_head', 'fix_html_output');

// Function to deregister unused CSS and JS
function deregister_unused_assets() {
    // Example: Deregister specific styles and scripts
    wp_dequeue_style('unnecessary-style-handle');
    wp_dequeue_script('unnecessary-script-handle');
}

add_action('wp_enqueue_scripts', 'deregister_unused_assets', 100);
