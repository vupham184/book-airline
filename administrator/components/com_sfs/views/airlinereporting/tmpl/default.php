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

<script type="text/javascript">

window.addEvent('domready', function(){
	var myForm = document.id('airlineReportForm'),
		myResult = document.id('airlineReportResult');

	// Validation.
	new Form.Validator.Inline(myForm);

	new Form.Request(myForm, myResult, {
		requestOptions: {
			'spinnerTarget': myForm
		},		
		resetForm : false
	});
});

</script>

<div class="width-100" style="margin-top:15px;">
	<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=reports&layout=airline&airline_id='.$this->airline->id)?>" method="post" id="airlineReportForm" name="airlineReportForm">
		<fieldset>
			<h1>Airline Reporting</h1>
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
						<td><?php echo JHtml::_('calendar',$date_from, 'date_from', 'date_from', '%Y-%m-%d',$attribs)?></td>						
						<td style="padding-left:20px;">Untill:</td>
						<td><?php echo JHtml::_('calendar',$date_to, 'date_to', 'date_to', '%Y-%m-%d',$attribs)?></td>
												
						<td style="padding-left:20px;"><input type="submit" value="GENERATE" class="button"></td>
					</tr>
				</table>				 						
			</div>
		</fieldset>
		
		
		
		<input type="hidden" name="task" value="airlinereporting.displayReports">
				
		<input type="hidden" name="option" value="com_sfs">		
		<input type="hidden" name="format" value="raw">		
	</form>
</div>
<div class="clr"></div>

<div class="width-100" id="airlineReportResult"></div>

<div class="clr"></div>

