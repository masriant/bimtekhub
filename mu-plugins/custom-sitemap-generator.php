<?php
/**
 * Plugin Name: Custom Sitemap Generator
 * Description: Generates a sitemap.xml for the WordPress site.
 * Version: 1.0
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

// Ensure WordPress is ready before running the plugin
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Hook to handle requests for sitemap.xml
function generate_custom_sitemap() {
    if (is_sitemap_request()) {
        header('Content-Type: application/xml; charset=utf-8');
        echo build_sitemap();
        exit;
    }
}

// Check if the request is for sitemap.xml
function is_sitemap_request() {
    return (strpos($_SERVER['REQUEST_URI'], 'sitemap.xml') !== false);
}

// Build the XML sitemap content
function build_sitemap() {
    // Query to get all published posts and pages
    $posts = get_posts(array(
        'post_type' => array('post', 'page'),
        'post_status' => 'publish',
        'numberposts' => -1
    ));

    // Start XML format for sitemap
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    foreach ($posts as $post) {
        setup_postdata($post);
        $sitemap .= '<url>';
        $sitemap .= '<loc>' . get_permalink($post) . '</loc>';
        $sitemap .= '<lastmod>' . get_the_modified_date('Y-m-d', $post) . '</lastmod>';
        $sitemap .= '<changefreq>weekly</changefreq>';
        $sitemap .= '<priority>0.8</priority>';
        $sitemap .= '</url>';
    }

    wp_reset_postdata();

    $sitemap .= '</urlset>';

    return $sitemap;
}

// Add rewrite rule to handle sitemap.xml
function add_sitemap_rewrite_rule() {
    add_rewrite_rule('sitemap\.xml$', 'index.php?sitemap=1', 'top');
}

// Hook to 'init' to add the rewrite rule
add_action('init', 'add_sitemap_rewrite_rule');

// Hook to generate the sitemap XML
add_action('template_redirect', 'generate_custom_sitemap');
