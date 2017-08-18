<?php
session_start();
include_once("../includes/php/functions.php");
include_once("../includes/php/conx.php");
?>

<?php
if(is_login())
{
    header("location: index.php");
}
if(isset($_POST["user_name"], $_POST["user_password"]))
{
    $user_name = $_POST["user_name"];
    $user_password = $_POST["user_password"];
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
                                    ) && user.user_password = '$user_password' && role.role_name = 'admin'
                                LIMIT 1
                        ");
    if($query && mysqli_num_rows($query)>0)
    {
        $info = mysqli_fetch_array($query);
        set_login_session($conx, $info["user_uname"], $info["user_id"]);
        header("location: ./");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="../includes/libraries/bootstrap/css/bootstrap.min.css" />
        <script src="../includes/libraries/bootstrap/js/jquery.js" ></script>
    </head>
    <body>
        <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h3>Admin Login</h3>
                        <form class="form" method="post" action="login.php">
                            <div class="form-group">
                                <label for="user_name">Name:</label>
                                <input class="form-control" type="text" placeholder="user name or email" name="user_name" id="user_name"/>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input class="form-control" type="password" placeholder="Password" name="user_password" id="password">
                            </div>
                            <input class="btn btn-primary" type="submit" value="login" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="../includes/libraries/bootstrap/js/bootstrap.min.js" ></script>
    </body>
</html>