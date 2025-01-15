<?php
/**
 * Plugin Name: Alt and Title Attributes
 * Description: Adds alt and title attributes to images in WordPress.
 * Version: 1.0
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

function add_image_alt_and_title($attr, $attachment) {
    // Check if alt attribute is empty
    if (!isset($attr['alt']) || empty($attr['alt'])) {
        $attr['alt'] = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
        
        // If alt is still empty, use the image title
        if (empty($attr['alt'])) {
            $attr['alt'] = 'bimtekhub-' . $attachment->ID; // Set alt to "bimtekhub-*"
        }
    }

    // Check if title attribute is empty
    if (!isset($attr['title']) || empty($attr['title'])) {
        $attr['title'] = 'bimtekhub-' . $attachment->ID; // Set title to "bimtekhub-*"
    }

    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'add_image_alt_and_title', 10, 2);

// Admin notice for plugin usage
function alt_title_attributes_admin_notice() {
    echo '<div class="notice notice-info is-dismissible">
        <p><strong>Alt and Title Attributes Plugin Activated:</strong> This plugin adds alt and title attributes to images in WordPress.</p>
    </div>';
}
add_action('admin_notices', 'alt_title_attributes_admin_notice');
