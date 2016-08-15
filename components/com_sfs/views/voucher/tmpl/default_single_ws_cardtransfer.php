<script>
function printDiv(id) {

	//Get the HTML of div
	divID = "sfs-print-wrapper" +id;
	var divElements = document.getElementById(divID).innerHTML;
	//Get the HTML of whole page
	var oldPage = document.body.innerHTML;
	//Reset the page's HTML with div's HTML only
	document.body.innerHTML =
		"<html><head><title></title></head><body>" +
		divElements + "</body>";
	//Print Page
	window.print();
	//Restore orignal HTML
	document.body.innerHTML = oldPage;
}
</script>
<?php
defined('_JEXEC') or die();
?>
<?php /* @var $wsBooking Ws_Do_Book_Response */?>
<?php $wsBooking = @$this->wsBooking?>
<?php
$i = 0;
$airline = SFactory::getAirline();
$airplusparams= $airline->airplusparams;
$info_of_card = $_GET['info_of_card_ap_taxi'];
$ap_voucher = json_decode($info_of_card);

?>
<div id="sfs-print-wrapper<?php echo $i;?>" class="fs-14">
<div class="htmlvoucher">

	<div style="background-color:#fff; border:solid 10px #cc2030; padding:10px;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif;">

        <?php
        if ($airline->logo)
        {
            ?>
            <img style="float:right;" src="<?php echo $airline->logo?>" height="45px" >
        <?php
        }
        ?>
		<br clear="all" />
		<div class="heading-buttons" style="display:none;">
			<?php
			$rePrint = JRequest::getInt('reprint',0);
			if( $rePrint == 0 ) :
			?>
			<a onclick="window.parent.SqueezeBox.close();" class="sfs-button float-right">Close</a>
			<a onclick="printDiv(<?php echo $i;?>);window.parent.SqueezeBox.close();" class="sfs-button float-right">Print</a>
			<?php else :?>
			<a onclick="self.close();" class="sfs-button float-right">Close</a>
			<a onclick="printDiv(<?php echo $i;?>);self.close()" class="sfs-button float-right">Print</a>
			<?php endif;?>
		</div>
		<p> Dear <?php echo $ap_voucher->PassengerName; ?>,
		</p>
        <p>
        With this below Non card present creditcard issued by <?php echo $airline->name;?> <?php echo $airline->country_name ? '('.$airline->country_name.')':'';?> you will be able to pay for the taxi transportation from the <?php echo $airline->name;?> <?php echo $airline->country_name ? '('.$airline->country_name.')':'';?> to.
        <br />
        <strong>Hotel <?php echo $this->DataVoucher->hotel_name ;?> city center</strong>
        <br />
        <span>Address:</span><span style="padding-left:30px;"><?php echo ($this->DataVoucher->hotel_name != '' ) ? $this->DataVoucher->hotel_name. ', '. $this->DataVoucher->address : $this->DataVoucher->hotel_name . $this->DataVoucher->address;?></span>
        <br />
        <span>Phone:</span><span style="padding-left:30px;"><?php echo $this->DataVoucher->telephone;?></span>
        <br />
        and back from the hotel to the airport. (Please hold on to this creditcard for your return transfer to the airport). For this return transfer we have reserved <strong><?php echo $ap_voucher->Value;?> Euro</strong>, on this creditcard and it will have limited validity but at least untill your onward travel if applicable.
        <br />
        Please note that this Creditcard can only be used for Taxi transportation and not for other services.
        </p> 
        <p>
        Please <strong>give the numbers</strong> on this voucher<strong> to the taxi driver</strong> so he can enter the creditcard number in his credit card machine but keep the voucher for your return taxi trasfer.
        </p>
        <?php require_once JPATH_COMPONENT.'/libraries/emails/card.php';?>
        <p>
        Kindly note that for your return transfer you will have to make your own taxi reservation.<br />
        You can do this by arranging this in the taxi or through the front desk of your hotel.
        </p>
	</div>
</div>
</div>

<p align="center" style="color:#666; display:none;" >Share your experience on www.strandedexperience.com</p>
