<?php
include_once("user_info.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo isset($page_title)==true?$page_title:"TITLE";?></title>
        <link rel="stylesheet" href="../includes/libraries/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="">
        <link rel="stylesheet" href="includes/style.css">
        <?php
        if(isset($STYLES))
        {
            for($i=0;$i<count($STYLES);$i++)
            {
                $style = $STYLES[$i];
                echo "<link href='$style' rel='stylesheet'/>";
            }
        }?>
        <script src="../includes/libraries/bootstrap/js/jquery.js"></script>
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
                        <a class="navbar-brand" href="./">Admin Panel</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">

                            <li><a href="#">Welcome <?php echo $user_name;?></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    Add/Edit <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a href="categories.php">Categories</a></li>
                                    <li><a href="products.php">Products</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="countries.php">Countries</a></li>
                                </ul>
                            </li>
                        </ul>
                        <form class="navbar-form navbar-left">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search">
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                        </form>
                        <ul class="nav navbar-nav navbar-right">
                            <?php
                            if(!$login)
                            {
                            ?>
                            <li><a href="#login">Login</a></li>
                            <li><a href="#signup">SignUp</a></li>
                            <?php
                            }else
                            {
                            ?>
                            <li><a href="#">Profile</a></li>
                            <li><a href="../logout.php">Logout</a></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div>
            </div><!-- /.container-fluid -->
        </nav>
        <?php if(false){ ?></body></html><?php }?>