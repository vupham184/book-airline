<?php
defined('_JEXEC') or die;
$points = array();
$dates = array();
foreach ($this->reportdata as $key=>$value) {
	if($value->average_price) {
		$points[] = $value->average_price;
		$dates[]  = $key;	
	}
}
$params = JComponentHelper::getParams('com_sfs');
$sfs_system_currency = $params->get('sfs_system_currency','EUR');
?>
<div class="report-chart-wrap">
	<h3>Average room prices booked</h3>
	<center>
		<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=reports.hotelchart&format=raw&type=2&points='.implode(',',$points).'&dates='.implode(',',$dates);?>" />	
	</center>
</div>

<div style="clear:both;overflow:hidden;">
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="report-detail-table">
	<tbody>
		<tr>
		 	<td class="tableheader">Month</td>
	        <td class="tableheader" style="border-right: 1px solid #666;">ADR</td>
		</tr>
		<?php 					
		foreach ($this->reportdata as $date=>$value) {						
			echo '<tr class="sectiontableentry1">';
			echo '<td>'.JHtml::_('date',$date,'Y - F').'</td>';
			echo '<td style="border-right: 1px solid #666;">'.number_format($value->average_price,2).' '.$sfs_system_currency.'</td>';
			echo '</tr>';
		}
		?>										
	</tbody>
	</table>											
</div>	

<div style="margin-top:10px;">
	<form name="exportHotelAveragePrices" action="index.php" method="post">
		<button type="submit" class="button" style="margin-left:5px;">Export</button>
		
		<input type="hidden" name="m_from" value="<?php echo JRequest::getVar('m_from')?>" />
		<input type="hidden" name="y_from" value="<?php echo JRequest::getVar('y_from')?>" />
		<input type="hidden" name="m_to" value="<?php echo JRequest::getVar('m_to')?>" />
		<input type="hidden" name="y_to" value="<?php echo JRequest::getVar('y_to')?>" />
		
		<input type="hidden" name="option" value="com_sfs" />		
		<input type="hidden" name="task" value="report.exportAveragesForHotels" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>

