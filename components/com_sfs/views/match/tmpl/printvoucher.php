<?php
defined('_JEXEC') or die;
$voucherCode = JRequest::getString('voucher');

if( !empty($voucherCode) ) :

$airline = SFactory::getAirline();

$params = JComponentHelper::getParams('com_sfs');
$system_currency = $params->get('sfs_system_currency','EUR');

$document = JFactory::getDocument();
$document->addStylesheet( JURI::base().'components/com_sfs/assets/css/print.css', 'text/css' , 'print' );

$isHtmlFormat = false;

if( isset($airline->params['voucher_format']) && (int)$airline->params['voucher_format'] == 1 ) {
	$isHtmlFormat = true;
} 
			
if(!$isHtmlFormat):
?>

<div class="heading-buttons">
	<a onclick="self.close()" class="sfs-button float-right">Close</a>
	<a onclick="window.print();self.close();return false;" class="sfs-button float-right">Print</a>	
</div>
<div class="voucher-img">
	<img width="723" height="822" src="index.php?option=com_sfs&task=ajax.drawvoucher&format=raw&voucher=<?php echo $voucherCode;?>" border="0" />
</div>

<?php else :

$voucher = new SVoucher();
$voucher->loadFromCode($voucherCode);

$firstname1 = JRequest::getVar('firstname1');
$firstname2 = JRequest::getVar('firstname2');
$firstname3 = JRequest::getVar('firstname3');
$lastname1 = JRequest::getVar('lastname1');
$lastname2 = JRequest::getVar('lastname2');
$lastname3 = JRequest::getVar('lastname3');

$returnflight 		= JRequest::getVar('returnflight');
$returnflightdate 	= JRequest::getVar('returnflightdate');

if( ! $voucher->return_flight_number && $returnflight ) {
	$voucher->return_flight_number = $returnflight;
	$voucher->return_flight_date = $returnflightdate;
}

$hotel_id = $voucher->get('hotel_id');		
$hotel = SFactory::getHotel((int)$hotel_id);
$paymentType = $voucher->get('payment_type'); 
$totalAmount = $voucher->calculateEstimatedCharge();
$seats		= $voucher->get('seats');
$vgroup 	= $voucher->get('vgroup');
$room_type 	= $voucher->get('room_type');

$blockDate 	= $voucher->get('date');
$date = JFactory::getDate($blockDate);
$dayFrom = (int)$date->format('d'); 		
$dayFromText = SfsHelper::addOrdinalNumberSuffix( $dayFrom ); 		 		 		 	
$dateTo = SfsHelperDate::getNextDate('d', $date); 		 		
$dateToText = SfsHelper::addOrdinalNumberSuffix( (int) $dateTo).' of '.SfsHelperDate::getNextDate('F Y', $date);

$namesArray = array();
$namesText=null;
$tracePassenger = $voucher->getTracePassengers();

if($tracePassenger){	
	if( $fullname1 = SfsHelper::getFullnameFrom($tracePassenger->firstname1, $tracePassenger->lastname1 ) ) {
		$namesArray[] = $fullname1;
	}	
	if( $fullname2 = SfsHelper::getFullnameFrom($tracePassenger->firstname2, $tracePassenger->lastname2 ) ) {
		$namesArray[] = $fullname2;
	}	
	if( $fullname3 = SfsHelper::getFullnameFrom($tracePassenger->firstname3, $tracePassenger->lastname3 ) ) {
		$namesArray[] = $fullname3;
	}		
} else {
	if( $fullname1 = SfsHelper::getFullnameFrom($firstname1, $lastname1 ) ) {
		$namesArray[] = $fullname1;
	}	
	if( $fullname2 = SfsHelper::getFullnameFrom($firstname2, $lastname2 ) ) {
		$namesArray[] = $fullname2;
	}	
	if( $fullname3 = SfsHelper::getFullnameFrom($firstname3, $lastname3 ) ) {
		$namesArray[] = $fullname3;
	}		
}

if(count($namesArray)){
	$namesText = implode(', ', $namesArray);	
}
?>


<div id="sfs-print-wrapper" class="fs-14">

<div class="htmlvoucher">	

	<div style="background-color:#fff; border:solid 10px #82ADF1; padding:10px;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif">
	
		<div class="heading-buttons">
			<a onclick="self.close()" class="sfs-button float-right">Close</a>
			<a onclick="window.print();self.close();return false;" class="sfs-button float-right">Print</a>
		</div>
	
		<h2 style="margin-top:0">YOUR ACCOMMODATION DETAILS:</h2>
		
		<p>
		<?php echo $hotel->name.'<br />'.$hotel->address.'<br />'.$hotel->zipcode.', '.$hotel->city;?><br /><?php echo 'Tel: '.$hotel->telephone?>
		</p>
		
		
		<h2>YOUR VOUCHER DETAILS:</h2>
		<p>
			<?php		
			if($paymentType=='passenger'):				
			?>		
				NON PREPAID VOUCHER,<br />
				<?php echo "Your total estimated charges will be ".$totalAmount." ".$system_currency;?><br />
			<?php endif;?>		
			
			<?php if($namesText):
				
			?>
				Voucher issued for: <?php echo $namesText; ?><br />
			<?php endif;?>
			
			Hotel Voucher Code: <?php echo $voucher->voucher_code; ?><br />
			Voucher for <?php echo $seats;?> person(s) entitled for:<br />	
			<?php	
			if( (int)$vgroup == 1 ) {				
				echo "- One night accommodation in ".$seats." different rooms";		
		 	} else {			
		 		if( (int) $room_type == 3 ) {
		 			echo "- One night accommodation in a triple room";
		 		} else {
		 			echo "- One night accommodation";
		 		}
		 	}		 	  		 
			?>		
			<?php echo ' starting '.$dayFromText.'   ending '.$dateToText;?><br />

			<?php
			
			if($hotel){
				$mealplan = $hotel->getMealPlan();
						
				if((int)$voucher->lunch && (int)$voucher->v_lunch) { 
				?>
				
					<?php 	
					$service_hour = $mealplan->lunch_service_hour;
		 			if( (int) $service_hour == 1 ) {
		 				echo "- Pre arranged lunch available 24 hours";
		 			} else {
		 				$lunchText=' available between '.str_replace(':','h',$mealplan->lunch_opentime).' and '.str_replace(':','h',$mealplan->lunch_closetime);
		 				echo "- Pre arranged lunch".$lunchText;	
		 			}	
		 			?>
		 		<br />
	 			<?php  			
		 		} 
		 		
			 		
				if( (int)$voucher->mealplan && (int)$voucher->v_mealplan ) { 
				?>
				
					<?php 			
		 			$stop_selling_time = $mealplan->stop_selling_time;
		 			if( (int) $stop_selling_time == 24 ) {
		 				echo "- Pre arranged dinner available 24 hours";
		 			} else {
		 				echo "- Pre arranged dinner available until ".str_replace(':','h',$mealplan->stop_selling_time);	
		 			} 				
		 			?>
		 			<br />
	 			<?php  			
		 		} 
		 				 		
		 		if( (int)$voucher->breakfast && (int)$voucher->v_breakfast ) {	
		 		?>
				
					<?php 	 			
		 			if( (int)$mealplan->bf_service_hour == 1 ) {
		 				$breakfastText=' available 24 hours';	
		 			} else {
		 				$breakfastText=' available between '.str_replace(':','h',$mealplan->bf_opentime).' and '.str_replace(':','h',$mealplan->bf_closetime) ;
		 			} 			 				
		 			echo "- Pre arranged breakfast".$breakfastText;	 			
		 			?>
		 		<br />
	 			<?php  			
		 		}	 		
			}
			?>		
		</p>
		
		
		<h2>Transportation to hotel</h2>
		<p>
		<?php
		$transport = $hotel->getTransportDetail();
		if( !empty($voucher->transport) && !empty($transport) ):
		?>				
			<?php
				$transportText = 'Transport to accommodation included: ';
	 			switch ( (int)$transport->transport_available ) {
					case 1:
						$transportText .= 'Yes';
						break;
					case 2:
						$transportText.='Not necessary (walking distance)';
						break;							
					default : 
						$transportText .= 'No';				
						break;
				}
				$transportText .= (int)$transport->transport_complementary == 1 ? '<br/>Complimentary: Yes':'<br/>Complimentary: No';
				echo $transportText;
				
				$transportText = '';			
				$transport->operating_hour = (int)$transport->operating_hour;
	 			if($transport->operating_hour == 0 ){
					$transportText .='Operation hours: Not available';
				} else if($transport->operating_hour == 1) {
					$transportText .='Operation hours: 24 hours';						
				} else if($transport->operating_hour == 2) {
					$transportText .='Operation hours: From '.str_replace(':','h',$transport->operating_opentime).' till '.str_replace(':','h',$transport->operating_closetime);	
				}
				$transportText .='   Every: '.$transport->frequency_service.' minutes';
				echo '<br/>'.$transportText;			
			?>
			<br/>	
			<?php echo 'Transport details: '.$transport->pickup_details?>
				
		<?php else:?>
			<?php echo 'Transport to accommodation included: No'; ?>
		<?php endif;?>
		
		</p>
		
		<?php 
		$vouchercomment = JRequest::getString('vouchercomment');
		if( $voucher->comment || $vouchercomment || $voucher->return_flight_number || ( isset($airline->params['show_general_comment']) && (int)$airline->params['show_general_comment'] == 1 ) ) :?>
			<h2>General comments</h2>			
			<?php
			if(isset($airline->params['show_general_comment']) && (int)$airline->params['show_general_comment'] == 1) {
	 			$generalComment = $airline->getVoucherComment();
		 		if($generalComment)
		 		{	
		 		?>
		 			<?php echo $generalComment?><br/>
		 		<?php 	
		 		}
	 		} 
			?>
			
			<?php if($voucher->return_flight_number) : ?>
			Your new flight details: Flight number <?php echo $voucher->return_flight_number?> departure date <?php echo JHTML::_('date', $voucher->return_flight_date , JText::_('DATE_FORMAT_LC3'), false )?><br/><br/>
			<?php endif;?>
							
			<?php 
				if( $voucher->comment) {
					echo $voucher->comment;
				} else {					
					if($vouchercomment) echo $vouchercomment;
				}
			?>
					
		<?php endif;?>
		
	</div>
	
	<div style="background-color:#FFDE73; border:solid 10px #FFB700; padding:10px; margin-top:15px;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif">
		<p style="font-weight: bold;margin-top:5px;">
			Hotel billing information:<br />
			<?php echo 'Block code:   '.$voucher->blockcode?><br />
			<?php echo 'Voucher code:   '.$voucher->voucher_code?><br />
			<?php echo 'Voucher '.$seats.' person'?><br />	
			<?php if($voucher->return_flight_number) : ?>
			Old flight info: Flight number <?php echo JString::strtoupper($voucher->flight_code);?> departure date <?php echo JHTML::_('date', $blockDate , JText::_('DATE_FORMAT_LC3'), false )?><br/>
			New flight info: Flight number <?php echo $voucher->return_flight_number?> departure date <?php echo JHTML::_('date', $voucher->return_flight_date , JText::_('DATE_FORMAT_LC3'), false )?><br/>
			<?php endif;?>
		</p>
		
		<p style="font-size:90%;">
		<?php if( $paymentType!='passenger'):?>
			<?php if( isset($airline->params['voucher_vat_comment_line']) && strlen(trim($airline->params['voucher_vat_comment_line'])) > 0 ) : ?>
				<?php echo $airline->params['voucher_vat_comment_line'];?>
			<?php else : ?>
			*Bestelbon - vrijstelling van de BTW art 42 para 2 alinea 5 W.BTW
			<?php endif;?>
			<br/>
		<?php endif?>
		
		<?php if($paymentType!='passenger'):?>
			<span><?php echo 'Booking guaranteed by '.$airline->name.' as agreed with your hotel on SFS-web.com'?></span>
		<?php endif?>
		</p>
	</div>
	
	<p align="center" style="color:#666">Share your experience on www.strandedexperience.com</p>
	
	
</div>

</div>

<?php endif;?>



<?php endif;?>
