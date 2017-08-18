<?php
@session_start();
include_once($_SERVER["DOCUMENT_ROOT"]."/mystore/includes/php/functions.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/mystore/includes/php/conx.php");
?>
<?php
$login = false;
if(is_login())
{
    $login = true;
    $user_id = $_SESSION["user_id"];
    $user_uname = $_SESSION["user_uname"];
    $user_info_query = mysqli_query($conx, "SELECT * FROM user WHERE user_id='$user_id' && user_uname='$user_uname' LIMIT 1");
    if($user_info_query)
    {
        $user_info = mysqli_fetch_array($user_info_query);
        
        extract($user_info);
    }else
    {
        header("refresh: login.php", 5);
        echo "<h2>No match found for the users with session</h2>";
    }
}else{
    header("location: login.php");
}
?>