<?php
defined('_JEXEC') or die;

if( $this->cancel_count ) :
?>
<p class="fs-16" style="margin-bottom:5px;">Cancelled Vouchers</p>

<div class="sfs-main-wrapper" style="padding:10px;">
<div class="floatbox sfs-white-wrapper" style="padding:15px">

<table class="airblocktable trace-passenger-table" width="100%">
	<tr>
		<th>Arr/dep date hotel</th>
		<th>First name</th>
		<th>Last name</th>
		<th>Hotel name</th>
		<th>Mealplan</th>					
		<th>Phone numbers</th>
		<th>Flight number</th>
		<th>Voucher number</th>
	</tr>
	<?php
	if(count($this->passengers)):
	$filter_lastname = $this->state->get('filter_lastname');
	foreach ($this->passengers as $item) :

		if( (int)$item->status < 3 ) continue;
	
		 $toolTip = '';
		 $hasTip  = false;
		 $toolTip = '<table class="tooltiptable"><tr><th>Breakfast</th><th>Lunch</th><th>Dinner</th></tr>';

		 $toolTip .= '<tr><td>';
		 if($item->breakfast){
		 	$hasTip = true;	
		 	$toolTip .='Yes';					 	
		 } else {
		 	$toolTip .='No';
		 }						 
		 $toolTip .= '</td><td>';
		 if($item->lunch){
		 	$hasTip = true;		
		 	$toolTip .='Yes';				 	
		 } else {
		 	$toolTip .='No';
		 }					
		 $toolTip .= '</td><td>';		
		 if($item->mealplan){
		 	$hasTip = true;							 	
		 	$toolTip .= $item->course_type.'-course';					 	
		 } else {
		 	$toolTip .='No';
		 }	 
		 $toolTip .= '</td></tr>';
		 $toolTip .= '</table>';
								
		if ($filter_lastname)
		{			
			$class  = '';																
			$regex		= '/'.$filter_lastname.'/i';													
			preg_match_all($regex, $item->last_name, $matches, PREG_SET_ORDER);							
			if ($matches) {
				$class = 'even';
			} else {
				preg_match_all($regex, $item->first_name, $matches, PREG_SET_ORDER);
				if ($matches) {
					$class = 'even';
				} 		
			}						
		}
		?>
			<tr class="<?php echo $class?>">
				<td>
					<?php 
						echo JFactory::getDate($item->date)->format('d-M');
						$nextDate = SfsHelperDate::getNextDate('d-M', $item->date);
						echo ' / '.$nextDate;
					?>
				</td>
				<td><?php echo $item->first_name?></td>
				<td><?php echo $item->last_name?></td>
				<td><?php echo $item->hotel_name?></td>
				<td>
					<span <?php if($hasTip):?>class="underline-text hasTip2" title="<?php echo SfsHelper::escape($toolTip);?>"<?php endif;?>>
					<?php
					if($hasTip) {
						if($item->breakfast){ echo 'B ';} 
						?>
						<?php
						if($item->lunch) {echo 'L ';} 
						?>
						<?php
						if($item->mealplan) {echo 'D ';}						
					}else {
						echo 'No';
					}
					?>
					</span>
				</td>							
				<td>
					<?php 
					if( $item->hotel_phone ) :
					$toolTip ='<div class="fs-14">Hotel Phone Number<br/>'.$item->hotel_phone.'<div class="passenger-phone-number">Passenger Phone Number<br/>'.$item->phone_number.'</div></div>';
					?>
					<span class="underline-text hasTip2" title="<?php echo SfsHelper::escape($toolTip);?>">
						<?php echo $item->hotel_phone; ?>
					</span>	
					<?php endif;?>
					
				</td>
				<td><?php echo $item->flight_code?></td>
				<td>							
					<?php if($item->reason):
					$commentTip = '<strong>Comment on voucher</strong><br/><p>'.$item->reason.'</p>';
					?>	
					<span class="hasTip2 <?php echo 'underline-text'; ?>" title="<?php echo SfsHelper::escape($commentTip);?>">
						<?php echo $item->voucher_code?>
					</span>
					<?php else:?>
						<?php echo $item->voucher_code?>
					<?php endif;?>
				</td>
			</tr>
			<?php
		

	endforeach;
	endif;
	?>
</table>

</div>
</div>

<?php endif;?>