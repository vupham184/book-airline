<?php
defined('_JEXEC') or die;
?>

<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=transportreservations');?>" method="post" name="adminForm" id="adminForm">
	
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
				<th width="10%" style="text-align:left; text-indent:5px;">
					<a href="#">Ref Number</a>
				</th>				
				<th width="14%" style="text-align:left;text-indent:5px;">
					<a href="#">Date/Booked By</a>
				</th>									
				<th width="14%" style="text-align:left;text-indent:5px;">
					<a href="#">Bus Company</a>
				</th>									
				<th width="7%" style="text-align:left;text-indent:5px;">
					<a href="#" style="text-align:left;text-indent:5px;">Passengers</a>
				</th>	
				<th width="7%" style="text-align:left;text-indent:5px;">
					<a href="#">Flight Number</a>
				</th>	
				<th width="7%" style="text-align:left;text-indent:5px;">
					<a href="#" style="text-align:left;text-indent:5px;">Departure</a>
				</th>	
				<th width="7%" style="text-align:left;text-indent:5px;">
					<a href="#">Requested Time</a>
				</th>	
				<th><a href="#">Comment</a></th>											
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="14">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>			
				<td>													
					<?php echo $item->reference_number;?>										
				</td>				
				<td>
					<?php 
					//echo JHtml::_('date',$item->booked_date,JText::_('DATE_FORMAT_LC2'),true);
					echo sfsHelper::getATDate($item->booked_date,'d F Y H:i');
					?>
					<br />
					<?php if(!empty($item->company_name)) : ?>						
						<?php echo $item->company_name;?>						
					<?php else : ?>
						<?php echo $item->airline_name;?>						
					<?php endif; ?>		
					<br />
					<?php echo $item->booked_name;?>
				</td>					
				
				<td>					
					<?php echo $item->transport_company_name;?> 									   
				</td>					
				<td>
					<?php echo $item->total_passengers;?>
				</td>	
				<td>
					<?php echo $item->flight_number;?>
				</td>	
				<td>
					<?php echo $item->terminal;?>
				</td>	
				<td>
					<?php 
					if( $item->requested_time == '0' ) {
						echo 'asap';
					}  else {
						echo $item->requested_time;
					}
					?>
				</td>
				<td>
					<?php echo $item->comment;?>
				</td>		
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
