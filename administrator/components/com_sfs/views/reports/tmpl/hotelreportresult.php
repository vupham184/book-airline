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

$m_from = JRequest::getInt('m_from');
$y_from = JRequest::getInt('y_from');
$m_to = JRequest::getInt('m_to');
$y_to = JRequest::getInt('y_to');

?>

<h2 style="margin: 0; padding:15px 0 0 20px;">Report Period: <?php echo $month_array[$m_from].' '.$y_from.' till '.$month_array[$m_to].' '.$y_to;?></h2>

<?php ?>

<div class="fltlft" style="width:33%">
	<fieldset>
	<?php echo $this->loadTemplate('roomnights');?>
	</fieldset>
</div>

<div class="fltlft" style="width:33%">
	<fieldset>
	<?php echo $this->loadTemplate('average');?>
	</fieldset>
</div>

<div class="fltlft" style="width:34%">
	<fieldset>
	<?php echo $this->loadTemplate('revenue');?>
	</fieldset>
</div>

