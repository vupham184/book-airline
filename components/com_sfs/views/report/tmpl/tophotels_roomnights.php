<?php
defined('_JEXEC') or die;
$points = array();
$dates = array();
foreach ($this->chartData as $value) {
	if( (int) $value->blocked_rooms ) {
		$points[] 	=  $value->blocked_rooms;	
		$dates[] 	=  $value->date;
	}	
}
$gh_airline = (int) JRequest::getInt('gh_airline');
$lightBoxUrl = 'index.php?option=com_sfs&view=report&layout=print&type='.$this->drawType.'&tmpl=component&points='.implode(',',$points).'&dates='.implode(',',$dates);
?>

<script type="text/javascript">
	window.addEvent('domready', function(){
		$('roomnight-modal').addEvent('click', function(e){			
			SqueezeBox.open('<?php echo JURI::base().$lightBoxUrl?>', {handler: 'iframe', size: {x:800, y: 350}});	
		});					
	});
</script>

<div class="report-chart-wrap">
	<span class="report-chart-title"><?php echo JText::_('COM_SFS_REPORT_TOTAL_ROOMNIGHTS')?></span>
	<div class="report-chart-img">
		<center>
			<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=report.airlinechart&type='.$this->drawType.'&format=raw&points='.implode(',',$points).'&dates='.implode(',',$dates);?>" />	
		</center>
	</div>
	
	<div style="text-align:right;">
		<a id="roomnight-modal" style="cursor:pointer; color:#0066CC;">View Full Image</a>
	</div>
</div>

<div class="report-detail-wrap">
	<h4 class="report-detail-title"><?php echo JText::_('COM_SFS_REPORT_TOP_HOTELS')?></h4>
	<div style="margin-top:10px">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="report-detail-table">  
			<tbody>
				<tr>
				 	<td class="tableheader" nowrap="nowrap"><?php echo JText::_('COM_SFS_REPORT_HOTEL_NAME')?></td>
			        <td class="tableheader" style="border-right: 1px solid #666;" nowrap="nowrap"><?php echo JText::_('COM_SFS_REPORT_ROOMNIGHTS')?></td>
				</tr>
				<?php 					
				foreach ($this->items as $item) {					
					echo '<tr class="sectiontableentry1">';
					echo '<td width="100%">'.$item->name.'</td>';
					echo '<td style="border-right: 1px solid #666; text-align:center">'.$item->blocked_rooms.'</td>';
					echo '</tr>';
				}
				?>										
			</tbody>
		</table>
	</div>
	
	<div>
		<form name="exportRoomnights" action="index.php" method="post">
			<button type="submit" class="sfs-button">
				<?php echo JText::_('COM_SFS_REPORT_EXPORT') ?>
			</button>
			<input type="hidden" name="date_from" value="<?php echo JRequest::getVar('date_from')?>" />
			<input type="hidden" name="date_to" value="<?php echo JRequest::getVar('date_to')?>" />
			<input type="hidden" name="gh_airline" value="<?php echo $gh_airline;?>" />
			
			<input type="hidden" name="option" value="com_sfs" />
			<input type="hidden" name="task" value="report.exportRoomnights" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
				
</div>		
