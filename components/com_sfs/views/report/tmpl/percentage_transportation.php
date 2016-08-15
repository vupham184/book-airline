<?php
defined('_JEXEC') or die;
$gh_airline = (int) JRequest::getInt('gh_airline');
?>
<div class="report-chart-wrap">					
	<span class="report-chart-title"><?php echo JText::_('COM_SFS_REPORT_TRANSPORTATION');?></span>						
	<center>
		<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=report.drawpie&format=raw&points='.$this->items[0].','.$this->items[1];?>" />	
	</center>
</div>
<div class="report-detail-wrap">
	<h4 class="report-detail-title"><?php echo JText::_('COM_SFS_REPORT_TRANSPORTATION_DETAILS');?></h4>
	<div style="margin-top:10px">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="report-detail-table">		
			<tr>
			 	<td class="tableheader"><?php echo JText::_('COM_SFS_REPORT_INCLUDED')?></td>
		        <td class="tableheader" style="border-right: 1px solid #666;"><?php echo number_format($this->items[0]/($this->items[0]+$this->items[1]) * 100,2)  ?>%</td>
			</tr>
			<tr>
			 	<td class="tableheader"><?php echo JText::_('COM_SFS_REPORT_EXCLUDED')?></td>
		        <td class="tableheader" style="border-right: 1px solid #666;"><?php echo number_format($this->items[1]/($this->items[0]+$this->items[1]) * 100,2)  ?>%</td>
			</tr>											
		</table>
		<div class="report-detail-desc">
			<?php echo JText::_('COM_SFS_REPORT_TRANSPORTATION_DESC');?>
		</div>				
	</div>	
	<div>
		<form name="exportTransportationDetails" action="index.php" method="post">
			<button type="submit" class="sfs-button">
				<?php echo JText::_('COM_SFS_REPORT_EXPORT') ?>
			</button>
			<input type="hidden" name="date_from" value="<?php echo JRequest::getVar('date_from')?>" />
			<input type="hidden" name="date_to" value="<?php echo JRequest::getVar('date_to')?>" />
			<input type="hidden" name="gh_airline" value="<?php echo $gh_airline;?>" />
			<input type="hidden" name="option" value="com_sfs" />
			<input type="hidden" name="task" value="report.exportTransportationDetails" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
</div>	