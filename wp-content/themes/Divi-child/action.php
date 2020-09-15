<?php
include_once("../../../wp-load.php");
global $wpdb;
extract($_POST);

$show_count     = 0;
$show_price     = 0;
$show_servers_cost = 0;
$equal_forty = 0;
$greater_forty = 0;
$greater_ninty = 0;
$greater_two_hundred = 0;
$children_price = 0;
$final_cost_server_perhour = 0;


$child_data = get_option('children_data');

if(isset($_POST['checkpizza'])){
    $_POST['checkpizza'] = (int)$_POST['checkpizza'];

    WC()->session->set('pizza_checked', $_POST['checkpizza']);
}

if($child_data != false){
    $show_count = $child_data['count_children'];
    $show_price = $child_data['pizza_price'];
    $show_servers_cost = $child_data['servers_cost'];  
    $equal_forty = $child_data['equal_forty'];
    $greater_forty = $child_data['greater_forty'];
    $greater_ninty = $child_data['greater_ninty'];
    $greater_two_hundred = $child_data['greater_two_hundred'];
}

if(isset($_POST['qty']) && is_array($key)){
    $i = 0;
    foreach ($key as $tmp_Key) {
    if($qty[$i] == 0){
            WC()->cart->remove_cart_item($tmp_Key);
            WC()->session->__unset( 'updated_subtotal' );
            WC()->session->__unset( 'shop_subtotal' );
            WC()->session->__unset( 'final_cost_servers');
            WC()->session->__unset( 'no_of_adults' );
            WC()->session->__unset( 'no_of_childs' );
            WC()->session->__unset( 'pizza_checked' );
    }
    else{
        $tmpkarday = WC()->cart->set_quantity($tmp_Key, (float)$qty[$i]);
        // var_dump($tmpkarday);die;
        }
        $i = $i + 1;    
    }
}else{
    if(isset($qty) && $qty == 0){
        WC()->cart->remove_cart_item($key);
    }
}



        //If no adults isset
        if (isset($_POST['no_of_adults'])){
            (int)$no_of_adults;
            $have_adults = WC()->session->get( 'no_of_adults',null);
            if($have_child != null){
                WC()->session->__unset( 'no_of_adults' );
            }

                WC()->session->set( 'no_of_adults', $no_of_adults);
        
            //If no child isset
            if(isset($_POST['no_of_childs'])){
                (int)$no_of_childs;
                $have_child = WC()->session->get('no_of_childs', null);
                if($have_child != null){
                    WC()->session->__unset( 'no_of_childs' );
                }
                
                WC()->session->set('no_of_childs', $no_of_childs);

                if(isset($_POST['checkpizza']) && $_POST['checkpizza'] == 0){
                    $no_of_childs = 0;
                }
                $children_price = $no_of_childs / $show_count;
                $children_price = ceil($children_price);
                $children_price = $show_price * $children_price;
            }
            //get cart subtotal
            $cart_subtotal = WC()->cart->get_subtotal();
            //add pizza price of childrens
            $cart_subtotal = (int)$cart_subtotal + $children_price;

            // add x% of order
            if($no_of_adults < 41){
                $TMP_subtoal = ($cart_subtotal / 100) * $equal_forty;
            }else if($no_of_adults > 40 && $no_of_adults < 96){
                $TMP_subtoal = ($cart_subtotal / 100) * $greater_forty;
            }else if($no_of_adults > 95 && $no_of_adults < 200){
                $TMP_subtoal = ($cart_subtotal / 100) * $greater_ninty;
            }else if($no_of_adults >= 200){
                $TMP_subtoal = ($cart_subtotal / 100) * $greater_two_hundred;
            }

            $TMP_subtoal = $TMP_subtoal + $cart_subtotal;
            //set session for cart subtotal
            
            $have_subtotal = WC()->session->get('shop_subtotal', 0);

            if($have_subtotal != 0){
                WC()->session->__unset( 'shop_subtotal' );
            }

            WC()->session->set('shop_subtotal', $TMP_subtoal);

            $subtotal = $TMP_subtoal;
        }

//if servers requeried
if(isset($_POST['how_many_servers']) && isset($_POST['servers_hours'])){
    $how_many_servers = (int)$how_many_servers;
    $servers_hours = str_replace(":", ".",$servers_hours);
    $servers_hours = (float)$servers_hours;
    
    $final_cost_of_server = $show_servers_cost * $how_many_servers;
    $final_cost_server_perhour = $final_cost_of_server * $servers_hours;
    WC()->session->set('final_cost_servers', $final_cost_server_perhour);
    
    //Calculate Final total after add server cost 
    $checkout_subtotal = WC()->session->get('shop_subtotal', 0);
    $subtotal =  $checkout_subtotal + $final_cost_server_perhour;
}

    WC()->cart->set_subtotal($subtotal);

    $have_updated_subtotal = WC()->session->get('updated_subtotal', 0);

            if($have_updated_subtotal != 0){
                WC()->session->__unset( 'updated_subtotal' );
            }
    WC()->session->set('updated_subtotal', $subtotal);
?>

<?php
echo WC()->cart->get_cart_subtotal();
?>
