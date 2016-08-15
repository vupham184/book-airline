<?php
defined('_JEXEC') or die;
$airlineId = JRequest::getInt('airline_id');
?>
<div class="report-chart-wrap">					
	<h3>Initial blocked pick up</h3>						
	<center>
		<?php if($this->initialPercentages->initial_rooms == $this->initialPercentages->claimed_rooms) : ?>
		<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=reports.drawpie&format=raw&airline_id='.$airlineId.'&points=100';?>" />
		<?php else: ?>
		<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=reports.drawpie&format=raw&airline_id='.$airlineId.'&points='.$this->initialPercentages->claimed_rooms.','.($this->initialPercentages->initial_rooms-$this->initialPercentages->claimed_rooms);?>" />
		<?php endif;?>			
	</center>
</div>
<div style="overflow: hidden; clear:both;">
	<h4>Initial blocked pick up</h4>
	<div style="margin-top:10px">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="report-detail-table">
		<tbody>
			<tr>
			 	<td class="tableheader">Picked Up</td>
		        <td class="tableheader" style="border-right: 1px solid #666;"><?php echo number_format( $this->initialPercentages->claimed_rooms/$this->initialPercentages->initial_rooms * 100,2)  ?>%</td>
			</tr>									
		</tbody>
		</table>
		<div class="report-detail-desc" style="padding-top: 10px;">
			 Percentage of the booked rooms by the airline compared to the total number of rooms initial requested 
		</div>				
	</div>	
	<div style="margin-top:10px;">
		<form name="exportInitialBlockedPickup" action="index.php" method="post">
			<button type="submit" class="button" style="margin-left:5px;background:green;color:white;padding:5px 20px;border:none;">Export</button>
			<input type="hidden" name="airline_id" value="<?php echo $airlineId;?>" />
			<input type="hidden" name="gh_airline" value="<?php echo JRequest::getInt('gh_airline');?>" />
			<input type="hidden" name="date_from" value="<?php echo JRequest::getVar('date_from')?>" />
			<input type="hidden" name="date_to" value="<?php echo JRequest::getVar('date_to')?>" />
			
			<?php if( count($this->statuses) ):?>
			
				<?php foreach ($this->statuses as $status) : ?>
					<input type="hidden" name="blockStatus[]" value="<?php echo $status;?>">
				<?php endforeach;?>
			
			<?php endif;?>
			
			<input type="hidden" name="option" value="com_sfs" />
			<input type="hidden" name="task" value="report.exportInitialBlockedPickup" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
</div>	