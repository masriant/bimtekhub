<?php
/**
 * Plugin Name: Custom Robots.txt
 * Description: Generates custom robots.txt file for the site.
 * Version: 1.0
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

// Hook to 'robots_txt' filter to customize the robots.txt content
add_filter('robots_txt', 'custom_robots_txt', 10, 2);

function custom_robots_txt($output, $public) {
    $output .= "Sitemap: https://www.bimtekhub.com/sitemap.xml\n";
    $output .= "Sitemap: https://www.bimtekhub.com/sitemap.rss\n";
    $output .= "Sitemap: https://bimtekhub.com/sitemap_index.xml\n\n";

    $output .= "# Global settings for all user agents\n";
    $output .= "User-agent: *\n";
    $output .= "Disallow: /wp-admin/\n";
    $output .= "Disallow: /admin/\n";
    $output .= "Disallow: /login/\n";
    $output .= "Disallow: /register/\n";
    $output .= "Disallow: /private/\n";
    $output .= "Allow: /wp-admin/admin-ajax.php\n";
    $output .= "Allow: /wp-content/uploads/\n";
    $output .= "Allow: /public/\n\n";

    $output .= "# Specific settings for Googlebot\n";
    $output .= "User-agent: Googlebot\n";
    $output .= "Disallow: /private/\n";
    $output .= "Disallow: /nogooglebot/\n";
    $output .= "Allow: /\n\n";

    // Add specific settings for Twitterbot
    $output .= "# Specific settings for Twitterbot\n";
    $output .= "User-agent: Twitterbot\n";
    $output .= "Disallow: *\n";
    $output .= "Allow: /images\n";
    $output .= "Allow: /archives\n\n";

    // Block completely for BadBot
    $output .= "# Block completely for BadBot\n";
    $output .= "User-agent: BadBot\n";
    $output .= "Disallow: /\n\n";

    $output .= "# Delay for all crawlers to reduce server load\n";
    $output .= "User-agent: *\n";
    $output .= "Crawl-delay: 10\n\n";

    $output .= "# Block specific file types\n";
    $output .= "Disallow: /*.pdf$\n";
    $output .= "Disallow: /*.doc$\n";
    $output .= "Disallow: /*.docx$\n";
    $output .= "Disallow: /*.xls$\n";
    $output .= "Disallow: /*.xlsx$\n\n";

    $output .= "# Block access to search results pages\n";
    $output .= "Disallow: /search/\n";

    return $output;
}
?>
