<?php
if(!isset($_GET["pid"]))
{
    if(isset($_SERVER["HTTP_REFERER"]))
    {
        header("location: ".$_SERVER["HTTP_REFERER"]);
    }else
    {
        header("location: ./");
    }
}

$pid = $_GET["pid"];
?>

<?php

$SCRIPTS[] = "includes/js/product.js";

include_once("header.php");
include_once("includes/php/conx.php");
include_once("includes/php/functions.php");
?>

<div class="container">
    <div id="product_action_result">
    </div>

    <?php
    $product_select_query = "
                    SELECT
                        product.*, category.category_name as category, country.name as country
                    FROM
                        product
                    JOIN
                        category on category.category_id=product.product_category
                        JOIN
                        country on country.id=product.product_country

                    WHERE
                    product_id='$pid LIMIT 1'
                            ";

    $product_select_result = mysqli_query($conx, $product_select_query);

    if($product_select_result && mysqli_num_rows($product_select_result))
    {
        $product_data = mysqli_fetch_array($product_select_result);
        $product = new Product($conx, $product_data);
        $outOfStock = false;
        if($product->stock<=0)
        {
            $outOfStock = true;    
        }
    ?>

    <div class="col-md-8">
        <div class="product">
            <div class="product_head">
                <div class="product_name">
                    <h2><?=$product->name?></h2>
                </div>

                <div class="product_image">
                    <?php
        $img_count = count($product->images);
        if($img_count>0)
        {
            if($img_count==1)
            {
                    ?>
                    <img class='img-responsive' src="images/<?=$product->images[0]["name"]?>" alt="">
                    <?php
            }else
            {
                //slide show of images
                    ?>

                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators" style='background:rgba(0,0,0,.5);padding:5px;'>
                            <?php
                for($i=0;$i<$img_count;$i++)
                {
                    $img = $product->images[$i];
                            ?>
                            <!-- Indicators -->

                            <li data-target="#myCarousel" data-slide-to="<?=$i?>" class="<?php if($i==0){echo "active";}?>"></li>

                            <?php
                }
                            ?>
                        </ol>
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">
                            <?php
                for($i=0;$i<$img_count;$i++)
                {
                    $img = $product->images[$i];
                            ?>


                            <div class="item <?php if($i==0){echo "active";}?>">
                                <img class='img-responsive' src="images/<?=$img["name"]?>" alt="Los Angeles">
                            </div>


                            <?php
                }
                            ?>
                        </div>
                        <!-- Left and right controls -->
                        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#myCarousel" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>



                    <?php
            }
        }
                    ?>
                </div>

                <div class="product_details">
                    Price <?=$product->price?><br>
                    <?=$product->stock?> in stock
                    <div class="product_description">
                        <h3>Description</h3>
                        <p>
                            <?=$product->description?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--    Add to Cart    -->
    <div class="col-md-4">

        <h3>Add to Cart</h3>
        <?php
                        if($outOfStock)
                        {
        ?>
        <div class="alert alert-danger">Out of stock</div>
        <p>
            Sorry we don't have this product in stock.
        </p>
        <?php
                        }else{
        ?>
        <div class="">
    <?=$product->stock?> Items in Stock.
        </div>
        <form id='add_to_cart_form' action="cart.php" method="post" class='form'>
           <input name='product_id' type="text" value='<?=$product->id?>' hidden>
           <input name='product_price' type="text" value='<?=$product->price?>' hidden>
           <input name='product_stock' type="text" value='<?=$product->stock?>' hidden>
            <div class="form-group">
                <label for="">
                    Quantity
                </label>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <a class='btn btn-danger input-group-addon range_change disabled' data-value='-1'>-</a>

                            <input class='product_quantity_input' name="product_quantity" id='product_quantity' type="number_format" min='1'  max='<?=$product->stock?>' class='form-control' step='1' value='1'>

                            <a class='btn btn-primary btn-info input-group-addon range_change <?php if($product->stock<=1){echo "disabled";} ?>' data-value='1'>+</a>
                        </div>
                    </div>
                </div>
            </div>
            <p>
                Item price <?=$product->price?>
            </p>

            <input type="submit" value="Add to cart" class="btn btn-primary"/>
        </form>
        <?php
                        }
        ?>
    </div>

    <?php
    }
    else
    { 

    ?>
    Product not found
    <?php    
    }
    ?>

</div>
<?php
include_once("footer.php");
?>
