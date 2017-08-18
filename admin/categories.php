<?php
$catid = null;
if(isset($_GET["catid"]))
{
    $catid = $_GET["catid"];
}
?>
<?php include_once("user_info.php");?>
<?php include_once("includes/functions.php");?>
<?php $page_title = "Categories"?>
<?php include("header.php");?>
<div class="container">
    <div class="col-md-10">
        <h3>Add new category</h3>
        <a class="btn btn-primary" href="cat_add.php">Add Category</a>
        <?php
        if(!$catid)
        {
            $parent = "NULL";
        ?>
        <h2>Main Categories</h2>
        <div class="cat-container">
            <?php
            get_category($conx, $parent);
            ?>
        </div>
        <?php

        }else
        {
            echo "<div>
                <a href='categories.php'>Main Categories</a>
            ";
            echo get_category_path($conx, $catid);
            echo "</div>";
            $query = mysqli_query($conx, "SELECT * FROM category WHERE category_id='$catid' LIMIT 1");
            if($query && mysqli_num_rows($query)>0)
            {
                $row = mysqli_fetch_array($query);
                extract($row);
        ?>
        <br>
        <div class="actions">
            Actions on Category <strong><?php echo $category_name;?></strong>
            <a href="cat_edit.php?catid=<?php echo $category_id;?>" class="btn btn-xs btn-primary">
                Edit
            </a>
            <a href="cat_remove.php?catid=<?php echo $category_id;?>" class="btn btn-danger btn-xs">
                Remove
            </a>
            <a href="cat_add.php?parent=<?=$category_id?>" class="btn btn-success btn-xs">
                Add subcategory
            </a>
        </div>
        <h2><?php echo $category_name;?></h2>
        <p><?php echo $category_description;?></p>
        <?php
            }

        ?>
        <?php
            $parent = $catid;
        }
        ?>


        <?php 
        if(is_parent($conx, $parent))
        {
        ?>
        <h3>Subcategories of <?php echo $category_name;?></h3>
        <div class="cat-container">
            <?php
            get_category($conx, $parent);
            ?>
        </div>
        <?php 
        }
        ?>
    </div>
</div>

<script src="includes/action.js"></script>
<?php include("footer.php");?>