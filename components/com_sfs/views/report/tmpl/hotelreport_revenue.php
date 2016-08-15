<?php
defined('_JEXEC') or die;
$points = array();
$dates = array();
foreach ($this->reportdata as $value) {
	if($value->revenue_booked) {
		$points[] = $value->revenue_booked;
		$dates[]  =  $value->date;
	}
}
?>
<div class="report-chart-wrap">
	<span class="report-chart-title"><?php echo JText::_('COM_SFS_REPORT_REVENUE_BOOKED');?></span>
	<center>
		<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=report.hotelchart&format=raw&type='.$this->drawType.'&points='.implode(',',$points).'&dates='.implode(',',$dates);?>" />
	</center>
</div>
<div class="report-detail-wrap">
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="report-detail-table">
	<tbody>
		<tr>
		 	<td class="tableheader">Month</td>
	        <td class="tableheader" style="border-right: 1px solid #666;">Gross revenue</td>
		</tr>
		<?php
		foreach ($this->reportdata as $value) {
			echo '<tr class="sectiontableentry1">';
			echo '<td>'.JHtml::_('date',$value->date,'Y - F').'</td>';
			echo '<td style="border-right: 1px solid #666;">'.$value->revenue_booked.' '.$this->currency.'</td>';
			echo '</tr>';
		}
		?>

	</tbody></table>
</div>
<div>
	<form name="exportHotelRevenueBooked" action="index.php" method="post">
		<button type="submit" class="sfs-button">
				<?php echo JText::_('COM_SFS_REPORT_EXPORT') ?>
		</button>
		<input type="hidden" name="m_from" value="<?php echo JRequest::getVar('m_from')?>" />
		<input type="hidden" name="y_from" value="<?php echo JRequest::getVar('y_from')?>" />
		<input type="hidden" name="m_to" value="<?php echo JRequest::getVar('m_to')?>" />
		<input type="hidden" name="y_to" value="<?php echo JRequest::getVar('y_to')?>" />
		<input type="hidden" name="option" value="com_sfs" />
		<input type="hidden" name="task" value="report.exportHotelRevenueBooked" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>