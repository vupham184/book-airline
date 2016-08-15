<?php
defined('_JEXEC') or die;
$points = array();
$dates = array();
$currency_code = $this->datatotalbookingoverview[0];
$datatotalbookingoverview = $this->datatotalbookingoverview[1];
foreach ($datatotalbookingoverview as $key => $value) {
		$rooms = $value->s_room+$value->sd_room+$value->t_room+$value->q_room;
		/*$rooms = 0;
		if ( $v->s_room > 0 ) {
			$rooms = $v->s_room;
		}
		if ( $v->sd_room > 0 ) {
			$rooms = $v->sd_room;
		}
		if ( $v->t_room > 0 ) {
			$rooms = $v->t_room;
		}
		if ( $v->q_room > 0 ) {
			$rooms = $v->q_room;
		}
		if ( $v->ws_room == 1 && $rooms == 0){
			$rooms = 1;
		}*/
		$points[] 	=  $rooms;
		$dates[] 	=  $value->date;
}

$uk   = JRequest::getVar('uk');
if ( $uk != '' ) {
	$uk = '&uk=' . $uk; 
}

$gh_airline = (int) JRequest::getInt('gh_airline');

//lchung add sesstion change param &points='.implode(',',$points).'&dates='.implode(',',$dates)
$session = JFactory::getSession();
$report_points_dates = array('points' => implode(',',$points), 'dates' => implode(',',$dates));
$session->set('report_points_dates', $report_points_dates);

$lightBoxUrl = 'index.php?option=com_sfs&view=report&layout=print&type=1&tmpl=component&points='.implode(',',$points).'&dates='.implode(',',$dates);
?>

<script type="text/javascript">
	window.addEvent('domready', function(){
		$('roomnight-modal').addEvent('click', function(e){			
			SqueezeBox.open('<?php echo JURI::base().$lightBoxUrl?>', {handler: 'iframe', size: {x:800, y: 350}});	
		});					
	});
</script>

<div class="report-chart-wrap">
	<span class="report-chart-title" style="color:#8c523f;"><?php echo JText::_('Total booking overview')?></span>
	<div class="report-chart-img">
		<center><!--airlinechart--> 
			<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=report.totalbookingoverviewchart&type='.$this->drawType.'&format=raw'; echo $uk;?>" />	
		</center>
	</div>
	
	<div style="text-align:right;">
		<a id="roomnight-modal" style="cursor:pointer; color:#0066CC;">View Full Image</a>
	</div>
</div>

<div class="report-detail-wrap">
	<h4 class="report-detail-title" style="color:#8c523f;"><?php echo JText::_('Individual booking list')?></h4>
	<div style="margin-top:10px">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="report-detail-table" style="color:#8c523f;">  
			<tbody>
				<tr>
				 	<td class="tableheader" nowrap="nowrap"><?php echo JText::_('Booking details')?></td>
			        <td class="tableheader" style="border-right: 1px solid #666;" nowrap="nowrap"><?php echo JText::_('Date')?></td>
                    <td class="tableheader" style="border-right: 1px solid #666;" nowrap="nowrap"><?php echo JText::_('Hotel')?></td>
                    <td class="tableheader" style="border-right: 1px solid #666;" nowrap="nowrap"><?php echo JText::_('Rooms')?></td>
                    <td class="tableheader" style="border-right: 1px solid #666;" nowrap="nowrap"><?php echo JText::_('Rate per room')?><!--Gross--></td>
				</tr>
				<?php 				
				foreach ($datatotalbookingoverview as $v) {
					///$gross_price = $v->sd_rate+$v->t_rate+$v->s_rate+$v->q_rate;
					$gross_price = 0;
					$rooms = 0;
					if ( $v->s_room > 0 ) {
						$gross_price = $v->s_rate;
						$rooms = $v->s_room;
					}
					if ( $v->sd_room > 0 ) {
						$gross_price = $v->sd_rate;
						$rooms = $v->sd_room;
					}
					if ( $v->t_room > 0 ) {
						$gross_price = $v->t_rate;
						$rooms = $v->t_room;
					}
					if ( $v->q_room > 0 ) {
						$gross_price = $v->q_rate;
						$rooms = $v->q_room;
					}
					
					if ( $v->ws_room == 1 && $rooms == 0){
						$rooms = 1;
						$gross_price = $v->s_rate;
					}
				
					$total = floatval( $gross_price );
					if ($v->breakfast > 0 ) {
						$total += $v->people_num * floatval( $v->breakfast );
					}
					if ($v->lunch > 0 ) {
						$total += $v->people_num * floatval( $v->lunch );
					}
					if ($v->mealplan > 0 ) {
						$total += $v->people_num * floatval( $v->mealplan );
					}
										
					echo '<tr class="sectiontableentry1">';
					echo '<td >'.$v->blockcode.'</td>';
					echo '<td >'.$v->date.'</td>';
					echo '<td >'.$v->hotel_name.'</td>';
					echo '<td >'.$rooms.'</td>';
					echo '<td style="border-right: 1px solid #666; text-align:right">
					'. number_format( $total, 2, ".", ",") . ' ' . $currency_code . '</td>';
					echo '</tr>';
				}
				?>										
			</tbody>
		</table>
	</div>
	
	<div>
    	<form method="post" action="index.php" name="newReportAirline" id="newReportAirline">
        
        <input type="hidden" name="date_from" value="<?php echo JRequest::getVar('date_from')?>" id="newReportAirline_date_from" />
		<input type="hidden" name="date_to" value="<?php echo JRequest::getVar('date_to')?>" id="newReportAirline_date_to" />
            
                   
        <input type="hidden" value="com_sfs" name="option">
        <input type="hidden" value="report.newReportAirline" name="task">
        <input type="hidden" value="<?php echo $this->check_userkey->secret_key;?>" name="uk" id="uksecretkey">
        <?php echo JHtml::_('form.token'); ?>
             <button type="button" class="sfs-button new-report-airline"><?php echo JText::_('&nbsp;Export&nbsp;')?></button>
		</form>
	</div>
				
</div>	
<script type="text/javascript">
jQuery(function( $ ){
	$('.new-report-airline').click(function(e) {
		$('#uksecretkey').val( $('#uk_secret_key').val() );
		$('#newReportAirline').submit();
    });;
});
</script>	
