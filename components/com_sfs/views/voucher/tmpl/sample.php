<?php
defined('_JEXEC') or die();
$document = JFactory::getDocument();
$document->addStylesheet( JURI::base().'components/com_sfs/assets/css/print.css', 'text/css' , 'print' );
?>

<div id="sfs-print-wrapper" class="fs-14">
<div class="htmlvoucher">	

	<div style="background-color:#fff; border:solid 10px #82ADF1; padding:10px;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif">
		
		<div class="heading-buttons">
			<a onclick="window.parent.SqueezeBox.close();" class="sfs-button float-right">Close</a>
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
			Hotel Voucher Code: <?php echo $this->voucher->voucher_code; ?><br />
			Voucher for <?php echo $this->voucher->seats;?> person(s) entitled for:<br />
			One night accommodation in xx different rooms
			<?php 
			 
			$date 		 = JFactory::getDate();
			$dayFrom 	 = (int)$date->format('d'); 		
			$dayFromText = SfsHelper::addOrdinalNumberSuffix( $dayFrom ); 		 		 		 	
			$dateTo 	 = SfsHelperDate::getNextDate('d', $date); 		 		
			$dateToText  = SfsHelper::addOrdinalNumberSuffix( (int) $dateTo).' of '.SfsHelperDate::getNextDate('F Y', $date);
		
			echo ' starting '.$dayFromText.'   ending '.$dateToText;
			?>
			<br />	
			<?php 
			if($this->hotel):
				$mealplan = $this->hotel->getMealPlan();
			?>
				
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
		
		
		
			<?php 			
 			$stopSellingTime = $mealplan->stop_selling_time;
 			if( (int)$stopSellingTime == 24 ) {
 				echo '- Pre arranged dinner available 24 hours';
 			} else {
 				echo '- Pre arranged dinner available until '.str_replace(':','h',$mealplan->stop_selling_time);	
 			} 				
 			?>
 			<br />
		
		
		
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
		</p>
		
		<h2>Transportation to hotel</h2>
		<p>
			<?php 
			
				$transport = $this->hotel->getTransportDetail();
				if($transport) {
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
					if($transport->pickup_details){
						echo '<br/>';
						echo 'Transport details: '.$transport->pickup_details;	
					}
											
				} else {
					echo 'Transport to accommodation included: No';	
				}
			?>		
			
		</p>
				
	</div>

	<div style="background-color:#FFDE73; border:solid 10px #FFB700; padding:10px; margin-top:15px;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif">
		<p style="font-weight: bold;margin-top:5px;">
			Hotel billing information:<br />
			<?php echo 'Block code: XXXX-XX-XXX-X-XX'?><br />
			<?php echo 'Voucher code: XXXXX-XXX-X-XX'?><br />
			<?php echo 'Voucher xx person'?><br />				
		</p>
		
		<p style="font-size:90%;">		
			*Bestelbon - vrijstelling van de BTW art 42 para 2 alinea 5 W.BTW		
			<br/>		
			<span><?php echo 'Booking guaranteed by xxx as agreed with your hotel on SFS-web.com'?></span>
		
		</p>
	</div>
	
	<p align="center" style="color:#666">Share your experience on www.strandedexperience.com</p>	


</div>
</div>
