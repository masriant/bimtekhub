<?php
/**
 * Template Name: Sitemap Page
 * Description: A custom sitemap page template for GeneratePress theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

	<div <?php generate_do_attr( 'content' ); ?>>
		<main <?php generate_do_attr( 'main' ); ?>>
			<?php
			/**
			 * generate_before_main_content hook.
			 *
			 * @since 0.1
			 */
			do_action( 'generate_before_main_content' );

			if ( generate_has_default_loop() ) :
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class(); ?>>
						<header class="entry-header">
							<h1 class="entry-title"><?php the_title(); ?></h1>
						</header><!-- .entry-header -->

						<div class="entry-content">
							<p>Berikut adalah sitemap lengkap dari situs BimtekHub, yang mencakup daftar halaman, posting, dan kategori yang ada di situs kami.</p>

							<h2>Pages</h2>
							<ul>
								<?php
								$pages = get_pages();
								foreach ( $pages as $page ) {
									echo '<li><a href="' . get_permalink( $page->ID ) . '">' . esc_html( $page->post_title ) . '</a></li>';
								}
								?>
							</ul>

							<h2>Posts</h2>
							<ul>
								<?php
								$posts = get_posts( array( 'numberposts' => -1 ) );
								foreach ( $posts as $post ) {
									echo '<li><a href="' . get_permalink( $post->ID ) . '">' . esc_html( $post->post_title ) . '</a></li>';
								}
								?>
							</ul>

							<h2>Categories</h2>
							<ul>
								<?php
								$categories = get_categories();
								foreach ( $categories as $category ) {
									echo '<li><a href="' . get_category_link( $category->term_id ) . '">' . esc_html( $category->name ) . '</a></li>';
								}
								?>
							</ul>
						</div><!-- .entry-content -->
					</article><!-- #post-<?php the_ID(); ?> -->
					<?php
				endwhile;
			endif;

			/**
			 * generate_after_main_content hook.
			 *
			 * @since 0.1
			 */
			do_action( 'generate_after_main_content' );
			?>
		</main>
	</div>

	<?php
	/**
	 * generate_after_primary_content_area hook.
	 *
	 * @since 2.0
	 */
	do_action( 'generate_after_primary_content_area' );

	generate_construct_sidebars();

	get_footer();
?>
