<?php
if(isset($_POST["submit"]))
{
    include("../data.config");
    include("../includes/functions.php");
    $conx = get_conx(DB_NAME);
    $name = $_POST["name"];
    $description = $_POST["description"];
    $parent = $_POST["parent"];
    $parent = $parent!="null"?"'$parent'":$parent;
    
    //FIRST CEHCK THE CATEGORY
    $query = mysqli_query($conx, "SELECT *FROM categories where name=");

    //check for the parent if the parent is also a child of another parent thorug error
    if($parent!="null")
    {
        $query = mysqli_query($conx, "SELECT *FROM categories where ID=$parent");
        while($row = mysqli_fetch_array($query))
        {
            if($row["parent"]!=null)
            {
                echo "failed: Select different Parent the parent is also child of another parent";
                exit;
            }
        }
    }

    $query = mysqli_query($conx, "INSERT INTO categories(ID, name, description, parent) VALUES(null, '$name', '$description', $parent)");
    if($query && mysqli_affected_rows($conx)>0)
    {
        echo "success: ";
        exit;
    }
}else{

    $parent = null;
    if(isset($_GET["parent"]))
    {
        $parent  = $_GET["parent"];
    }
?>

<?php if(0){?><html lang="en"><body><?php }?>
        <?php $page_title = "Add category";?>
        <?php include("header.php");?>
        
        <script src="js/action.js"></script>
        <?php include("footer.php");?>
        <?php
     }
        ?>
<?php if(0){?></body></html><?php }?>