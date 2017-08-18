<?php

include_once("../includes/php/conx.php");
include_once("includes/functions.php");
function get_category_select_options($conx, $level=0, $parent="NULL")
{
    if($parent=="NULL")
    {
        $query = "SELECT * FROM category WHERE parent is NULL";
    }else
    {
        $query = "SELECT * FROM category WHERE parent='$parent'";
    }

    $query = mysqli_query($conx, $query);

    if($query)
    {
?>
<?php
        while($row = mysqli_fetch_array($query))
        {
            extract($row);
            $is_parent = is_parent($conx, $category_id)
?>
<option>
    <?php
                echo str_repeat(" &nbsp; &nbsp; ", $level);
                echo "> $category_name";
    ?>
</option>
<?php
            if($is_parent)
            {
                get_category_select_options($conx, $level+1, $category_id);
            }

        }
    }
}

get_category_select_options($conx);
?>