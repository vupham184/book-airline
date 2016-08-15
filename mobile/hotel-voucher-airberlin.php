<?php
include_once("getInfo.php");
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hotel voucher airplan</title>

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
        <div class="common-site-wrap air-berlin-hotel">
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
                        Hotel voucher
                    </div>
                </div>
            </header>
            <div id="main-content" class="site-content">
                <section class="section-1 padding-all">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 voucher-name">
                            <div class="voucher-text">
                                <p class="title">Hotel Voucher</p>
                                <p class="sub-title">for: <strong>Roelf Nienhuis / MR</strong></p>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 voucher-logo clearfix">
                            <div class="img-voucher">
                                <img src="images/logo2.png"/>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="section-2">
                    <div class="section-wrap">
                        <div class="title-section">
                            Hotel The Airport Grand
                        </div>
                        <div class="voucher-detail">
                            <div class="address common-block clearfix">
                                <div class="address-block clearfix">
                                    <div class="title-common">
                                        Address:
                                    </div>
                                    <div class="content-common">
                                        <span>6121 East Airport Road</span><br>
                                        <span>90040, Commerce, Brussels</span><br>
                                        <span>Tel: 0032 3 728 3600</span>
                                    </div>
                                </div>
                            </div>
                            <div class="valid common-block">
                                <div class="title-common">
                                    Valid for:
                                </div>
                                <div class="content-common">
                                    <span>Voucher for 1 person(s) entitled for:</span><br>
                                    <span>One night accommodation in single room</span><br>
                                    <span><strong>starting 1st ending 2nd of March 2016</strong></span><br><br>
                                    <span>Hotel Voucher Code: <strong>MS1</strong></span><br>
                                </div>
                            </div>
                            <div class="comment common-block">
                                <div class="title-common">
                                    Comments:
                                </div>
                                <div class="content-common">
                                    <span>Test general comment airline 1</span>
                                </div>
                            </div>
                        </div>
                        <div class="billing-info">
                            <div class="title-billing">
                                Hotel billing information:
                            </div>
                            <div class="content">
                                <span>Block code: DEV3-MAP2-V3_010316_AF_CB_1986</span><br>
                                <span>Voucher code: MS1</span><br><br>
                                <span>Voucher 1 person</span><br><br>
                                <span>Booking reference: 1502228</span><br>
                                <span>This reservation is booked and payable by Exclusively Hotels with reference 1502228 under no circumstances should the customer be charged for this booking.</span><br>
                                <span>Booking made through Exclusively Hotels partner sfs-web.com.</span><br>
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
