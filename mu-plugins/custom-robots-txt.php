<?php
/**
 * Plugin Name: Custom Robots.txt
 * Description: Generates custom robots.txt file for the site.
 * Version: 1.0
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

function custom_robots_txt() {
    // Custom robots.txt content
    echo "User-agent: *\n";
    echo "Disallow: /wp-admin/\n";
    echo "Allow: /wp-admin/admin-ajax.php\n";
    echo "Disallow: /admin/\n";
    echo "Disallow: /login/\n";
    echo "Disallow: /register/\n";
    echo "Disallow: /private/\n";
    echo "Allow: /wp-content/uploads/\n";
    echo "Allow: /public/\n";

    echo "\nSitemap: https://www.bimtekhub.com/sitemap.xml\n";
    echo "Sitemap: https://www.bimtekhub.com/sitemap.rss\n";
    echo "Sitemap: https://bimtekhub.com/sitemap_index.xml\n";
}

add_action('do_robotstxt', 'custom_robots_txt');
