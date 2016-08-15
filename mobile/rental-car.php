<?php
include_once("getInfo.php");
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rental car</title>

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
        <div class="common-site-wrap hotel-voucher">
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
                        Rental car 
                    </div>
                </div>
            </header>
            <div id="main-content" class="site-content">
                <section class="section-1">
					<div class="items-section">
                        <div class="item-wrapper padding-left">
							<div class="col-xs-2">
								<div class="icon-wrap">
									<img src="images/logo-hert.png"/>
								</div>
							</div>
							<div class="col-xs-10">
								<div class="row text-left"><br><br><br>
									<p>There is a Hertz rental car arranged for your convenience</p>
								</div>
								<div class="row text-center">
									<p>
										<strong>Pick up locaction: TXL Berlin Tegel Airport</strong></p>
									<p>
									address: Airportstrasse 1234, 12345 Berlin
									</p>
								</div>
								<div class="row text-center">								
									<p>
										<strong>Drop of locaction: HAJ Hannover airport</strong></p>
									<p>
									address: Airportstrasse 51234, 56345 Hannover
									</p>
								</div>

							</div>
                        </div>
                    </div>
                    <div class="row">
						<p>
							<br><br><br><br><b>Information for the passengers</b>
						</p>
						<p><i>
This voucher entitles you to car hire of our car hire partner Hertz, on a single occasion and for a period of 24 hours from the start of hire. You will be entering into a contract of hire. This voucher pays for the car hire contract. The voucher only applies to cars collected from and returned to a location in Germany.
						</i></p>
<p>
<b>The following terms and conditions apply</b><br><i>
To be able to enter into a contract of hiire you will need to show your drivers licence, as well as a credit card or EC card. Your credit card or EC card will be debited with at least EUR 160,00 as deposit when you hire a car.
This amount will be re-credited to your credit card or EC card when you return the vehicle, unless the car hire partner is entitled to further claims ( e.g. if you have failed to fill the tank with fuel). </i>
</p>
<p><i>
The deposit will be increased if you take out additional insurance or choose other optional extra's.<br>
For further information, please ask at the rental car station when you rent the vehicle.
</i></p>
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
