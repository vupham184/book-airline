<?php
defined('_JEXEC') or die;
$params = JComponentHelper::getParams('com_sfs');
$systemCurrency = $params->get('sfs_system_currency');

$colSpan = 2;
if( $this->taxi->getParam('enable_night_fare') == 1 )
{
	$colSpan++;
}
if( $this->taxi->getParam('enable_weekend_fare') == 1 )
{
	$colSpan++;
}
?>

<table class="taxi-rate-table taxi-rate-table-left">
	<tr>
		<th></th>
		<th><?php echo JText::_('COM_SFS_DAY_FARE')?></th>
		
		<?php if( $this->taxi->getParam('enable_night_fare') == 1 ) : ?>
		<th><?php echo JText::_('COM_SFS_NIGHT_FARE')?></th>
		<?php endif;?>
		
		<?php if( $this->taxi->getParam('enable_weekend_fare') == 1 ) : ?>
		<th><?php echo JText::_('COM_SFS_WEEKEND_FARE')?></th>
		<?php endif;?>
		
	</tr>	
	<?php 
	foreach ($this->rates as $key => $item): ?>	
			
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
			<span><?php echo $systemCurrency; ?></span>
			<input type="text" name="ringrates[<?php echo $key?>][0][day_fare]" value="<?php echo isset($item->day_fare)? floatval($item->day_fare) : ''?>" class="inputbox validate-numeric" />
		</td>
		
		<?php if( $this->taxi->getParam('enable_night_fare') == 1 ) : ?>
		<td>
			<span><?php echo $systemCurrency; ?></span>
			<input type="text" name="ringrates[<?php echo $key?>][0][night_fare]" value="<?php echo isset($item->night_fare)? floatval($item->night_fare) : ''?>" class="inputbox validate-numeric" />
		</td>
		<?php endif;?>
		
		<?php if( $this->taxi->getParam('enable_weekend_fare') == 1 ) : ?>
		<td class="last-column">
			<span><?php echo $systemCurrency; ?></span>
			<input type="text" name="ringrates[<?php echo $key?>][0][weekend_fare]" value="<?php echo isset($item->weekend_fare)? floatval($item->weekend_fare) : ''?>" class="inputbox validate-numeric" />
		</td>
		<?php endif;?>
		
	</tr>
	
	<?php if( count( $item->hotels) ) : ?>
		<tr>
			<td colspan="<?php echo $colSpan?>">
				<div class="ring-hotel">
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
				<td class="first-column">
					<div>
					<?php
						echo $hotel->name;
					?>	
					</div>		
				</td>
				<td>
					<span><?php echo $systemCurrency; ?></span>
					<input type="text" name="ringrates[<?php echo $key?>][<?php echo $hotel->hotel_id?>][day_fare]" value="<?php echo isset($hotel->day_fare)? floatval($hotel->day_fare) : ''?>" class="inputbox validate-numeric" />
				</td>
				
				<?php if( $this->taxi->getParam('enable_night_fare') == 1 ) : ?>
				<td>
					<span><?php echo $systemCurrency; ?></span>
					<input type="text" name="ringrates[<?php echo $key?>][<?php echo $hotel->hotel_id?>][night_fare]" value="<?php echo isset($hotel->night_fare)? floatval($hotel->night_fare) : ''?>" class="inputbox validate-numeric" />
				</td>
				<?php endif;?>
				
				<?php if( $this->taxi->getParam('enable_weekend_fare') == 1 ) : ?>
				<td class="last-column">
					<span><?php echo $systemCurrency; ?></span>
					<input type="text" name="ringrates[<?php echo $key?>][<?php echo $hotel->hotel_id?>][weekend_fare]" value="<?php echo isset($hotel->weekend_fare)? floatval($hotel->weekend_fare) : ''?>" class="inputbox validate-numeric" />
				</td>
				<?php endif;?>
				
			</tr>				
		<?php endforeach;?>	
		<tr><td colspan="<?php echo $colSpan?>" style="height: 20px"></td></tr>			
	<?php endif;?>

	<?php endforeach;?>
	
</table>

