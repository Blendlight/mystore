<?php
include_once("user_info.php");
include_once("includes/functions.php");
if(isset($_POST["submit"]))
{

    $name = $_POST["name"];
    $description = $_POST["description"];
    $parent = $_POST["parent"];
    $parent = $parent!="null"?"'$parent'":$parent;
    //FIRST CEHCK THE CATEGORY
    $query = "SELECT count(category_id) FROM category where category_name='$name'";
    echo $query;
    $query = mysqli_query($conx, $query);
    $query = mysqli_fetch_array($query)[0];
    if($query>0)
    {
        echo "failed: Category on this name already exist";
        exit;
    }
    $query = "INSERT INTO category(category_id, category_name, category_description, parent) VALUES(null, '$name', '$description', null)";
        echo $query;
        $query = mysqli_query($conx, $query);
    if($query && mysqli_affected_rows($conx)>0)
    {
        echo "success:";
        exit;
    }
}else{

    $parent = null;
    if(isset($_GET["parent"]))
    {
        $parent  = $_GET["parent"];
    }
?>
<?php $page_title = "Add category";?>

<?php include("header.php");?>


<div id="result"></div>
<div class="container">
    <div category_id="result">

    </div>
    <form class="form" onsubmit="return false;">
        <div class="form-group">
            <label for="name">Name</label>
            <input placeholder="Name" type="text" class="form-control" category_id="name" name="name" />
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea placeholder="Enter Description" class="form-control" type="text" category_id="description" name="description"></textarea>
        </div>
        <div class="form-group">
            <label for="parent">
                Parent
            </label>
            <select name="parent" category_id="parent">
                <option value="null">None</option>
                <?php get_category_select_options($conx, $parent);?>
            </select>
        </div>
        <div>
            <button class="btn btn-primary" onclick="add_category(this)">Add</button>
        </div>
    </form>
</div>
<script src="js/action.js"></script>
<?php include("footer.php");?>
<?php
}
?>
