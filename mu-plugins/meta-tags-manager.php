<?php
/**
 * Plugin Name: Meta Tags Manager
 * Description: Manages meta descriptions, keywords, Open Graph, and Twitter Card tags for bimtekhub.com.
 * Version: 1.0
 * Author: Your Name
 * Author URI: https://bimtekhub.com
 */

// Hook to add meta tags in the head section
add_action('wp_head', 'add_meta_tags');

function add_meta_tags() {
    if (is_single() || is_page() || is_front_page()) {

        global $post;

        // Meta Description for homepage
        if (is_front_page()) {
            $meta_description = get_option('home_meta_description'); // Assuming you have a way to set this
        } else {

        $meta_description = get_post_meta($post->ID, 'meta_description', true);
        if ($meta_description) {
            echo '<meta name="description" content="' . esc_attr($meta_description) . '">' . "\n";
        }

        // Keywords for homepage
        if (is_front_page()) {
            $keywords = get_option('home_keywords'); // Assuming you have a way to set this
        } else {

        $keywords = get_post_meta($post->ID, 'keywords', true);
        if ($keywords) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
        }

        // Open Graph Tags
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($meta_description) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
        echo '<meta property="og:type" content="article">' . "\n";
        echo '<meta property="og:image" content="' . esc_url(get_the_post_thumbnail_url($post->ID)) . '">' . "\n";

        // Twitter Card Tags
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($meta_description) . '">' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url(get_the_post_thumbnail_url($post->ID)) . '">' . "\n";
    }
}
