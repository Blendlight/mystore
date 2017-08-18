<?php
include_once("includes/php/user_info.php");
if(!isset($_POST["submit"]))
{
	$SCRIPTS[] = "includes/js/product.js";
	include_once("header.php");
?>


<div class="container">
	<div id="checkout_result"></div>
	<div class="col-md-8">
		<?php 
	if(!$login)
	{
		?>
		<h3>You need to have account to place an order</h3>
		<p>
			Already have account?
			<a href="login.php" class="btn btn-primary">Login</a>
		</p>
		<p>
			Or create new account here     
			<a href="signup.php" class="btn btn-warning">Register</a>
		</p>
		<?php
	}else
	{ 
		if(isset($_GET["address"]))
		{
			$address_id = $_GET["address"];
			$address_query = mysqli_query($conx, "SELECT * from address WHERE address_user='$user_id' && address_id='$address_id'");
			if($address_query && $row=mysqli_fetch_array($address_query))
			{
		?>
		<div class="address_container table-bordered">
			<h3>Name</h3>
			<p><?= $row["address_name"]?></p>
			<h3>Address line1</h3>
			<p><?= $row["address_line1"]?></p>
			<h3>Address line1</h3>
			<p><?= $row["address_country"]?></p>
		</div>
		<a href="checkout.php" class="btn btn-info">Select Another</a>
		<?php
			}
		}else{

			//check if the user have already added addresses show select option for
			//selecting one from it
		?>
		<div class="addresses_container">
			<?php show_address_select($conx, $user_id);?>
		</div>
		<div class="address_form">
			<a class="btn btn-primary" data-toggle="collapse" data-target="#address_form">
				toggle form
			</a>
			<div class="collapse" id="address_form">
				<?php view_address_form();?>
			</div>
		</div>
		<?php
		}
		?>
		<?php
	}
		?>

	</div>
</div>
<?php
	include_once("footer.php");
}else
{
	if(isset($_POST["action"]))
	{
		$action = $_POST["action"];
		switch($action)
		{
			case "address_submit":
				//get all variables
				$address_name		=	$_POST["address_name"];
				$address_line1		=	$_POST["address_line1"];
				$address_line2		=	$_POST["address_line2"];
				$address_city		=	$_POST["address_city"];
				$address_state		=	$_POST["address_state"];
				$address_zip		=	$_POST["address_zip"];
				$address_country	=	$_POST["address_country"];
				$address_phone		=	$_POST["address_phone"];
				$address_query = "
									INSERT INTO
										address
									SET
										address_user='$user_id',
										address_name='$address_name',
										address_line1='$address_line1',
										address_line2='$address_line2',
										address_city='$address_city',
										address_state='$address_state',
										address_zip='$address_zip',
										address_country='$address_country',
										address_phone='$address_phone'";
				$address_result = mysqli_query($conx, $address_query);
				if($address_result && mysqli_affected_rows($conx))
				{
					echo "success:Address added successfully";
				}else
				{
					echo "fail:Address not added";
				}
				break;
		}
	}
}
?>
