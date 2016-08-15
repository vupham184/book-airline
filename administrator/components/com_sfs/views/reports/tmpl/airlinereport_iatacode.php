<?php
defined('_JEXEC') or die;
$points = array();
foreach ($this->iataCodePercentages as $item) {
	$points[]=$item->seat_total;
}
$airlineId = JRequest::getInt('airline_id');
?>

<div class="report-chart-wrap">					
	<h3>IATA code reason</h3>						
	<center>
		<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=reports.drawpie&format=raw&airline_id='.$airlineId.'&points='.implode(',',$points);?>" />	
	</center>
</div>
<div style="overflow: hidden; clear:both;">
	<h4>Top IATA codes</h4>
	<div style="margin-top:10px">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="report-detail-table">
		<tbody>
			<tr>
			 	<td class="tableheader">Reason</td>
		        <td class="tableheader" style="border-right: 1px solid #666;">Code</td>
			</tr>
			
			<?php 	
			$j=0;				
			foreach ($this->iataCodePercentages as $value) {
				if($j==5) break;		
				$value->description=substr(trim($value->description),0,10);				
				echo '<tr class="sectiontableentry1">';
				echo '<td>'.$value->description.'</td>';
				echo '<td style="border-right: 1px solid #666;">'.$value->code.'</td>';
				echo '</tr>';
				$j++;
			}
			?>										
		</tbody></table>				
	</div>	
	<div style="margin-top:10px;">
		<form name="exportIATACodeReason" action="index.php" method="post">
			<button type="submit" class="button" style="margin-left:5px;background:green;color:white;padding:5px 20px;border:none;">Export</button>
			<input type="hidden" name="airline_id" value="<?php echo $airlineId;?>" />
			<input type="hidden" name="gh_airline" value="<?php echo JRequest::getInt('gh_airline');?>" />
			<input type="hidden" name="date_from" value="<?php echo JRequest::getVar('date_from')?>" />
			<input type="hidden" name="date_to" value="<?php echo JRequest::getVar('date_to')?>" />
			<input type="hidden" name="option" value="com_sfs" />
			<input type="hidden" name="task" value="report.exportIATACodeReason" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
</div>