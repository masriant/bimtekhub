<?php
/**
 * Plugin Name: Meta Deskripsi
 * Description: Generates and outputs meta description and keywords for posts and pages.
 * Version: 1.2
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

function add_meta_description() {
    global $post;

    // Check if it's the homepage
    if (is_front_page()) {
        $description = "Portal Informasi Bimbingan Teknis Nasional, Pendidikan, Pelatihan Terupdate.";
        $keywords = "bimbingan teknis, pendidikan, pelatihan";
    } elseif (is_single() || is_page()) {
        // Ambil deskripsi dari excerpt atau konten
        $description = get_the_excerpt($post->ID);
        if (empty($description)) {
            $description = wp_trim_words(get_the_content($post->ID), 30);
        }

        // Jika masih kosong, gunakan deskripsi default
        if (empty($description)) {
            $description = "Portal Informasi Bimbingan Teknis Nasional, Pendidikan, Pelatihan Terupdate.";
        }

        // Set keywords based on the post
        $keywords = "bimbingan teknis, pendidikan, pelatihan, " . get_the_title($post->ID);
    } else {
        return; // Do not output anything for other types of pages
    }

    // Batasi panjang deskripsi untuk SEO
    $description = mb_substr($description, 0, 160);

    // Output meta description
    echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    // Output meta keywords
    echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
}
add_action('wp_head', 'add_meta_description');
