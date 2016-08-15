<?php
defined('_JEXEC') or die();
$document = JFactory::getDocument();
$document->addStylesheet( JURI::base().'components/com_sfs/assets/css/print.css', 'text/css' , 'print' );
?>

<div id="sfs-print-wrapper" class="fs-14">
<div class="htmlvoucher">	

	<div style="background-color:#fff; border:solid 10px #82ADF1; padding:10px;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif">
		
		<div class="heading-buttons">
			<?php
			$rePrint = JRequest::getInt('reprint',0); 
			if( $rePrint == 0 ) :
			?>			
			<a onclick="window.parent.SqueezeBox.close();" class="sfs-button float-right">Close</a>			
			<a onclick="window.print();if(document.body.style.MozTransform==undefined){window.parent.SqueezeBox.close();};" class="sfs-button float-right">Print</a>
			<?php else :?>
			<a onclick="self.close();" class="sfs-button float-right">Close</a>
			<a onclick="window.print();self.close()" class="sfs-button float-right">Print</a>
			<?php endif;?>
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
			
			<?php if( isset($this->formatPassengerNames) ) : ?>
				Voucher issued for: <?php echo $this->formatPassengerNames; ?><br />
			<?php endif;?>	
			
			Hotel Voucher Code: <?php echo $this->voucher->voucher_code; ?><br />
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
		 			} 					 			?>
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
			<?php 
			if( (int)$this->voucher->transport == 1 ) : 
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
			<?php else : ?>
				<?php echo 'Transport to accommodation included: No';?> 		
			<?php endif;?>
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
			<?php echo 'Block code: '.$this->voucher->blockcode?><br />
			<?php echo 'Voucher code: '.$this->voucher->voucher_code?><br />
			<?php echo 'Voucher '.$this->voucher->seats.' person'?><br />	
			<?php if($this->voucher->return_flight_number) : ?>
			Old flight info: Flight number <?php echo JString::strtoupper($this->voucher->flight_code);?> departure date <?php echo JHTML::_('date', $this->voucher->date , JText::_('DATE_FORMAT_LC3'), false )?><br/>
			New flight info: Flight number <?php echo $this->voucher->return_flight_number?> departure date <?php echo JHTML::_('date', $this->voucher->return_flight_date , JText::_('DATE_FORMAT_LC3'), false )?><br/>
			<?php endif;?>
		</p>
		
		<p style="font-size:90%;">
		<?php if( $this->voucher->get('payment_type','airline') != 'passenger') : ?>
			<?php if( isset($this->airline->params['voucher_vat_comment_line']) && strlen(trim($this->airline->params['voucher_vat_comment_line'])) > 0 ) : ?>
				<?php echo $this->airline->params['voucher_vat_comment_line'];?>
			<?php else : ?>
			*Bestelbon - vrijstelling van de BTW art 42 para 2 alinea 5 W.BTW
			<?php endif;?>
			<br/>
		<?php endif?>
		
		<?php if( $this->voucher->get('payment_type','airline') != 'passenger') : ?>
			<span><?php echo 'Booking guaranteed by '.$this->airline->getAirlineName().' as agreed with your hotel on SFS-web.com'?></span>
		<?php endif?>
		</p>
	</div>
	
	<p align="center" style="color:#666">Share your experience on www.strandedexperience.com</p>	


</div>
</div>
