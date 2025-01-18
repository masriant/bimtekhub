<?php
/**
 * Plugin Name: RSS Sitemap Generator
 * Description: Generates an RSS feed sitemap for BimtekHub.
 * Version: 1.0
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

function output_rss_sitemap() {
    if (isset($_GET['rss_sitemap'])) {
        header('Content-Type: application/rss+xml; charset=utf-8');

        // Start the RSS feed
        echo '<?xml version="1.0" encoding="UTF-8" ?>';
        echo '<rss version="2.0">';
        echo '<channel>';
        echo '<title>Sitemap for BimtekHub</title>';
        echo '<link>https://bimtekhub.com</link>';
        echo '<description>This is the sitemap for BimtekHub.</description>';

        // Query to get all published posts and pages
        $posts = get_posts(array(
            'post_type' => array('post', 'page'),
            'post_status' => 'publish',
            'numberposts' => -1
        ));

        foreach ($posts as $post) {
            setup_postdata($post);
            echo '<item>';
            echo '<title>' . get_the_title($post) . '</title>';
            echo '<link>' . get_permalink($post) . '</link>';
            echo '<description>' . get_the_excerpt($post) . '</description>';
            echo '<pubDate>' . get_the_date('D, d M Y H:i:s O', $post) . '</pubDate>';
            echo '</item>';
        }

        wp_reset_postdata();

        echo '</channel>';
        echo '</rss>';
        exit;
    }
}

// Hook to 'init' to add a query variable for RSS sitemap
function add_rss_sitemap_query_var() {
    add_rewrite_rule('rss-sitemap/?$', 'index.php?rss_sitemap=1', 'top');
    add_filter('query_vars', function($vars) {
        $vars[] = 'rss_sitemap';
        return $vars;
    });
}
add_action('init', 'add_rss_sitemap_query_var');

// Hook to generate the sitemap when the query var is present
add_action('template_redirect', 'output_rss_sitemap');
