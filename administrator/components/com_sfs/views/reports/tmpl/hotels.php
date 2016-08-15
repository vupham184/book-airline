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

$m_from = JRequest::getInt('m_from');
$y_from = JRequest::getInt('y_from');
$m_to	= JRequest::getInt('m_to');
$y_to 	= JRequest::getInt('y_to');

?>

<script type="text/javascript">

window.addEvent('domready', function(){
	var myForm = document.id('hotelReportForm'),
		myResult = document.id('hotelReportResult');

	new Form.Request(myForm, myResult, {
		requestOptions: {
			'spinnerTarget': myForm
		},		
		resetForm : false
	});
});

</script>



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

$m_from = JRequest::getInt('m_from');

$y_from = JRequest::getInt('y_from');
$m_to	= JRequest::getInt('m_to');
$y_to 	= JRequest::getInt('y_to');

?>

<script type="text/javascript">

window.addEvent('domready', function(){
	var myForm = document.id('hotelReportForm'),
		myResult = document.id('hotelReportResult');

	new Form.Request(myForm, myResult, {
		requestOptions: {
			'spinnerTarget': myForm
		},		
		resetForm : false
	});
});

</script>

<div class="width-100">
	<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=reports&layout=hotels');?>" method="post" id="hotelReportForm" name="hotelReportForm">
		<fieldset>
			<h1>Hotels Report</h1>
			<div style="padding:5px 0 ;">
				Include following statuses in report:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<input type="checkbox" value="O" name="blockStatus[]" checked="checked"> Open
				<input type="checkbox" value="P" name="blockStatus[]" checked="checked"> Pending
				<input type="checkbox" value="C" name="blockStatus[]" checked="checked"> Challenged
				<input type="checkbox" value="T" name="blockStatus[]" checked="checked"> Tentative
				<input type="checkbox" value="A" name="blockStatus[]" checked="checked"> Approved 
				<input type="checkbox" value="R" name="blockStatus[]" checked="checked"> Archived
				<input type="checkbox" value="D" name="blockStatus[]" checked="checked"> Deleted 
			</div>
			<div>	
				<table>
					<tr>
						<td>From:</td>
						<td><?php echo SfsHelperField::getMonthField('m_from',$m_from)?></td>
						<td><?php echo SfsHelperField::getYearField('y_from',$y_from)?></td>
						<td style="padding-left:20px;">Untill:</td>
						<td><?php echo SfsHelperField::getMonthField('m_to',$m_to)?></td>
						<td><?php echo SfsHelperField::getYearField('y_to',$y_to)?></td>
						<td style="padding-left:20px;"><input type="submit" value="GENERATE" class="button"></td>
					</tr>
				</table>				 						
			</div>
		</fieldset>
		
		<input type="hidden" name="task" value="reports.reportAllHotels">
				
		<input type="hidden" name="option" value="com_sfs">		
		<input type="hidden" name="format" value="raw">		
	</form>
</div>
<div class="clr"></div>

<div class="width-100" id="hotelReportResult"></div>

<div class="clr"></div>




