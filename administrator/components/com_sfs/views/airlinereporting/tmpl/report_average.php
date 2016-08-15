<?php
defined('_JEXEC') or die;
$points = array();
$dates = array();

foreach ($this->averageChart as $value) {
	if( (int) $value->rate_total && $value->booked_total ) {
		$points[]	= (int)($value->rate_total / $value->booked_total);	
		$dates[] 	=  $value->date;
	}	
}
$airlineId = JRequest::getInt('airline_id');

$params = JComponentHelper::getParams('com_sfs');
$sfs_system_currency = $params->get('sfs_system_currency','EUR');
?>

<script type="text/javascript">
	window.addEvent('domready', function(){
		$('average-modal').addEvent('click', function(e){			
			SqueezeBox.open($('averages'), {handler: 'clone',size: {x: 980, y: 360}});	
		});					
	});
</script>

<div class="chartmodal" id="average-modal">[ View Lightbox ]</div>

<h3>Average room prices booked</h3>

<div class="airline-reporting-chart">	
	<div class="report-chart-img">
		<center>
			<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=airlinereporting.chart&format=raw&type=2&points='.implode(',',$points).'&dates='.implode(',',$dates);?>" />	
		</center>
		<div style="display:none">
			<div id="averages">
				<div style="padding: 15px;">
					<h3>Average room prices booked</h3>
					<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=airlinereporting.chart&format=raw&type=2&points='.implode(',',$points).'&dates='.implode(',',$dates);?>" />
				</div>
			</div>		
		</div>
	</div>
</div>

<div style="overflow: hidden; clear:both;">
	<h4>Top Hotels</h4>
	<div style="margin-top:10px">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="report-detail-table">
			<tbody>
				<tr>
				 	<td class="tableheader" nowrap="nowrap">Hotel Name</td>
			        <td class="tableheader" style="border-right: 1px solid #666;" nowrap="nowrap">ADR</td>
				</tr>
				<?php 					
				foreach ($this->averages as $item) {					
					echo '<tr class="sectiontableentry1">';
					echo '<td width="100%">'.$item->name.'</td>';
					echo '<td nowrap="nowrap" style="border-right: 1px solid #666; text-align:center">'.number_format($item->rate_total / $item->booked_total,2).' '.$sfs_system_currency.'</td>';
					echo '</tr>';
				}
				?>										
			</tbody>
		</table>
	</div>
	
	<div style="margin-top:10px;">
		<form name="exportAveragePrices" action="index.php" method="post">
			<button type="submit" class="button" style="margin-left:5px;">Export</button>
			
			<input type="hidden" name="date_from" value="<?php echo JRequest::getVar('date_from')?>" />
			<input type="hidden" name="date_to" value="<?php echo JRequest::getVar('date_to')?>" />
			
			<?php if( count($this->statuses) ):?>
			
				<?php foreach ($this->statuses as $status) : ?>
					<input type="hidden" name="blockStatus[]" value="<?php echo $status;?>">
				<?php endforeach;?>
			
			<?php endif;?>
			
			
			<input type="hidden" name="option" value="com_sfs" />
			<input type="hidden" name="task" value="airlinereporting.exportAverages" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
	
</div>		
