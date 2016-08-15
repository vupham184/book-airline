<?php
// no direct access
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=vouchers');?>" method="post" name="adminForm" id="adminForm">
	
	<fieldset id="filter-bar">
	
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		
		<div class="filter-select fltrt">
			<?php
			$filter_airline_id = $this->state->get('filter.airline_id',0); 
			?>
			<select name="filter_airline_id" class="inputbox" onchange="this.form.submit()">
				<option value="">Select Airline</option>
				<?php foreach ($this->airlines as $airline) : ?>
					<option value="<?php echo $airline->id?>"<?php echo (int)$filter_airline_id == (int)$airline->id ? ' selected="selected"':''?>>
						<?php echo (!empty($airline->name)) ? $airline->name : $airline->company_name;?>
					</option>
				<?php endforeach;?>				
			</select>
			
			<?php
			$filter_hotel_id = $this->state->get('filter.hotel_id',0); 
			?>
			<select name="filter_hotel_id" class="inputbox" onchange="this.form.submit()">
				<option value="">Select Hotel</option>
				<?php foreach ($this->hotels as $hotel) : ?>
					<option value="<?php echo $hotel->id?>"<?php echo (int)$filter_hotel_id == (int)$hotel->id ? ' selected="selected"':''?>><?php echo $hotel->name;?></option>
				<?php endforeach;?>				
			</select>
			
			<?php
			$filter_voucher_type = $this->state->get('filter.voucher_type',null); 
			?>
			<select name="filter_voucher_type" class="inputbox" onchange="this.form.submit()">
				<option value="">Select Type</option>			
				<option value="0"<?php echo $filter_voucher_type!=null & $filter_voucher_type == 0 ? ' selected="selected"':''?>>single</option>
				<option value="1"<?php echo $filter_voucher_type == 1 ? ' selected="selected"':''?>>group</option>								
			</select>
		
			<?php
			$filter_voucher_status = $this->state->get('filter.voucher_status',''); 
			?>
			<select name="filter_voucher_status" class="inputbox" onchange="this.form.submit()">
				<option value="">Select Status</option>			
				<option value="1"<?php echo $filter_voucher_status == 1 ? ' selected="selected"':''?>>in used</option>
				<option value="3"<?php echo $filter_voucher_status == 3 ? ' selected="selected"':''?>>cancelled</option>								
			</select>
		</div>		
		
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>								
				<th width="10%" style="text-align:left;text-indent:5px;">
					<a href="#">Blockcode</a>
				</th>			
				<th width="10%" style="text-align:left; text-indent:5px;">
					<a href="#">Voucher number</a>
				</th>
				<th width="15%" style="text-align:left; text-indent:5px;">
					<a href="#">Transportation Ref</a>
				</th>							
				<th width="10%" style="text-align:left;text-indent:5px;"><a href="#">Flight number</a></th>
				<th width="10%" style="text-align:left;text-indent:5px;"><a href="#">New Flight/Date</a></th>	
				<th width="5%" style="text-align:left;text-indent:5px;">
					<a href="#">Seats</a>
				</th>							
				<th width="7%" style="text-align:left;text-indent:5px;">
					<a href="#">Room type</a>
				</th>				
				<th width="10%" style="text-align:left;text-indent:5px;">
					<a href="#">Created</a>
				</th>	
				<th width="7%" style="text-align:left;text-indent:5px;">
					<a href="#">Printed/Emailed</a>
				</th>	
				<th width="5%" style="text-align:left;text-indent:5px;">
					<a href="#">Status</a>
				</th>															
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php echo $item->blockcode;?>
				</td>										
				<td>
					<a href="index.php?option=com_sfs&view=vouchers&voucher_id=<?php echo $item->id?>&layout=voucher&tmpl=component" class="modal" rel="{handler: 'iframe', size: {x: 900, y: 650}, onClose: function() {}}" style="text-decoration:underline;">
						<?php echo $item->code;?>
					</a>
				</td>
				<td>
					<?php 
					if( !empty($item->taxi_voucher_code) )
					{
						echo 'Taxi: '.$item->taxi_voucher_code;
					} else if( !empty($item->bus_reference_number) )
					{
						echo 'Bus: '.$item->bus_reference_number;
					} else {
						echo 'N/A';
					}
					?>
				</td>													
				<td><?php echo $item->flight_code;?></td>
				<td>
					<?php 
					if($item->return_flight_number)
					{
						echo $item->return_flight_number.'<br/>'.$item->return_flight_date;	
					}
					
					?>
				</td>
				<td><?php echo $item->seats;?></td>
				<td>
					<?php
					switch ($item->room_type) {
						case 1:
							echo 'Single'; 
							break;
						case 2: 
							echo 'Double';
							break;
						case 3: 
							echo 'Triple';
							break;
						case 4: 
							echo 'Quad';
							break;
						default:
							$array = array();
							if( (int)$item->sroom > 0 ) {
								$array[] = $item->sroom.' Single';
							}
							if( (int)$item->sdroom > 0 ) {
								$array[] = $item->sdroom.' Double';
							}
							if( (int)$item->troom > 0 ) {
								$array[] = $item->troom.' Triple';
							}
							if( (int)$item->qroom > 0 ) {
								$array[] = $item->qroom.' Quad';
							}
							if(count($array))
							{
								echo implode('<br />', $array);
							}
							break;				
					}
					?>			    
				</td>									
				<td>
					<?php echo sfsHelper::getATDate($item->created, 'd F Y H:i');?>					
				</td>	
				<td>
					<?php
					if( (int)$item->printed == 1 )
					{
						echo sfsHelper::getATDate($item->printed_date, 'd F Y H:i');
					}
					if( $item->passenger_email ) {
						echo $item->passenger_email;
					} 
					?>
				</td>	
				<td>
					<?php 
					if( (int)$item->status < 3 )
					{
						echo 'in used';
					} else {
						echo '<span style="color:red">cancelled</span>';
					}
					?>
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
