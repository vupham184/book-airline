<?php
// No direct access.
defined('_JEXEC') or die;

// Load the behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');

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

<div class="width-100" style="margin-top:15px;">
	<form action="<?php echo JRoute::_('index.php')?>" method="post" id="availabilityReport" name="availabilityReport">
		<fieldset>
				<h1>Report for Hotel availability ( incl WS hotels)</h1>
			<div>	
				<table>
					<tr>
						<td>From:</td>
						<td><?php echo JHtml::_('calendar',$date_from, 'date_from', 'date_from', '%Y-%m-%d',$attribs)?></td>						
						<td style="padding-left:20px;">Untill:</td>
						<td><?php echo JHtml::_('calendar',$date_to, 'date_to', 'date_to', '%Y-%m-%d',$attribs)?></td>
												
						<td style="padding-left:20px;"><input type="submit" value="GENERATE" class="button"></td>
					</tr>
				</table>				 						
			</div>
		</fieldset>
		
		<input type="hidden" name="task" value="reportforhotelavailability.availabilityReport">				
		<input type="hidden" name="option" value="com_sfs">
				
		<?php echo JHtml::_('form.token'); ?>		
	</form>
</div>
<div class="clr"></div>

