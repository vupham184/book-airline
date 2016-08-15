<?php
// No direct access
defined('_JEXEC') or die;
$actions = SfsHelper::getActions();
$isCoreAdmin = $actions->get('core.admin');
?>

<div id="cpanel">
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=airlines">
		<img alt="" src="components/com_sfs/images/airline.png"> <span>Airlines</span></a>
	</div>
	</div>

    <div class="icon-wrapper">
        <div class="icon">
            <a href="index.php?option=com_sfs&view=ghs">
                <img alt="" src="components/com_sfs/images/ground_hander.png"> <span>Ground Handlers</span></a>
        </div>
    </div>
	
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=hotels">
		<img alt="" src="components/com_sfs/images/hotel.png"> <span>Hotels</span></a>
	</div>
	</div>
	
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=contacts">
		<img alt="" src="templates/bluestork/images/header/icon-48-contacts.png"> <span>Contacts</span></a>
	</div>
	</div>
	
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=reservations">
		<img alt="" src="components/com_sfs/images/booking.jpg"> <span>Booked, Blocked Rooms</span></a>
	</div>
	</div>
	
	
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=chainaffs">
		<img alt="" src="components/com_sfs/images/affiliation.jpg"> <span>Hotel Chains</span></a>
	</div>
	</div>
	
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=iatacodes&type=1">
		<img alt="" src="components/com_sfs/images/iata.png"> <span>IATA Airline Codes</span></a>
	</div>
	</div>
	
	
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=iatacodes&type=2">
		<img alt="" src="components/com_sfs/images/airport_location.jpg"> <span>IATA Airport Codes</span></a>
	</div>
	</div>
	
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=iatacodes&type=3">
		<img alt="" src="components/com_sfs/images/bus.png"> <span>Terminal Codes</span></a>
	</div>
	</div>
	
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=delaycodes">
		<img alt="" src="components/com_sfs/images/delay_code.jpg"> <span>Delay Codes</span></a>
	</div>
	</div>
	

	
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=locations">
		<img alt="" src="components/com_sfs/images/localtion.png"> <span>Hotel Locations</span></a>
	</div>
	</div>
	
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=countries">
		<img alt="" src="components/com_sfs/images/country.jpg"> <span>Countries</span></a>
	</div>
	</div>
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=states">
		<img alt="" src="components/com_sfs/images/state.png"> <span>States</span></a>
	</div>
	</div>
							
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=currencies">
		<img alt="" src="components/com_sfs/images/currency.jpg"> <span>Currencies</span></a>
	</div>
	</div>
	
	
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=reports">
		<img alt="" src="components/com_sfs/images/report.png"> <span>Reporting</span></a>
	</div>
	</div>
	<?php if($isCoreAdmin) : ?>
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=taxilist">
		<img alt="" src="components/com_sfs/images/taxi.png"> <span>Taxi Company</span></a>
	</div>
	</div>
	<?php endif; ?>
	<?php if($isCoreAdmin) : ?>
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=association">
		<img alt="" src="components/com_sfs/images/association.png"> <span>Associations</span></a>
	</div>
	</div>
	
	
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=tooltip&layout=airline">
		<img alt="" src="components/com_sfs/images/tooltip.png"> <span>System Tooltip</span></a>
	</div>
	</div>
	<?php endif;?>
	<?php if($isCoreAdmin) : ?>
	<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_sfs&view=update">
		<img alt="" src="components/com_sfs/images/update.png"> <span>Update</span></a>
	</div>
	</div>
	

    <div class="icon-wrapper">
        <div class="icon">
            <a href="index.php?option=com_sfs&view=synchotel">
                <img alt="" src="components/com_sfs/images/hotel.png"> <span>Sync Hotels</span></a>
        </div>
    </div>

    <!-- <div class="icon-wrapper">
		<div class="icon">
			<a href="index.php?option=com_sfs&view=exchangerates">
			<img alt="" src="components/com_sfs/images/rate.png" width="45px"> <span>Exchange Rate</span></a>
		</div>
	</div> -->

	<div class="icon-wrapper">
		<div class="icon">
			<a href="index.php?option=com_sfs&view=managemails">
			<img alt="" src="components/com_sfs/images/email.png" width="55px"> <span>Manage Email</span></a>
		</div>
	</div>

	<div class="icon-wrapper">
		<div class="icon">
			<a href="index.php?option=com_sfs&view=managetemplates">
			<img alt="" src="components/com_sfs/images/IconList.png" width="55px"> <span>Manage Template Airline</span></a>
		</div>
	</div>
	<div class="icon-wrapper">
		<div class="icon">
			<a href="index.php?option=com_sfs&view=trainlists">
			<img alt="" src="components/com_sfs/images/train.jpg" width="55px"> <span>Train Ariline</span></a>
		</div>
	</div>

	<div class="icon-wrapper">		
		<div class="icon">
			<a href="index.php?option=com_sfs&view=titleairlines">
			<img alt="" src="components/com_sfs/images/IconList.png" width="55px"> <span>Title Airline</span></a>
		</div>
	</div>

	<div class="icon-wrapper">
		<div class="icon">
			<a href="index.php?option=com_sfs&view=managetemplatemobiles">
			<img alt="" src="components/com_sfs/images/IconList.png" width="55px"> <span>Manage Template Mobile</span></a>
		</div>
	</div>
    <?php endif;?>
</div>

