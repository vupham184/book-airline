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
$hotelId = JRequest::getInt('hotel_id');

?>
<div class="report-chart-wrap">
	<h3>Revenue booked</h3>
	<center>
		<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=reports.hotelchart&format=raw&type=3&hotel_id='.$hotelId.'&points='.implode(',',$points).'&dates='.implode(',',$dates);?>" />
	</center>
</div>
<div style="clear:both;overflow:hidden;">
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
			echo '<td style="border-right: 1px solid #666;">'.$value->revenue_booked.' '.$this->hotel->currency.'</td>';
			echo '</tr>';
		}
		?>

	</tbody></table>
</div>

<div style="margin-top:10px;">
	<form name="exportHotelRevenueBooked" action="index.php" method="post">
		<button type="submit" class="button" style="margin-left:5px;background:green;color:white;padding:5px 20px;border:none;">Export</button>
		<input type="hidden" name="m_from" value="<?php echo JRequest::getVar('m_from')?>" />
		<input type="hidden" name="y_from" value="<?php echo JRequest::getVar('y_from')?>" />
		<input type="hidden" name="m_to" value="<?php echo JRequest::getVar('m_to')?>" />
		<input type="hidden" name="y_to" value="<?php echo JRequest::getVar('y_to')?>" />
		
		<?php if( count($this->statuses) ):?>
			
				<?php foreach ($this->statuses as $status) : ?>
					<input type="hidden" name="blockStatus[]" value="<?php echo $status;?>">
				<?php endforeach;?>
			
			<?php endif;?>
			
			
		<input type="hidden" name="option" value="com_sfs" />
		<input type="hidden" name="hotel_id" value="<?php echo $hotelId?>" />
		<input type="hidden" name="task" value="report.exportHotelRevenueBooked" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>