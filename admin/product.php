<?php
if(!isset($_POST["product_submit"]))
{
    //add scripts in queue
    $SCRIPTS[] = "js/product_add.js";
?>
<?php
    if(isset($_GET["pid"]))
    {
        $pid = $_GET["pid"];
    }else{
        header("location: products.php");
    }
    $action = isset($_GET['action'])?$_GET['action']:'show';
?>
<?php
    $page_title = strtoupper($action[0]).substr($action, 1)." Product";
    include("../includes/php/conx.php");
    include("includes/functions.php");
    include("header.php");
?>
<?php 
    $product = get_product($conx, "product_id", $pid);
?>
<div class="container">
    <?php
    if($action=="remove")
    {
    ?>
    <div>
        <p>
            Do you want to remove Product <strong><?=$product->name?></strong>
        </p>
        <p>
            These Orders include this product and are not compleated<br/>
            list of products<br/>
            action on Order<br/>
        </p>
        <p>
            <a class="btn btn-danger btn-product-remove" data-id='<?=$pid?>'>Remove</a>
            <a href="products.php" class="btn btn-primary">Cancel</a>
        </p>
    </div>
    <?php
    }
    elseif($action=="edit")
    {
    ?>
    <div class="container">
        <h2>Edit Product <strong><?=$product->name?></strong></h2>
        <form class="form" id="product_edit_form" onsubmit="return false;" method="post" action="product_save.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_name">Name</label>
                <input type="text" name="product_name" class="form-control" placeholder="Product Name" id="product_name"
                       value="<?=$product->name?>"
                       required>
            </div>
            <div class="form-group">
                <label for="product_description">description</label>
                <textarea type="text" name="product_description" class="form-control" placeholder="papalaceholder" id="product_description"><?=$product->description?></textarea>
            </div>
            <div class="row">
                <div class="col-sm-4 form-group">
                    <label for="product_category">Category</label>
                    <SELECT type="text" name="product_category" class="form-control" id="product_category" required>
                        <option>--PRODUCT CATEGORY--</option>
                        <?php get_category_select_options($conx, $product->category_id);?>
                    </SELECT>
                </div>
                <div class="col-sm-4 form-group">
                    <label for="product_price">Price</label>
                    <input type="number" name="product_price" class="form-control" placeholder="papalaceholder" id="product_price"
                           value="<?=$product->price?>"
                           required>
                </div>
                <div class="col-sm-4">
                    <label for="product_stock">Stock</label>
                    <input type="number" name="product_stock" class="form-control" placeholder="papalaceholder" id="product_stock"
                           value="<?=$product->stock?>"
                           required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="product_date">date</label>
                    <input type="date" name="product_date" class="form-control" placeholder="papalaceholder" id="product_date"
                           value="<?=explode(" ",$product->dateAdded)[0]?>"
                           >
                </div>
                <div class="col-md-4 form-group">
                    <label for="product_country">Country</label>
                    <select class="form-control" name="product_country" id="product_country">
                        <option value="">No</option>
                        <?php
        get_country_select_options($conx, $product->country_id);
                        ?>
                    </select>
                </div>
                <div class="col-md-4">

                </div>
            </div>
            <div class="image_container">
                <h3>Current Images of product</h3>
                <p>Select those images you want to delete</p>
                <div class="row">
                    <?php
        for($i=0;$i<count($product->images);$i++)
        {
                    ?>
                    <div class="col-md-3">
                        <input type='checkbox' class="img_selector img_to_remove" data-ignore="true" data-id='<?=$product->images[$i]["id"]?>'/>
                        <img  class="img-responsive img-thumbnail" src="../images/<?=$product->images[$i]["name"]?>" alt="">
                    </div>
                    <?php
        }?>                
                </div>
            </div>
            <hr>
            <h3>Add more Images</h3>
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
            <input name="product_id" type="text" hidden="hidden" value='<?=$pid?>'>
            <input name="product_edit_submit" class="btn btn-primary" type="submit" value="Edit Product">
        </form>
    </div>
    <?php
    }
    else
    {

    }
    ?>
</div>
<script>
    $img_selector = $(".img_selector");

    $img_selector.on("click", function(evt)
                     {
        $(this).toggleClass("selected");
    });

</script>
<?php include("footer.php");?>
<?php
}else
{

}
?>