<?php
include_once("getInfo.php");
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trains</title>

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
                        Train 
                    </div>
                </div>
            </header>
            <div id="main-content" class="site-content">
                <section class="section-1">
					<div class="items-section">
                        <div class="item-wrapper padding-left">
							<div class="">
								<div class="icon-wrap">
                                        <img style="width:100px" src="images/trains-logo.jpg"/>
								</div>
							</div>
							<div class="col-xs-10">
								<div class="row text-left">
									<p><br><br>DB train coupon for <?php echo $content->firstname;?> <?php echo $content->lastname;?></p>
<p>
From: Cologne / Bonn Flughafen</p>
<p>
To: Amsterdam Central Station</p>
								</div><br>
								<div class="row text-center">
									<img style="width:250px" src="images/Train_QR_barcode.jpg"/>
								</div><br>
							</div>
                        </div>
                    </div>
                    <div class="row">
						<p>
							<b>Information for the passengers</b>
						</p>
						<p><i>
							

This coupon is only valid on the date of issue and on the following day, for the mentioned destination in second class and only if presented by the person named on the coupon.
The coupon is not transferable. Miles will not e credited for this coupon.
						</i></p>
<p>
<b>The following terms and conditions apply</b><i>
The coupon is only valid for certain mainline stations in Germany and as such only the following may be entered as departure / destination stations: Berlin, Dresden, Dusseldorf, Frankfurt, Hamburg, Hanover, Karlsruhe/Baden-Baden, Cologne/Bonn Flughafen, Munich, Munster/Osnabruck, Nuremberg, Saarbrucken, Stuttgart, Westerland</i>
</p>
<p><i>
The coupon is only valid for passenger trains operated by Deutche Bahn AG. Trains classified as City Night Line (CNL) and Intercity Express ( ICE) Sprinter train services may also be booked subject to cumpulsory reservation and seperate surcharge. 
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
