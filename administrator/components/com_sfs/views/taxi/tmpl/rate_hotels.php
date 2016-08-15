<?php
defined('_JEXEC') or die;
$params = JComponentHelper::getParams('com_sfs');
$systemCurrency = $params->get('sfs_system_currency');
?>

<table class="taxi-rate-table">
	<tr>
		<th></th>
		<th><?php echo JText::_('COM_SFS_DAY_FARE')?></th>
		<th><?php echo JText::_('COM_SFS_NIGHT_FARE')?></th>
		<th><?php echo JText::_('COM_SFS_WEEKEND_FARE')?></th>
		<th><?php echo JText::_('COM_SFS_KM_RATE')?></th>
		<th><?php echo JText::_('COM_SFS_STARTING_TARIFF')?></th>
	</tr>	
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
		<td>
			<?php echo $systemCurrency; ?>&nbsp;&nbsp;
			<input type="text" name="ringrates[<?php echo $key?>][0][night_fare]" value="<?php echo isset($item->night_fare)? $item->night_fare : ''?>" class="inputbox validate-numeric" /> 
		</td>
		<td>
			<?php echo $systemCurrency; ?>&nbsp;&nbsp;
			<input type="text" name="ringrates[<?php echo $key?>][0][weekend_fare]" value="<?php echo isset($item->weekend_fare)? $item->weekend_fare : ''?>" class="inputbox validate-numeric" /> 
		</td>
		<td>
			<?php echo $systemCurrency; ?>&nbsp;&nbsp;
			<input type="text" name="ringrates[<?php echo $key?>][0][km_rate]" value="<?php echo isset($item->km_rate)? $item->km_rate : ''?>" class="inputbox validate-numeric" />
		</td>
		<td>
			<?php echo $systemCurrency; ?>&nbsp;&nbsp;
			<input type="text" name="ringrates[<?php echo $key?>][0][starting_tariff]" value="<?php echo isset($item->starting_tariff)? $item->starting_tariff : ''?>" class="inputbox validate-numeric" />
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
				<td>
					<?php echo $systemCurrency; ?>&nbsp;&nbsp;
					<input type="text" name="ringrates[<?php echo $key?>][<?php echo $hotel->hotel_id?>][night_fare]" value="<?php echo isset($hotel->night_fare)? $hotel->night_fare : ''?>" class="inputbox validate-numeric" /> 
				</td>
				<td>
					<?php echo $systemCurrency; ?>&nbsp;&nbsp;
					<input type="text" name="ringrates[<?php echo $key?>][<?php echo $hotel->hotel_id?>][weekend_fare]" value="<?php echo isset($hotel->weekend_fare)? $hotel->weekend_fare : ''?>" class="inputbox validate-numeric" /> 
				</td>
				<td>
					<?php echo $systemCurrency; ?>&nbsp;&nbsp;
					<input type="text" name="ringrates[<?php echo $key?>][<?php echo $hotel->hotel_id?>][km_rate]" value="<?php echo isset($hotel->km_rate)? $hotel->km_rate : ''?>" class="inputbox validate-numeric" />
				</td>
				<td>
					<?php echo $systemCurrency; ?>&nbsp;&nbsp;
					<input type="text" name="ringrates[<?php echo $key?>][<?php echo $hotel->hotel_id?>][starting_tariff]" value="<?php echo isset($hotel->starting_tariff)? $hotel->starting_tariff : ''?>" class="inputbox validate-numeric" />
				</td>

			</tr>				
		<?php endforeach;?>	
		<tr><td colspan="5" style="height: 20px"></td></tr>			
	<?php endif;?>

	<?php endforeach;?>
	
</table>

