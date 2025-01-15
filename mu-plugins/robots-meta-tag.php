<?php
/**
 * Plugin Name: Robots Meta Tag
 * Description: Adds robots meta tag for controlling index/follow behavior.
 * Version: 1.0
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

function add_robots_meta_tag() {
    if (is_single() || is_page()) {
        echo '<meta name="robots" content="index, follow">' . "\n";
    } else {
        echo '<meta name="robots" content="noindex, nofollow">' . "\n";
    }
}

add_action('wp_head', 'add_robots_meta_tag');
