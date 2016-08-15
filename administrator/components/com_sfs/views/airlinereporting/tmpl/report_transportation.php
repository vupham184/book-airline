<?php
defined('_JEXEC') or die;
$airlineId = JRequest::getInt('airline_id');
?>
<div class="report-chart-wrap">					
	<h3>Transportation</h3>						
	<center>
		<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=airlinereporting.drawpie&format=raw&points='.$this->transportationPercentages[0].','.$this->transportationPercentages[1];?>" />	
	</center>
</div>
<div style="overflow: hidden; clear:both;">
	<h4>Transportation Details</h4>
	<div style="margin-top:10px">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="report-detail-table">		
			<tr>
			 	<td class="tableheader">Included</td>
		        <td class="tableheader" style="border-right: 1px solid #666;"><?php echo number_format($this->transportationPercentages[0]/($this->transportationPercentages[0]+$this->transportationPercentages[1]) * 100,2)  ?>%</td>
			</tr>
			<tr>
			 	<td class="tableheader">Excluded</td>
		        <td class="tableheader" style="border-right: 1px solid #666;"><?php echo number_format($this->transportationPercentages[1]/($this->transportationPercentages[0]+$this->transportationPercentages[1]) * 100,2)  ?>%</td>
			</tr>											
		</table>
		<div class="report-detail-desc" style="padding-top: 10px;">
			 Percentage of the booked rooms with hotel transportation by the airline compared to the total number of rooms booked by the airline 
		</div>				
	</div>	
	<div style="margin-top:10px;">
		<form name="exportTransportationDetails" action="index.php" method="post">
		
			<button type="submit" class="button" style="margin-left:5px;display:none;">Export</button>
						
			<input type="hidden" name="date_from" value="<?php echo JRequest::getVar('date_from')?>" />
			<input type="hidden" name="date_to" value="<?php echo JRequest::getVar('date_to')?>" />
			
			<?php if( count($this->statuses) ):?>
			
				<?php foreach ($this->statuses as $status) : ?>
					<input type="hidden" name="blockStatus[]" value="<?php echo $status;?>">
				<?php endforeach;?>
			
			<?php endif;?>
			
			<input type="hidden" name="option" value="com_sfs" />
			<input type="hidden" name="task" value="report.exportTransportationDetails" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
</div>	