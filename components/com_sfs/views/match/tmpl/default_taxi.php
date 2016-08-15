<?php
defined('_JEXEC') or die;

$airline = SFactory::getAirline();

$sfs_system_currency = $this->params->get('sfs_system_currency');

//Taxi
if( $airline->allowTaxiVoucher() ) :
 
$taxiCompanies = $airline->getTaxiCompanies();
 
if( count($taxiCompanies) ):

$nightDay = SfsHelperDate::getDate($this->night,'N');

$currentH = SfsHelperDate::getDate('now','H');
$currentI = SfsHelperDate::getDate('now','i');

?>

<script type="text/javascript">
<!--
window.addEvent('domready', function() {
	
	$('isTaxiVoucherCheck<?php echo $this->item->id ;?>').addEvent('change',function(e){

		if( $('isTaxiVoucherCheck<?php echo $this->item->id ;?>').checked ) 
		{
			$$('.taxi-list<?php echo $this->item->id ;?>').setStyle('display','block');
		} else {
			$$('.taxi-list<?php echo $this->item->id ;?>').setStyle('display','none');
		}
		
	});
});	
-->	
</script>

<div style="padding-left: 20px;">
	
	<?php
	$j = 0;
	foreach ($taxiCompanies as $taxi) :
	
		$checked = ($j == 0) ? ' checked="checked"':'';
		
		$is_available = true;
		
		if( ! empty($taxi->available_days) ) 
		{
			$available_days = explode(',', $taxi->available_days);
			JArrayHelper::toInteger($available_days);
			
			if( count($available_days)  ){
				if( !in_array($nightDay, $available_days) ){
					$is_available=false;
				}
			}			
		} else{
			$is_available=false;
		}
		
		//if( ! $is_available ) continue;	

		$taxiHotelRate = $airline->getTaxiHotelRate($taxi->taxi_id, $this->item->hotel_id,$this->item->hotel_ring);
		//print_r($taxiHotelRate);
		
		$fare_from 	 = $taxi->fare_from;
		$fare_until  = $taxi->fare_until;
		
		$taxiVoucherRate = null;
		
		$fare_type = 'day';
		
		if( $fare_from )
		{
			$array = explode(':', $fare_from);
			if( (int)$array[0] == (int)$currentH )
			{
				if( (int)$currentI >= (int)$array[1]  )
				{
					$taxiVoucherRate = $taxiHotelRate->night_fare;
					$fare_type = 'night';
				}				
			} 
			if ( (int)$currentH > (int)$array[0] ){
				$taxiVoucherRate = $taxiHotelRate->night_fare;	
				$fare_type = 'night';			
			}
		}
		if( $taxiVoucherRate == null )
		{
			if( $fare_until )
			{
				$array = explode(':', $fare_until);
				if( (int)$array[0] == (int)$currentH )
				{
					if( (int)$currentI <= (int)$array[1]  )
					{
						$taxiVoucherRate = $taxiHotelRate->night_fare;
						$fare_type = 'night';
					}				
				} 
				if ( (int)$currentH < (int)$array[0] ){
					$taxiVoucherRate = $taxiHotelRate->night_fare;	
					$fare_type = 'night';			
				}
			}
		}
		
		if($taxiVoucherRate !== null)
		{
			$taxiVoucherRate = floatval($taxiVoucherRate);	
		}
		
		if( !$taxiVoucherRate )
		{
			$taxiVoucherRate = $taxiHotelRate->day_fare;
			$fare_type = 'day';
		}
		?>
		
		<?php if($j==0): ?>
			<input type="checkbox" name="reservations[<?php echo $this->item->id ;?>][isTaxiVoucher]" id="isTaxiVoucherCheck<?php echo $this->item->id ;?>" value="1" />
			Add Transportation 
		<?php endif;?>
		
		<?php if( $taxiVoucherRate ) : ?>
		<div style="padding-left: 20px;display:none" class="taxi-list taxi-list<?php echo $this->item->id ;?>">
			<input type="radio" name="reservations[<?php echo $this->item->id ;?>][taxi_id]" value="<?php echo $taxi->taxi_id ;?>" <?php echo $checked?> />
			<?php echo $taxi->name;?> - <?php echo JText::_('COM_SFS_ONE_WAY')?> (<?php echo number_format($taxiVoucherRate).' '.$sfs_system_currency?>)
			<input type="hidden" name="reservations[<?php echo $this->item->id ;?>][<?php echo $taxi->taxi_id ;?>][rate]" value="<?php echo $taxiVoucherRate ;?>"/>
			<input type="hidden" name="reservations[<?php echo $this->item->id ;?>][<?php echo $taxi->taxi_id ;?>][fare_type]" value="<?php echo $fare_type ;?>"/>
			&nbsp;&nbsp;&nbsp;<input type="checkbox" name="reservations[<?php echo $this->item->id ;?>][<?php echo $taxi->taxi_id ;?>][is_return]" value="1"/> Return included
		</div>
		<?php endif;?>
	<?php 
	$j++;
	endforeach;?>

</div>
<?php endif;?>

<?php endif; //End Taxi?>