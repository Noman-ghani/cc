/*============================================*/
//STEP 1 GET DATA AND FILTER BY HIHEST PRICE
/*============================================*/

function getproductKey(){
	let productKeys = [];
	
		let keycartData = getFinalCartData();
		keycartData.forEach(data => {
			productKeys.push(data.key);
		})
		console.log(productKeys);
		return productKeys;
	
}

function generateDataFromCart(){
	//It will return an object of price and serve people order bu higest price
	var mainCoursePriceAll = [];
	jQuery(".mini_cart_item").each(function(){
		jQuery(this).find('.woocommerce-Price-currencySymbol').remove();
			var amount = jQuery(this).find('.amount').text();
			amount = parseFloat(amount);
			var surve_people = jQuery(this).find('.product-name').data("surve_people");
			var product_type = jQuery(this).find('.product-name').data("product_type");
			var product_name = jQuery(this).find('.product-name').text();
			var product_key = jQuery(this).attr("key");
			var category = jQuery(this).data("catname");
			// var surve_people = jQuery(this).parent().next().next().data("surve_people");
			var TMP_obj = {
				name	: product_name,
				key		: product_key,
				serve 	 : surve_people,
				price 	 : amount,
				type 	 : product_type,
				qty		 : 0,
				category : category
			}
			mainCoursePriceAll.push(TMP_obj);
		})
		var higestPriceObj = mainCoursePriceAll.sort(compare);
		return higestPriceObj;
	
}

function compare(a, b) {
	const priceA = a.price;
	const priceB = b.price;
  
	let comparison = 0;
	if (priceA < priceB) {
	  comparison = 1;
	} else if (priceA > priceB) {
	  comparison = -1;
	}
	return comparison;
}

/*============================================*/
//STEP 2 ARRANGE DATA BY QUANTITY AND FINALIZE DATA IN OBJECT
/*============================================*/

function getFinalCartData(){
	var TMP_totalmainCourseServe = 0;
	var TMP_totaltrayServe = 0;
	var TMP_totalsingles = 0;
	
	var cartData = generateDataFromCart(); 
	var fieldAdult = jQuery('input[name="numberOfAdults"]').val();
		fieldAdult = parseInt(fieldAdult);
		
    if(jQuery('.deduct-pizza').prop("checked") == false){
		var fieldChild = jQuery('input[name="numberOfchildren"]').val();
		fieldChild = fieldChild / 2;
		fieldAdult = fieldAdult + fieldChild;
		fieldAdult = Math.round(fieldAdult);
		console.log('Total crowd is '+fieldAdult);
    }

		//debugger;
		let TMP_totalServe = get_total_serve_tray();
		let userLikhe = fieldAdult / 20;
		userLikhe = ownRound(userLikhe);
		let tray_value = 0;
		let nm_tray_value = 0;
		let count = 0;


		let main_course = cartData.filter((cart)=>{
			if(cart.category == "main-course")
			{
				return cart;
			}
			return false;
		})
		 

		let non_main_course = cartData.filter((cart)=>{
			if(cart.category != "main-course" && cart.type == 'tray' )
			{
				return cart;
			} 
		})
		let single_non_main_course = cartData.filter((cart)=>{
			if(cart.category != "main-course" && cart.type == 'single' )
			{
				return cart;
			}
			return false;
		})

		if(userLikhe < TMP_totalServe)
		{ 

			if(main_course.length > 0 )
			{	
				let tray_value = 0;
				let count = 0;
				while(tray_value < userLikhe )
				{
					tray_value +=  0.5;
					main_course[count].qty +=  0.5;
					count++;
					if(main_course.length == count ){
						count = 0;
					}
				}
			}
				
		}
		else{
				
			//Execute when num of adults are greater then num of cart items
			if(jQuery(".product-name[data-product_type='tray']").length > 0){
				TMP_totalmainCourseServe = fieldAdult; 
				
				if(main_course.length > 0 )
				{	
					let tray_value = 0;
					let count = 0;  
					while(tray_value < userLikhe )
					{ 
					//	debugger;
						if(tray_value > 0 ){
							TMP_totalmainCourseServe -= main_course[count].serve;
						}
						
						tray_value +=  1;	
						if(TMP_totalmainCourseServe <= 10)
						{
							main_course[count].qty +=  0.5;
						}
						else{
							main_course[count].qty +=  1;
						} 
						count++; 
						if(main_course.length == count )
						{
							count = 0;
						} 
					}
				}
			}
		}
	
	if(non_main_course.length > 0 )
	{
		for(let index in non_main_course ){ 
			non_main_course[index].qty =  ownRound(fieldAdult / non_main_course[index].serve); 
		};
	}
	
	if(jQuery(".product-name[data-product_type='single']").length > 0){
		while(TMP_totalsingles <= fieldAdult){
		single_non_main_course.forEach(data => {
				if(data.type == 'single'){
					TMP_totalsingles += data.serve
					if(TMP_totalsingles <= fieldAdult){
						data.qty = data.qty + 1;
					}
				}
			});
		}
	}

	let updatedCartData = main_course.concat(non_main_course,single_non_main_course);
	return updatedCartData;
}
function ownRound(number){
	let a = Math.floor(number);
	let b = number % Math.floor(number);
			
		if(b == 0){
			b = 0;
		}else if(b <= 0.5){
			b = 0.5;
		}else{
			b = 1
		}
		return a + b; 
}


function getproductQty(){
	let productQtys = [];
		let qtycartData = getFinalCartData();
		console.log(qtycartData);
		qtycartData.forEach(data => {
			productQtys.push(data.qty);
		})
		console.log(productQtys);
		return productQtys;
}

function get_total_serve_tray(){
	//Generate array of item list serve peoples
	var TMP_arrayServe = 0;
	jQuery(".mini_cart_item").each(function(){
		if(jQuery(this).data('catname') == 'main-course'){
			TMP_arrayServe += 1;
		}
	})

	return TMP_arrayServe;
}


/*Set Quantity End*/

( function ( $ ) {
	"use strict";
   // Define the PHP function to call from here
	var data = {
	  'action': 'mode_theme_update_mini_cart'
	};
	$(window).on('load',function(){
		$('.cart-loader').addClass('active');
		
			$.post(
				woocommerce_params.ajax_url, // The AJAX URL
				data, // Send our PHP function
				function(response){
					$('#sidebar .widget_shopping_cart_content').html(response); // Repopulate the specific element with the new content
					$('.cart-loader').removeClass('active');
				}
			);
		
	})
    setTimeout(() => {
		$('.cart-loader').removeClass('active');
	}, 1000);
   }( jQuery ) );


jQuery(function($){
	$(".menu-item-price .amount .woocommerce-Price-currencySymbol").remove(); 
	
	$("#numop").on("submit", function (e) {
		e.preventDefault();
		var fieldAdult = jQuery('input[name="numberOfAdults"]').val();
		fieldAdult = parseInt(fieldAdult);
		var tmp_adults = fieldAdult;

		var fieldChild = jQuery('input[name="numberOfchildren"]').val();
		fieldChild = parseInt(fieldChild);
		var tmp_child = 0;
		
		var checkpizza = 0;

		if(jQuery('.deduct-pizza').prop("checked") == true){
			checkpizza = 1;
		}
		if(jQuery('.deduct-pizza').prop("checked") == false){
			tmp_child = fieldChild / 2;
			tmp_adults = tmp_adults + tmp_child ;
			tmp_adults = Math.round(tmp_adults);
		}
		
		
		$(".cart-loader").addClass("active");
		var tmp_qtys = getproductQty();
		var tmp_keys = getproductKey();
		
		let TMP_totalServe = get_total_serve_tray();
		
		let userLikhe = tmp_adults / 20;
		let a = Math.floor(userLikhe);
		let b = userLikhe % Math.floor(userLikhe);

		
		if(b == 0){
			b = 0;
		}else if(b <= 0.5){
			b = 0.5;
		}else{
			b = 1
		}
		userLikhe = a + b;
		if(userLikhe < TMP_totalServe){
			userLikhe = userLikhe * 2
		}
		
		if(userLikhe >= TMP_totalServe){
			var new_promise = new Promise((resolve, reject) =>{
				$.ajax({
					type: "POST",
					url: action_file_url,
					data: {pageajax:true,qty:tmp_qtys,key:tmp_keys,no_of_adults:fieldAdult,no_of_childs:fieldChild,checkpizza:checkpizza},
					success: function(data)
					{
						$(".menu-estimate").html(data);
						resolve(data)
						$(".cart-loader").removeClass("active");
						$(".checkout-btn").show();
						
						},
					error: function(error) {
						reject(error)
					}
				});
			});
		}else{
				$('.checkout-btn').hide();
				$(".cart-loader").removeClass("active");
				// $('input[name="nopsubmit"]').remove();
				var html = '<span class="error items-errors">You have entered less num of adults according to order Please remove items or increase num of adults or please enter an email address then CC Team will contact you.</span>';
				html += '<form id="email-customer"><label>Enter your email:</label>';
				html += '<input type="email" name="customer_email"/>';
				html += '<input type="submit" /></form>';
				$('.subtotal.menu-estimate').html(html);
		}
		
	});
	  
	$(document).on('submit','#email-customer',function(e){
		e.preventDefault();
		var email = $('input[name="customer_email"]').val();
		var fieldAdult = jQuery('input[name="numberOfAdults"]').val();
		var fieldChild = jQuery('input[name="numberOfchildren"]').val();
		var new_promise = new Promise((resolve, reject) =>{
			$.ajax({
				type: "POST",
				url: error_customer_email_url,
				data: {pageajax:true,email:email,fieldAdult:fieldAdult,fieldChild:fieldChild},
				success: function(data)
				{
					$('.cc-contactyou').remove();
					$("#email-customer").hide();
					$("#email-customer").after('<label class="cc-contactyou">CC Team will contact you later.</label>');
					
				},
				error: function(error) {
					reject(error)
				}
			});
		})
	})
  
	$(".remove-cart-menu").on("click", function () {
		
		var tmp_pdt_name = $(this).next(".product-name").text();		
		var tmp_keys = $(this).data("menu_key");
        var tmp_qtys = 0;
        $(".cart-loader").addClass("active");
        $(this).parent().remove();
		var new_promise = new Promise((resolve, reject) =>{
			$.ajax({
				type: "POST",
				url: action_file_url,
				data: {pageajax:true,qty:tmp_qtys,key:tmp_keys},
				success: function(data)
				{
					$('ul.cart_list').prepend('<li class="remove-item-notice">'+tmp_pdt_name+' has been removed from cart</li>');
                    resolve(data);
					$(".cart-loader").removeClass("active");
					$(document.body).trigger('wc_fragment_refresh');
				},
				error: function(error) {
					reject(error)
				}
			});
		});
	});
	$(".remove-all-mini-cart").on("click", function () {
		//debugger;
		var fieldAdult = jQuery('input[name="numberOfAdults"]').val();
        fieldAdult = parseInt(fieldAdult);
		$(".cart-loader").addClass("active");
		
		var tmp_keys = getproductKey();
        var tmp_qtys = 0;
        
        
        // console.log(a);
		var new_promise = new Promise((resolve, reject) =>{
			$.ajax({
				type: "POST",
				url: action_file_url,
				data: {pageajax:true,qty:tmp_qtys,key:tmp_keys},
				success: function(data)
				{
                    resolve(data);
                    $(".rightcart .overflow-sec").children().remove();
				},
				error: function(error) {
					reject(error)
				}
			});
		});
	});
	$(document).on('click','.set-servers',function(){
		var how_many_servers = $("#how_many_servers").val();
		var servers_hours	 = $("#server_hours").val();
		if(how_many_servers == '' || servers_hours == ''){
			$('.servers-error').remove();
			$("#how_many_servers_field").before('<span class="error nop-errors servers-error">Both fields are required.</span>')
		}else{
			var new_promise = new Promise((resolve, reject) =>{
				$.ajax({
					type: "POST",
					url: action_file_url,
					data: {pageajax:true,how_many_servers: how_many_servers,servers_hours:servers_hours},
					success: function(data)
					{
						$(".menu-estimate").html(data);
						resolve(data)
						$(".cart-loader").removeClass("active");
					},
					error: function(error) {
						reject(error)
					}
				});
			});
		}

	})
	
})