<?php
/**
 * Template Name: Sitemap Page
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>

            <div class="entry-content">
                <h2>Pages</h2>
                <ul>
                    <?php
                    // Query all published pages
                    $pages = get_pages();
                    foreach ($pages as $page) {
                        echo '<li><a href="' . get_permalink($page->ID) . '">' . $page->post_title . '</a></li>';
                    }
                    ?>
                </ul>

                <h2>Posts</h2>
                <ul>
                    <?php
                    // Query all published posts
                    $posts = get_posts(array(
                        'numberposts' => -1,
                        'post_status' => 'publish',
                    ));
                    foreach ($posts as $post) {
                        setup_postdata($post);
                        echo '<li><a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a></li>';
                    }
                    wp_reset_postdata();
                    ?>
                </ul>

                <h2>Categories</h2>
                <ul>
                    <?php
                    // Query all categories
                    $categories = get_categories();
                    foreach ($categories as $category) {
                        echo '<li><a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';
                    }
                    ?>
                </ul>
            </div><!-- .entry-content -->
        </article><!-- #post-<?php the_ID(); ?> -->

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
