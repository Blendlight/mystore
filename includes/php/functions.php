<?php

class Product
{
	public $id='';
	public $name='';
	public $category='';
	public $category_id='';
	public $description='';
	public $price='';
	public $stock='';
	public $dateAdded='';
	public $country='';
	public $country_id='';
	public $images = array();

	public function __construct($conx=null, $row=null)
	{
		if($row != null)
		{
			if(is_int($row))
			{
				$product_select_query = "SELECT
                                            product.*, category.category_name as category, country.name as country
                                        FROM
                                            product
                                        JOIN
                                            category on category.category_id=product.product_category
                                            JOIN
                                            country on country.id=product.product_country

                                        WHERE
                                            product.product_id='$row'
                                        LIMIT 1";
				$product_result = mysqli_query($conx, $product_select_query);
				if($product_result && mysqli_num_rows($product_result)>0)
				{
					$row = mysqli_fetch_array($product_result);
					$this->set_data($conx, $row);
				}
			}else if(is_array($row))
			{
				$this->set_data($conx, $row);    
			}

		}
	}

	public function set_data($conx, $row)
	{
		$this->id = $row["product_id"];   
		$this->name = $row["product_name"];
		$this->category_id = $row["product_category"];
		$this->category = $row["category"];
		$this->description = $row["product_description"];
		$this->price = $row["product_price"];
		$this->stock = $row["product_stock"];
		$this->dateAdded = $row["product_date"];
		$this->country_id = $row["product_country"];
		$this->country = $row["country"];

		$images_result = mysqli_query($conx, "
                                            SELECT
                                                *
                                            FROM
                                                product_image
                                            WHERE
                                                product_id='$this->id'");
		if($images_result && mysqli_num_rows($images_result)>0)
		{
			while($images_data = mysqli_fetch_array($images_result,1))
			{
				$this->images[] = array(
					"id"=>$images_data["pr_img_id"],
					"name"=>$images_data["pr_img_name"],
					"description"=>$images_data["pr_img_description"]
				);
			}
		}
	}

	public function display()
	{
?>
<div class="product">
	<div class="product_img">
		<img class='img-responsive' src="images/<?=$this->images[0]["name"]?>" alt="">
	</div>
	<div class="product_name">
		<a href="product.php?pid=<?=$this->id?>">
			<?=$this->name?>
		</a>
	</div>
	<div class="row product_description">
		<div class="col-sm-6">
			<div class="product_price">
				Price $<?=$this->price?>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="product_stock  <?php if($this->stock<=0){echo "alert-danger";}?>">
				<?php
	 if($this->stock<=0)
	 {
				?>
				Out of stock.
				<?php
	 }else
	 {
				?>
				<?=$this->stock?> in stock
				<?php
	 }
				?>
			</div>
		</div>
	</div>
</div>
<?php
	}
}

function is_login()
{
	if(isset($_SESSION["user_uname"], $_SESSION["user_id"]))
	{
		return true;
	}
	return false;   
}

function is_parent($conx, $parent)
{
	$query = "SELECT
*
FROM
category
WHERE
parent='$parent'
";
	$query = mysqli_query($conx, $query);
	if($query && mysqli_num_rows($query)>0)
	{
		return true;
	}

	return false;
}

function get_categories($conx, $parent)
{
	$childs = [];
	$query = "SELECT
*
FROM
category
WHERE
parent='$parent'
";
	$query = mysqli_query($conx, $query);
	if($query && mysqli_num_rows($query)>0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$childs[] = array(
				"data"=>array(
					"id"=>$row["category_id"],
					"name"=>$row["category_name"],
					"description"=>$row["category_description"],
					"parent"=>$row["parent"]
				),
				"childs"=> get_categories($conx, $row["category_id"])
			);
		}
	}
	return $childs;
}

function make_select_query($elements)
{
	$result = "";
	$len = count($elements);
	for($i=0;$i<$len;$i++)
	{
		$element = $elements[$i];
		$data = $element["data"];
		$id = $data["id"];
		$result .= " product.product_category='$id' || ";
		$result .= make_select_query($element["childs"]);
	}

	return $result;
}

function unset_cookie($name)
{
	setcookie($name, "", time()-60*60*24);
}


function item_add_to_cart_cookie($stock, $p, $q)
{
	if(isset($_COOKIE["cart"]))
	{
		$cart = $_COOKIE["cart"];
		$cart = json_decode($cart);
	}else
	{
		$cart = array();
	}
	$find = false;
	foreach($cart as $k=>$v)
	{
		if($v[0] == $p)
		{
			$find =  true;
			$qo = intval($v[1]);
			$qt = $qo+$q;
			if($stock<$qt)
			{
				echo "fail: You have already selected $qo and only $stock items are in stock can't add $q more items of this product";
				return;
			}
			$cart[$k] = array($v[0], $qt);
			echo "success: $qt items of this product is in your cart";
		}
	}

	if(!$find)
	{
		$cart[] = array($p, $q);
		echo "success: $q items of this product is added to your cart";
	}

	setcookie("cart", json_encode($cart));
}


function item_add_to_cart_table($conx, $uid, $stock, $p, $q)
{
	//first chech the item is already on cart or not
	//if it is on cart update the quantity
	//else add a new record to cart table

	$product_cart_check_result = mysqli_query($conx, "SELECT * FROM cart WHERE cart_user='$uid' && cart_product='$p'");

	//if already exist
	if(mysqli_num_rows($product_cart_check_result)>0)
	{
		$cart_data = mysqli_fetch_array($product_cart_check_result);
		$cart_id = $cart_data["cart_id"];
		$cart_quantity = $cart_data["cart_quantity"];
		$qo = intval($cart_quantity);

		$qt = $qo + $q;
		if($qt>$stock)
		{
			$msg = "fail: Can't Add $q Items into cart $qo elements already in cart only have $stock elements in stock";
			echo $msg;
			return $msg;
		}else
		{
			$pro_cart_update_query = "UPDATE cart SET cart_quantity='$qt' WHERE cart_id='$cart_id'";
			$pro_cart_update_result = mysqli_query($conx, $pro_cart_update_query);
			if(mysqli_affected_rows($conx)>0)
			{
				$msg =  "success: $q Items added to cart";
				echo $msg;
				return $msg;
			}else
			{
				$msg = "fail: cant't update the cart";
				echo $msg;
				return $msg;
			}
		}

	}else//if not exist
	{
		//set stock level and quantity
		if($stock>=$q)
		{
			$pro_cart_insert_query = "
                INSERT INTO
                `ecommerce`.`cart` (
	                               `cart_id`,
                                   `cart_user`,
                                   `cart_product`, 
                                    `cart_quantity`
                                    )
                VALUES 
	                               (
                                   NULL,
                                   '$uid',
                                   '$p',
                                   '$q')
        ";
			$pro_cart_insert_result = mysqli_query($conx, $pro_cart_insert_query);
			if(mysqli_affected_rows($conx)>0)
			{
				$msg = "success: $q Items added to cart";
				echo $msg;
				return $msg;
			}
		}else
		{
			$msg = "fail: Can't Add $q Items into cart because only have $stock products in stock";
			echo $msg;
			return $msg;
		}
	}
}

function item_remove_from_cart_cookie($p)
{
	$cart = $_COOKIE["cart"];
	$cart = json_decode($cart, true);
	foreach($cart as $k=>$v)
	{
		if($v[0] == $p)
		{
			array_splice($cart, $k, 1);
		}
	}
	setcookie("cart", json_encode($cart));
	echo "success: Product removed from cart";
}

function item_remove_from_cart_table($conx, $uid, $p)
{
	$pro_cart_remove_query = "DELETE FROM `ecommerce`.`cart` WHERE `cart`.`cart_product` = '$p'";
	$pro_cart_remove_result = mysqli_query($conx, $pro_cart_remove_query);
	if( mysqli_affected_rows($conx) > 0 )
	{
		echo "success: Product removed from cart";
	}else
	{
		echo "fail: Can't  remove product from cart";
	}
}


function get_selected_product_value($ps, $cart)
{
	foreach($cart as $c)
	{
		$p = $c[0];
		if($ps == $p)
		{
			return $c[1];
		}
	}
	return null;
}

function get_cart_data($conx, $login, $user_id=null)
{
	//if login get cart info from database
	if($login)
	{
		$cart_select_query = "SELECT * FROM cart WHERE cart_user='$user_id'";
		$cart_select_result = mysqli_query($conx, $cart_select_query);

		if(mysqli_num_rows($cart_select_result)>0)
		{

			$products = array();
			while($row = mysqli_fetch_array($cart_select_result))
			{
				$pid = $row["cart_product"];
				$pid = intval($pid);

				$product = new Product($conx, $pid);
				$product->selected = $row["cart_quantity"];
				$products[] = $product;
			}

			return $products;
		}else
		{
			return null;
		}
	}else // if not login get data from cookies
	{
		if(isset($_COOKIE["cart"]))
		{
			$cart = $_COOKIE["cart"];
			$cart = json_decode($cart, true);
			$products = array();
			$cart_products_select = "SELECT
                                    product.*, category.category_name as category, country.name as country
                                FROM
                                    product
                                JOIN
                                    category on category.category_id=product.product_category
                                    JOIN
                                    country on country.id=product.product_country

                                WHERE ";
			$i = 0;
			$count = count($cart);
			foreach($cart as $c)
			{
				$p = $c[0];
				$cart_products_select .= " product_id='$p' ";
				if($i++<$count-1)
				{
					$cart_products_select .= "||";
				}
			}

			$i = 0;
			$cart_products_result = mysqli_query($conx, $cart_products_select);
			if($cart_products_result && mysqli_num_rows($cart_products_result)>0)
			{
				while($product_data = mysqli_fetch_array($cart_products_result))
				{
					$product = new Product($conx, $product_data);
					$product->selected = get_selected_product_value($product->id, $cart);
					$products[] = $product;
				}
			}else
			{

			}
			return $products;
		}else
		{
			return null;
		}
	}
}

function update_cart_cookie($stock, $p, $q)
{
	if(isset($_COOKIE["cart"]))
	{
		$cart = $_COOKIE["cart"];
		$cart = json_decode($cart);
	}else
	{
		//this will not probably happen
		echo "failed:No items in cart";
		exit;
	}

	$find = false;
	foreach($cart as $k=>$v)
	{
		if($v[0] == $p)
		{
			$find =  true;
			if($stock<$q)
			{
				echo "fail: Can't select $q items because only $stock items in stock";
				return;
			}
			$cart[$k] = array($v[0], $q);
			echo "success: Cart updated";
		}
	}

	if(!$find)
	{
		echo "faild:Product not in cart";
		//change_1.0 exit;
	}else{
		setcookie("cart", json_encode($cart));
	}
	//change_1.0 setcookie("cart", json_encode($cart));

}

function update_cart_table($conx, $uid, $stock, $p, $q)
{
	//first check the product is in cart or wrong product info is sent
	$pro_select_result = mysqli_query($conx, "SELECT * FROM cart WHERE cart_product='$p' LIMIT 1");
	if(mysqli_num_rows($pro_select_result)>0)
	{
		if(intval($q) > intval($stock))
		{
			$msg = "fail: Can't select $q items because only $stock items in stock";
			echo $msg;
			return;
		}

		$pro_update_result = mysqli_query($conx, "UPDATE cart SET cart_quantity='$q' WHERE cart_product='$p'");
		if(mysqli_affected_rows($conx)>0)
		{
			echo "success: Cart updated";
		}else
		{
			echo "fail: Failed to update cart";
		}

	}else
	{
		echo "faild:Product not in cart";
	}
}

function get_cart_info($products_in_cart, $login=false)
{
	if($products_in_cart)
	{
		if($login)
		{

			$total_items = 0;
			$subtotal = 0;

			foreach($products_in_cart as $pc)
			{

				$total_items += intval($pc->selected);
				$subtotal += ($pc->price*$pc->selected);
			}
			return array(
				"total_items"=>$total_items,
				"subtotal"=>$subtotal
			);

		}else
		{
			$total_items = 0;
			if(isset($_COOKIE["cart"]))
			{
				$cart = $_COOKIE["cart"];
				$cart = json_decode($cart, true);
				foreach($cart as $k=>$v)
				{
					$total_items += $v[1];
				}
			}
			$subtotal = 0;
			foreach($products_in_cart as $pc)
			{
				$subtotal += ($pc->price*$pc->selected);
			}

			return array(
				"total_items"=>$total_items,
				"subtotal"=>$subtotal
			);
		}
	}
}


function show_cart_small($products_in_cart, $login)
{
	$products_in_cart_count = count($products_in_cart);
	$cart_info = get_cart_info($products_in_cart, $login);
?>
<a  data-toggle="dropdown" class="btn btn-default">
	<span class='glyphicon glyphicon-shopping-cart'></span>
	<div class="badge"><?=$cart_info["total_items"]?></div>
</a>

<div class="dropdown-menu" >
	<h1>Cart</h1>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Price</th>
				<th>Quantity</th>
			</tr>
		</thead>
		<tbody>
			<?php
		$i = 0;
	for($i;$i<min($products_in_cart_count, 5);$i++)
	{
		$pc = $products_in_cart[$i];
			?>
			<tr>
				<td><?=$pc->name?></td>
				<td><?=$pc->price?></td>
				<td><?=$pc->selected?></td>
			</tr>
			<?php
	}
	if($i<$products_in_cart_count)
	{
			?>
			<tr class='text-center'>
				<td colspan="3">And <?=(intval($products_in_cart_count)-$i)?> more</td>
			</tr>
			<?php
	}
			?>
		</tbody>
	</table>
	<h3>Subtotal: <storng class="sub_total">$<?=$cart_info["subtotal"]?></storng></h3>
	<a href="mycart.php" class="btn btn-primary">
		Cart
	</a>
	<a href="checkout.php" class="btn btn-warning">
		Checkout
	</a>
</div>

<?php
}

function check_user_registration($conx, $uname, $email)
{
	$uname = trim($uname);
	$email = trim($email);

	$result = array();

	$query = mysqli_query($conx, "SELECT * FROM user WHERE user.user_uname='$uname'");
	if($query && mysqli_num_rows($query)>0)
	{
		$result[] =  "true";
	}else
	{
		$result[] =  "false";
	}

	$query = mysqli_query($conx, "SELECT * FROM user WHERE user.user_email='$email'");
	if($query && mysqli_num_rows($query)>0)
	{
		$result[] = "true";
	}else
	{
		$result[] =  "false";
	}
	return json_encode($result);
}


function set_login_session($conx, $user_uname, $user_id)
{
	@session_start();
	$_SESSION["user_uname"] = $user_uname;
	$_SESSION["user_id"] = $user_id;

	//if cookie exist of cart transfer it to the database table cart and remove cookie of cart 
	move_cart_cookie_data_to_table($conx);
}

function move_cart_cookie_data_to_table($conx, $uid)
{
	$msg = "";
	$cart_data = get_cart_data($conx, false);
	foreach($cart_data as $pc)
	{
		$pid = $pc->id;
		$pselected = $pc->selected;
		$pstock = $pc->stock;
		$m = item_add_to_cart_table($conx, $uid, $pstock, $pid, $pselected);
		$alert_type = "";
		if(preg_match_all("/success:/", $m))
		{
			$alert_type = "success";
		}else{
			$alert_type = "danger";
		}
		$msg .= "<div class='alert alert-$alert_type'><h4>".$pc->name."</h4>$m <div class='close' data-dismiss='alert'> &times; </div> </div>";
	}
	setcookie("cart", "", time()-60*60*24);
	return $msg;
}



function view_address_form()
{
?>
<form id="address_form">
	<div class="form-group">  
		<label for="name">name</label>      
		<input type="text" class="form-control" id="address_name" value="Somevalue" required>      
	</div>
	<div class="form-group">      
		<label for="address_line1">address_line1</label>      
		<input type="text" class="form-control" id="address_line1" value="Somevalue" required>       
	</div>
	<div class="form-group">     
		<label for="address_line2">address_line2</label> 
		<input type="text" class="form-control" id="address_line2" >       
	</div>
	<div class="form-group">  
		<label for="city">city</label>       
		<input type="text" class="form-control" id="address_city" value="Somevalue" required>    
	</div>
	<div class="form-group">     

		<label for="state">state</label>        
		<input type="text" class="form-control" id="address_state" value="Somevalue" required>     
	</div>
	<div class="form-group">  
		<label for="zip">zip</label>    
		<input type="text" class="form-control" id="address_zip" value="Somevalue" required>   
	</div>

	<div class="form-group">      
		<label for="country">country</label>      
		<input type="text" class="form-control" id="address_country" value="Somevalue" required>      
	</div>
	<div class="form-group">  
		<label for="phone">phone</label>        
		<input type="text" class="form-control" id="address_phone" value="Somevalue" required>   
	</div>
	<input class="btn btn-primary" type="submit" name="address_submit" value="Add">
</form>
<?php
}

function show_address_select($conx, $uid)
{
	$query = mysqli_query($conx, "SELECT * FROM address where address_user='$uid'");
	$c = 1;
	if($query && mysqli_num_rows($query)>0)
	{
		while($row = mysqli_fetch_array($query))
		{
?>
<div class="address_container col-md-4">
	<a href="checkout.php?address=<?= $row["address_id"]?>">
		<h4><?= $row["address_name"]?></h4>
		<p><?= $row["address_line1"]?></p>
		<p><?= $row["address_country"]?></p>
	</a>
	<p>
		<button class="btn btn-danger">Remove</button>
	</p>
</div>
<?php
			if($c++%3 == 0)
			{
				echo "<div class='clearfix'></div>";
			}
		}
	}else
	{
	}
}
?>