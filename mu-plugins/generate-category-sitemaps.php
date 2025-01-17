<?php
/**
 * Plugin Name: Generate Category Sitemaps
 * Description: Generates a separate sitemap for each category and a sitemap index.
 * Version: 1.0
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

// Fungsi untuk membangun sitemap berdasarkan kategori
function generate_category_sitemap() {
    // Ambil semua kategori
    $categories = get_categories(array(
        'orderby' => 'name',
        'order'   => 'ASC',
    ));

    // Mulai file sitemap index
    $sitemap_index = '<?xml version="1.0" encoding="UTF-8"?>';
    $sitemap_index .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    // Loop untuk setiap kategori dan buat sitemap
    foreach ($categories as $category) {
        $sitemap_file = 'sitemap-category-' . $category->term_id . '.xml';

        // Simpan URL sitemap untuk kategori ini ke sitemap index
        $sitemap_index .= '<sitemap>';
        $sitemap_index .= '<loc>' . home_url('/' . $sitemap_file) . '</loc>';
        $sitemap_index .= '<lastmod>' . date('Y-m-d') . '</lastmod>';
        $sitemap_index .= '</sitemap>';

        // Bangun file sitemap untuk kategori ini
        generate_category_specific_sitemap($category, $sitemap_file);
    }

    // Menutup sitemap index
    $sitemap_index .= '</sitemapindex>';

    // Menyimpan sitemap index ke file
    file_put_contents(ABSPATH . 'sitemap-index.xml', $sitemap_index);
}

// Fungsi untuk menghasilkan sitemap untuk kategori tertentu
function generate_category_specific_sitemap($category, $sitemap_file) {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1, // Ambil semua post
        'category' => $category->term_id,
        'post_status' => 'publish'
    );
    $posts = get_posts($args);

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

    // Menyimpan sitemap kategori ke file
    file_put_contents(ABSPATH . $sitemap_file, $sitemap);
}

// Menambahkan halaman sitemap ke WordPress
function add_category_sitemap_rewrite_rule() {
    add_rewrite_rule('^sitemap-category-([0-9]+)\.xml$', 'index.php?sitemap_category_id=$1', 'top');
}
add_action('init', 'add_category_sitemap_rewrite_rule');

function add_category_sitemap_query_var($vars) {
    $vars[] = 'sitemap_category_id';
    return $vars;
}
add_filter('query_vars', 'add_category_sitemap_query_var');


function handle_category_sitemap_request() {
    // Pastikan permintaan untuk sitemap-category
    $category_id = get_query_var('sitemap_category_id');
    if ($category_id) {
        // Cek apakah kategori valid
        $category = get_category($category_id);
        if ($category) {
            // Cek apakah file sitemap kategori ada
            $sitemap_file = ABSPATH . 'sitemap-category-' . $category_id . '.xml';
            if (file_exists($sitemap_file)) {
                header('Content-Type: application/xml; charset=utf-8');
                echo file_get_contents($sitemap_file);
                exit;
            } else {
                // Jika file sitemap kategori tidak ada, tampilkan error
                wp_die('Sitemap kategori tidak ditemukan!');
            }
        } else {
            // Jika kategori tidak ditemukan
            wp_die('Kategori tidak ditemukan!');
        }
    }
}
add_action('template_redirect', 'handle_category_sitemap_request');


// Menangani permintaan untuk file sitemap kategori
function handle_category_sitemap_request() {
    if (get_query_var('sitemap_category_id')) {
        $category_id = get_query_var('sitemap_category_id');
        $category = get_category($category_id);

        if ($category) {
            header('Content-Type: application/xml; charset=utf-8');
            echo file_get_contents(ABSPATH . 'sitemap-category-' . $category->term_id . '.xml');
            exit;
        }
    }
}
add_action('template_redirect', 'handle_category_sitemap_request');

// Menjalankan fungsi untuk menghasilkan sitemap setelah plugin diaktifkan
function generate_sitemap_on_activation() {
    generate_category_sitemap();
}
register_activation_hook(__FILE__, 'generate_sitemap_on_activation');
