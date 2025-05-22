<!DOCTYPE html>
<?php session_start();?>
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
    </head>

    <body>

        
        <div id="top" class="starter_container bg">
            <div class="follow_container">
               <ul style="list-style-type:none;padding-left: 0 !important;margin: 0;">
                    <li><?php
                        if(isset($_SESSION["RoleID"])){
                            echo '<a class="color_animation" href="View/dangXuat.php" onclick="return confirm(\'Are you sure to logout?\');">LOGOUT</a>';
                            echo '<span style="margin: 0 40px;">|</span>';
                            if ($_SESSION["RoleID"] == 1){
                            echo '<a class="color_animation" href="nhanvienkho.php">MANAGE</a>';
                            }
                            if ($_SESSION["RoleID"] == 2){
                            echo '<a class="color_animation" href="nhanvientieptan.php">MANAGE</a>';
                            }
                            if ($_SESSION["RoleID"] == 3){
                            echo '<a class="color_animation" href="nhanvienkho.php">MANAGE</a>';
                            }
                            if ($_SESSION["RoleID"] == 4){
                            echo '<a class="color_animation" href="khachhang.php">MANAGE</a>';
                            }
                        }else{
                            echo '<a class="color_animation" href="?dangnhap">LOGIN</a>';
                            echo '<span style="margin: 0 40px;">|</span>';
                            echo '<a class="color_animation" href="?account">CREATE ACCOUNT</a>';
                        }
                        if(isset($_GET["dangnhap"])){
                            include_once("View/dangNhap.php");
                        }elseif(isset($_GET["account"])){
                            include_once("View/account.php");
                        }
                        ?>
                    </li>
                    <hr>  
                    <li>
                        <?php
                            include_once("View/Categories.php");
                        ?>
                    </li>
                    </ul>
            </div>
        </div>
        <section id ="pricing" class="description_content">
            <div class="text-content container"> 
                <div class="container">
                    <div class="row">
                            <div id="w">
                            <ul id="portfolio">
                                <li>
                                    <?php
                                        if(isset($_REQUEST["action"])&&$_REQUEST["action"]=="xemctsp"){
                                            include_once("View/monan.php");
                                        } 
                                        else{
                                            include_once("View/Products.php");
                                        }                                  
                                    ?>
                                </li>
                            </ul><!-- @end #portfolio -->
                        </div><!-- @end #w -->
                            <!-- Phần này sẽ hiển thị chi tiết sản phẩm -->
                        <div id="product-details" class="container" style="display: none;">
                            <!-- Thông tin chi tiết sản phẩm sẽ được hiển thị ở đây -->
                        </div>
                    </div>
                </div>
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

        <!-- Chat Box Icon -->
        <div id="chat-icon" title="Chat với chúng tôi">
            <i class="fa fa-comments" aria-hidden="true"></i>
        </div>

        <!-- Chat Box Window -->
        <div id="chat-box" style="display:none;">
            <div id="chat-header">
                <span>Chat hỗ trợ</span>
                <button id="chat-close">&times;</button>
            </div>
            <div id="chat-content">
                <div id="chat-messages"></div>
            </div>
            <div id="chat-input-area">
                <input type="text" id="chat-input" placeholder="Nhập tin nhắn...">
                <button id="chat-send">Gửi</button>
            </div>
        </div>

        <script>
            const chatIcon = document.getElementById('chat-icon');
            const chatBox = document.getElementById('chat-box');
            const chatClose = document.getElementById('chat-close');
            const chatSend = document.getElementById('chat-send');
            const chatInput = document.getElementById('chat-input');
            const chatMessages = document.getElementById('chat-messages');

            // Mở chat
            chatIcon.addEventListener('click', () => {
                chatBox.style.display = 'flex';
                chatIcon.style.display = 'none';
                chatInput.focus();
            });

            // Đóng chat
            chatClose.addEventListener('click', () => {
                chatBox.style.display = 'none';
                chatIcon.style.display = 'flex';
            });

            // Thêm tin nhắn vào khung chat
            function addMessage(text, sender) {
                const msg = document.createElement('div');
                msg.classList.add('message', sender);
                msg.textContent = text;
                chatMessages.appendChild(msg);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Gửi tin nhắn khi click nút Gửi
            chatSend.addEventListener('click', () => {
                const text = chatInput.value.trim();
                if(text === '') return;

                addMessage(text, 'user');
                chatInput.value = '';

                // Gửi câu hỏi lên backend xử lý
                fetch('chat_process.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                    },
                    body: 'question=' + encodeURIComponent(text)
                })
                .then(response => response.json())
                .then(data => {
                    if(data.reply) {
                        addMessage(data.reply, 'bot');
                    } else {
                        addMessage("Xin lỗi, tôi không hiểu câu hỏi của bạn.", 'bot');
                    }
                })
                .catch((error) => {
                    console.error('Fetch error:', error);
                    addMessage("Có lỗi xảy ra, vui lòng thử lại sau.", 'bot');
                });
            });

            // Cho phép nhấn Enter để gửi
            chatInput.addEventListener('keydown', e => {
                if(e.key === 'Enter'){
                    chatSend.click();
                    e.preventDefault();
                }
            });
        </script>

    </body>
</html>