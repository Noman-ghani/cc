<?php
/* Insert Children data in database's wp-options table */

if(isset($_POST['submit'])){ 
    insert_timer_data_in_wp_options();
}

global $count_children;
global $pizza_price;
global $servers_cost;
global $equal_forty;
global $greater_forty;
global $greater_ninty;
global $greater_two_hundred;
global $minimum_adults;
global $minimum_childs;

function insert_timer_data_in_wp_options(){

    $count_children    =   $_POST['count_children'];
    $pizza_price       =   $_POST['pizza_price'];
    $servers_cost       =   $_POST['servers_cost'];
    $equal_forty       =   $_POST['equal_forty'];
    $greater_forty       =   $_POST['greater_forty'];
    $greater_ninty       =   $_POST['greater_ninty'];
    $greater_two_hundred       =   $_POST['greater_two_hundred'];
    $minimum_adults       =   $_POST['minimum_adults'];
    $minimum_childs       =   $_POST['minimum_childs'];

    $children_data = array(
        'count_children' => $count_children,
        'pizza_price' => $pizza_price,
        'servers_cost' => $servers_cost,
        'equal_forty' => $equal_forty,
        'greater_forty' => $greater_forty,
        'greater_ninty' => $greater_ninty,
        'greater_two_hundred' => $greater_two_hundred,
        'minimum_adults' => $minimum_adults,
        'minimum_childs' => $minimum_childs
    );
    
    $new_value = get_option("children_data");

    if($new_value != FALSE){
        update_option('children_data', $children_data);
        success_msg();
    }else{
        add_option('children_data', $children_data);
        success_msg();
    }
}

function success_msg(){
    return '<span class="txt">Data Inserted successfully.</span>';
}

/* Insert Children data in database's wp-options table end */

$show_count = "";
$show_price = "";
$show_servers_cost = "";
$equal_forty = "";
$greater_forty = "";
$greater_ninty = "";
$greater_two_hundred = "";
$minimum_adults = "";
$minimum_childs = "";
$child_data = get_option('children_data');
if($child_data != false){
    $show_count = $child_data['count_children'];
    $show_price = $child_data['pizza_price'];
    $show_servers_cost = $child_data['servers_cost'];
    $equal_forty = $child_data['equal_forty'];
    $greater_forty = $child_data['greater_forty'];
    $greater_ninty = $child_data['greater_ninty'];
    $greater_two_hundred = $child_data['greater_two_hundred'];
    $minimum_adults = $child_data['minimum_adults'];
    $minimum_childs = $child_data['minimum_childs'];
}
?>
<div class="container">
    <section class="hundred">
        <h2 class="pizza-title">Add pizza price for children</h2>
        <form method="post" class="pizza-form">
            <p class="msg"><?php if(isset($_POST['submit'])){ echo success_msg();} ?></p>
            <div class="hundred">
                <div class="twenty-five"><p class="label">Number of children for calculate per pizza.</p></div>
                <div class="seventy-five"><input type="number" id="count_children" value="<?php echo $show_count?>" name="count_children" required /></div>
            </div>
            <div class="hundred">
                <div class="twenty-five"><p class="label">Add Pizza Price</p></div>
                <div class="seventy-five"><input type="number" id="pizza_price" value="<?php echo $show_price?>" name="pizza_price" required /></div>
            </div>
            <div class="hundred">
                <h2 class="pizza-title">Add cost per server</h2>
            </div>
            <div class="hundred">
                <div class="twenty-five"><p class="label">Server Cost Per Hour</p></div>
                <div class="seventy-five"><input type="number" id="servers_cost" value="<?php echo $show_servers_cost?>" name="servers_cost" required /></div>
            </div>
            <div class="hundred">
                <h2 class="pizza-title">Add X% hidden charges</h2>
            </div>
            <div class="hundred x_percent">
                <div class="twenty-five"><p class="label">40 people  X= </p></div>
                <div class="seventy-five"><input type="number" id="forty_people" value="<?php echo $equal_forty?>" name="equal_forty" required />
                <span class="perc">%</span>
                </div>
            </div>
            <div class="hundred x_percent">
                <div class="twenty-five"><p class="label">41 to 95; X= </p></div>
                <div class="seventy-five"><input type="number" id="forty_one" value="<?php echo $greater_forty?>" name="greater_forty" required />
                <span class="perc">%</span>
                </div>
            </div>
            <div class="hundred x_percent">
                <div class="twenty-five"><p class="label">96 to 200; X = </p></div>
                <div class="seventy-five"><input type="number" id="ninty_six" value="<?php echo $greater_ninty?>" name="greater_ninty" required />
                <span class="perc">%</span>
                </div>
            </div>
            <div class="hundred x_percent">
                <div class="twenty-five"><p class="label">200 and above; X = </p></div>
                <div class="seventy-five"><input type="number" id="two_hundred" value="<?php echo $greater_two_hundred?>" name="greater_two_hundred" required />
                <span class="perc">%</span>
                </div>
            </div>
            <div class="hundred">
                <h2 class="pizza-title">Add limits</h2>
            </div>
            <div class="hundred">
                <div class="twenty-five"><p class="label">Add minimum adults limit</p></div>
                <div class="seventy-five"><input type="number" id="minimum_adults" value="<?php echo $minimum_adults?>" name="minimum_adults" required /></div>
            </div>
            <div class="hundred" hidden>
                <div class="twenty-five"><p class="label">Add minimum childs limit</p></div>
                <div class="seventy-five"><input type="number" id="minimum_childs" value="<?php echo $minimum_childs?>" name="minimum_childs" required /></div>
            </div>
            <div class="hundred">
                <div class="twenty-five"></div>
                <div class="seventy-five"><input type="submit" value="Save Changes" id="expire-btn" name="submit" /></div>
            </div>
        </form>
    </section>
</div>
