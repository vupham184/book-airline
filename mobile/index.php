<?php
include_once("getInfo.php");
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Passenger overview default</title>

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
        <div class="passenger-overview-default">
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
            </header>
            <div id="main-content" class="site-content">
                <section class="common-section section-messages">
                    <div class="title-section-common clearfix">
                        <div class="title">Your Messages</div>
                    </div>
                    <div class="items-section">
                        <div class="item-wrapper padding-left">
                            <a href="messages-list.php?code=<?php echo $code;?>" class="item clearfix">
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
                <section class="common-section section-services">
                    <div class="title-section-common clearfix">
                        <div class="title">Your Services</div>
                    </div>
					<?php if( isset( $dataService[4] ) ): ?>
                    <div class="items-section obj-service obj4">
                        <div class="item-wrapper padding-left">
                            <a href="taxi-transfer-voucher-creditcard.php?code=<?php echo $code;?>" class="item clearfix">
                                <div class="icon-item taxi-transfers">
                                    <div class="icon-wrap">
                                        <img src="images/car_blue.png"/>
                                    </div>
                                </div>
                                <div class="title-wrap">
                                    <div class="title">
                                        Taxi transfers
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
					<?php endif;?>
					<?php if( isset( $dataService[2] ) ): ?>
                    <div class="items-section obj-service obj2">
                        <div class="item-wrapper padding-left">
                            <a href="mealplan-voucher-creditcard.php?code=<?php echo $code;?>" class="item clearfix">
                                <div class="icon-item mealplan">
                                    <div class="icon-wrap">
                                        <img src="images/eat_blue.png"/>
                                    </div>
                                </div>
                                <div class="title-wrap">
                                    <div class="title">
                                        Mealplan <?php echo $dmealplan_refreshment->valamount;?> 
					<?php echo ( strtolower($dmealplan_refreshment->currency) == 'eur') ? 'Euro' : '';?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
					<?php endif;?>
					<?php if( isset( $dataService[1] ) ): ?>
                    <div class="items-section obj-service obj1">
                        <div class="item-wrapper padding-left">
                            <a href="hotel-voucher.php?code=<?php echo $code;?>" class="item clearfix">
                                <div class="icon-item econtel">
                                    <div class="icon-wrap">
                                        <img src="images/calendar_blue.png"/>
                                    </div>
                                </div>
                                <div class="title-wrap">
                                    <div class="title">
                                        <?php echo $dhotel->name;?>
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div>
					<?php endif;?>
					<?php if( isset( $dataService[3] ) ): ?>
					<div class="items-section obj-service obj3">
                        <div class="item-wrapper padding-left">
                            <a href="group-transport.php?code=<?php echo $code;?>" class="item clearfix">
                                <div class="icon-item econtel">
                                    <div class="icon-wrap">
                                        <img src="images/bus_blue.png"/>
                                    </div>
                                </div>
                                <div class="title-wrap">
                                    <div class="title">
                                        Group transport
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div>
					<?php endif;?>
					<?php if( isset( $dataService[6] ) ): ?>
					<div class="items-section obj-service obj6">
                        <div class="item-wrapper padding-left">
                        <?php 
                            $url_page= 'rental-car.php';
                            if( isset($_GET['tmp'])  && $_GET['tmp']==41){
                                $url_page = 'rental-car-41.php';
                            }
                        ?>
                            <a href="<?php echo $url_page; ?>?code=<?php echo $code;?>" class="item clearfix">
                                <div class="icon-item econtel">
                                    <div class="icon-wrap">
                                        <img src="images/rental-car_blue.png"/>
                                    </div>
                                </div>
                                <div class="title-wrap">
                                    <div class="title">
                                        Rental car
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div>
					<?php endif;?>
					<?php if( isset( $dataService[5] ) ): ?>
					<div class="items-section obj-service obj5">
                        <div class="item-wrapper padding-left">
                            <a href="trains.php?code=<?php echo $code;?>" class="item clearfix">
                                <div class="icon-item econtel">
                                    <div class="icon-wrap">
                                        <img src="images/trains-logo.jpg"/>
                                    </div>
                                </div>
                                <div class="title-wrap">
                                    <div class="title">
                                        Train voucher
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div>
					<?php endif;?>
					
                </section>
                <section class="common-section section-rebooked-flight-details">
                    <div class="title-section-common clearfix">
                        <div class="title">Your rebooked flight details</div>
                    </div>
                    <div class="items-section">
                        <div class="item-wrapper padding-left">
                            <a href="passenger-details.php?code=<?php echo $code;?>" class="item clearfix">
                                <div class="icon-item plan-travel">
                                    <div class="icon-wrap">
                                        <img src="images/plan_blue.png"/>
                                    </div>
                                </div>
                                <div class="title-wrap">
                                    <div class="title">
                                        <?php echo $content->firstname;?> 
                                        <?php echo $content->lastname;?> <span class="sub-title">PNR: <?php echo $content->pnr;?> </span>
                                    </div>
                                </div>
                            </a>
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