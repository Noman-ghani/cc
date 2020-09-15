<?php
/*
Plugin Name: Add Pizze Price WOO
Description: woocommerce adone plugin for add custom pizza price for children
Author: Noman Ghani
Author URI: https://www.genetechsolutions.com
Version: 1.0.0
*/

add_action('admin_menu','add_menu_item');

function add_menu_item(){
    add_submenu_page('woocommerce','Custom Setting','Custom Setting','manage_options','add_pizza_price','add_pizza_price_func');
}
function add_pizza_price_func(){
  include 'add-pizza-price.php';
}
function enqueue_plugin_styles(){
    wp_enqueue_style('plugin_style', plugins_url('/css/style.css',__FILE__));
    wp_enqueue_script('custom_script', plugins_url('/js/script.js',__FILE__));
}
add_action( 'admin_enqueue_scripts', 'enqueue_plugin_styles' );
add_action('wp_enqueue_scripts', 'enqueue_plugin_styles' );
