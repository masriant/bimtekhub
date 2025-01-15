<?php
/**
 * Plugin Name: Disable Emojis
 * Description: A simple plugin to disable emojis in WordPress.
 * Version: 1.0
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

// Code to disable emojis
add_filter('emoji_svg_url', '__return_false');
add_filter('wp_resource_hints', function($urls, $relation_type) {
    if ('dns-prefetch' === $relation_type) {
        $urls = array_diff($urls, array('https://s.w.org/images/core/emoji/'));
    }
    return $urls;
}, 10, 2);

// Admin notice for plugin usage
function disable_emojis_admin_notice() {
    echo '<div class="notice notice-info is-dismissible">
        <p><strong>Disable Emojis Plugin Activated:</strong> This plugin disables emojis in WordPress. No additional settings are required.</p>
    </div>';
}
add_action('admin_notices', 'disable_emojis_admin_notice');
