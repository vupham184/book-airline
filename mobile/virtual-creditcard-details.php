<?php
include_once("getInfo.php");
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Virtual CreditCard details</title>

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
        <div class="taxi-transfer-voucher-creditcard-wrapper">
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
                        Virtual creditcards
                    </div>
                </div>
            </header>
            <div id="main-content" class="site-content">
                <div class="section-content-wrap">
                    <span>The Virtual creditcard or Non card present credit card is a normal Mastercard transaction wich can be done by all credit Mastercard acceptants which have the possibility to type the numbers of the credidcard into the system.</span>
                    <br>
                    <div class="credit-card-wrap align-right">
                        <img src="images/credit-card-test.png"/>
                    </div>
                    <span>As the virtual creditcard can obviously not be swiped through an machine.</span>
                    <br>
                    <br>
                    <span>To accept the creditcard the acceptant will have to have the creditcard number, the expiry date and the CVC code which you find on your voucher.</span>
                    <br>
                    <br>
                    <span>Your Airline has reserved a specific amount on the creditcard for you. Which in case of a taxi voucher should be sufficient to travel from the airport to your hotel and back but the creditcard wil only be valid for transportation.</span>
                    <br>
                    <br>
                    <span>Or in case of a Refreshment Food and Beverage voucher, the amount on the voucher will be either <?php echo $dmealplan_refreshment->valamount;?> 
					<?php echo $dmealplan_refreshment->currency;?>, depending on the delay time.</span>
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
