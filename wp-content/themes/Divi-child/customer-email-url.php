<?php
include_once("../../../wp-load.php");
global $wpdb;
extract($_POST);

if(isset($_POST['email'])){
    
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();
    $Mail_html = '<div style="max-width:600px;width:100%;background: #9effeb;margin:auto;padding:30px;"><img src="'.get_stylesheet_directory_uri().'/images/logo.png" style="width;200px"/>';
    $Mail_html .= '<div style="margin:30px 0;"><p>This email has been recieved from customer cart.</p>';
    $Mail_html .= '<p><strong>Customer Email is :'.$_POST['email'].'</strong></p>';
    $Mail_html .= '<p>Cart Details mention below:</p></div>';
    $Mail_html .= '<table border style="border-collapse: collapse;width: 100%;">';
        
            $Mail_html .= '<tr>';
                $Mail_html .= '<th style="text-align:left;padding:10px;width:100px">Product ID</th> <th style="text-align:left;padding:10px">Product Name</th>'; 
            $Mail_html .= '</tr>';
       
        foreach($items as $item => $values) { 
            
            $Mail_html .= '<tr>';
                $_product =  wc_get_product( $values['data']->get_id()); 
                $Mail_html .= '<td style="padding:10px">'.$values["product_id"].'</td><td style="padding:10px">'.$_product->get_title().'</td>'; 
            $Mail_html .= '</tr>';
        }
        $Mail_html .= '<tr>';
            $Mail_html .= '<th style="text-align:left;padding:10px">Num of Adults</th>';
            $Mail_html .= '<th style="text-align:left;padding:10px">'.$_POST["fieldAdult"].'</th>';
        $Mail_html .= '</tr>';
        $Mail_html .= '<tr>';
            $Mail_html .= '<th style="text-align:left;padding:10px">Num of Childs</th>';
            $Mail_html .= '<th style="text-align:left;padding:10px">'.$_POST["fieldChild"].'</th>';
        $Mail_html .= '</tr>';
    $Mail_html .= '</table></div>';

    $to = get_bloginfo('admin_email');;
    $subject = "Invalid Order";
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    $tmp_email = wp_mail( $to, $subject, $Mail_html, $headers );

    if($tmp_email){
        echo "Email has been send.";
    }else{
        echo "Email not send please check.";
    }
}