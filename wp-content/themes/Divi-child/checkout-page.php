<?php
/*
Template Name: Checkout Page
*/

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() ); ?>
<?php 
$link = $_SERVER['REQUEST_URI'];
$link_array = explode('/',$link);
$page = $link_array[count($link_array)-2];
$section_id = "full-width";
$checkout = false;
if($page == "checkout"){
	$section_id = "left-area";
	$checkout = true;
}
?>
<div id="main-content">
	
<?php
if($checkout == true){
	echo do_shortcode('[et_pb_section global_module="375"][/et_pb_section]'); 
}else{
	echo do_shortcode('[et_pb_section global_module="511"][/et_pb_section]');
}
?>

<?php if ( ! $is_page_builder_used ) : ?>

	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="<?php echo $section_id;?>">

<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( ! $is_page_builder_used ) : ?>

				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_featured_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					if ( 'on' === et_get_option( 'divi_page_thumbnails', 'false' ) && '' !== $thumb )
						print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
				?>

				<?php endif; ?>

					<div class="entry-content">
					<?php
						the_content();

						if ( ! $is_page_builder_used )
							wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>
					</div> <!-- .entry-content -->

				<?php
					if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
				?>

				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>

		</div> <!-- #left-area -->
<?php if ( ! $is_page_builder_used && $checkout == true) : ?>


			<?php 
			get_sidebar(); 
			?>
			
<?php endif; ?>

		</div> <!-- #content-area -->
	</div> <!-- .container -->

</div> <!-- #main-content -->

<?php

get_footer();