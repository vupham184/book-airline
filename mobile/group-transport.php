<?php
include_once("getInfo.php");
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bus Transport</title>

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
                        Bus Transport
                    </div>
                </div>
            </header>
            <div id="main-content" class="site-content">
                <section class="section-1">
                    <div class="row">
						<p>
                        There was a bus arranged for your transportation to the <br>
						<?php echo $group_transport->to_hotel_name;?> airport hotel
						</p>
						<p>
						Pick up time <?php echo ( $group_transport->date_expire_time != '' ) ? date("d-M-Y H:i", strtotime( $group_transport->date_expire_time)) : "";?> hours<br>
						Pick up at <?php echo $group_transport->from_airport_name;?>
						</p>
						<p>
							Additional information: <?php echo $group_transport->comment;?>
						</p>
						<p>
						Your bus service is provided to you by our partner <?php echo $group_transport->g_name;?>
						</p>
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
