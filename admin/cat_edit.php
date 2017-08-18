<?php include_once("includes/functions.php");?>
<?php include_once("../includes/php/conx.php");?>
<?php
if(isset($_POST["submit"]))
{
    $name = $_POST["name"];
    $catid = $_POST["catid"];
    $description = $_POST["description"];
    $parent = $_POST["parent"];
    $parent = $parent!="null"?"'$parent'":$parent;
    $query = mysqli_query($conx, "
    UPDATE
        category
    SET
        category_name='$name', category_description='$description', parent=$parent 
    WHERE
        category_id='$catid'");
    var_dump($query);
    if($query && mysqli_affected_rows($conx)>0)
    {
        echo "success:";
    }

    exit;
}
?>

<?php 
if(!isset($_GET["catid"]))
{
    header("location: ./");
}

$catid = $_GET["catid"];
?>

<?php include("header.php");?>

<?php
$query = mysqli_query($conx, "
    SELECT
        *
    FROM
        category
    WHERE
        category_id='$catid'
    ");

if(!($query && mysqli_num_rows($query)>0))
{
    echo "Can't find the category";   
}else
{
    $cat_data = mysqli_fetch_array($query, 1);
    extract($cat_data);
}
?>
<div id="result"></div>
<div class="container">
    <div category_id="result">

    </div>
    <form class="form" onsubmit="return false;">
        <input type="text" name="catid" value='<?php echo $category_id;?>' hidden />
        <div class="form-group">
            <label for="name">Name</label>
            <input  placeholder="Name" type="text" class="form-control" category_id="name" name="name" value="<?php echo $category_name;?>" />
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea  placeholder="Enter Description" class="form-control" type="text" category_id="description" name="description"><?php echo $category_description;?></textarea>
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
            <button class="btn btn-primary" onclick="add_category(this, 'edit', '<?php echo $category_id;?>')";>Edit</button>
        </div>
    </form>
</div>
<script src="js/action.js"></script>
<?php include("footer.php");?>