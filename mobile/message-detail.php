<?php
include_once("getInfo.php");
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Message detail</title>

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
        <div class="messages-detail-wrapper">
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
                    <a href="messages-list.php?code=<?php echo $code;?>" class="btn-pre">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                    <div class="btn-label">
                        Your Messages
                    </div>
                </div>
            </header>
            <div id="main-content" class="site-content">
                <section class="common-section section-messages-detail padding-all">
                    <div class="common-block header-message clearfix">
                        <div class="icon-in-left">
                            <div class="icon">
                                <img src="images/thinking_blue.png"/>
                            </div>
                        </div>
                        <div class="content-in-right">
                            <div class="content-wrap">
                                <div class="content-line message-from">
                                    <span class="title">From:</span>
                                    <span class="content"> <?php echo $dairline->name;?> customer services</span>
                                </div>
                                <div class="content-line message-date">
                                    <span class="title">Date:</span>
                                    <span class="content"> <?php echo $content->std;?></span>
                                </div>
                                <div class="content-line message-subject">
                                    <span class="title">Subject:</span>
                                    <span class="content"> Apologies for the inconvenience</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="common-block body-message">
                        <div class="icon-in-left">
                            <div class="icon"></div>
                        </div>
                        <div class="content-in-right">
                            <div class="content-wrap">
                                <div class="content-line message-detail">
                                    <p class="title">Message:</p>
                                    <br>
									<p>Dear <?php echo $content->firstname;?> <?php echo $content->lastname;?>,</p><br><br>
                                    <p>We apologise for the inconvenience caused by this unfortunate cancellation of flight <?php echo $content->flight_number;?>  due to technical problems we hope that your stay at the <?php echo $dhotel->name;?> will be enjoyable.</p>
                                    <br>
                                    <p>Despite the fact that we do our utmost to prevent situations like this unfortunately sometimes we can not prevent this from happening</p>
                                    <p>That is why we try to inform you in the best way possible through these online services, if any questions you are welcome to contact our customer care team</p>
									<p>In the mean time we remain</p>
                                    <br>
                                    <p>With best regards,</p>
                                    <br>
                                    <p>
                                        Jurg Kaizer<br>
                                        Manager Customer care services
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
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
