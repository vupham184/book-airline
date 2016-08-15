<?php
include_once("getInfo.php");
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Messages list</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="css/main.css"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic'
          rel='stylesheet' type='text/css'>
		  <style>
		  <?php echo $cssCustom;?>
		  </style>
</head>
<body>
<div id="main-container" class="site-wrapper">
    <div class="site-inner">
        <div class="messages-list-wrapper">
            <header class="header-wrapper">
                <div class="logo-main-wrap">
                    <div class="container">
                        <div class="logo-main col-sm-12">
                            <a href="index.php?code=<?php echo $code;?>" class="logo-wrap">
                                <img src="<?php echo ( $TMPcontent->logo_header_mobile != '' ) ? '../' . $TMPcontent->logo_header_mobile : 'images/logo3.png';?>"/>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="pre-page clearfix">
                    <a href="index.php?code=<?php echo $code;?>" class="btn-pre">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                    <div class="btn-label">
                        Your Messages
                    </div>
                </div>
            </header>
            <div id="main-content" class="site-content">
                <section class="common-section section-messages">
                    <div class="items-section">
                        <div class="item-wrapper padding-left">
                            <a href="message-detail.php?code=<?php echo $code;?>" class="item clearfix">
                                <div class="icon-item messages">
                                    <div class="icon-wrap">
                                        <img src="images/thinking_violet.png"/>
                                        <!--if have not notification
                                        <img src="images/thinking_blue.png"/>
                                        -->
                                        <i class="alert-number">1</i>
                                    </div>
                                </div>
                                <div class="title-wrap">
                                    <div class="title">
                                        Apologies for the incon...
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </section>
                <section class="common-section section-recent-messages">
                    <div class="title-section-common clearfix">
                        <div class="icon-title">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <div class="title">
                            Recent Messages
                        </div>
                    </div>
                    <div class="items-section">
                        <div class="item-wrapper padding-left">
                            <a href="message-detail.php?code=<?php echo $code;?>" class="item clearfix">
                                <div class="icon-item taxi-transfers">
                                    <div class="icon-wrap">
                                        <img src="images/thinking_blue.png"/>
                                    </div>
                                </div>
                                <div class="title-wrap">
                                    <div class="title">
                                       Apologies for the incon...
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <!--    <div class="items-section">
                        <div class="item-wrapper padding-left">
                            <a href="message-detail.php?code=<?php echo $code;?>" class="item clearfix">
                                <div class="icon-item taxi-transfers">
                                    <div class="icon-wrap">
                                        <img src="images/thinking_blue.png"/>
                                    </div>
                                </div>
                                <div class="title-wrap">
                                    <div class="title">
                                        Message 1 title
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="items-section">
                        <div class="item-wrapper padding-left">
                            <a href="message-detail.php?code=<?php echo $code;?>" class="item clearfix">
                                <div class="icon-item taxi-transfers">
                                    <div class="icon-wrap">
                                        <img src="images/thinking_blue.png"/>
                                    </div>
                                </div>
                                <div class="title-wrap">
                                    <div class="title">
                                        Message 1 title
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </section>
            </div>
			-->
            <footer class="footer-wrapper">

            </footer>
        </div>
        <!-- /#main-content -->
    </div>
    <!-- /.site-inner -->
</div>
<!-- /#main-container -->

<!-- script files -->
<script src="js/modernizr.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/main.js"></script>
</body>
</html>
