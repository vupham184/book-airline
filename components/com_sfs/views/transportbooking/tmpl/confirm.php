<?php
defined('_JEXEC') or die;
$app	= JFactory::getApplication();
$status = $app->getUserState('com_sfs.transportbooking.status');
$reservation = $app->getUserState('com_sfs.transportbooking.reservation');
if( empty($reservation) )
{
	$app->redirect('index.php?option=com_sfs&view=transportbooking&Itemid='.JRequest::getInt('Itemid'));
}
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3>Bus Transportation</h3>
	</div>
</div>
<div id="sfs-wrapper" class="fs-14 main">
	<div class="sfs-main-wrapper-none">
	<div class="sfs-orange-wrapper">
	<div class="sfs-white-wrapper floatbox">
		
		<p>Confirmation</p>
		<p>You have booked group bus transportation for <?php echo $reservation['total_passengers']; ?> persons</p>				
		<?php
		$terminalName='';
		foreach ($this->terminals  as $terminal ) {
			if( (int)$terminal->id == (int)$reservation['departure'] )
			{
				$terminalName = $terminal->name; 
				break;
			}
		} 
		$requestedTime = 'as soon as possible';
		if( $reservation['requested_time'] != '0' ) {
			$reservation['requested_date'] = JHTML::_('date',$reservation['requested_date'], 'l, d F Y');
			$requestedTime = 'at '.$reservation['requested_date'].' '.$reservation['requested_time'];
		} 
		?>		
		<?php
		$hotelName = '';
		foreach ($this->hotels as $hotel ) {
			if( (int)$hotel->id == (int)$reservation['hotel_id'] )
			{
				$hotelName = $hotel->name; 
				break;
			}
		} 
		if($reservation['departure_type']=='airport'){
			$pickUp = $terminalName;
			$dropOf = $hotelName;
		} else {
			$pickUp = $hotelName;
			$dropOf = $terminalName;
		}
		?>
		<p>Pick up at <?php echo $pickUp?> <?php echo $requestedTime?></p>
		<p>Drop of <?php echo $dropOf?></p> 
		
		<?php
		$reservation['comment'] = trim($reservation['comment']);
		if( strlen($reservation['comment']) ) :
		?>
		<p>
			Your comments:<br />
			<?php echo $reservation['comment']?>
		</p>
		<?php endif;?>
		<p class="fs-12">
			For special request like disabled<br />
			passengers or odd size baggage, please contact the bus company by phone as well
		</p>
		
		<p>
			Contact details:<br/>
			<?php echo $this->transportCompany->name;?><br />
			Phone: <?php echo $this->transportCompany->telephone;?>
		</p>
		
		<p>Your reference number is <strong><?php echo $reservation['reference_number'];?></strong></p>
		
	</div>
	</div>
	</div>		
</div>
<?php
//$app->setUserState('com_sfs.transportbooking.status', null);
///$app->setUserState('com_sfs.transportbooking.reservation', null); 
?>
