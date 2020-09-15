<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */
defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
echo do_shortcode('[et_pb_section global_module="363"][/et_pb_section]');

remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);
do_action( 'woocommerce_before_main_content' );


?>

<div id="tabs">
	<?php 
	$orderby = 'name';
	$order = 'asc';
	$hide_empty = true ;
	$cat_args = array(
		'orderby'    => $orderby,
		'order'      => $order,
		'hide_empty' => $hide_empty,
	);
	 
	$product_categories = get_terms( 'product_cat', $cat_args );
	
	if( !empty($product_categories) ){
		echo '<ul class="product-cat-list slick_cate_shop">';
	
		foreach ($product_categories as $key => $category) {
			
				$tab_ID = "'cat_". $category->term_id."'";
				echo '<li class="produ-cat-item">';
				echo '<a href="javascript:;" class="tablinks" onclick="openCity(event,'.$tab_ID.')">';
				echo $category->name;
				echo '</a>';
				echo '</li>';
			
		}
		echo '</ul>';
}
?>

<!-- Tab End -->

<?php
foreach ($product_categories as $key => $category) {
	
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'product_cat'    => $category->name
	);
	
    $loop = new WP_Query($args);
	if($loop->have_posts()) {
		
            echo '<div class="products-row product-tab-content" id="cat_'. $category->term_id.'">';
			echo '<h2 class="cat-title">'.$category->name.'</h2>';
			
                while ($loop->have_posts()) : $loop->the_post();
                global $product;
                echo '<div class="product-col" data-product_type="'.$product->get_meta('menu_type').'">';
                echo '<div class="product-content">';
                echo '<div class="product-img-wrap">' . get_the_post_thumbnail().'</div>';
                echo '<h2 class="product-title">' . get_the_title().'</h2>';
                echo '<p class="product-tool-tip">' . get_the_content().'</p>';
                echo '</div>';
                add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
                echo do_action('woocommerce_after_shop_loop_item');
                echo '</div>';
                endwhile;
            
            echo '</div>';
        
	}
	wp_reset_query();
}

?>
</div>
<?php


/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
