<?php
if(!isset($_POST["product_submit"]))
{
    //add scripts in queue
    $SCRIPTS[] = "js/product_add.js";
    $page_title = "Add Product";
    include_once("header.php");
    include_once("includes/functions.php");
    include_once("../includes/php/conx.php");
?>
<!--<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>-->
<!--<script>tinymce.init({ selector:'textarea' });</script>-->
<!--<script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>-->

<div class="jumbotron">
</div>
<div class="container">
    <h2>Add new Product</h2>
    <form class="form" id="product_add_form" onsubmit="return false;" method="post" action="product_save.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="product_name">Name</label>
            <input type="text" name="product_name" class="form-control" placeholder="Product Name" id="product_name" required>
        </div>
        <div class="form-group">
            <label for="product_description">description</label>
            <textarea type="text" name="product_description" class="form-control" placeholder="papalaceholder" id="product_description"></textarea>
            <!--
<script>
CKEDITOR.replace('product_description');
</script>
-->
        </div>
        <div class="row">
            <div class="col-sm-4 form-group">
                <label for="product_category">Category</label>
                <SELECT type="text" name="product_category" class="form-control" id="product_category" required>
                    <option>--PRODUCT CATEGORY--</option>
                    <?php get_category_select_options($conx);?>
                </SELECT>
            </div>
            <div class="col-sm-4 form-group">
                <label for="product_price">Price</label>
                <input type="number" name="product_price" class="form-control" placeholder="papalaceholder" id="product_price" required>
            </div>
            <div class="col-sm-4">
                <label for="product_stock">Stock</label>
                <input type="number" name="product_stock" class="form-control" placeholder="papalaceholder" id="product_stock" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="product_date">date</label>
                <input type="date" name="product_date" class="form-control" placeholder="papalaceholder" id="product_date" >
            </div>
            <div class="col-md-4 form-group">
                <label for="product_country">Country</label>
                <select class="form-control" name="product_country" id="product_country">
                    <option value="">No</option>
                    <?php
    {
        {
            {
                {
                    {
                        {
                            get_country_select_options($conx);
                        }
                    }
                }
            }
        }
    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">

            </div>
        </div>
        <div class="img_input_container">
            <div class="img_input">
                <div class='row'>
                    <input data-ignore="true" type="file" class="product_imgs col-sm-2" onchange="load_images(this)" multiple />
                    <div class="col-sm-1">
                        <div class="close" onclick="input_remove(this)">&times;</div>
                    </div>
                </div>
                <div class="product_img_container">

                </div>
            </div>
            <a href="#" class='add_more'>Add more</a>
        </div>
        <input name="product_submit" class="btn btn-primary" type="submit" value="Add Product">
    </form>
</div>
<?php
    include_once("footer.php");
?>
<?php
}else
{
    include("../includes/php/conx.php");
    function group_files($arr)
    {
        $files = array();
        for($i=0;$i<count($arr["name"]);$i++)
        {
            $files[] = array("name"=>$arr["name"][$i], 
                             "type"=>$arr["type"][$i],
                             "tmp_name"=>$arr["tmp_name"][$i],
                             "size"=>$arr["size"][$i],
                             "error"=>$arr["error"][$i]
                            );
        };

        return $files;
    }

    $product_name = $_POST["product_name"];
    $product_description = $_POST["product_description"];
    $product_category = $_POST["product_category"];
    $product_price = $_POST["product_price"];
    $product_stock = $_POST["product_stock"];
    $product_date = $_POST["product_date"];
    $product_country = $_POST["product_country"];
    //insert fileds in to database
    $query = mysqli_query($conx,
                          "INSERT INTO `ecommerce`.`product` (
	`product_id`, `product_name`, `product_category`, 
	`product_price`, `product_stock`, 
	`product_date`, `product_country`, 
	`product_description`
) 
VALUES 
	(
		NULL, '$product_name', '$product_category', '$product_price', 
		'$product_stock', '$product_date', '$product_country', '$product_description'
	)"
                         );

    if( !($query && mysqli_affected_rows($conx)>0))
    {
        $log .= '\n'.mysqli_error($conx);
        echo "failed: can't insert into product_table";
        exit;
    }
    //save files to the harddrive and create values for inserting images name in databass
    if(isset($_FILES["product_images"])){
        $files = group_files($_FILES["product_images"]);
        $values = "";
        if(count($files)>0){

            for($i=0;$i<count($files);$i++)
            {
                $file = $files[$i];
                $name = rand(0, time())."fff".rand(0, time()).$file["name"];
                $tmp_name = $file["tmp_name"];
                move_uploaded_file($tmp_name, "../images/".$name);
                $values .= "(NULL, '".mysqli_insert_id($conx)."', '$name', '".$file["name"]."')";
                if($i!=count($files)-1)
                {
                    $values .= ",";
                }
            }

            //join values with query
            $query = "
INSERT INTO `ecommerce`.`product_image` (
	`pr_img_id`, `product_id`, `pr_img_name`, 
	`pr_img_description`
) 
VALUES 
	$values
";
            //run query of saving images name to database
            $query = mysqli_query($conx, $query);
            if(!($query && mysqli_affected_rows($conx)>0) )
            {
                echo "failed: can't insert into product_images_table";
                exit;
            }else
            {
                echo "success:";
            }
        }
    }
    echo "success:";
    exit;
}
?>
