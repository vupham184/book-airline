<?php
defined('_JEXEC') or die;
$points = array();

foreach ($this->items as $item) {
    if($item->seat_total && (int)$item->seat_total!=0)
        $points[]=$item->seat_total;
}

$gh_airline = (int) JRequest::getInt('gh_airline');
?>

<div class="report-chart-wrap">					
	<span class="report-chart-title"><?php echo JText::_('COM_SFS_REPORT_IATA_CODE_REASON');?></span>						
	<center>
		<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=report.drawpie&format=raw&points='.implode(',',$points);?>" />	
	</center>
</div>
<div class="report-detail-wrap">
	<h4 class="report-detail-title"><?php echo JText::_('COM_SFS_REPORT_TOP_CODE');?></h4>
	<div style="margin-top:10px">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="report-detail-table">
		<tbody>
			<tr>
			 	<td class="tableheader"><?php echo JText::_('COM_SFS_REPORT_REASON');?></td>
		        <td class="tableheader" style="border-right: 1px solid #666;"><?php echo JText::_('COM_SFS_REPORT_CODE');?></td>
			</tr>
			<?php 	
			$j=0;				
			foreach ($this->items as $value) {
                if($item->seat_total && (int)$item->seat_total!=0)
                {
                    if($j==5) break;
                    $value->description=substr(trim($value->description),0,10);
                    echo '<tr class="sectiontableentry1">';
                    echo '<td>'.$value->description.'</td>';
                    echo '<td style="border-right: 1px solid #666;">'.$value->code.'</td>';
                    echo '</tr>';
                    $j++;
                }
			}
			?>										
		</tbody></table>				
	</div>	
	<div>
		<form name="exportIATACodeReason" action="index.php" method="post">
			<button type="submit" class="sfs-button">
				<?php echo JText::_('COM_SFS_REPORT_EXPORT') ?>
			</button>
			<input type="hidden" name="date_from" value="<?php echo JRequest::getVar('date_from')?>" />
			<input type="hidden" name="date_to" value="<?php echo JRequest::getVar('date_to')?>" />
			<input type="hidden" name="gh_airline" value="<?php echo $gh_airline;?>" />
			<input type="hidden" name="option" value="com_sfs" />
			<input type="hidden" name="task" value="report.exportIATACodeReason" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
</div>