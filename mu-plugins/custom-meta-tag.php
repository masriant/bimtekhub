<?php
/**
 * Plugin Name: Custom Meta Tag for Open Graph and Twitter Card
 * Description: Menambahkan meta tag untuk Open Graph dan Twitter Card
 * Version: 1.0
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

  // Google Site Verification Meta Tag
function add_google_facebook_verification() {
    echo '<meta name="google-site-verification" content="av2rpSIYvX4gfgjThO7LgHUib4d-JfZTgwMo_3w_BME" />' . "\n";
    echo '<meta name="facebook-domain-verification" content="y2er2fbvtu3to2s1h4l5gxex84rak5" />' . "\n";
}
add_action('wp_head', 'add_google_facebook_verification');

// Add Open Graph, Twitter Card, Meta Description, Meta Keywords, and Canonical Tag
function add_open_graph_and_twitter_card_meta_tags() {
    if (is_single() || is_page()) {
        global $post;

        // Ambil judul, deskripsi, kategori dan gambar
        $title = get_the_title($post->ID);
        $description = get_the_excerpt($post->ID);

        // Jika tidak ada excerpt, gunakan potongan konten sebagai deskripsi
        if (empty($description)) {
            $description = wp_trim_words(get_the_content($post->ID), 30);
        }

        // Jika masih kosong, gunakan deskripsi default
        if (empty($description)) {
            $description = "Portal Informasi Bimbingan Teknis Nasional, Pendidikan, Pelatihan Terupdate, Temukan Materi Bimtek Diklat OPD, ASN, DPRD, Dana Desa, Regulasi Pemerintah Terkini";
        }

        // Ambil kategori dari postingan
        $categories = get_the_category($post->ID);
        $category_names = wp_list_pluck($categories, 'name'); // Ambil nama kategori

        // Gabungkan kategori menjadi satu string (misalnya untuk dimasukkan dalam deskripsi atau keywords)
        $category_names = implode(', ', $category_names);

        // Batasi panjang judul dan deskripsi untuk OG, Twitter Card, dan meta description
        $title = mb_substr($title, 0, 60); // Batasi panjang judul
        $description = mb_substr($description, 0, 160); // Batasi panjang deskripsi untuk SEO (meta description)

        // Gambar featured image
        if (has_post_thumbnail($post->ID)) {
            $image_url = get_the_post_thumbnail_url($post->ID, 'full');
        } else {
            $image_url = 'https://bimtekhub.com/wp-content/uploads/2025/01/logo-1-png.webp'; // Gambar default
        }

        // URL halaman atau postingan
        $url = get_permalink($post->ID);

        // Ambil tag yang terkait dengan postingan
        $tags = get_the_tags($post->ID);
        $keywords = '';
        if ($tags) {
            $keywords = implode(', ', wp_list_pluck($tags, 'name')); // Gabungkan tag menjadi string
        }

        // Meta Deskripsi untuk SEO
        echo '<meta name="description" content="' . esc_attr($description) . ' | Kategori: ' . esc_attr($category_names) . '">' . "\n";  // Menambahkan kategori di meta deskripsi

        // Meta Keywords untuk SEO (dari tag)
        if ($keywords) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
        }

        // Canonical Tag untuk SEO
        echo '<link rel="canonical" href="' . esc_url($url) . '">' . "\n";

        function add_canonical_tag() {
            if (is_singular()) {
                $canonical_url = get_permalink();
                echo '<link rel="canonical" href="' . esc_url($canonical_url) . '" />' . "\n";
            }
        }
        
        add_action('wp_head', 'add_canonical_tag');
        

        // Mengecek apakah halaman adalah postingan (single) atau halaman statis (page)
        if (is_single() || is_page()) {
            
            // Pengecekan berdasarkan kategori
            $no_index_categories = ['Kategori yang tidak diindeks', 'Kategori lainnya']; // Daftar kategori yang tidak ingin diindeks
            $category_names = get_the_category(); 
            $category_names = wp_list_pluck($category_names, 'name');
            
            // Jika kategori ada dalam daftar 'no_index_categories', set noindex, nofollow
            if (array_intersect($category_names, $no_index_categories)) {
                echo '<meta name="robots" content="noindex, nofollow">' . "\n";
            } else {
                echo '<meta name="robots" content="index, follow">' . "\n";
            }

            // Pengecekan berdasarkan tag
            $tags = get_the_tags();
            $tag_names = wp_list_pluck($tags, 'name'); // Mendapatkan nama tag
            $no_index_tags = ['Tag yang tidak diindeks', 'Tag lainnya']; // Daftar tag yang tidak ingin diindeks

            // Jika tag ada dalam daftar 'no_index_tags', set noindex, nofollow
            if (array_intersect($tag_names, $no_index_tags)) {
                echo '<meta name="robots" content="noindex, nofollow">' . "\n";
            } else {
                echo '<meta name="robots" content="index, follow">' . "\n";
            }

        } else {
            // Halaman selain posting atau halaman statis (seperti arsip, kategori, pencarian, dll) di-set noindex, nofollow
            echo '<meta name="robots" content="noindex, nofollow">' . "\n";
        }

        // Open Graph Meta Tags
        echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
        echo '<meta property="og:image" content="' . esc_url($image_url) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
        echo '<meta property="og:type" content="article">' . "\n";

        // Twitter Card Meta Tags
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:site" content="@bimtekhub">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($image_url) . '">' . "\n";
    }
}
add_action('wp_head', 'add_open_graph_and_twitter_card_meta_tags');
