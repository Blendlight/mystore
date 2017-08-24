<?php
include_once("includes/php/user_info.php");
$SCRIPTS[] = "includes/js/product.js";
include_once("header.php");
?>
<style>
	.address_form
	{
		margin:20px 0px;
	}
</style>
<div class="container">
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
		}
		else
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
		
		 
		<div class="addresses_container" id="addresses_container">
			<?php show_address_select($conx, $user_id);?>
		</div>
		<div class="clearfix"></div>
		<div id="checkout_result"></div>
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
?>