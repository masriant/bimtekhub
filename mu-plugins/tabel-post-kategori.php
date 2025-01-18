<?php
/**
 * Plugin Name: Tabel Daftar Post dan Kategori
 * Description: Menampilkan daftar post beserta kategori dalam bentuk tabel.
 * Version: 1.0
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

// Shortcode untuk menampilkan tabel daftar post dan kategori
function display_posts_and_categories_table() {
    // Menyiapkan query untuk mendapatkan semua post
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1, // Menampilkan semua post
    );

    $query = new WP_Query($args);
    $output = '<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-bottom: 20px;">';
    $output .= '<thead><tr><th>Judul Post</th><th>Kategori</th></tr></thead>';
    $output .= '<tbody>';

    // Loop melalui setiap post
    while ($query->have_posts()) {
        $query->the_post();

        // Mengambil judul post dan link-nya
        $post_title = get_the_title();
        $post_link = get_permalink();

        // Mengambil kategori yang terkait dengan post
        $categories = get_the_category();
        $category_links = array();

        foreach ($categories as $category) {
            $category_links[] = '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
        }

        $categories_output = implode(', ', $category_links); // Gabungkan semua kategori menjadi string

        // Menambahkan baris ke tabel
        $output .= '<tr>';
        $output .= '<td><a href="' . $post_link . '">' . $post_title . '</a></td>';
        $output .= '<td>' . $categories_output . '</td>';
        $output .= '</tr>';
    }

    wp_reset_postdata(); // Reset query

    $output .= '</tbody>';
    $output .= '</table>';

    return $output; // Kembalikan output untuk ditampilkan di halaman
}

// Daftarkan shortcode untuk menampilkan tabel
add_shortcode('posts_and_categories_table', 'display_posts_and_categories_table');
