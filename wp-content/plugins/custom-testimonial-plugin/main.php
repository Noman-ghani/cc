<?php
/*
Plugin Name: Custom Testimonial Plugin
Version: 1.0
Author: Genetech Solutions
Description: Custom Testimonial Plugin Provided By Genetech Solutions
*/

# Register Plugin Menu
add_action( 'init', 'register_custom_testimonial' );
function register_custom_testimonial() {
	$labels = array(
		"name" => "Testimonials",
		"singular_name" => "Testimonial",
		"menu_name" => "Testimonial",
		);

	$args = array(
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"show_ui" => true,
		"has_archive" => false,
		"show_in_menu" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "tesimonials", "with_front" => true ),
		"query_var" => true,
		"supports"  => array( 'title', 'editor', 'thumbnail', 'excerpt' ,'post-thumbnails' ),
		'menu_icon'  => 'dashicons-format-quote',
	);
	register_post_type( "custom_testimonials", $args );
}

add_action('init', 'create_topics_hierarchical_taxonomy', 0);
function create_topics_hierarchical_taxonomy()
{

    $labels = array(
        'name' => _x('Categories', 'taxonomy general name'),
        'singular_name' => _x('Topic', 'taxonomy singular name'),
        'search_items' =>  __('Search Categories'),
        'all_items' => __('All Categories'),
        'parent_item' => __('Testimonials Categories'),
        'parent_item_colon' => __('Testimonials Categories:'),
        'edit_item' => __('Edit Categories'),
        'update_item' => __('Update Categories'),
        'add_new_item' => __('Add New Categories'),
        'new_item_name' => __('New Categories Name'),
        'menu_name' => __('Categories'),
    );

    register_taxonomy('category', array('custom_testimonials'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'category'),
    ));
}
include('list.php');