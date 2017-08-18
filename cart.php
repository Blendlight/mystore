<?php
include_once("includes/php/user_info.php");
?>

<?php
if(isset($_POST["submit"], $_POST["action"]))
{
    $action = $_POST["action"];
    $pid = $_POST["pid"];
    $pid = intval($pid);
    $product = new Product($conx, $pid);
    switch($action)
    {
        case "add":
            $pquantity = intval($_POST["pquantity"]);

            if($product->id==null)
            {
                echo "fail: product not found";
                exit;
            }

            if($product->stock<$pquantity)
            {
                echo "fail: Not enough items in stock";
            }

            if($login)
            {
                item_add_to_cart_table($conx, $user_id , $product->stock, $pid, $pquantity);
            }else
            {
                item_add_to_cart_cookie($product->stock, $pid, $pquantity);
            }
            break;
        case "remove":
            if($login)
            {
                item_remove_from_cart_table($conx, $user_id, $pid);
            }else
            {
                item_remove_from_cart_cookie($pid);
            }
            break;
        case "update":
            $pquantity = intval($_POST["pquantity"]);
            
            if($login)
            {
                update_cart_table($conx, $user_id, $product->stock, $pid, $pquantity);
            }else
            {
                update_cart_cookie($product->stock, $pid, $pquantity);
            }
    }
}
?>