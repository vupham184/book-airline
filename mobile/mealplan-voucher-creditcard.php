<?php
include_once("getInfo.php");
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mealplan voucher creditcard</title>

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
        <div class="common-voucher-creditcard maelplan-voucher-creditcard-wrapper">
            <header class="header-wrapper">
                <div class="logo-main-wrap">
                    <div class="container">
                        <div class="logo-main col-sm-12">
                            <a href="/" class="logo-wrap">
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
                        Food and Beverage voucher
                    </div>
                </div>
            </header>
            <div id="main-content" class="site-content">
                <section class="common-section section-taxi-transfer-voucher padding-all">
                    <div class="content-section-wrap">
                        <div class="description-wrap">
                            <p>With this below Virtual creditcard you will be able to pay for food
                                and beverage at the airport F&B outlets that accept creditcards. </p>
                            <p>We have reserved <?php echo $dmealplan_refreshment->valamount;?> 
					<?php echo ( strtolower($dmealplan_refreshment->currency) == 'eur') ? 'Euro' : '';?> on this creditcard and it has a limited validity but at least
                                until your onward travel if applicable. </p>
                        </div>
                        <div class="virtual-card-wrap">
                            <div class="card-common front-card">
                                <div class="section-top">
                                    <div class="row">
                                        <div class="col-xs-6 type-card">
                                            <div class="type-card-text pull-left">
                                                <h4>CREDITCARD</h4>
                                                <h4>Valid for: <strong>meal</strong></h4>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 logo-card ">
                                            <div class="logo-card-image pull-right">
												<img src="<?php echo ( $TMPcontent->logo_creditcard_mobile != '' ) ? '../' . $TMPcontent->logo_creditcard_mobile : 'images/logo-credit-card.png';?>"/>
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="section-number">
                                    <div class="row">
                                        <div class="col-xs-3 the-number number-group-1">
                                            4184
                                        </div>
                                        <div class="col-xs-3 the-number number-group-2">
                                            5794
                                        </div>
                                        <div class="col-xs-3 the-number number-group-3">
                                            3984
                                        </div>
                                        <div class="col-xs-3 the-number number-group-4">
                                            7411
                                        </div>
                                    </div>
                                </div>
                                <div class="section-date-thru">
                                    <div class="row">
                                        <div class="col-xs-3 number-group-1">
                                            4184
                                        </div>
                                        <div class="col-xs-6 date-valid-thru">
                                            <div class="content clearfix">
                                                <div class="in-left pull-left">
                                                    <h4>VALID</h4>
                                                    <h4>THRU</h4>
                                                </div>
                                                <div class="in-right pull-left">
                                                    <h4>YEAR/MONTH</h4>
                                                    <h4><strong>16/03</strong></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-3 type-card">
                                            CREDIT
                                        </div>
                                    </div>
                                </div>
                                <div class="section-logo-card-type">
                                    <img src="images/master-card.png"/>
                                </div>
                            </div>
                            <div class="card-common back-card">
                                <div class="magnetic-strip"></div>
                                <div class="signature">

                                    <div class="cvc-code clearfix">
                                        <div class="code-number-wrap">
                                            <div class="code-number"><strong>533</strong></div>
                                        </div>
                                        <div class="cvc-text">
                                            <span>CVC Code</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="section-logo-card-type">
                                    <img src="images/master-card.png"/>
                                </div>
                            </div>
                        </div>
                        <div class="content-bottom">
                            <p>Please note that this Creditcard can only be used for Food and Beverage and not for other
                                services or items.
                            <p>Please give the numbers on this creditcard at the counter when you have to pay for your
                                order so they can enter the creditcard number in the credit card machine. </p></p>
                            <p>If you have not used the entire sum on this creditcard at the same vendor you may use the
                                remaining value at another vendor but always before your onward travel as your
                                creditcard has a limited validity. </p>
                        </div>
                    </div>

                </section>
                <div class="link-to-wrap">
                    <a class="link-to" href="virtual-creditcard-details.php?code=<?php echo $code;?>">more details about virtual creditcard ></a>
                </div>
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
