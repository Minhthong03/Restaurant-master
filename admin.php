<!DOCTYPE html>
<?php session_start();
if (!isset($_SESSION["RoleID"]) || $_SESSION["RoleID"] != 1) {
    echo "<script>alert('Không có quyền truy cập');</script>";
    header("refresh:0;url='index.php'");
    exit;
}
?>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Restaurant</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css" media="screen" type="text/css">
        <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/style-portfolio.css">
        <link rel="stylesheet" href="css/picto-foundry-food.css" />
        <link rel="stylesheet" href="css/jquery-ui.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link rel="icon" href="favicon-1.ico" type="image/x-icon">
    </head>

    <body>         
        <div id="top" class="starter_container bg">
            <div class="follow_container">
               <ul style="list-style-type:none;padding-left: 0 !important;margin: 0;">
                    <li><?php
                        if(isset($_SESSION["RoleID"])){
                            echo '<a class="color_animation" href="View/dangXuat.php" onclick="return confirm(\'Are you sure to logout?\');">LOGOUT</a>';
                            echo '<span style="margin: 0 40px;">|</span>';
                            echo '<a class="color_animation" href="index.php">HOME</a>';
                        }
                        ?>
                    </li>
                    <hr>  
                    <li><a class="color_animation" href="?action=AThongKe">THỐNG KÊ</a></li>
                    <li><a class="color_animation" href="?action=ADishes">QUẢN LÝ THỰC ĐƠN</a></li>
                    <li><a class="color_animation" href="?action=ACategories">QUẢN LÝ DANH MỤC THỰC ĐƠN</a></li>
                    <li><a class="color_animation" href="?action=AOrder">QUẢN LÝ ĐƠN HÀNG</a></li>
                    <li><a class="color_animation" href="?action=ANguoiDung">QUẢN LÝ NHÂN VIÊN</a></li>
                    </ul>
            </div>
        </div>
        <section id ="pricing" class="description_content">
            <div>
                <ul>
                    <?php
                        if(isset($_REQUEST["action"])&&$_REQUEST["action"]=="ANguoiDung"){
                            include_once("View/ANguoiDung.php");
                        }
                        elseif(isset($_REQUEST["action"])&&$_REQUEST["action"]=="ACategories"){
                            include_once("View/ACategories.php");
                        }
                        elseif(isset($_REQUEST["action"])&&$_REQUEST["action"]=="ADishes"){
                            include_once("View/ADishes.php");
                        }
                        elseif(isset($_REQUEST["action"])&&$_REQUEST["action"]=="AOrder"){
                            include_once("View/AOrder.php");
                        }
                        elseif(isset($_REQUEST["action"]) && $_REQUEST["action"]=="AThongKe"){
                            include_once("View/AThongKe.php");
                        }
                    ?>
                </ul><!-- @end #portfolio -->
            </div>  
        </section>
        <section class="social_connect">
            <div class="text-content container"> 
                <div class="col-md-6">
                    <span class="social_heading">FOLLOW</span>
                    <ul class="social_icons">
                        <li><a class="icon-twitter color_animation" href="#" target="_blank"></a></li>
                        <li><a class="icon-github color_animation" href="#" target="_blank"></a></li>
                        <li><a class="icon-linkedin color_animation" href="#" target="_blank"></a></li>
                        <li><a class="icon-mail color_animation" href="#"></a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <span class="social_heading">OR DIAL</span>
                    <span class="social_info"><a class="color_animation" href="tel:883-335-6524">(941) 883-335-6524</a></span>
                </div>
            </div>
        </section>

        <!-- ============ Contact Section  ============= -->

        <section id="contact">
           
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="inner contact">
                            <!-- Form Area -->
                            <div class="contact-form">
                                <!-- Form -->
                                <form id="contact-us" method="post" action="contact.php">
                                    <!-- Left Inputs -->
                                    <div class="col-md-6 ">
                                        <!-- Name -->
                                        <input type="text" name="name" id="name" required="required" class="form" placeholder="Name" />
                                        <!-- Email -->
                                        <input type="email" name="email" id="email" required="required" class="form" placeholder="Email" />
                                        <!-- Subject -->
                                        <input type="text" name="subject" id="subject" required="required" class="form" placeholder="Subject" />
                                    </div><!-- End Left Inputs -->
                                    <!-- Right Inputs -->
                                    <div class="col-md-6">
                                        <!-- Message -->
                                        <textarea name="message" id="message" class="form textarea"  placeholder="Message"></textarea>
                                    </div><!-- End Right Inputs -->
                                    <!-- Bottom Submit -->
                                    <div class="relative fullwidth col-xs-12">
                                        <!-- Send Button -->
                                        <button type="submit" id="submit" name="submit" class="form-btn">Send Message</button> 
                                    </div><!-- End Bottom Submit -->
                                    <!-- Clear -->
                                    <div class="clear"></div>
                                </form>
                            </div><!-- End Contact Form Area -->
                        </div><!-- End Inner -->
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ Footer Section  ============= -->

        <footer class="sub_footer">
            <div class="container">
                <div class="col-md-4"><p class="sub-footer-text text-center">&copy; Restaurant 2014, Theme by <a href="https://themewagon.com/">ThemeWagon</a></p></div>
                <div class="col-md-4"><p class="sub-footer-text text-center">Back to <a href="#top">TOP</a></p>
                </div>
                <div class="col-md-4"><p class="sub-footer-text text-center">Built With Care By <a href="#" target="_blank">Us</a></p></div>
            </div>
        </footer>


        <script type="text/javascript" src="js/jquery-1.10.2.min.js"> </script>
        <script type="text/javascript" src="js/bootstrap.min.js" ></script>
        <script type="text/javascript" src="js/jquery-1.10.2.js"></script>     
        <script type="text/javascript" src="js/jquery.mixitup.min.js" ></script>
        <script type="text/javascript" src="js/main.js" ></script>

    </body>
</html>