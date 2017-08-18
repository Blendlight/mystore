<?php
include("../includes/php/conx.php");
function group_files($arr)
{
    $files = array();
    for($i=0;$i<count($arr["name"]);$i++)
    {
        $files[] = array("name"=>$arr["name"][$i], 
                         "type"=>$arr["type"][$i],
                         "tmp_name"=>$arr["tmp_name"][$i],
                         "size"=>$arr["size"][$i],
                         "error"=>$arr["error"][$i]
                        );
    };

    return $files;
}

if(isset($_POST["product_edit_submit"]))
{

    $product_id = $_POST["product_id"];
    $product_name = $_POST["product_name"];
    $product_description = $_POST["product_description"];
    $product_category = $_POST["product_category"];
    $product_price = $_POST["product_price"];
    $product_stock = $_POST["product_stock"];
    $product_date = $_POST["product_date"];
    $product_country = $_POST["product_country"];
    $fields = array(
        "product_name",
        "product_description",
        "product_category",
        "product_price",
        "product_stock",
        "product_date",
        "product_country");

    $to_update = array();
    $update = false;

    //check the datasent is changed or not
    $check_query = mysqli_query($conx, 
                                "SELECT
                                    *
                                FROM
                                    product
                                WHERE   product_id='$product_id'
                                LIMIT 1
                               ");
    if($check_query && mysqli_num_rows($check_query)>0)
    {
        $check_data = mysqli_fetch_array($check_query);
        foreach($fields as $field)
        {
            if($_POST[$field]!=$check_data[$field])
            {
                $to_update[] = $field;
                $update = true;
            }
        }
    }else
    {
        echo "can't find the product of id $product_id";
        exit;
    }

    //update the fields in product_table
    if($update)
    {
        $update_query  = "UPDATE `ecommerce`.`product` SET ";
        $to_update_length = count($to_update);
        for($i=0;$i<$to_update_length;$i++)
        {
            $updater = $to_update[$i];
            $update_query .= "`$updater`='$_POST[$updater]'";
            if($i<$to_update_length-1)
            {
                $update_query .= ",";
            }
            $update_query .= "\n";
        }
        $update_query .= " WHERE `product_id`='$product_id'";

        $update_result = mysqli_query($conx, $update_query);
        if($update_result && mysqli_affected_rows($conx)>0)
        {
            echo ":update-successfull:\n";
        }else
        {
            echo ":update-failed:\n";
        }
    }

    //start of --delete those images which are selected to be removed
    if(isset($_POST["image_to_remove"]))
    {
        $img_to_remove_query = "DELETE FROM product_image WHERE (";
        $img_to_remove_length = count($_POST["image_to_remove"]);
        for($i=0;$i<$img_to_remove_length;$i++)
        {
            //create set of elements which need to be removed
            $img_to_remove = $_POST["image_to_remove"][$i];
            $img_to_remove_query .= "pr_img_id='$img_to_remove'";
            if($i<$img_to_remove_length-1)
            {
                $img_to_remove_query .= " || ";
            }

            //now get names of images from database and remove those images
            $img_name_query = "SELECT
                                    *
                                FROM
                                    product_image
                                WHERE pr_img_id='$img_to_remove' && product_id='$product_id'
                                           ";
            $img_name_result = mysqli_query($conx,$img_name_query);
            if($img_name_result && mysqli_num_rows($img_name_result)>0)
            {
                $img_name_data = mysqli_fetch_array($img_name_result);
                $img_name = $img_name_data["pr_img_name"];
                unlink("../images/$img_name");
            }
        }
        $img_to_remove_query .= ") && product_id='$product_id'";

        $img_to_remove_result = mysqli_query($conx, $img_to_remove_query);
        if($img_to_remove_result && mysqli_affected_rows($conx)>0)
        {
            echo ":images-removed-successfull-/$img_to_remove_length/:\n";
        }else
        {
            echo ":images-removed-failed:\n";
        }
    }
    //end of --delete those images which are selected to be removed

    //now store new images names in database and files in server
    if(isset($_FILES["product_images"]))
    {
        $img_files = group_files($_FILES["product_images"]);
        $img_files_length = count($img_files);
        $img_insert_query = "INSERT INTO product_image(pr_img_id, product_id, pr_img_name, pr_img_description) VALUES";
        for($i=0; $i < $img_files_length; $i++)
        {
            $img_file = $img_files[$i];
            $img_name = rand(0, time())."fff".rand(0, time()).$img_file["name"];
            $img_tmp_name = $img_file["tmp_name"];
            $img_insert_query .= "(NULL, '$product_id', '$img_name', '".($img_file["name"])."')";
            if($i<$img_files_length-1)
            {
                $img_insert_query .= ",";
            }
            move_uploaded_file($img_tmp_name, "../images/".$img_name);
        }


        $img_insert_result = mysqli_query($conx, $img_insert_query);
        if($img_insert_result && mysqli_affected_rows($conx)>0)
        {
            echo ":images-saved-successfull-/".mysqli_affected_rows($conx)."/:\n";
        }else
        {
            echo ":images-saved-successfull:\n";
        }
    }
}else if(isset($_POST["product_remove_submit"]))
{
    $product_id = $_POST["product_id"];
    $check_query = mysqli_query($conx, 
                                "SELECT
                                    *
                                FROM
                                    product
                                WHERE   product_id='$product_id'
                                LIMIT 1
                               ");
    if(!($check_query && mysqli_num_rows($check_query)>0))
    {
        echo "can't find the product of id $product_id";
        exit;
    }else
    {
        $product_delete_result = mysqli_query($conx, "DELETE FROM `ecommerce`.`product` WHERE `product`.`product_id` = '$product_id'");
        if($product_delete_result && mysqli_affected_rows($conx)>0)
        {
            echo "success";
        }else
        {
            echo "fail";
        }
    }
}
?>