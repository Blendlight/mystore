<?php
$page_title = "Login account";
include_once("header.php");
if($login)
{
    if(isset($_SERVER["HTTP_REFERER"]))
    {
        header("location: ".$_SERVER["HTTP_REFERER"]);
    }else{
        header("location: ./");
    }
}

if(isset($_POST["user_name"], $_POST["user_password"]))
{
    $user_name = $_POST["user_name"];
    $user_password = $_POST["user_password"];
//	$q = "
//                                SELECT 
//                                    user.*, 
//                                    role.role_name 
//                                FROM 
//                                    `user` 
//                                    JOIN `role` on user.user_role = role.role_id 
//                                WHERE 
//                                    (
//                                        user.user_uname = '$user_name' || user.user_email = '$user_name'
//                                    ) && user.user_password = '$user_password'
//                                LIMIT 1
//                        ";
//	echo $q;
//	exit;
    $query = mysqli_query($conx,"
                                SELECT 
                                    user.*, 
                                    role.role_name 
                                FROM 
                                    `user` 
                                    JOIN `role` on user.user_role = role.role_id 
                                WHERE 
                                    (
                                        user.user_uname = '$user_name' || user.user_email = '$user_name'
                                    ) && user.user_password = '$user_password'
                                LIMIT 1
                        ");
    if($query && mysqli_num_rows($query)>0)
    {
        $info = mysqli_fetch_array($query);
        $_SESSION["user_uname"] = $info["user_uname"];
        $_SESSION["user_id"] = $info["user_id"];
        $msg = urlencode(move_cart_cookie_data_to_table($conx, $info["user_id"]));
        header("location: ./?msg=$msg");
    }
}

?>


<div class="container">

</div>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="inp_name">UserName or Email</label>
                    <input type="text" name="user_name" id="inp_name"/>
                </div>
                <div class="form-group">
                    <label for="inp_pass">Password</label>
                    <input type="password" name="user_password" id="inp_pass"/>
                </div>
                <input type="submit" value="Login" class="btn btn-primary"/>
            </form>
        </div>
    </div>
</div>
<?php
include_once("footer.php");
?>