<?php

$SCRIPTS[] = "includes/js/product.js";

include_once("header.php");
include_once("includes/php/conx.php");
include_once("includes/php/functions.php");
?>

<style>
    .pr_sm_img
    {
        width: 100px;
    }
</style>

<div class="container">
    <div id="cart_action_result">
    </div>
    <div class="col-md-8">
        <?php
        if($products_in_cart != null)
        {
        ?>
        <table class="table table-striped table-bordered table-responsive">
            <thead>
                <tr>
                    <th>#</th>
                    <th>
                        Image
                    </th>
                    <th>
                        Name
                    </th>
                    <th>
                        Quantity
                    </th>
                    <th>
                        Price
                    </th>
                    <th>
                        Total
                    </th>
                    <th colspan="1">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
            $pc_count = 1;
            $sub_total = 0;
            foreach($products_in_cart as $pc)
            {
                ?>
                <tr class='text-center cart-element'>
                    <td><?=($pc_count++)?></td>
                    <td>
                        <img src="images/<?=$pc->images[0]["name"]?>" class="pr_sm_img" alt="">
                    </td>
                    <td>
                        <strong>
                            <?=$pc->name?>
                        </strong>
                    </td>
                    <td>
                        <div class="input-group">
                            <a class='btn btn-danger input-group-addon range_change ' data-value='-1'>-</a>

                            <input class='product_quantity_input' name="product_quantity" type="number_format" min='1'  max='<?=$pc->stock?>' class='form-control' step='1' data-ovalue='<?=$pc->selected?>' value='<?=$pc->selected?>'>

                            <a class='btn btn-primary btn-info input-group-addon range_change <?php if($pc->stock<=1){echo "disabled";} ?>' data-value='1'>+</a>
                        </div>
                        <p>
                            <br>
                            <button data-id='<?=$pc->id?>' class="btn btn-primary btn-xs hidden btn_product_update_cart">
                                Update
                            </button>

                        </p>
                        <div class="result">

                        </div>
                    </td>
                    <td class="product_price" data-price="<?=$pc->price?>">
                        <?=$pc->price?>
                    </td>
                    <td class='product_total'>
                        <?=(intval($pc->selected) * intval($pc->price))?>
                    </td>
                    <td>
                        <button class="btn btn-danger btn_product_remove_cart" data-id='<?=$pc->id?>'>
                            Remove
                        </button>
                    </td>

                </tr>
                <?php
            }
                ?>
            </tbody>
        </table>
        <div>
            <h2 class="text-right">Sub-total: <strong class="sub_total">$<?=$cart_info["subtotal"]?></strong></h2>
        </div>
        <?php
        }else
        {
            echo "No products in cart";
        }
        ?>
    </div>
    <div class="col-md-4">
        <div class="jumbotron">
            <h2>Cart info</h2>
            <p>
                <strong><?=$cart_info["total_items"]?></strong> Items in cart
            </p>
            <p>
                SubTotal: <strong class="sub_total">$<?=$cart_info["subtotal"]?></strong>
            </p>
            <a href="checkout.php" class="btn btn-warning">
                Checkout
            </a>
        </div>
    </div>
</div>
<?php
include_once("footer.php");
?>
