//script for product.php

//function after adding product to cart
$(function()
  {
	$add_to_cart_form = $("#add_to_cart_form");
	$product_action_result = $("#product_action_result");
	$add_to_cart_form.on("submit", function(evt){
		evt.preventDefault();
		$this = $(this);

		$p_quantity = $this.find("input[name=product_quantity]");
		p_quantity = $p_quantity.val();

		$p_id = $this.find("input[name=product_id]");
		$p_price = $this.find("input[name=product_price]");
		$p_stock = $this.find("input[name=product_stock]");
		$submit = $this.find("input[type=submit]");

		p_id = $p_id.val();
		p_price = $p_price.val();
		p_stock = $p_stock.val();


		$.ajax(
			{
				method:"post",
				url:"cart.php",
				data:"submit=submit&action=add&pid="+p_id+"&pquantity="+p_quantity,
				start:function()
				{
					$submit.addClass("disabled");
				},
				success:function(data_return)
				{
					type = data_return.match(/[\w]+:/);
					if(type)
					{
						type = type[0];
						type = type.toLowerCase();
						switch(type)
								{
							case "fail:":
								var alertbox = $("<div class='alert alert-danger'>"+data_return+"<div class='close' data-dismiss='alert'>&times;</div></div>");
								$product_action_result.append(alertbox);
								break;
							case "success:":
								var alertbox = $("<div class='alert alert-success'>"+data_return+"<div class='close' data-dismiss='alert'>&times;</div></div>");
								$product_action_result.append(alertbox);
								update_cart_small();
								update_cart_subtotal();
								break;
							default:
								console.log("Unknown status returned", data_return);
						}
					}
					else
					{
						console.log(data_return);
					}

					$submit.removeClass("disabled");
				}
			}
		);

	});
});

//script for mycart.php

//FUNCTION for removing item from cart
$(function()
  {
	$cart_action_result = $("#cart_action_result");
	$btn_product_remove_cart = $(".btn_product_remove_cart");
	$product_quantity_input = $(".product_quantity_input");

	$btn_product_update_cart = $(".btn_product_update_cart");

	$btn_product_remove_cart.on("click", function(){

		$this = $(this);
		pid = $this.attr("data-id");
		$cart_element = $this.closest(".cart-element");


		$.ajax(
			{
				url:"cart.php",
				type:"post",
				data:"submit=submit&action=remove&pid="+pid,
				success:function(data_return)
				{
					type = data_return.match(/[\w]+:/);
					if(type)
					{

						type = type[0];
						type = type.toLowerCase();
						console.log(type);
						switch(type)
								{
							case "fail:":
								var alertbox = $("<div class='alert alert-danger'>"+data_return+"<div class='close' data-dismiss='alert'>&times;</div></div>");
								$cart_action_result.append(alertbox);
								break;
							case "success:":
								var alertbox = $("<div class='alert alert-success'>"+data_return+"<div class='close' data-dismiss='alert'>&times;</div></div>");
								$cart_action_result.append(alertbox);
								$cart_element.slideUp(500).remove();
								update_cart_subtotal();
								update_cart_small();
								break;
							default:
								console.log("Unknown status returned", data_return);
						}
					}
					else
					{
						console.log(data_return);
					}
				}
			}
		);




	});

	$product_quantity_input.on("change", function(evt){
		$this = $(this);
		$cart_element = $this.closest(".cart-element");
		$btn_update = $cart_element.find(".btn_product_update_cart");
		ovalue = $this.attr("data-ovalue");
		value = $this.val();
		if(ovalue!=value)
		{
			if($btn_update.hasClass("hidden"))
			{
				$btn_update.removeClass("hidden");    
			}

		}else
		{
			if(!$btn_update.hasClass("hidden"))
			{
				$btn_update.addClass("hidden");    
			}
		}

	});

	$btn_product_update_cart.on("click", function(evt){
		$this = $(this);
		pid = $this.attr("data-id");
		$cart_element = $this.closest(".cart-element");

		var $product_total = $cart_element.find(".product_total");
		var $product_price = $cart_element.find(".product_price");
		var product_price = $product_price.attr("data-price");

		$cart_quantity_input = $cart_element.find(".product_quantity_input");
		value = $cart_quantity_input.val();
		$this.addClass("disabled");
		$result = $cart_element.find(".result");

		$.ajax(
			{
				url:"cart.php",
				method:"post",
				data:"submit=submt&action=update&pid="+pid+"&pquantity="+value,
				success:function(data_return)
				{
					type = data_return.match(/[\w]+:/);
					if(type)
					{
						type = type[0];
						type = type.toLowerCase();
						console.log(type);
						switch(type)
								{
							case "fail:":
								var alertbox = $("<div class='alert alert-danger'>"+data_return+"<div class='close' data-dismiss='alert'>&times;</div></div>");
								$result.append(alertbox);
								break;
							case "success:":
								var alertbox = $("<div class='alert alert-success'>"+data_return+"<div class='close' data-dismiss='alert'>&times;</div></div>");
								$result.append(alertbox);
								$cart_quantity_input.attr("data-ovalue", value);
								$this.addClass("hidden");
								$this.removeClass("disabled");
								$product_total.html( parseInt(product_price)*value);
								break;
							default:
								console.log("Unknown status returned", data_return);
						}
					}
					else
					{
						console.log(data_return);
					}
				}
			});

		update_cart_subtotal();

	});
});


$(function()
  {
	$stock = $("#product_quantity");
	$stock.on("change", function(){
		val = $stock.val();
		min = $stock.attr("min");
		min = parseInt(min);
		max = $stock.attr("max");
		max = parseInt(max);
		if(val>max)
		{
			console.log("Can't select "+val+" items only "+max+" items in stock");
		}
		if(val<min)
		{
			console.log("Can't select "+val+" minimum selection value is "+min);
		}
		$stock.val(Math.min(max, Math.max(min, $stock.val())));
	})
	$range_change = $(".range_change");
	$range_change.on("click", function(){

		$product_quantity_input = $(this).closest(".input-group").find(".product_quantity_input");

		value = parseInt($(this).attr("data-value"));

		stock_val = parseInt($product_quantity_input.val());

		min = $product_quantity_input.attr("min");

		max = $product_quantity_input.attr("max");

		newvalue = stock_val+value;

		newvalue = Math.min(max, Math.max(min, newvalue));

		$product_quantity_input.val(newvalue);
		$product_quantity_input.trigger("change");
		//update button state

		$range_change2 = $(this).closest(".input-group").find(".range_change");;

		$.each($range_change2, function(i, el)
			   {
			$el = $(el);
			var v = $el.attr("data-value");
			if(newvalue == max)
			{
				if(v>0 && !$el.hasClass("disabled"))
				{
					$el.addClass("disabled");
				}

			}else if(newvalue<max)
			{
				if(v>0 && $el.hasClass("disabled"))
				{
					$el.removeClass("disabled");
				}
			}

			if(newvalue == min)
			{
				if(v<0 && !$el.hasClass("disabled"))
				{
					$el.addClass("disabled");
				}

			}else if(newvalue>min)
			{
				if(v<0 && $el.hasClass("disabled"))
				{
					$el.removeClass("disabled");
				}
			}
		});

	});
});


$(function()
  {
	$sub_total = $(".sub_total");
	$cart_small = $("#cart_small");
});

//function for address
$(function(){
	$address_form	=  $("#address_form");
	$address_name	=  $("#address_name");
	$address_line1	=  $("#address_line1");
	$address_line2	=  $("#address_line2");
	$address_city	=  $("#address_city");
	$address_state	=  $("#address_state");
	$address_zip	=  $("#address_zip");
	$address_country=  $("#address_country");
	$address_phone	=  $("#address_phone");
	$address_submit	=  $("#address_submit");
	$checkout_result = $("#checkout_result");
	$address_container = $("#addresses_container");




	$address_form.on("submit", function(evt){
		evt.preventDefault();

		address_name 		=	$address_name.val();
		address_line1 		= 	$address_line1.val();
		address_line2 		= 	$address_line2.val();
		address_city 		= 	$address_city.val();
		address_state 		= 	$address_state.val();
		address_zip 		= 	$address_zip.val();
		address_country	 	= 	$address_country.val();
		address_phone	 	= 	$address_phone.val();

		fdata = `address_name=${address_name}&address_line1=${address_line1}&address_line2=${address_line2}&address_city=${address_city}&address_state=${address_state}&address_zip=${address_zip}&address_country=${address_country}&address_phone=${address_phone}&submit=submit&action=address_submit`;

		$.ajax({
			url:"address_add.php",
			data:fdata,
			type:"post",
			success:function(returned_data)
			{

				if(returned_data.match(/success:/))
				{
					$checkout_result.append($(`<div class='alert alert-success'>${returned_data}<div class='close' data-dismiss='alert'>&times;</div></div>`));
					//update address list
					update_address_list();
					$address_form.toggleClass("in");
				}else if(returned_data.match(/fail:/))
				{
					$checkout_result.append($(`<div class='alert alert-danger'>${returned_data}</div>`));
				}
			}
		});
	});
});



function update_cart_subtotal()
{
	$sub_total = $(".sub_total");
	$.each($sub_total, function(i, el){
		$el = $(el);
		$el.html("");
		$el.append($("<img src='images/load.gif' class='loader'/>"));
	});
	setTimeout(function(){
		$.ajax(
			{
				url:"get_data_ajax.php",
				type:"post",
				data:"get_data=cart_info",
				success:function(data_return)
				{
					data = JSON.parse(data_return);
					$.each($sub_total, function(i, el){
						$el = $(el);
						$el.html("$"+data.subtotal);
					});
				}
			})
	}, 1000);
}

function update_cart_small()
{
	$.ajax({
		url:"get_data_ajax.php",
		type:"post",
		data:"get_data=cart_small",
		success:function(data_return)
		{
			$cart_small.html(data_return);
		}

	});
}

function update_address_list()
{
	$.ajax({
		url:"get_data_ajax.php",
		type:"post",
		data:"get_data=address_list",
		success:function(data_return)
		{
			$address_container.html(data_return);
		}

	});
}