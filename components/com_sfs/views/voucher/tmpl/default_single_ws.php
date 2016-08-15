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
<script>
function CloseParent(){
	///window.parent.location.reload( true );
	
	if( self.parent.document.getElementById("closeVoucherPrintForm") == null ){
		self.parent.document.getElementById("closeGroupVoucherForm").click();
	}
	else {
		self.parent.document.getElementById("closeVoucherPrintForm").click();
	}
	window.parent.SqueezeBox.close();
}
</script>
<?php
defined('_JEXEC') or die();
?>
<?php /* @var $wsBooking Ws_Do_Book_Response */?>
<?php $wsBooking = @$this->wsBooking?>
<?php
$phone_number = '';
foreach($this->trace_passengers as $passengerA) {
	foreach($passengerA as $passenger) {
		if($phone_number == '' )
			$phone_number = $passenger->phone_number;
	}
}
$i = 0;
///$passengersA = !empty($this->trace_passengers) ? $this->trace_passengers : $this->passengers;
$names = !empty($this->formatTracePassengerNames) ? $this->formatTracePassengerNames : $this->formatPassengerNames;
foreach((array)$names as $room):
	$nameText = implode(', ', $room);

$airline = SFactory::getAirline();
?>
<div id="sfs-print-wrapper<?php echo $i;?>" class="fs-14">
<div class="htmlvoucher">

	<div style="background-color:#fff; border:solid 10px #82ADF1; padding:10px;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif">

        <?php
        if ($airline->logo)
        {
            ?>
            <img style="margin-bottom: 20px;" src="<?php echo $airline->logo?>"/>
        <?php
        }
        ?>

		<div class="heading-buttons">
			<?php
			 $t = 0;
			$rePrint = JRequest::getInt('reprint',0);
			if( $rePrint == 0 ) :
			?>
			<!--<a onclick="window.parent.SqueezeBox.close();" class="sfs-button float-right">Close</a>-->
            <a onclick="CloseParent();" class="sfs-button float-right">Close</a>
			<a onclick="printDiv(<?php echo $i;?>);window.parent.SqueezeBox.close();" class="sfs-button float-right">Print</a>
			<?php else :  $t = 1;?>
			<a onclick="self.close();" class="sfs-button float-right">Close</a>
			<a onclick="printDiv(<?php echo $i;?>);self.close()" class="sfs-button float-right">Print</a>
			<?php endif;?>
            
            <?php
			if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')) :
			if( $t == 0 ):
			?>
				<a onclick="self.close();" class="sfs-button float-right">Close</a>
				<a onclick="window.print();self.close()" class="sfs-button float-right">Print</a>
			<?php
			endif;
			endif;
			?>
			
		</div>
		<h2 style="margin-top:0">YOUR ACCOMMODATION DETAILS:</h2>

		<?php if($this->hotel):?>
		<p>
			<?php
			echo $this->hotel->name.'<br />'.$this->hotel->address.'<br />'.$this->hotel->zipcode.', '.$this->hotel->city;?>
			<br />
            <?php
            if($this->hotel->telephone)
                echo 'Tel: '.$this->hotel->telephone;
            else
                echo 'Tel: '.JText::_('COM_SFS_NO_TELEPHONE')
            ?>
		</p>
		<?php endif;?>

		<h2>YOUR VOUCHER DETAILS:</h2>

		<p>

			<?php if( $this->voucher->get('payment_type','airline') == 'passenger' ):	?>
				NON PREPAID VOUCHER,<br />
				<?php
				echo 'Your total estimated charges will be '.$this->voucher->getTotalCharged().' '.$this->system_currency;?>
				<br />
			<?php endif;?>
			<?php if( isset($nameText) ) : ?>
				Voucher issued for: <?php echo $nameText; ?><br />
			<?php endif;?>
			<?php ///if( isset($passengers[$i]->phone_number) ) : 
			if( $phone_number != '' ):
			?>
				Telephone number: <?php echo $phone_number;//$passengers[$i]->phone_number; ?><br />
			<?php endif;?>

			Booking reference: <?php echo $wsBooking->BookingReference; ?><br />
			Voucher for <?php echo $this->voucher->seats;?> person(s) entitled for:<br />

			<?php
			if( (int)$this->voucher->vgroup == 1 ) {
				$totalOfRooms = (int)$this->voucher->sroom + (int)$this->voucher->sdroom + (int)$this->voucher->troom + (int)$this->voucher->qroom;
				echo "- One night accommodation in ".$totalOfRooms." different rooms";
		 	} else {
		 		switch ( (int)$this->voucher->room_type )
		 		{
		 			case 1:
		 				echo "- One night accommodation in single room";
		 				break;
		 			case 2:
		 				echo "- One night accommodation in double room";
		 				break;
		 			case 3:
		 				echo "- One night accommodation in triple room";
		 				break;
		 			case 4:
		 				echo "- One night accommodation in quad room";
		 				break;
		 			default:
		 				break;
		 		}
		 	}

			$date 		 = JFactory::getDate($this->voucher->get('date'));
			$dayFrom 	 = (int)$date->format('d');
			$dayFromText = SfsHelper::addOrdinalNumberSuffix( $dayFrom );
			$dateTo 	 = SfsHelperDate::getNextDate('d', $date);
			$dateToText  = SfsHelper::addOrdinalNumberSuffix( (int) $dateTo).' of '.SfsHelperDate::getNextDate('F Y', $date);

			echo ' starting '.$dayFromText.'   ending '.$dateToText;

			if( (int)$this->voucher->vgroup == 1 ) {
				$totalOfRooms = (int)$this->voucher->sroom + (int)$this->voucher->sdroom + (int)$this->voucher->troom + (int)$this->voucher->qroom;
				if($this->voucher->sroom)
				{
					echo '<br/>&nbsp;&nbsp;'.$this->voucher->sroom.' single room';
				}
				if($this->voucher->sdroom)
				{
					echo '<br/>&nbsp;&nbsp;'.$this->voucher->sdroom.' single/double room';
				}
				if($this->voucher->troom)
				{
					echo '<br/>&nbsp;&nbsp;'.$this->voucher->troom.' triple room';
				}
				if($this->voucher->qroom)
				{
					echo '<br/>&nbsp;&nbsp;'.$this->voucher->qroom.' quad room';
				}
		 	}
			?>
			<br />

			<?php
			if($this->hotel):
				$mealplan = $this->hotel->getMealPlan();
			?>
				<?php if( (int)$this->voucher->lunch && (int)$this->voucher->v_lunch ) : ?>
					<?php
					$serviceHour = $mealplan->lunch_service_hour;
		 			if( (int)$serviceHour == 1 ) {
		 				echo '- Pre arranged lunch available 24 hours';
		 			} else {
		 				$lunchText=' available between '.str_replace(':','h',$mealplan->lunch_opentime).' and '.str_replace(':','h',$mealplan->lunch_closetime);
		 				echo '- Pre arranged lunch'.$lunchText;
		 			}
		 			?>
		 			<br />
				<?php endif;?>

				<?php if( (int)$this->voucher->mealplan && (int)$this->voucher->v_mealplan ) : ?>
					<?php
		 			$stopSellingTime = $mealplan->stop_selling_time;
		 			if( (int)$stopSellingTime == 24 ) {
		 				echo '- Pre arranged dinner available 24 hours';
		 			} else {
		 				echo '- Pre arranged dinner available until '.str_replace(':','h',$mealplan->stop_selling_time);
		 			}
		 			?>
		 			<br />
				<?php endif;?>

				<?php if( (int)$this->voucher->breakfast && (int)$this->voucher->v_breakfast ) : ?>
					<?php
		 			if( (int)$mealplan->bf_service_hour == 1 ) {
		 				$breakfastText=' available 24 hours';
		 			} else {
		 				$breakfastText=' available between '.str_replace(':','h',$mealplan->bf_opentime).' and '.str_replace(':','h',$mealplan->bf_closetime) ;
		 			}
		 			echo '- Pre arranged breakfast'.$breakfastText;
		 			?>
		 			<br />
				<?php endif;?>

			<?php endif;?>
		</p>

		<h2>Transportation to hotel</h2>
		<p>
			Transport to accommodation included: No
		</p>

		<?php
		if( $this->voucher->comment || $this->voucher->return_flight_number || ( isset($this->airline->params['show_general_comment']) && (int)$this->airline->params['show_general_comment'] == 1 ) ) :?>
			<h2>General comments</h2>
			<?php
			if(isset($this->airline->params['show_general_comment']) && (int)$this->airline->params['show_general_comment'] == 1) {
	 			$generalComment = $this->airline->getVoucherComment();
		 		if($generalComment)
		 		{
		 		?>
		 			<?php echo $generalComment?><br/>
		 		<?php
		 		}
	 		}
			?>

			<?php if($this->voucher->return_flight_number) : ?>
			Your new flight details: Flight number <?php echo $this->voucher->return_flight_number?> departure date <?php echo JHTML::_('date', $this->voucher->return_flight_date , JText::_('DATE_FORMAT_LC3'), false )?><br/><br/>
			<?php endif;?>

			<?php
				if( $this->voucher->comment) {
					echo $this->voucher->comment;
				}
			?>

		<?php endif;?>
	</div>

	<div style="background-color:#FFDE73; border:solid 10px #FFB700; padding:10px; margin-top:15px;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif">
		<p style="font-weight: bold;margin-top:5px;">
			Hotel billing information:<br />
			<?php echo 'Voucher '.$this->voucher->seats.' person'?><br />
			<?php if($this->voucher->return_flight_number) : ?>
			Old flight info: Flight number <?php echo JString::strtoupper($this->voucher->flight_code);?> departure date <?php echo JHTML::_('date', $this->voucher->date , JText::_('DATE_FORMAT_LC3'), false )?><br/>
			New flight info: Flight number <?php echo $this->voucher->return_flight_number?> departure date <?php echo JHTML::_('date', $this->voucher->return_flight_date , JText::_('DATE_FORMAT_LC3'), false )?><br/>
			<?php endif;?>
		</p>
		<p style="font-size: 90%">
            Flight number:<?php echo  $this->voucher->flight_code?><br />
			Booking reference: <?php echo $wsBooking->BookingReference;?> <br/>
			This reservation is booked and payable by <?php echo $wsBooking->PropertyBookings[0]->Supplier?>
		 	with reference <?php echo $wsBooking->PropertyBookings[0]->SupplierReference?> under no circumstances should
 			the customer be charged for this booking.<br/>
		</p>
		<p style="font-size:90%;">
            Booking made through <?php echo $wsBooking->PropertyBookings[0]->Supplier?>  partner sfs-web.com.
		</p>
	</div>
    <?php foreach( $this->card_airplusws as $card_airplusws) :?>
    <?php if(!empty($card_airplusws['info_of_card_ap_taxi'])) : ?>
    	<?php foreach($card_airplusws['info_of_card_ap_taxi'] as $info_of_card) : ?>
    		<?php $_GET['info_of_card_ap_taxi'] = json_encode($info_of_card);?>
    		<?php include 'default_single_ws_cardtransfer.php';?>
    	<?php endforeach;?>
    <?php endif; ?>
    
    <?php if(!empty($card_airplusws['info_of_card_ap_meal'])) : ?>
    	<?php foreach($card_airplusws['info_of_card_ap_meal'] as $info_of_card) : ?>
    		<?php $_GET['info_of_card_ap_meal'] = json_encode($info_of_card);?>
    		<?php include 'default_single_ws_cardmealplan.php';?>
    	<?php endforeach;?>
    <?php endif; ?>
	<?php endforeach;?>
</div>
</div>

<?php # separate through lines, don't need for the last one?>
<?php if( ($i + 1) < count($this->formatPassengerNames)) : ?>
	<br/>
	<br/>
<?php endif; ?>

<?php
	$i++;
	endforeach;
?>

<p align="center" style="color:#666;display:none;">Share your experience on www.strandedexperience.com</p>
