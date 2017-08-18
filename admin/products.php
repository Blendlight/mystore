<?php
$page_title = "Products";
include_once "includes/functions.php";
include_once("header.php");
?>

<style>
    .product 
    {
        border:1px dashed black;
    }

</style>

<div class="jumbotron">
    <div class="container">
        <h2>Products</h2>
        <a class="btn btn-primary btn-xs" href="product_add.php">Add New</a>
        <a class="btn btn-default btn-xs" href="#" disabled>Products in trash <span class="badge">0</span></a>
    </div>
</div>
<div class="container">
    <div class="products_container">
        <table class="table tabel-striped table-bordered text-center">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Date</th>
                    <th>Country</th>
                    <th>Images</th>
                    <th>Actions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $products = get_products_list($conx);
                while($product = fetch_products($products)){
                ?>
                <tr>
                    <td>
                        <a href="http://localhost/mystore/product.php?pid=<?=$product->id?>">
                            <?php echo $product->name;?>
                        </a>
                    </td>
                    <td><?php echo $product->description;?></td>
                    <td><?php echo $product->category;?></td>
                    <td><?php echo $product->price;?></td>
                    <td><?php echo $product->stock;?></td>
                    <td><?php echo $product->dateAdded;?></td>
                    <td><?php echo $product->country;?></td>
                    <td><?php echo count($product->images);?></td>
                    <td>
                        <a href="product.php?pid=<?=$product->id?>&action=edit"     class="btn btn-primary">Edit</a>
                    </td>
                    <td>
                    <a href="product.php?pid=<?=$product->id?>&action=remove"     class="btn btn-primary">Remove</a>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once("footer.php");?>