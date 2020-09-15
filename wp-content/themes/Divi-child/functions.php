<?php

function enqueue_parent_styles(){

    wp_enqueue_style('jquery_ui_style', get_stylesheet_directory_uri().'/css/jquery-ui.css');
    wp_enqueue_style('mdTImepickercss', get_stylesheet_directory_uri().'/css/mdtimepicker.css');
    wp_enqueue_style('parent_style', get_template_directory_uri().'/style.css');
    wp_enqueue_style('custom_style', get_stylesheet_directory_uri().'/css/custom.css');
    wp_enqueue_style('custom_slick_style', get_stylesheet_directory_uri().'/slick/slick.css');
    wp_enqueue_style('custom_slick_theme_style', get_stylesheet_directory_uri().'/slick/slick-theme.css');
    wp_enqueue_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );

    wp_enqueue_script('jquery-ui', get_stylesheet_directory_uri().'/js/jquery-ui.js');
    wp_enqueue_script('custom_slick_min_script', get_stylesheet_directory_uri().'/slick/slick.min.js');
    wp_enqueue_script('cleave_min', get_stylesheet_directory_uri().'/cleave/cleave.min.js');
    wp_enqueue_script('cleave_addons', get_stylesheet_directory_uri().'/cleave/addons/phone-type-formatter.pk.js');
    wp_enqueue_script('mdTImepickerjs', get_stylesheet_directory_uri().'/js/mdtimepicker.js');
    wp_enqueue_script('child_theme_script', get_stylesheet_directory_uri().'/js/custom.js');

    wp_enqueue_style( 'flickr_css', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css' );
    wp_enqueue_script('flickr_js', 'https://cdn.jsdelivr.net/npm/flatpickr');

}
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );


if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
function my_jquery_enqueue() {
   wp_deregister_script('jquery');
   wp_register_script('jquery', "//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js", false, null);
   wp_enqueue_script('jquery');
}

/* Start woocomerce Customization */
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields');
// Save Fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
function woocommerce_product_custom_fields()
{
  global $woocommerce, $post;
  $checkbox_field = get_post_meta(get_the_ID(), '_main_course_check', true);
    echo '<div class="product_custom_field">';
    woocommerce_wp_checkbox( 
      array( 
        'id'            => '_main_course_check', 
        'wrapper_class' => $checkbox_field ? $checkbox_field : 'other', 
        'label'         => __('Main Course', 'woocommerce' ),
        'description'   => __( 'Check if product is in main course', 'woocommerce' ) 
        )
      );
    // WooCommerce custom dropdown Select
    woocommerce_wp_select(
    array(
      'id'      => 'menu_type',
      'wrapper_class' => 'menu-type-wrap',
      'label'   => __( 'Select Menu Type', 'woocommerce' ),
      'options' => array(
        // 'empty'  => __( 'Select Product Type', 'woocommerce' ),
        'single' => __( 'Single Serve', 'woocommerce' ),
        'tray'   => __( 'Tray', 'woocommerce' )
      ),
      
      'desc_tip'    => 'true',
      'description' => __( 'Set a type of product', 'woocommerce' ) 
      )
    );
    woocommerce_wp_text_input( 
      array( 
        'id'          => '_wc_min_qty_product', 
        'label'       => __( 'No of serving', 'woocommerce-max-quantity' ), 
        'placeholder' => '0',
        'desc_tip'    => 'true',
        'description' => __( 'Set a quantity limit allowed per tray. Enter a number, 1 or greater.', 'woocommerce-max-quantity' ) 
      )
    );
    ?>
  <?php
  ?>
  <?php
      echo '</div>';
}
function woocommerce_product_custom_fields_save($post_id)
{
    // Checkbox
    $woocommerce_checkbox = isset( $_POST['_main_course_check'] ) ? 'main-course' : '';
    update_post_meta( $post_id, '_main_course_check', $woocommerce_checkbox );
    // WooCommerce custom dropdown Select
    $woocommerce_custom_product_select = $_POST['menu_type'];
    if (!empty($woocommerce_custom_product_select)) {
        update_post_meta($post_id, 'menu_type', esc_attr($woocommerce_custom_product_select));
    }
    // //WooCommerce Custom Checkbox
    $val_min = trim( get_post_meta( $post_id, '_wc_min_qty_product', true ) );
	  $new_min = sanitize_text_field( $_POST['_wc_min_qty_product'] );
    
    if ( $val_min != $new_min ) {
      update_post_meta( $post_id, '_wc_min_qty_product', $new_min );
    }

}

// Custom Product  Variation Settings
add_filter( 'woocommerce_available_variation', 'custom_load_variation_settings_products_fields' );
function custom_load_variation_settings_products_fields( $variations ) {
  
  // duplicate the line for each field
  $variations['menu_type'] = get_post_meta( $variations[ 'variation_id' ], 'menu_type', true );
  
  return $variations;
}

/*Remove Short Discription field backend */
function remove_short_description() {
 
    remove_meta_box( 'postexcerpt', 'product', 'normal');
     
}
add_action('add_meta_boxes', 'remove_short_description', 999);

add_action('wp_head','woocommerce_js');

function woocommerce_js()
{ // break out of php 
?>
<script>
  jQuery(document).ready(function($) {
    $(".menu-logout > a").attr("href", "<?php echo wc_logout_url() ?>");
  });
</script>
<?php } // break back into php

//301 Redirection for Cart to shop
add_action('init', 'redirect_url', 999);

function redirect_url(){
  
  wp_cache_flush();
  
  $link = $_SERVER['REQUEST_URI'];
  $link_array = explode('/',$link);
  $page = $link_array[count($link_array)-2];

  //Redirect Non logged in users to login page
  if ((!is_user_logged_in() && $page == "my-account")) {
    wp_redirect(home_url("login"));
    exit();
  } 

  //Redirect logged in users to my account page
  if (is_user_logged_in() && ($page == "login" || $page == "register")) {
    wp_redirect(home_url("my-account"));
    exit();
  } 

  
  // Redirect User to shop page from cart because there is no cart page for our website.
  if($page == "cart" || $page == "Cart"){
     wp_redirect(home_url("checkout"));
    exit();
  }
}
//End Redirect Function

/*Show cart on checkout page */
add_filter( 'woocommerce_widget_cart_is_hidden', 'always_show_cart', 40, 0 );
function always_show_cart() {
    return false;
}


//Sepetare Register form page for woocomerce start
// THIS WILL CREATE A NEW SHORTCODE: [custom_woo_register_page]
  
add_shortcode( 'custom_woo_register_page', 'bbloomer_separate_registration_form' );
    
function bbloomer_separate_registration_form() {
   if ( is_admin() ) return;
   if ( is_user_logged_in() ) return;
   ob_start();
   woocommerce_output_all_notices();
   // NOTE: THE FOLLOWING <FORM></FORM> IS COPIED FROM woocommerce\templates\myaccount\form-login.php
   // IF WOOCOMMERCE RELEASES AN UPDATE TO THAT TEMPLATE, YOU MUST CHANGE THIS ACCORDINGLY
    
   ?>
 
        <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

        <?php do_action( 'woocommerce_register_form_start' ); ?>

        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

          <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
          </p>

        <?php endif; ?>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
          <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
        </p>

        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

          <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
          </p>

        <?php else : ?>

          <p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

        <?php endif; ?>
        <div class="woocommerce-privacy-policy-text">
          <p>Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our 
            <a href="<?php echo home_url('?page_id=3')?>" class="woocommerce-privacy-policy-link">privacy policy</a>.</p>
        </div>
        <?php
        remove_action( 'woocommerce_register_form', 'wc_registration_privacy_policy_text', 20 );
        do_action( 'woocommerce_register_form' ); ?>

        <p class="woocommerce-FormRow form-row">
          <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
          <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
        </p>

        <?php do_action( 'woocommerce_register_form_end' ); ?>

        </form>
 
   <?php
     
   return ob_get_clean();
}
//Sepetare Register form page for woocomerce end

//==================================================================================//
//Sepetare Login form page for woocomerce start
// THIS WILL CREATE A NEW SHORTCODE: [custom_woo_login_page]
  
add_shortcode( 'custom_woo_login_page', 'bbloomer_separate_login_form' );
  
function bbloomer_separate_login_form() {
   if ( is_admin() ) return;
   if ( is_user_logged_in() ) return; 
   ob_start();
   woocommerce_output_all_notices();
   woocommerce_login_form();
   return ob_get_clean();
}


add_action( 'woocommerce_calculate_totals', 'action_cart_calculate_totals', 10, 1 );
function action_cart_calculate_totals( $cart_object ) {
    if ( !WC()->cart->is_empty() ){
      $updated_Subtotal = WC()->session->get('updated_subtotal', 0);
      $cart_object->cart_contents_total = $updated_Subtotal;
      WC()->cart->set_total($updated_Subtotal);
    }

}

add_action( 'woocommerce_checkout_create_order', 'change_total_on_checking', 20, 1 );
function change_total_on_checking( $order) {
    // Get order total
    $total = $order->get_total();
    $updated_Subtotal = WC()->session->get('updated_subtotal', 0);
    if($updated_Subtotal > 0){
      $updated_Subtotal = $updated_Subtotal + $order->shipping_total;
    }
    $order->set_cart_tax(WC()->session->get('final_cost_servers', 0));
    $order->set_total($updated_Subtotal);

}


//onclick change order status
add_action( 'wp_loaded', 'so_38792085_form_handler' );
function so_38792085_form_handler(){

    // not a "mark as received" form submission
    if ( ! isset( $_POST['mark_as_received'] ) ){
        return;
    }

    // basic security check
    if ( ! isset( $_POST['_so_38792085_nonce_field'] ) || ! wp_verify_nonce( $_POST['_so_38792085_nonce_field'], 'so_38792085_nonce_action' ) ) {   
        return;
    } 

    // make sure order id is submitted
    if ( isset( $_POST['order_id'] ) ){
        $order_id = intval( $_POST['order_id'] );
        $order = wc_get_order( $order_id );
        $order->update_status( "completed" );
        
        WC()->session->__unset( 'updated_subtotal' );
        WC()->session->__unset( 'shop_subtotal' );
        WC()->session->__unset( 'final_cost_servers');
        WC()->session->__unset( 'no_of_adults' );
        WC()->session->__unset( 'no_of_childs' );

        // return '<strong class="green">Estimate email has been sent.</strong>';
        wp_redirect(home_url("thank-you"));
        exit();
        return;
    }   
}
add_action("woocommerce_order_status_changed", "my_awesome_publication_notification");

function my_awesome_publication_notification($order_id, $checkout=null)
{
  $order = wc_get_order( $order_id );
  if ($order->status == "completed") {
      $heading = 'Thank you for order.';
      $subject = 'Order Estimate';

      // Get WooCommerce email objects
      $mailer = WC()->mailer()->get_emails();

      // Use one of the active emails e.g. "Customer_Completed_Order"
      // Wont work if you choose an object that is not active
      // Assign heading & subject to chosen object
      $mailer['WC_Email_Customer_On_Hold_Order']->heading = $heading;
      $mailer['WC_Email_Customer_On_Hold_Order']->settings['heading'] = $heading;
      $mailer['WC_Email_Customer_On_Hold_Order']->subject = $subject;
      $mailer['WC_Email_Customer_On_Hold_Order']->settings['subject'] = $subject;

      // Send the email with custom heading & subject
      $mailer['WC_Email_Customer_On_Hold_Order']->trigger($order_id);
  }
}

//Remove Status from order
function misha_remove_order_statuses( $wc_statuses_arr ){
	// // Processing
	// if( isset( $wc_statuses_arr['wc-processing'] ) ) { // if exists
	// 	unset( $wc_statuses_arr['wc-processing'] ); // remove it from array
	// }
	// Refunded
	if( isset( $wc_statuses_arr['wc-refunded'] ) ){
		unset( $wc_statuses_arr['wc-refunded'] );
	}
	// On Hold
	if( isset( $wc_statuses_arr['wc-on-hold'] ) ){
		unset( $wc_statuses_arr['wc-on-hold'] );
	}
	// Failed
	if( isset( $wc_statuses_arr['wc-failed'] ) ){
		unset( $wc_statuses_arr['wc-failed'] );
	}
	// Pending payment
	if( isset( $wc_statuses_arr['wc-pending'] ) ){
		unset( $wc_statuses_arr['wc-pending'] );
	}
	// Completed
	//if( isset( $wc_statuses_arr['wc-completed'] ) ){
	//    unset( $wc_statuses_arr['wc-completed'] );
	//}
	// Cancelled
	//if( isset( $wc_statuses_arr['wc-cancelled'] ) ){
	//    unset( $wc_statuses_arr['wc-cancelled'] );
	//}
	return $wc_statuses_arr; // return result statuses
}
add_filter( 'wc_order_statuses', 'misha_remove_order_statuses' );


add_action( 'woocommerce_order_status_completed', 'wc_send_order_to_mypage' );
function wc_send_order_to_mypage( $order_id ) {

$order = wc_get_order( $order_id );
      $order_data = $order->get_data();
        
        $order_meta_date = array();
        foreach($order_data['meta_data'] as $order_meta){
          $order_meta_date[$order_meta->key] = $order_meta->value;
        }
        $TMP_need_servers = $order_meta_date['need_servers'];
        if($TMP_need_servers == "no"){
          $order_meta_date['server_hours'][0] = "";
        }
        //Generate csv file
        $filename = ABSPATH.'csv/estiamte.csv';
        $csv_handler = fopen ($filename,'w');
  
          $TMP_heading					= array(
            'Products Name',
            'No of Adults',
            'No of Childrens',
            'Order Number',
            'Order Date',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Customer Address',
            'Delivery Address',
            'City',
            'Zip Code',
            'Event Date',
            'Event Time',
            'Need Servers',
            'How many servers required',
            'For how many hours',
            'From',
            'To',
            'Server Cost',
            'Subtotal',
            'Shipping Charges',
            'Final Total'
            
          );
        fputcsv($csv_handler, $TMP_heading);
        $products_names = '';
        $TMP_i = 1;
        // Get and Loop Over Order Items
        foreach ( $order->get_items() as $item_id => $item ) {

            $products_names .= $TMP_i .") ". $item->get_name() . "<br/>";
            $TMP_i = $TMP_i + 1;
        }
        
        $data_order = array(
                      $products_names,
                      WC()->session->get('no_of_adults', 0),
                      WC()->session->get('no_of_childs', 0),
                      $order_data['id'],
                      $order->get_date_created(),
                      $order->get_formatted_billing_full_name(),
                      $order->get_billing_email(),
                      $order->get_billing_phone(),
                      $order->get_billing_address_1(),
                      $order->get_billing_address_2(),
                      $order->get_billing_city(),
                      $order->get_billing_postcode(),
                      $order_meta_date['event_date'],
                      $order_meta_date['event_time'],
                      $order_meta_date['need_servers'],
                      $order_meta_date['how_many_servers'],
                      $order_meta_date['server_hours'][0],
                      $order_meta_date['server_from'],
                      $order_meta_date['server_to'],
                      WC()->session->get('final_cost_servers', 0),
                      WC()->session->get('updated_subtotal', 0),
                      $order_data['shipping_total'],
                      $order_data['total']
                    );

        
        fputcsv($csv_handler, $data_order);
        
        // fwrite ($csv_handler,$csv);
        fclose ($csv_handler); 

        add_filter( 'woocommerce_email_attachments', 'attach_order_notice', 11, 3 );     
}


function attach_order_notice ( $attachments, $email_id, $order ) 
{
    // Avoiding errors and problems
    if ( ! is_a( $order, 'WC_Order' ) || ! isset( $email_id ) ) {
      return $attachments;
    }
    if ( $email_id == 'customer_completed_order' ){
      $attachments[] = ABSPATH.'csv/estiamte.csv';

    }
      
    
    return $attachments;
}


add_filter( 'woocommerce_email_recipient_customer_completed_order', 'bbloomer_order_completed_email_add_to', 9999, 3 );
 
function bbloomer_order_completed_email_add_to( $email_recipient, $email_object, $email ) {
   $email_recipient = get_bloginfo('admin_email');
   return $email_recipient;
}


function mode_theme_update_mini_cart() {
  echo wc_get_template( 'cart/mini-cart.php' );
  die();
}
add_filter( 'wp_ajax_nopriv_mode_theme_update_mini_cart', 'mode_theme_update_mini_cart' );
add_filter( 'wp_ajax_mode_theme_update_mini_cart', 'mode_theme_update_mini_cart' );

// In theme's functions.php or plug-in code:

function wpse27856_set_content_type(){
  return "text/html";
}
add_filter( 'wp_mail_content_type','wpse27856_set_content_type' );