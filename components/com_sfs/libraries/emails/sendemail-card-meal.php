<style>
@charset "utf-8";
/* CSS Document */
.container-card{
	width:945px;
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
	height:290px;
	border-radius:10px;
	/*box-shadow: 0 4px 2px -2px gray;*/
	box-shadow: 1px 16px 7px -2px rgba(153, 153, 153, 0.27);
	-moz-box-shadow: 1px 16px 7px -2px rgba(153, 153, 153, 0.27);
	-webkit-box-shadow: 1px 16px 7px -2px rgba(153, 153, 153, 0.27);
}

/* card-view -----------------------------*/
.card-view{
	margin-right:24px;
}
.card-view-top,.card-view-text-valid, .card-view-text-numbers, .card-view-monthdayyear, .card-view-name-logo{
	padding-left:32px;
}
.card-view-top{
	height:70px;
	position:relative;
}
.card-view-text{
	position:absolute;
	top:52px;
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
	margin-top:45px;
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
	right:31px;
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
	background-color: #999999;
	margin-top:42px;
	height:50px;
}

.card-back-code-text{
	margin-top:35px;
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
	right:15px;
	bottom:28px;
}
</style>
<?php
$info_of_card = json_decode( $info_of_card_meal ); 
$CardNumber = SfsHelper::getCardNumber($info_of_card->CardNumber);
?>
<div style="padding:10px; margin-top:10px;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif">
	<div class="container-card" style="width:945px; margin:auto; color:#828282;">
    
    	<div class="card-view" style="float:left; width:445px;
	border:2px solid #999999;
	height:290px;
	border-radius:10px; margin-right:24px; ">
        	<div class="card-view-top" style="padding-left:32px; height:70px; position:relative;">
            	<div class="card-view-text" style="text-transform:uppercase; padding-top:52px; float:left;">
                	creditcard
                </div>
                <div class="card-view-logo-top" style="height:45px; text-align:right">
                	{logo}
                </div>
                <br style="clear:both;" />
            </div>
            <div class="card-view-text-valid" style="padding-left:32px;">
            	<span>Valid for: </span> <strong><?php echo $info_of_card->TypeOfService;?></strong>
            </div>
            <div class="card-view-text-numbers bold" style="font-weight:bold; padding-left:32px; margin-top:10px;font-size:26px;	position:relative;">
            	<span class="bold" style="font-weight:bold; margin-right:27px;"><?php echo substr($CardNumber, 0,4);?></span>
                <span class="bold" style="font-weight:bold; margin-right:27px;"><?php echo substr($CardNumber, 4,4);?></span>
                <span class="bold" style="font-weight:bold; margin-right:27px;"><?php echo substr($CardNumber, 8,4);?></span>
                <span><?php echo substr($CardNumber, -4);?></span>
                <br />
                <span class="small" style="font-size:10px;position:absolute;left:35px;top:30px;font-weight:normal; float:left;"><?php echo substr($CardNumber, 0,4);?></span>
            </div>
            <div class="card-view-monthdayyear" style="padding-left:32px; margin-top:10px;	position:relative;">
            	<div class="card-view-text-monthdayyear">
                	<span class="validthru" style="font-size:11px;width:25px;position:absolute;top:4px;left:180px;text-transform:uppercase; width:30px; float:left; margin-top:3px; margin-left:60px;">
                    	valid thru
                    </span>
                    <span class="validthru-arrow"  style="font-size:11px;width:25px;position:absolute;top:4px;left:180px;text-transform:uppercase;  width:15px; float:left; margin-top:10px;">
                    	<img src="<?php echo JURI::base();?>components/com_sfs/assets/images/arrow.png" width="9" height="9" />
                    </span>                    
                    <span class="monthdayyear"  style="font-size:11px;width:25px;position:absolute;top:4px;left:180px;text-transform:uppercase;top:0px;left:225px;  width:200px; float:left;">
                    	year/month
                        <br />
                        <span class="date" style="font-size:11px;width:25px;position:absolute;top:4px;left:180px;text-transform:uppercase;font-size: 15px;font-weight: bold;left: 225px;top: 24px;width: 113px;">
                            <?php echo substr( $info_of_card->ValidFrom, 0,2) . '/' . substr( $info_of_card->ValidFrom, -2);?>
                            - <?php echo substr( $info_of_card->ValidThru, 0,2) . '/' . substr( $info_of_card->ValidThru, -2);?>
                        </span>
                    </span>
                    <span class="credit" style="font-size:11px;width:25px;position:absolute;top:4px;left:180px;text-transform:uppercase;top:0px;right:60px;left:auto;font-size:16px; margin-top:0px; float:left;">
                        credit
                    </span>
                 <br style="clear:both;" />
                </div>
                <br style="clear:both;" />
            </div><!--End card-view-monthdayyear-->
           <div class="card-view-name-logo" style="padding-left:32px; position:relative;
	margin-top:33px;">
           		<div class="card-view-name" style="font-size: 21px; padding-bottom: 16px; text-transform:uppercase; float:left; width:295px;">
                <?php echo $info_of_card->PassengerName;?>
                </div>
                <div class="card-view-logo" style="width:80px; float:right; margin-right:10px">
                    <img class="logo" src="<?php echo JURI::base();?>components/com_sfs/assets/images/logo.png" width="78" height="47" style="padding:0px 10px 30px 0px;"  />
                </div>
                <br class="br-clear" style="clear:both;" />
           </div> 
        </div><!--End card-view-->
        
        <div class="card-back" style="float:left; width:445px;
	border:2px solid #999999;
	height:290px;
	border-radius:10px;
	box-shadow: 1px 16px 7px -2px rgba(153, 153, 153, 0.27);
	-moz-box-shadow: 1px 16px 7px -2px rgba(153, 153, 153, 0.27);
	-webkit-box-shadow: 1px 16px 7px -2px rgba(153, 153, 153, 0.27);">
        	<div class="card-back-line" style="background-color: #999999;
	margin-top:42px;
	height:50px;"></div>
            <div class="card-back-code-text" style="margin-top:35px;">
            	<div class="card-back-code" style="float:left; height:48px; border:1px solid #999999;margin-left:10px;width:258px;position:relative;">
                	<div class="card-back-code-sub-text bold" style="font-weight:bold; position:absolute;right:-1px;top:-1px;
	border:1px solid #f00;width:51px;text-align:right;padding:5px; float:right;">
                    	<?php echo $info_of_card->CVC;?>
                    </div>
                    <div class="card-back-code-sub-text-bottom" style="margin-top:28px;
	margin-left:20px;
	text-transform:uppercase;">
                    	<?php echo $info_of_card->PassengerName;?>
                    </div>
            	</div>
                <div class="card-back-text" style="float:left; height:48px; margin-left:5px;
	width:auto;">
                	CVC Code
            	</div>
                <br class="br-clear" style="clear:both;" />
            </div><!--End card-back-code-text-->
            <div class="card-back-logo" style="height:56px;
	position:relative; text-align:right; padding-top:50px">
            	<img class="logo" style="margin:0px 10px 30px 0px;" src="<?php echo JURI::base();?>components/com_sfs/assets/images/logo.png" width="78" height="47" />
            </div>
        </div><!--End card-back-->
        
        <br class="br-clear" style="clear:both;" />
    </div><!--End container-card-->
</div>    