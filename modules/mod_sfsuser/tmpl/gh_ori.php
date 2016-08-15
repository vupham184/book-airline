<?php
defined('_JEXEC') or die;
?>
<div class="wellcomepart" style="float:right;overflow:hidden;">
	<div class="namecus">
		Welcome back:<span class="infoname"> <?php echo $user->name;?></span>
	</div>
	<div class="namecountry">
		<?php
		 $selectedAirline = $airline->getSelectedAirline();
		 if( ! empty($selectedAirline) ) :
		 	echo 'Selected Airline: <span style="color:red;">'.$selectedAirline->code.' '.'('.$airline->country_name.')</span> <a href="'.JRoute::_( SfsHelperRoute::getSFSRoute('airlineprofile','changeairline') ).'">Change airline</a>';
		 else :
		 	echo $airline->name.' '.'('.$airline->country_name.') <a href="'.JRoute::_( SfsHelperRoute::getSFSRoute('airlineprofile','changeairline') ).'">Change airline</a>';
		 endif;
		?>
	</div>
	<div class="datetime">
		<span class="date">Date: <?php echo JHTML::_('date',JFactory::getDate()->toSQL(), JText::_('DATE_FORMAT_LC3'));?></span> <span class="time">Time: <?php echo JHTML::_('date',JFactory::getDate()->toSQL(), JText::_('H:i'));?></span>
	</div>
</div>