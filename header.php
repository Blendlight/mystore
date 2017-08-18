<?php
@session_start();
include_once("includes/php/user_info.php");
$products_in_cart = get_cart_data($conx, $login, $user_id);

$products_in_cart_count = count($products_in_cart);
$cart_info = get_cart_info($products_in_cart, $login);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo isset($page_title)==true?$page_title:"TITLE";?></title>
        <link rel="stylesheet" href="includes/libraries/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="">
        <link rel="stylesheet" href="includes/css/style.css">
        <?php
        if(isset($STYLES))
        {
            for($i=0;$i<count($STYLES);$i++)
            {
                $style = $STYLES[$i];
                echo "<link href='$style' rel='stylesheet'/>";
            }
        }?>
        <script src="includes/libraries/bootstrap/js/jquery.js"></script>
        <?php
        if(isset($SCRIPTS))
        {
            for($i=0;$i<count($SCRIPTS);$i++)
            {
                $script = $SCRIPTS[$i];
                echo "<script src='$script'></script>";
            }
        }
        ?>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <a class="navbar-brand" href="./">My shop</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <form class="navbar-form navbar-left">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search">
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                        </form>

                        <ul class="navbar-nav nav">
                            <li><a href="">Item 1</a></li>
                            <li><a href="">Item 2</a></li>
                            <li><a href="">Item 3</a></li>
                        </ul>
                        <ul class="navbar-nav navbar-right nav">
                            <li class="dropdown" id="cart_small">

                                <?php show_cart_small($products_in_cart, $login);?>

                            </li>
                            <?php
                            if($login)
                            {
                            ?>
                            <li>
                                <a href="logout.php">Logout</a>
                            </li>
                            <?php
                            }else
                            {
                            ?>

                            <li>
                                <a href="login.php">Login</a>
                            </li>
                            <li>
                                <a href="register.php">Signup</a>
                            </li>
                            <?php
                            }
                            ?>

                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div>
            </div><!-- /.container-fluid -->
        </nav>
        <?php if(false){ ?></body></html><?php }?>