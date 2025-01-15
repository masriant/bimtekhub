<?php
/**
 * Plugin Name: Canonical Tag
 * Description: Adds canonical link tag for SEO optimization.
 * Version: 1.0
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

function add_canonical_tag() {
    if (is_singular()) {
        $canonical_url = get_permalink();
        echo '<link rel="canonical" href="' . esc_url($canonical_url) . '" />' . "\n";
    }
}

add_action('wp_head', 'add_canonical_tag');
