<?php
defined('_JEXEC') or die;?>

<div class="register-field clear floatbox">
	<label><?php echo JText::_('COM_SFS_GH_COMPANY_NAME');?>
	</label>
	<div id="company_name">
	<?php echo $this->airline->name; ?>
	</div>
</div>
<div class="register-field clear floatbox">
	<label><?php echo JText::_('COM_SFS_GH_SERVICING_AIRLINES');?>
	</label>
	<div>
	<?php 
	$servicingAirlines = $this->airline->getServicingAirlines();
	if (count ($servicingAirlines)  ) :
		$i=0;
		foreach ($servicingAirlines AS $a) :
			if($i==0) :
				echo $a->code;
			else :
				echo ', '.$a->code; 		
			endif;			
			$i++;
		endforeach;
	endif; 
	?>
	</div>
</div>

<div class="register-field clear floatbox">
	<label>
		<?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRPORT_LOCATION_CODE');?>
	</label>
	<?php echo SfsHelperField::getAirportName( $this->airline->airport_id ); ?>
</div>
<div class="register-field clear floatbox">
	<label><?php echo JText::_('COM_SFS_TIME_ZONE');?> </label>
	<?php echo $this->airline->time_zone ;?>
</div>
