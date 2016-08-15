<?php
defined('_JEXEC') or die;
$points = array();
$dates = array();
foreach ($this->reportdata as $value) {
	if( (int) $value->number_rooms ) {
		$points[] 	=  $value->number_rooms;	
		$dates[] 	=  $value->date;
	}	
}
//example input
/*$m = 6;
for($i=0;$i<$m;$i++){
	$points[] = rand(10,1000);
	$dates[]= $i < 10 ? '2010-0'.$i.'-01' : '2010-'.$i.'-01'; 
}*/
?>
<div class="report-chart-wrap">
	<span class="report-chart-title"><?php echo JText::_('COM_SFS_REPORT_TOTAL_ROOMNIGHTS')?></span>
	<div class="report-chart-img">
	<center>
		<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=report.hotelchart&format=raw&type='.$this->drawType.'&points='.implode(',',$points).'&dates='.implode(',',$dates);?>" />	
	</center>
	</div>
</div>

<div class="report-detail-wrap">
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="report-detail-table">
	<tbody>
		<tr>
		 	<td class="tableheader">Month</td>
	        <td class="tableheader" style="border-right: 1px solid #666;"><?php echo JText::_('COM_SFS_REPORT_ROOMNIGHTS');?></td>
		</tr>
		<?php 					
		foreach ( $this->reportdata as $value ) {						
			echo '<tr class="sectiontableentry1">';
			echo '<td>'.JHtml::_('date',$value->date,'Y - F').'</td>';
			echo '<td style="border-right: 1px solid #666;">'.$value->number_rooms.'</td>';
			echo '</tr>';
		}
		?>										
	</tbody></table>				
</div>	
	
<div>
	<form name="exportHotelRoomnights" action="index.php" method="post">
			<button type="submit" class="sfs-button">
				<?php echo JText::_('COM_SFS_REPORT_EXPORT') ?>
		</button>
		<input type="hidden" name="m_from" value="<?php echo JRequest::getVar('m_from')?>" />
		<input type="hidden" name="y_from" value="<?php echo JRequest::getVar('y_from')?>" />
		<input type="hidden" name="m_to" value="<?php echo JRequest::getVar('m_to')?>" />
		<input type="hidden" name="y_to" value="<?php echo JRequest::getVar('y_to')?>" />
		<input type="hidden" name="option" value="com_sfs" />
		<input type="hidden" name="task" value="report.exportHotelRoomnights" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
