<?php
defined('_JEXEC') or die;
$airlineId = JRequest::getInt('airline_id');

$picked_up_per = number_format( $this->marketPercentages[1]/($this->marketPercentages[1]+$this->marketPercentages[0])*100, 2);
?>
<div class="report-chart-wrap">					
	<h3>Market pick up</h3>						
	<center>
		<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=airlinereporting.drawpie&format=raw&points='.implode(',',$this->marketPercentages);?>" />	
	</center>
</div>
<div style="overflow: hidden; clear:both;">
	<h4>Market pick up</h4>
	<div style="margin-top:10px">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="report-detail-table">
		<tbody>
			<tr>
			 	<td class="tableheader">Picked Up</td>
		        <td class="tableheader" style="border-right: 1px solid #666;"><?php echo $picked_up_per;?>%</td>
			</tr>									
		</tbody></table>
		<div class="report-detail-desc" style="padding-top: 10px;">			 
			 Percentage of the booked rooms by the airline compared to the total number of rooms offered in the market 
		</div>				
	</div>	
	<div style="margin-top:10px;">
		<form name="exportMarketPickup" action="index.php" method="post">
			<button type="submit" class="button" style="margin-left:5px;display:none;">Export</button>
			
			<input type="hidden" name="date_from" value="<?php echo JRequest::getVar('date_from')?>" />
			<input type="hidden" name="date_to" value="<?php echo JRequest::getVar('date_to')?>" />
			
			<?php if( count($this->statuses) ):?>
			
				<?php foreach ($this->statuses as $status) : ?>
					<input type="hidden" name="blockStatus[]" value="<?php echo $status;?>">
				<?php endforeach;?>
			
			<?php endif;?>
			
			<input type="hidden" name="option" value="com_sfs" />
			<input type="hidden" name="task" value="report.exportMarketPickup" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
</div>	