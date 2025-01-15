<?php
/**
 * Enqueue styles and scripts for the child theme.
 */
function generatepress_child_enqueue_styles() {
    // Mengambil style dari tema induk dan anak
    wp_enqueue_style('generatepress-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('generatepress-child-style', get_stylesheet_directory_uri() . '/style.css', array('generatepress-parent-style'));
}
add_action('wp_enqueue_scripts', 'generatepress_child_enqueue_styles');

// Google Site Verification Meta Tag
function google_site_verification() {
    echo '<meta name="google-site-verification" content="av2rpSIYvX4gfgjThO7LgHUib4d-JfZTgwMo_3w_BME" />' . "\n";
}
add_action('wp_head', 'google_site_verification');

function generatepress_child_enqueue_scripts() {
    wp_enqueue_script('child-theme-script', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'generatepress_child_enqueue_scripts');

// Add Open Graph and Twitter Card Meta Tags
function add_open_graph_and_twitter_card_meta_tags() {
    if (is_single() || is_page()) {
        global $post;
        
        // Ambil judul, deskripsi, dan gambar
        $title = get_the_title($post->ID);
        $description = get_the_excerpt($post->ID);
        if (empty($description)) {
            $description = wp_trim_words(get_the_content($post->ID), 30);
        }
        
        // Batasi panjang judul dan deskripsi
        $title = mb_substr($title, 0, 60);
        $description = mb_substr($description, 0, 140);
        
        // Gambar featured image
        if (has_post_thumbnail($post->ID)) {
            $image_url = get_the_post_thumbnail_url($post->ID, 'full');
        } else {
            $image_url = 'URL_DEFAULT_IMAGE';
        }

        // URL halaman atau postingan
        $url = get_permalink($post->ID);

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

