<?php
/**
 * Plugin Name: Tabel Daftar Post dan Kategori dengan Filter
 * Description: Menampilkan daftar post beserta kategori dalam bentuk tabel, dengan filter kategori.
 * Version: 1.1
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

// Shortcode untuk menampilkan tabel daftar post dan kategori dengan filter
function display_filtered_posts_and_categories_table() {
    // Kategori yang akan dikecualikan (masukkan ID kategori atau slug)
    $excluded_categories = array('uncategorized', 'kategori-tidak-perlu'); // Ganti dengan slug atau ID kategori yang ingin dikecualikan

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
            // Periksa apakah kategori ada dalam daftar pengecualian
            if (!in_array($category->slug, $excluded_categories)) {
                $category_links[] = '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
            }
        }

        // Hanya tambahkan baris jika ada kategori yang lolos filter
        if (!empty($category_links)) {
            $categories_output = implode(', ', $category_links); // Gabungkan semua kategori menjadi string

            // Menambahkan baris ke tabel
            $output .= '<tr>';
            $output .= '<td><a href="' . $post_link . '">' . $post_title . '</a></td>';
            $output .= '<td>' . $categories_output . '</td>';
            $output .= '</tr>';
        }
    }

    wp_reset_postdata(); // Reset query

    $output .= '</tbody>';
    $output .= '</table>';

    return $output; // Kembalikan output untuk ditampilkan di halaman
}

// Daftarkan shortcode untuk menampilkan tabel
add_shortcode('filtered_posts_and_categories_table', 'display_filtered_posts_and_categories_table');
