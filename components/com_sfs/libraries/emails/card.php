<style>
@charset "utf-8";
/* CSS Document */
.container-card{
	width:700px;
	margin:auto;
	color:#828282;
}
.bold{
	font-weight:bold;
}
.br-clear{
	clear:both;
}
.card-view, .card-back, .card-back-code, .card-back-text{
	float:left;
}

.card-view, .card-back{
	width:445px;
	border:2px solid #999999;
	height:235px;
	border-radius:10px;
	margin-left: 100px;
	/*box-shadow: 0 4px 2px -2px gray;*/	
}

/* card-view -----------------------------*/
.card-view{
	/*margin-right:24px;*/
	margin-right:4px;
}
.card-view-top,.card-view-text-valid, .card-view-text-numbers, .card-view-monthdayyear, .card-view-name-logo{
	padding-left:32px;
}
.card-view-top{
	height:50px;
	position:relative;
}
.card-view-text{
	position:absolute;
	top:35px;
	text-transform:uppercase;
}
.card-view-logo-top{
	position:absolute;
	top:20px;
	right:10px;
	height:45px;
}
.card-view-text-valid{
}
.card-view-text-numbers{
	margin-top:25px;
	font-size:26px;
	position:relative;
}
.card-view-text-numbers .bold{
	margin-right:27px;
}
.card-view-text-numbers .small{
	font-size:10px;
	position:absolute;
	left:35px;
	top:30px;
	font-weight:normal;
}
.card-view-monthdayyear{
	margin-top:10px;
	position:relative;
}
.validthru, .validthru-arrow,.monthdayyear, .date, .credit{
	font-size:11px;
	width:25px;
	position:absolute;
	/*top:8px;*/
	top:4px;
	left:180px;
	text-transform:uppercase;
}
.validthru-arrow{
	top:19px;
	left:211px;
}
.monthdayyear{
	top:0px;
	left:225px;
}
.date{
	/*top:17px;*/
	font-size: 15px;
    font-weight: bold;
    left: 225px;
    top: 24px;
    width: 113px;
}
.credit{
	top:0px;
	right:60px;
	left:auto;
	font-size:16px;
}

.card-view-name-logo{
	position:relative;
	margin-top:60px;
}
.card-view-logo{
	width:auto;
	position:absolute;
	right:30px;
	top:0px;
}
.card-view-name{
	font-size: 21px;
    padding-top: 16px;
	text-transform:uppercase;
}

/* card-back -----------------------------*/
.card-back{
	
}
.card-back-line{	
	margin-top:42px;
	height:50px;
	border-top: 50px solid #999999;
}

.card-back-code-text{
	margin-top:25px;
}
.card-back-code, .card-back-text{
	height:48px;
}

.card-back-code{
	border:1px solid #999999;
	margin-left:10px;
	width:258px;
	position:relative;
}
.card-back-code-sub-text{
	position:absolute;
	right:-1px;
	top:-1px;
	border:1px solid #f00;
	width:51px;
	/*height:20px;*/
	text-align:right;
	padding:5px;
}
.card-back-code-sub-text-bottom{
	margin-top:28px;
	margin-left:20px;
	text-transform:uppercase;
}
.card-back-text{
	margin-left:5px;
	width:auto;
}
.card-back-logo{
	height:106px;
	position:relative;
}
.card-back-logo .logo{
	position:absolute;
	right:30px;
	bottom:50px;
}
</style>
<?php

$info_of_card = json_decode( $info_of_card ); 
///$CardNumber = base64_decode($info_of_card->CardNumber);
$CardNumber = SfsHelper::getCardNumber($info_of_card->CardNumber);
$airline = SFactory::getAirline();
$logo = $airline->logo;
if(strlen($info_of_card->ValidThru) == 4){
    $valid_thru = substr( $info_of_card->ValidThru, 0,2) . '/' . substr( $info_of_card->ValidThru, -2);
}elseif(strlen($info_of_card->ValidThru) == 6){
    $valid_thru = substr( $info_of_card->ValidThru, 0,2) . '/' . substr( $info_of_card->ValidThru, 2,2) . '/' . substr( $info_of_card->ValidThru, -2);
}

?><div style="margin-top:10px;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif">
	<div class="container-card" >
    	<div style="width: 100%; float: left;">
    		<div class="card-view">
	        	<div class="card-view-top">
	            	<div class="card-view-text">
	                	creditcard
	                </div>
	                <div class="card-view-logo-top">
	                    <?php if ($logo && (file_exists($logo))):?>
	                	    <img src="<?php echo JURI::base() . '/' . $logo; ?>" height="45" />
	                    <?php endif;?>
	                </div>
	            </div><!--End card-view-top-->
	            <div class="card-view-text-valid">
	            	<span>Valid for: </span> <strong><?php echo $info_of_card->TypeOfService;?></strong>
	            </div>
	            <div class="card-view-text-numbers bold">
	            	<span class="bold"><?php echo substr($CardNumber, 0,4);?></span>
	                <span class="bold"><?php echo substr($CardNumber, 4,4);?></span>
	                <span class="bold"><?php echo substr($CardNumber, 8,4);?></span>
	                <span><?php echo substr($CardNumber, -4);?></span>
	                <span class="small"><?php echo substr($CardNumber, 0,4);?></span>
	            </div>
	            <div class="card-view-monthdayyear">
	            	<div class="card-view-text-monthdayyear">
	                	<span class="validthru">
	                    	valid thru
	                    </span>
	                    <span class="validthru-arrow">
	                    	<img src="<?php echo JURI::base();?>components/com_sfs/assets/images/arrow.png" width="9" height="9" />
	                    </span>                    
	                    <span class="monthdayyear">
                            <?php if(strlen($info_of_card->ValidThru) == 4):?>
                                year/month
                            <?php else:?>
                                year/month/day
                            <?php endif;?>
	                    </span>
	                    <span class="date">
	                    	<?php echo $valid_thru;?>
	                    </span>
	                </div>
	                <span class="credit">
	                    credit
	                </span>
	            </div><!--End card-view-monthdayyear-->
           		<div class="card-view-name-logo">
	           		<div class="card-view-name">
	                <?php echo $info_of_card->PassengerName;?>
	                </div>
	                <div class="card-view-logo">
	                    <img class="logo" src="<?php echo JURI::base();?>components/com_sfs/assets/images/logo.png" width="78" height="47" />
                </div>
           </div> 
        </div><!--End card-view-->
    	</div>
    	<div style="width: 100%; float: left; margin-top: 5px;">
    		<div class="card-back">
	        	<div class="card-back-line"></div>
	            <div class="card-back-code-text">
	            	<div class="card-back-code">
	                	<div class="card-back-code-sub-text bold">
	                    	<?php echo $info_of_card->CVC;?>
	                    </div>
	                    <div class="card-back-code-sub-text-bottom">
	                    	<?php echo $info_of_card->PassengerName;?>
	                    </div>
	            	</div>
	                <div class="card-back-text">
	                	CVC Code
	            	</div>
	                <br class="br-clear" />
	            </div><!--End card-back-code-text-->
	            <div class="card-back-logo">
	            	<img class="logo" src="<?php echo JURI::base();?>components/com_sfs/assets/images/logo.png" width="78" height="47" />
	            </div>
	        </div><!--End card-back-->
    	</div>
        <br class="br-clear" />
    </div><!--End container-card-->
</div>     