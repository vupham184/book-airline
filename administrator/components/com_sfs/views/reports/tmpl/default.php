<?php
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
//JHtml::_('behavior.formvalidation');
//JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
?>
<style>
#toolbar-report_hotels .icon-32-report_hotels,
#toolbar-report_airlines .icon-32-report_airlines,
#toolbar-report_airplus .icon-32-report_airplus,
#toolbar-report_all .icon-32-report_all
{
	background: url(components/com_sfs/images/report.png) no-repeat;
	width:39px !important;
	height:44px !important;
}
</style>

<div class="width-40 fltlft">
	<fieldset>
	<legend>Hotels</legend>
	
	<?php foreach ($this->hotels as $hotel) : ?>
	<div class="report-row">
		<h3 class="report-title"><?php echo $hotel->name;?></h3>
		<div>
			Star: <?php echo $hotel->star;?> | Address: <?php echo $hotel->address;?>, <?php echo $hotel->city;?> 
		</div>		
		<a href="index.php?option=com_sfs&view=reports&layout=hotel&hotel_id=<?php echo $hotel->id?>">View Report</a> | <a href="index.php?option=com_sfs&view=hotelreporting&id=<?php echo $hotel->id?>">Availability Report</a>
	</div>
	<?php endforeach;?>
	</fieldset>
	
</div>

<div class="width-30 fltlft">
	<fieldset>
	<legend>Airlines</legend>
	
	<?php foreach ($this->airlines as $airline) : ?>
	<div class="report-row">
		<h3 class="report-title"><?php echo $airline->airline_name.', '.$airline->airline_code;?></h3>
		<div>
			Airport: <?php echo $airline->airport_code;?> | Address: <?php echo $airline->address;?>, <?php echo $airline->city;?> 
		</div>	
		<a href="index.php?option=com_sfs&view=reports&layout=airline&airline_id=<?php echo $airline->id?>">View Report</a>	
	</div>
	<?php endforeach;?>
	</fieldset>
	
</div>

<div class="width-30 fltlft">
	<fieldset>
	<legend>Ground Handlers</legend>
	
	<?php foreach ($this->ghs as $airline) : ?>
	<div class="report-row">
		<h3 class="report-title"><?php echo $airline->company_name;?></h3>
		<div>
			Airport: <?php echo $airline->airport_code;?> | Address: <?php echo $airline->address;?>, <?php echo $airline->city;?> 
		</div>	
		<a href="index.php?option=com_sfs&view=reports&layout=airline&airline_id=<?php echo $airline->id?>">View Report</a>
	</div>
	<?php endforeach;?>
	</fieldset>
	
	<fieldset>
		<div>
			<a href="index.php?option=com_sfs&view=hotelreporting">Availability report for all hotels</a>
		</div>
		<div>
			<a href="index.php?option=com_sfs&view=reports&layout=hotels">Report Hotels</a>
		</div>
		<div>
			<a href="index.php?option=com_sfs&view=airlinereporting">Report Airlines</a>
		</div>	
		<div>
			<a href="index.php?option=com_sfs&view=airlinereporting">Report Airplus</a>
		</div>		
	</fieldset>
	
</div>

