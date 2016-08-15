<?php
defined('_JEXEC') or die;
$picked_up_per = number_format( $this->items[1]/($this->items[1]+$this->items[0])*100, 2);
$gh_airline = (int) JRequest::getInt('gh_airline');
?>
<div class="report-chart-wrap">					
	<span class="report-chart-title"><?php echo JText::_('COM_SFS_REPORT_MARKET_PICK_UP')?></span>						
	<center>
		<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=report.drawpie&format=raw&points='.implode(',',$this->items);?>" />	
	</center>
</div>
<div class="report-detail-wrap">
	<h4 class="report-detail-title"><?php echo JText::_('COM_SFS_REPORT_MARKET_PICK_UP')?></h4>
	<div style="margin-top:10px">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="report-detail-table">
		<tbody>
			<tr>
			 	<td class="tableheader"><?php echo JText::_('COM_SFS_REPORT_PICKED_UP')?></td>
		        <td class="tableheader" style="border-right: 1px solid #666;"><?php echo $picked_up_per;?>%</td>
			</tr>									
		</tbody></table>
		<div class="report-detail-desc">
			<?php echo JText::_('COM_SFS_REPORT_MARKET_PICKED_UP');?>
		</div>				
	</div>	
	<div>
		<form name="exportMarketPickup" action="index.php" method="post">
			<button type="submit" class="sfs-button">
				<?php echo JText::_('COM_SFS_REPORT_EXPORT') ?>
			</button>
			<input type="hidden" name="date_from" value="<?php echo JRequest::getVar('date_from')?>" />
			<input type="hidden" name="date_to" value="<?php echo JRequest::getVar('date_to')?>" />
			<input type="hidden" name="gh_airline" value="<?php echo $gh_airline;?>" />
			<input type="hidden" name="option" value="com_sfs" />
			<input type="hidden" name="task" value="report.exportMarketPickup" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
</div>	