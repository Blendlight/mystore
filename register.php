<?php
if(isset($_POST["submit"]))
{

    include_once("includes/php/user_info.php");
    //    "submit=submit&register=register&name="+name+"&uname="+user_name+"&email="+email+"&pass="+pass
    if(isset($_POST["register"]))
    {
        $name = $_POST["name"];
        $uname = $_POST["uname"];
        $email = $_POST["email"];
        $pass = $_POST["pass"];

        //check for the username and the email
        $uinfo = check_user_registration($conx, $uname, $email);
        if($uinfo[0]=="true" || $uinfo[1]=="true")
        {
            echo "fail:";
            exit;
        }

        $user_insert_query = "INSERT INTO `ecommerce`.`user` (`user_id`, `user_name`, `user_uname`, `user_email`, `user_password`, `user_date`, `user_status`, `user_role`) VALUES (NULL, '$name', '$uname', '$email', '$pass', 'now()', '1', '2')";
        $user_insert_result = mysqli_query($conx, $user_insert_query);
        if($user_insert_result && mysqli_affected_rows($conx)>0)
        {
            echo "success:";
            $uid =  mysqli_insert_id($conx);
            set_login_session($conx, $uname, $uid);
            move_cart_cookie_data_to_table($conx, $uid);
//            header("location: ./");
        }
        else
        {
            echo "fail:";
            exit;
        }
    }
}else
{
?>
<?php
    $SCRIPTS[] = "includes/js/register.js";
    $page_title = "Register account";
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
?>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <form method="post" target="_self" id="form_register">
                <div class="form-group">
                    <label for="">Name</label>
                    <input name="name" class="form-control" type="text" required>

                </div>
                <div class="form-group">
                    <label for="">User Name</label>
                    <input name="user_name" class="form-control" type="text" required>
                    <div class="result"></div>
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <input name="email" class="form-control" type="email" required>
                    <div class="result"></div>
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input name="password" class="form-control" type="password" required>

                </div>
                <div class="form-group">
                    <label for="">Confirm Password</label>
                    <input name="repassword" class="form-control" type="password" required>
                    <div class="result"></div>
                </div>
                <input class='btn btn-primary' name="submit" type="submit" value="register">
            </form>
        </div>
    </div>
</div>
<?php
    include_once("footer.php");
?>

<?php
}
?>