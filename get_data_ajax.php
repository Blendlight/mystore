<?php
include_once("includes/php/user_info.php");
if(isset($_REQUEST["get_data"]))
{
    $get_data = $_REQUEST["get_data"];
    $cart_data = get_cart_data($conx, $login, $user_id);
    switch($get_data)
    {
        case "cart_info":
            $cart_info = get_cart_info($cart_data);
            echo json_encode($cart_info);
            break;
        case "cart_small":
            show_cart_small($cart_data, $login);
            break;
        case "check_user_registration":
            $uname = $_POST["uname"];
            $email = $_POST["email"];
            echo check_user_registration($conx, $uname, $email);
            break;
        case "address_list":
            echo show_address_select($conx, $user_id);
            break;
    }
}
?>