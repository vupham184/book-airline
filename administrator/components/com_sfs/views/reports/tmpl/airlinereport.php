<?php
defined('_JEXEC') or die;

$month_array = array(
1 => JText::_('JANUARY'),
2 => JText::_('FEBRUARY'),
3 => JText::_('MARCH'),
4 => JText::_('APRIL'),
5 => JText::_('MAY'),
6 => JText::_('JUNE'),
7 => JText::_('JULY'),
8 => JText::_('AUGUST'),
9 => JText::_('SEPTEMBER'),
10 => JText::_('OCTOBER'),
11 => JText::_('NOVEMBER'),
12 => JText::_('DECEMBER')
);

$date_from	= JRequest::getVar('date_from');
$date_to 	= JRequest::getVar('date_to');

?>

<h2>
	Report Period: <?php echo $date_from.' till '.$date_to;?>
</h2>

	<?php ?>

<div class="fltlft" style="width: 33%">
	<fieldset>
	<?php echo $this->loadTemplate('roomnights');?>
	</fieldset>
</div>

<div class="fltlft" style="width: 33%">
	<fieldset>
	<?php echo $this->loadTemplate('average');?>
	</fieldset>
</div>

<div class="fltlft" style="width: 34%">
	<fieldset>
	<?php echo $this->loadTemplate('revenue');?>
	</fieldset>
</div>
<div style="overflow: hidden; clear:both;">

	<div class="fltlft" style="width: 25%">
		<fieldset style="min-height: 400px;">
			<?php echo $this->loadTemplate('iatacode');?>
		</fieldset>
	</div>
	<div class="fltlft" style="width: 25%">
		<fieldset style="min-height: 400px;">
			<?php echo $this->loadTemplate('market');?>
		</fieldset>
	</div>
	
	<div class="fltlft" style="width: 25%">
		<fieldset style="min-height: 400px;">
			<?php echo $this->loadTemplate('transportation');?>
		</fieldset>
	</div>
	<div class="fltlft" style="width: 25%">
		<fieldset style="min-height: 400px;">
			<?php echo $this->loadTemplate('initial');?>
		</fieldset>
	</div>

</div>