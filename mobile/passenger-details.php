<?php
include_once("getInfo.php");
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Passenger detail</title>

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
                <div class="clearfix pre-page">
                    <a href="index.php?code=<?php echo $code;?>" class="btn-pre">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                    <div class="btn-label">
                        Your onwards flight details
                    </div>
                </div>
            </header>
            <div id="main-content" class="site-content">
                <section class="common-section section-messages-detail padding-all">
                    <div class="common-block header-message clearfix">
                        <div class="icon-in-left">
                            <div class="icon">
                                <img src="images/plan_blue.png"/>
                            </div>
                        </div>
                        <div class="content-in-right">
                            <div class="content-wrap">
                                <div class="content-line message-from">
                                    <strong>
                                        <span class="title">Name:</span>
                                        <span class="content"> <?php echo $content->firstname;?> / <?php echo $content->lastname;?></span>
                                    </strong>
                                </div>
                                <div class="content-line message-date">
                                    <strong>
                                        <span class="title">pnr:</span>
                                        <span class="content"> <?php echo $content->pnr;?></span>
                                    </strong>
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
                                    <p>
                                        <strong>
                                            From - To: <?php echo $content->dep;?> > <?php echo $content->arr;?> <br>
                                            Flight Number: <?php echo $content->flight_number;?> <br>
                                            Dep scheduled: <?php echo $content->std;?><br>
                                            Arr scheduled: <?php echo $content->etd;?><br>
                                        </strong>
                                    </p>
                                    <p>Please plan ahead and give yourself enough time to go to the airport and prepare
                                        for your flight </p>
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
