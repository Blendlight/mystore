<?php
$STYLES[] = "includes/css/product.css";
?>

<?php $page_title="Home";?>
<?php include("header.php");?>
<div class="container">
	<div class="msg">
		<?php
		if(isset($_GET["msg"]))
		{
			echo $_GET["msg"];
		}
		?>
	</div>
	<div class="col-md-3">
		<h3>Categories</h3>
		<ul>
			<?php
			$main_categories = array();
			$cat_select_query = "
                                        SELECT
                                            *
                                        FROM
                                            category
                                        WHERE
                                            status=1 && parent is NULL";
			$cat_result = mysqli_query($conx, $cat_select_query);
			if($cat_result && mysqli_num_rows($cat_result)>0)
			{
				while($cat_row = mysqli_fetch_array($cat_result))
				{
					$cat_id = $cat_row["category_id"];
					$cat_name = $cat_row["category_name"];
					$main_categories[] = array("id"=>$cat_id,"name"=>$cat_name);
			?>
			<li data-id='<?=$cat_id?>'><a href="products.php?catid=<?=$cat_id?>"><?=$cat_name?></a></li>
			<?php
				}

			}else{
				echo "Categories not found";
			}
			?>
		</ul>
	</div>
	<div class="col-md-6">
		<div>
			<?php
			foreach($main_categories as $main_category)
			{
				$cat_id = $main_category["id"];
				$cat_name = $main_category["name"];
			?>
			<div class="products-container">
				<h3><?=$cat_name?></h3>
				<hr>
				<div class="row">
					<?php
				$childs = get_categories($conx, $cat_id);

				$select_query = "SELECT
                        product.*, category.category_name as category, country.name as country
                    FROM
                        product
                    JOIN
                        category on category.category_id=product.product_category
                        JOIN
                        country on country.id=product.product_country

                    WHERE";
				$select_query .= make_select_query($childs)." product.product_category ='$cat_id'";

				$select_result = mysqli_query($conx, $select_query);
				if($select_result)
				{
					$count = 0;
					while($row = mysqli_fetch_array($select_result,1))
					{
						$product = new Product($conx, $row);
						//                                $product->display();
						if($count++%3==0)
						{
							echo "<div class='clearfix'></div>";
						}
					?>
					<div class="col-md-4">
						<?php
						$product->display();
						?>
					</div>
					<?php
					}
				}
					?>
				</div>
			</div>
			<?php
			}
			?>
		</div>
	</div>
</div>

<?php include("footer.php");?>
