<?php
/**
 * Plugin Name: Custom Sitemap Generator
 * Description: Membuat sitemap.xml secara otomatis untuk situs WordPress.
 * Version: 1.0
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

// Pastikan WordPress sudah siap sebelum menjalankan plugin
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Keluar jika dipanggil secara langsung
}

// Menambahkan rewrite rule untuk menangani sitemap.xml
function add_sitemap_rewrite_rule() {
    // Menambahkan aturan khusus untuk sitemap.xml
    add_rewrite_rule('^sitemap\.xml$', 'index.php?sitemap=1', 'top');
}
add_action('init', 'add_sitemap_rewrite_rule');

// Menghasilkan konten sitemap XML
function generate_custom_sitemap() {
    // Jika permintaan adalah untuk sitemap.xml
    if (isset($_GET['sitemap'])) {
        header('Content-Type: application/xml; charset=utf-8');
        echo build_sitemap(); // Memanggil fungsi yang menghasilkan sitemap
        exit;
    }
}

// Fungsi untuk membangun konten sitemap
function build_sitemap() {
    // Query untuk mendapatkan semua post dan halaman yang dipublikasikan
    $posts = get_posts(array(
        'post_type' => array('post', 'page'),
        'post_status' => 'publish',
        'numberposts' => 500, // Batasi jumlah post agar sitemap tidak terlalu besar
    ));

    // Mulai format XML untuk sitemap
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

// Memanggil fungsi ini pada 'template_redirect' untuk menangani sitemap.xml
add_action('template_redirect', 'generate_custom_sitemap');

// Flush rewrite rules untuk memastikan aturan baru diterapkan
function custom_flush_rewrite_rules() {
    flush_rewrite_rules();
}
add_action('init', 'custom_flush_rewrite_rules');

// Menonaktifkan sitemap default WordPress (wp-sitemap.xml)
add_filter('wp_sitemap_enabled', '__return_false');

// Menghindari halaman paginasi dengan hanya satu sitemap
function no_sitemap_pagination() {
    global $wp_query;
    if (isset($wp_query->query_vars['sitemap']) && $wp_query->query_vars['sitemap'] == 1) {
        $wp_query->set('paged', 1); // Pastikan hanya halaman pertama yang diproses
    }
}
add_action('parse_request', 'no_sitemap_pagination');
