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
<style type="text/css">
        dt,dd{
  display:inline-block;
  width:49%;
}
@media only screen and (max-width: 480px) {
  dt,dd{
    display:block;
    width:100%;
  }
}
dd{
  padding:5px;
  margin-bottom: 10px;
  border:black solid 2px;
}
.r2{
  margin-top:40px;
  margin-bottom:40px;
}
footer{
text-align:left;
padding: 10px 0;
}
.header-bottom{
  margin-top:10px;
  margin-bottom:30px;
  background:red;
  img{
    float:right;
  }
}
.box_header{
    font-weight: bold;
    font-size: 34px;
}

.row{padding: 10px;}
</style>

</head>
<body>

<?php
    class ArrayINI implements ArrayAccess, IteratorAggregate {
        private $lang;

        public function __construct($ini) {
            $this->lang = parse_ini_file($ini);
        }

        function __invoke($offset) {
            return $this->offsetGet($offset);
        }

        public function getIterator() {
            return new ArrayIterator($this->lang);
        }

        public function offsetSet($offset, $value) {
            if (is_null($offset)) {
                $this->lang[] = $value;
            } else {
                $this->lang[$offset] = $value;
            }
        }

        public function offsetExists($offset) {
            return isset($this->lang[$offset]);
        }

        public function offsetUnset($offset) {
            unset($this->lang[$offset]);
        }

        public function offsetGet($offset) {
            return isset($this->lang[$offset]) ? $this->lang[$offset] : null;
        }
    }
    
    $ini = new ArrayINI("../language/de-EN/de-EN.com_sfs.ini");
    $ini_en = new ArrayINI("../language/en-GB/en-GB.com_sfs.ini");   
?>
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
            <main>
    <!--   row 1 -->
    <div class="row">
      <div class="col-sm-12">
            <img src="../media/media/images/Logo_AB_for_Vouchers.png" width="90%" style="text-align:center;">
      </div>
      <div class="col-sm-12">
            <div class="box_header">
                <?php echo $ini('COM_MOBILYTI'); ?>/Mobility voucher             
            </div>
      </div>
      <div class="clearfix">
        <div class="col-sm-12" style="margin: 20px 0;">
              <div class="col-sm-8">
                  <?php echo $ini('COM_WE_OFFER_YOU'); ?>/We offer you complementary car hire
              </div>
              <div class="col-sm-4">
                  <img src="<?php echo '../'.$dataRental[0]->logo; ?>" width="40%" style="float:right;">
              </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="box">
          <dl>
            <dt>
                <?php  echo $ini("COM_NAME_PASSENGER"); ?>/Name of passengers
            </dt>
            <dd>
                <?php 
                  foreach ($dataRental[0]->group_name as $k => $val){                      
                      echo $val->first_name. ' ' . $val->last_name . "<br />";                     
                  } 
                ?>  
            </dd>
            <dt><?php  echo $ini("COM_DATE"); ?>/Date</dt>
            <dd>
              <?php 
                  $originalDate = $dataRental[0]->blockdate;
                  $newDate = date("d F Y", strtotime($originalDate));
                  echo $newDate;
              ?>
            </dd>
            <dt><?php  echo $ini("COM_PICKUP_LOCATION"); ?>/Rental pick-up location</dt>
            <dd><?php echo $dataRental[0]->location[0]->code; ?></dd>
            <dt><?php  echo $ini("COM_RETURN_LOCATION"); ?>/Rental return location</dt>
            <dd><?php echo $dataRental[0]->location[1]->code; ?></dd>
            <dt><?php  echo $ini("COM_FLIGHT"); ?>/Flight number</dt>
            <dd><?php echo $content->flight_number; ?></dd>
          </dl>
         
        </div>
      </div>
      <div class="col-sm-6">
        <div class="box">
          <dl>
            <dt>Vouchercode</dt>
            <dd style="border:none;"><?php echo $content->rental_blockcode; ?></dd>
            <dt>Billing address</dt>
            <dd style="border:none;">Air Berlin PLC & Co. Luftverkehrs KG • <br />
                Saatwinkler Damm 42-43 • 13627 Berlin
            </dd>
            <dt>Vehicle size</dt>
            <p>
                CDMR (Hertz) bzw CLMR (Sixt) (CDP 713764 / HCC 26501792 9992) <br />
                SX Agentur-Nummer/Agency No; 593115; <br/>
                SX CD Nummer/CD-No. 9939466
            </p>
            
          </dl>
         
        </div>
      </div>
    </div>
    <!--   row 2 -->
    <div class="row r2">
      <div class="col-sm-6">        
        <?php  echo $ini("COM_TEXT_RENTAL_CAR"); ?>
      </div>
      <div class="col-sm-6">
        <?php  echo $ini_en("COM_TEXT_RENTAL_CAR"); ?>        
      </div>
    </div>
  </main>
  <footer>
    <div class="row">
      <div class="col-sm-6">
        Powered by sfs-360.com
      </div>
      <div class="col-sm-6">
        DE / Stand: April 2016 | EN / Date of last edit: April 2016
      </div>
    </div>
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
