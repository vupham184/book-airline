<?php
	defined('_JEXEC') or die();
	$currency_symbol = $this->hotel->getTaxes()->currency_symbol;
	$sdroom = JRequest::getVar('sdroom',0);
	$troom = JRequest::getVar('troom',0);
	$sroom = JRequest::getVar('sroom',0);
	$qroom = JRequest::getVar('qroom',0);
    $breakfast = JRequest::getInt('breakfast',0);
    $lunch = JRequest::getInt('lunch',0);
    $dinner = JRequest::getInt('dinner',0);
    $course = JRequest::getInt('course',0);
?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr valign="top">
		<td width="55%" style="vertical-align: top;">
			<div class="fs-16"; style="padding-left: 15px;">
				<?php echo $this->hotel->name;?>
				<?php if( (int)$this->hotel->star > 0 ): ?>
				<div>
					<?php
					for($i=0;$i<(int)$this->hotel->star;$i++) {
						echo '<img src="components/com_sfs/assets/images/star.png" />';
					} 
					?>
				</div> 				
				<?php endif;?>
			</div>
			
			<div class="floatbox fs-14" style="padding: 15px;">
			
				<div class="sfs-row">
					<div class="sfs-column-left">
						Rooms: 
					</div>
					<?php 
					$tempArray = array();
					if($sroom > 0)
					{
						$tempArray['s'] = $sroom.' S room';
					}					
					if($sdroom > 0)
					{
						$tempArray['sd'] = $sdroom.' S/D room';
					}
					if($troom > 0)
					{
						$tempArray['t'] = $troom.' T room';
					}
					if($qroom > 0)
					{
						$tempArray['q'] = $qroom.' Q room';
					}	
					echo implode(', ', $tempArray);				
					?> 
				</div>	
				
				<div class="sfs-row">
					<div class="sfs-column-left">
						Rate: 
					</div>
					<?php
					$tempRateArray = array();
					foreach ($tempArray as $key=>$value)
					{
						switch ($key){
							case 's':
								$rate = $this->contracted_s_rate > 0 ? $this->contracted_s_rate : $this->inventory->s_room_rate_modified;
								$tempRateArray[] = $currency_symbol.floatval($rate);
								break;
							case 'sd':
								$rate = $this->contracted_sd_rate > 0 ? $this->contracted_sd_rate : $this->inventory->sd_room_rate_modified;
								$tempRateArray[] = $currency_symbol.floatval($rate);
								break;
							case 't':
								$rate = $this->contracted_t_rate > 0 ? $this->contracted_t_rate : $this->inventory->t_room_rate_modified;
								$tempRateArray[] = $currency_symbol.floatval($rate);
								break;
							case 'q':
								$rate = $this->contracted_q_rate > 0 ? $this->contracted_q_rate : $this->inventory->q_room_rate_modified;
								$tempRateArray[] = $currency_symbol.floatval($rate);
								break;			
							default:
								break;
						}
					} 
					?>
					<?php echo implode(', ', $tempRateArray); ?>
				</div>
				
				<div class="sfs-row">
					<div class="sfs-column-left">
						Mealplan: 
					</div>
					<div class="float-left">
					<?php if($breakfast || $lunch ||$dinner):?>
						<?php if($breakfast):?>
							<div>Breakfast</div>
						<?php endif;?>
						<?php if($lunch):?>
							<div>Lunch</div>
						<?php endif;?>
						<?php if($dinner):?>
							<div><?php echo $course?> course dinner</div>
						<?php endif;?>
					<?php else:?>
						No		
					<?php endif;?>							
					</div>
					
				</div>
				
				<?php
				$session = JFactory::getSession();
				$payment_type = $session->get('payment_type'); 				
				if($payment_type=='airline'||$payment_type=='passenger'):
				?>
				
				<div class="sfs-row">
					<div class="sfs-column-left">
						Invoice for: 
					</div>
					<div class="float-left">
						<?php if($payment_type=='airline'):?>
						<?php echo $airline->name?>
						<?php else :?>
						Passenger
						<?php endif;?>
					</div>
				</div>
				<?php endif;?>
				
			</div>
			
		</td>
		<td width="45%">
		
		<?php if( (int)$this->associationId==0) : ?>
		<div class="fs-16">
			Transport details:
		</div>
		<?php
		$transport = $this->hotel->getTransportDetail(); 
		?>
		<div style="padding-top:15px;" class="fs-14">
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
			echo $transportText.'<br/>';
			
			if( (int)$transport->transport_available > 0 ) :?>
				
				<?php 
				echo (int)$transport->transport_complementary == 1 ? '   Complimentary: Yes':'   Complimentary: No';
				
				$transportText = '';			
				$transport->operating_hour = (int)$transport->operating_hour;
	 			if($transport->operating_hour == 0 ){
					$transportText .='Operation hours: Not available';
				} else if($transport->operating_hour == 1) {
					$transportText .='Operation hours: 24 hours';						
				} else if($transport->operating_hour == 2) {
					$transportText .='Operation hours: From '.str_replace(':','h',$transport->operating_opentime).' till '.str_replace(':','h',$transport->operating_closetime);	
				}
				$transportText .='<br/>Every: '.$transport->frequency_service.' minutes';
				echo '<br/>'.$transportText;
				if($transport->pickup_details):
				?>
					<div style="padding-top: 15px">Details:</div>
					<div class="fs-12">
						<?php echo $transport->pickup_details?>
					</div>
				<?php endif;?>
				
			<?php endif;?>
			
		 </div>
		 <?php else:?>
		 <div class="fs-16">
			Transport details:
		 </div>
		 <div style="padding-top:15px;" class="fs-14">
		 	Transport to accommodation included: No
		 </div>
		 <?php endif;?>
		
		</td>
	</tr>
</table>
	

