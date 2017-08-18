<?php
$SCRIPTS[] = "js/country.js";
?>
<?php
$page_title = "Countries";
include_once("../includes/php/conx.php");
if(!isset($_POST["submit"]))
{
    include_once("header.php");
?>


<div id="modal_add_country" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New country</h4>
            </div>
            <div class="modal-body">
                <div id="country_form_result">

                </div>
                <form class="form" action="" id="new_country_form">
                    <div class="form-group">
                        <label for="name">Country Name</label>
                        <input type="text" class="form-control" id='country_name' name='country_name' placeholder="Country Name">
                    </div>
                    <input type="submit" class="btn btn-primary" value="Add" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="modal_remove_country" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Remove country</h4>
            </div>
            <div class="modal-body">
                <div id="country_remove_result">
                    <p>
                        Do you want to remove country <strong id="country_remove_name"></strong>
                    </p>
                    <button id="btn_country_remove" data-id="" class="btn btn-primary">Remove</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal_edit_country" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit country</h4>
            </div>
            <div class="modal-body">
                <div id="country_edit_form_result">

                </div>
                <form class="form" action="" id="edit_country_form">
                    <div class="form-group">
                        <label for="country_edit_name">Country Name</label>
                        <input type="text" name="country_id" id="country_edit_id" hidden>
                        <input type="text" class="form-control" id='country_edit_name' name='country_name' placeholder="Country Name">
                    </div>
                    <input type="submit" class="btn btn-primary" value="Change" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div class="jumbotron">
    <div class="container">
        <h3>Countries</h3>
        <a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal_add_country">Add new</a>
    </div>
</div>
<div class="container">
    <table class="table table-center table-responsive table-striped table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Products</th>
                <th colspan='2'>
                    Actions
                </th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php
    $query = mysqli_query($conx, "SELECT * FROM country");
    if($query && mysqli_num_rows($query)>0)
    {
        while($row = mysqli_fetch_array($query))
        {
            $id = $row["id"];
            $name = $row["name"];
            $status = $row["status"];
            ?>
            <tr class='country_row' data-id='<?=$id?>'>
                <td class='country_name'>
                    <?=$name?>
                </td>
                <td>
                    {Products_count}
                </td>
                <td>
                    <a href="#" class="btn btn-primary edit-country" data-toggle="modal" data-target="#modal_edit_country">Edit</a>
                </td>
                <td>
                    <a href="#" class="btn btn-danger remove_country" data-toggle='modal' data-target="#modal_remove_country" data-id="<?=$id?>">Remove</a>
                </td>
            </tr>
            <?php
        }
    }
            ?>
        </tbody>
    </table>
</div>
<?php
    include_once("footer.php");
}else
{
    if(isset($_POST["action"]))
    {
        $action = $_POST["action"];

        if($action=="add_country")
        {
            $country_name = $_POST["country_name"];
            $country_dupli_check = mysqli_query($conx, "
                                                       SELECT
                                                            *
                                                        FROM
                                                            country
                                                        WHERE
                                                            name='$country_name'
                                                        LIMIT 1");
            if($country_dupli_check && mysqli_num_rows($country_dupli_check)>0)
            {
                echo "fail:Country already exists";
            }else
            {
                $country_add_result = mysqli_query($conx, "INSERT INTO country(id, name, status) VALUES(NULL, '$country_name', '1')");
                if($country_add_result && mysqli_affected_rows($conx)>0)
                {
                    echo "success:";
                }else
                {
                    echo "fail:Country insertion failed";
                }
            }

        }else if($action == "remove_country")
        {
            $cid = $_POST["country_id"];
            $remove_result = mysqli_query($conx, "DELETE FROM `ecommerce`.`country` WHERE `country`.`id` = '$cid'");
            if($remove_result && mysqli_affected_rows($conx)>0)
            {
                echo "success:";    
            }else
            {
                echo "fail:Can't remove country of id = $cid";
				echo "<pre>";
				var_dump($remove_result);
				var_dump(mysqli_affected_rows($conx));
				echo "</pre>";
            }
        }else if($action=="edit_country")
        {
            $cid = $_POST["country_id"];
            $country_name = $_POST["country_name"];

            $country_dupli_check = mysqli_query($conx, "
                                                       SELECT
                                                            *
                                                        FROM
                                                            country
                                                        WHERE
                                                            name='$country_name'
                                                        LIMIT 1");
            if($country_dupli_check && mysqli_num_rows($country_dupli_check)>0)
            {
                echo "fail:Country already exists";
            }else{

                $country_edit_result = mysqli_query($conx, "UPDATE country SET name='$country_name' WHERE id='$cid'");
                if($country_edit_result && mysqli_affected_rows($conx)>0)
                {
                    echo "success:";
                }else
                {
                    echo "fial: can't update the name of country";
                }
            }
        }
    }
}
?>
