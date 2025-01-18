<?php
/**
 * Custom Functions
 * This file contains all custom shortcodes and filters.
 */

// Shortcode 1: Display posts in a table filtered by admin settings
function display_admin_filtered_posts_table() {
    $excluded_categories = get_option('tdp_excluded_categories', array());

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);
    $output = '<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-bottom: 20px;">';
    $output .= '<thead><tr><th>Judul Post</th><th>Kategori</th></tr></thead>';
    $output .= '<tbody>';

    while ($query->have_posts()) {
        $query->the_post();
        $post_title = get_the_title();
        $post_link = get_permalink();

        $categories = get_the_category();
        $category_links = array();

        foreach ($categories as $category) {
            if (!in_array($category->slug, $excluded_categories)) {
                $category_links[] = '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
            }
        }

        if (!empty($category_links)) {
            $categories_output = implode(', ', $category_links);
            $output .= '<tr>';
            $output .= '<td><a href="' . $post_link . '">' . $post_title . '</a></td>';
            $output .= '<td>' . $categories_output . '</td>';
            $output .= '</tr>';
        }
    }

    wp_reset_postdata();
    $output .= '</tbody>';
    $output .= '</table>';

    return $output;
}
add_shortcode('admin_filtered_posts_table', 'display_admin_filtered_posts_table');

// Shortcode 2: Display posts in a table filtered by static category list
function display_static_filtered_posts_table() {
    $excluded_categories = array('uncategorized', 'other-category-slug');

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);
    $output = '<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-bottom: 20px;">';
    $output .= '<thead><tr><th>Judul Post</th><th>Kategori</th></tr></thead>';
    $output .= '<tbody>';

    while ($query->have_posts()) {
        $query->the_post();
        $post_title = get_the_title();
        $post_link = get_permalink();

        $categories = get_the_category();
        $category_links = array();

        foreach ($categories as $category) {
            if (!in_array($category->slug, $excluded_categories)) {
                $category_links[] = '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
            }
        }

        if (!empty($category_links)) {
            $categories_output = implode(', ', $category_links);
            $output .= '<tr>';
            $output .= '<td><a href="' . $post_link . '">' . $post_title . '</a></td>';
            $output .= '<td>' . $categories_output . '</td>';
            $output .= '</tr>';
        }
    }

    wp_reset_postdata();
    $output .= '</tbody>';
    $output .= '</table>';

    return $output;
}
add_shortcode('static_filtered_posts_table', 'display_static_filtered_posts_table');

// Function to display posts by category with new label
function display_posts_by_category_with_new_label($atts) {
    // Ambil kategori dari atribut shortcode
    $atts = shortcode_atts(
        array(
            'category' => 'keuangan', // Default: keuangan
        ),
        $atts,
        'posts_by_category_with_new_label'
    );

    // Mengambil kategori berdasarkan slug
    $category = get_category_by_slug($atts['category']);
    if (!$category) {
        return 'Kategori tidak ditemukan.';
    }

    // Menyiapkan query untuk mendapatkan semua post dalam kategori yang dipilih, diurutkan berdasarkan tanggal
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1, // Ambil semua post
        'category_name' => $atts['category'], // Menyaring berdasarkan kategori
        'orderby' => 'date', // Urutkan berdasarkan tanggal
        'order' => 'DESC', // Urutkan dari yang terbaru
    );

    // Jalankan query untuk mengambil postingan
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $output = '<ul>';

        while ($query->have_posts()) {
            $query->the_post();
            $post_title = get_the_title();
            $post_link = get_permalink();
            $post_date = get_the_date('Y-m-d'); // Ambil tanggal publikasi
            $post_time = strtotime($post_date);
            $current_time = current_time('timestamp'); // Waktu saat ini
            $new_label = ''; // Label "Baru" default

            // Menentukan apakah postingan ini adalah postingan terbaru (misalnya dalam 7 hari terakhir)
            if (($current_time - $post_time) <= 14 * DAY_IN_SECONDS) {
                $new_label = '<span class="new-label" style="color: red; font-weight: bold;">New</span>';
            }

            // Menampilkan judul postingan dengan label "Baru" jika perlu
            $output .= '<li>';
            $output .= '<a href="' . $post_link . '">' . $post_title . '</a>';
            if ($new_label) {
                $output .= ' ' . $new_label; // Tampilkan label "Baru"
            }
            $output .= '</li>';
        }

        $output .= '</ul>'; // Ensure no stray quotation marks here
    } else {
        $output = 'Tidak ada postingan dalam kategori ini.';
    }

    wp_reset_postdata();

    return $output;
}

// Daftarkan shortcode untuk menampilkan postingan dengan kategori tertentu, urutkan berdasarkan terbaru dan label "Baru"
add_shortcode('posts_by_category_with_new_label', 'display_posts_by_category_with_new_label');
