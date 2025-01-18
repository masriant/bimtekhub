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

?>
