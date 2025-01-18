<?php
/**
 * Plugin Name: Tabel Daftar Post dan Kategori dengan Filter Admin
 * Description: Menampilkan daftar post beserta kategori dalam bentuk tabel dengan opsi filter kategori dari pengaturan admin.
 * Version: 1.2
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

// Fungsi untuk menambahkan halaman pengaturan ke menu admin
function tdp_register_settings_page() {
    add_options_page(
        'Pengaturan Kategori Tabel',
        'Pengaturan Kategori Tabel',
        'manage_options',
        'tdp-category-settings',
        'tdp_category_settings_page'
    );
}
add_action('admin_menu', 'tdp_register_settings_page');

// Fungsi untuk menampilkan halaman pengaturan
function tdp_category_settings_page() {
    // Simpan pengaturan jika formulir disubmit
    if (isset($_POST['tdp_save_settings'])) {
        $excluded_categories = isset($_POST['excluded_categories']) ? $_POST['excluded_categories'] : array();
        update_option('tdp_excluded_categories', $excluded_categories);
        echo '<div class="updated"><p>Pengaturan disimpan.</p></div>';
    }

    // Dapatkan kategori yang dikecualikan saat ini
    $excluded_categories = get_option('tdp_excluded_categories', array());

    // Ambil semua kategori yang ada
    $categories = get_categories();
    ?>

    <div class="wrap">
        <h1>Pengaturan Kategori Tabel</h1>
        <form method="POST">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Pilih Kategori yang Akan Dikecualikan:</th>
                    <td>
                        <?php foreach ($categories as $category) { ?>
                            <label>
                                <input type="checkbox" name="excluded_categories[]" value="<?php echo esc_attr($category->slug); ?>" <?php checked(in_array($category->slug, $excluded_categories)); ?>>
                                <?php echo esc_html($category->name); ?>
                            </label><br>
                        <?php } ?>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="tdp_save_settings" class="button-primary" value="Simpan Pengaturan">
            </p>
        </form>
    </div>

    <?php
}

// Shortcode untuk menampilkan tabel daftar post dan kategori dengan filter
function display_filtered_posts_and_categories_table() {
    // Ambil kategori yang dikecualikan dari pengaturan
    $excluded_categories = get_option('tdp_excluded_categories', array());

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
