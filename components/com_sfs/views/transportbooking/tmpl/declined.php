<?php
defined('_JEXEC') or die;
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3>Bus Transportation Confirmation</h3>
	</div>
</div>
<div id="sfs-wrapper" class="fs-14 main">
	<div class="sfs-main-wrapper-none">
	<div class="sfs-orange-wrapper">
	<div class="sfs-white-wrapper floatbox">
						
		<div class="uk-alert uk-alert-danger" style="font-size:14px;">
			You have been declined group bus transportation booking for <?php echo $this->reservation->total_passengers?> persons
		</div>	
					
		<p>Reference number: <strong><?php echo $this->reservation->reference_number;?></strong></p>
		
		
		<?php
		$terminalName='';
		foreach ($this->terminals  as $terminal ) {
			if( (int)$terminal->id == (int)$this->reservation->departure )
			{
				$terminalName = $terminal->name; 
				break;
			}
		} 
		$hotelName='';
		foreach ($this->hotels  as $hotel ) {
			if( (int)$hotel->id == (int)$this->reservation->hotel_id )
			{
				$hotelName = $hotel->name; 
				break;
			}
		} 
		
		if($this->reservation->departure_type=='hotel')
		{
			$pickUpLocation  = $hotelName;		
			$dropOffLocation = $terminalName;			
		} else {
			$pickUpLocation  = $terminalName;
			$dropOffLocation = $hotelName;
		}			
		
		$requestedTime = 'as soon as possible';
		if( $this->reservation->requested_time != '0' ) {
			$requestedTime = 'at '.$this->reservation->requested_time.' hours';
		} 
		?>		
		<p>Pick up at <?php echo $pickUpLocation?> <?php echo $requestedTime?></p>
		<p>Drop off <?php echo $dropOffLocation?></p>
		
		<?php
		$reservation['comment'] = trim($reservation['comment']);
		if( strlen($this->reservation->comment) ) :
		?>
		<p>
			Comments:<br />
			<?php echo $this->reservation->comment?>
		</p>
		<?php endif;?>
		
	</div>
	</div>
	</div>		
</div>

