<?php
defined('_JEXEC') or die;
//JHtml::_('behavior.mootools');
JHtml::_('behavior.framework');
JHtml::_('behavior.keepalive');

$params = JComponentHelper::getParams('com_sfs');
$systemCurrency = $params->get('sfs_system_currency');
?>


</script>
<div>	
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" id="groupTransportTypeForm" name="groupTransportTypeForm">
	
		<fieldset>
			<div class="fltrt">	
				<button type="submit">
					Save
				</button>			
				<button onclick="window.parent.SqueezeBox.close(window.parent.location.reload());" type="button">
					Close
				</button>
			</div>
			<div class="configuration">
				<?php echo $this->groupTransportTypeFixed->name.': ';?>Rates
			</div>
		</fieldset>
		
		<div style="overflow:hidden;padding:20px;background-color: #FFFFFF;box-shadow: none;clear: both;">						
		
		<div style="padding-bottom:20px;">
		<table>
			<tr>
				<td width="20%">Name</td>
				<td width="20%">Seat</td>
				<td width="13%">From</td>
				<td width="12%">To</td>
				<td width="15%">Rate</td>
			</tr>
			<tr>
				<td>
					<input type="text" name="name" value="<?php echo $this->groupTransportTypeFixed->name?>" />
				</td>
				<td>
					<input type="text" name="seats" value="<?php echo $this->groupTransportTypeFixed->seats?>" />
				</td>
				<td colspan="3">
					<table style="width:100%;" border="0">						
						<?php $rates = json_decode($this->groupTransportTypeFixed->rate_fixed); foreach($rates as $ke=>$rate) :?>
							<tr>
								<td width="30%">								
									<select name="viewRate[<?php echo $ke; ?>][airport_from]">
										<?php foreach ($this->listAirportTo as $key => $value) : ?>
											<?php if($value->code == $rate->airport_from): ?>
												<option value="<?php echo $value->code; ?>" selected><?php echo $value->code; ?></option>
											<?php else: ?>
												<option value="<?php echo $value->code; ?>"><?php echo $value->code; ?></option>
											<?php endif; ?>
										<?php endforeach; ?>
									</select>
								</td>
								<td width="30%">
									<select name="viewRate[<?php echo $ke; ?>][airport_to]">
										<?php foreach ($this->listAirport as $k => $val) : ?>
											<?php if($val->code == $rate->airport_to): ?>
												<option value="<?php echo $val->code; ?>" selected><?php echo $val->code; ?></option>
											<?php else: ?>
												<option value="<?php echo $val->code; ?>"><?php echo $val->code; ?></option>
											<?php endif; ?>
											
										<?php endforeach; ?>
									</select>
								</td>
								<td width="30%">							
									<input type="text" name="viewRate[<?php echo $ke; ?>][rate]" value="<?php echo $rate->rate; ?>">
								</td>								
							</tr>			
						<?php endforeach; ?>					
					</table>
				</td>
				<!-- <td>Rate for first 50 KM: <input type="text" name="rate_first" value="<?php //echo floatval($this->groupTransportType->rate_first)?>" /></td>
				<td>Rate 50 to 100 (km): <input type="text" name="rate_second" value="<?php //echo floatval($this->groupTransportType->rate_second)?>" /></td>
				<td>Rate 100 to 150 (km): <input type="text" name="rate_three" value="<?php //echo floatval($this->groupTransportType->rate_three)?>" /></td> -->
			</tr>
		</table>
		</div>
		
		
		<table class="taxi-rate-table" border="0">				
			<?php 
			foreach ($this->hotels as $key => $item): ?>	
					
			<tr>
				<td class="first-column">
					<?php
					if( (int)$key < 4 ) {
						echo JText::_('COM_SFS_ONE_WAY_RATE_FOR_RING_'.$key);	
					} else {
						echo JText::sprintf('COM_SFS_ONE_WAY_RATE_FOR_RING',$key);	
					}
					?>			
				</td>
				<td>
					<?php echo $systemCurrency; ?>&nbsp;&nbsp;
					<input type="text" name="ringrates[<?php echo $key?>][0][day_fare]" value="<?php echo isset($item->day_fare)? $item->day_fare : ''?>" class="inputbox validate-numeric" /> 
				</td>				
			</tr>
			
			<?php if( count( $item->hotels) ) : ?>
				<tr>
					<td colspan="4">
						<div style="padding-left: 20px;">
						<?php
						if( (int)$key < 4 ) {
							echo JText::_('COM_SFS_RING_HOTEL_ARE_'.$key);	
						} else {
							echo JText::sprintf('COM_SFS_RING_HOTEL_ARE',$key);	
						}
						?>
						</div>
					</td>		
				</tr>
				<?php foreach ($item->hotels as $hotel): ?>			
					<tr>
						<td class="">
							<div style="padding-left: 40px;">
							<?php
								echo $hotel->name;
							?>	
							</div>		
						</td>
						<td>
							<?php echo $systemCurrency; ?>&nbsp;&nbsp;
							<input type="text" name="ringrates[<?php echo $key?>][<?php echo $hotel->hotel_id?>][day_fare]" value="<?php echo isset($hotel->day_fare)? $hotel->day_fare : ''?>" class="inputbox validate-numeric" /> 
						</td>						
					</tr>				
				<?php endforeach;?>	
						
			<?php endif;?>
		
			<?php endforeach;?>
			
		</table>						
										
		</div>
		
		<input type="hidden" name="task" value="grouptransport.saveRateFixed" /> 
		<input type="hidden" name="option" value="com_sfs" />		
		<input type="hidden" name="id" value="<?php echo $this->state->get('grouptransport.id');?>" />
		<input type="hidden" name="group_transportation_type_id" value="<?php echo $this->state->get('grouptransport.type_id');?>" />
				
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
	</form>	
			
</div>

