<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');

$profile = null;
foreach ($this->profiles as $row)
{
	if( (int)$row->id == $this->profile_id )
	{		
		$profile = $row;
		break;
	}
}
if($profile) : ?>

<span class="xclose" onclick="window.parent.SqueezeBox.close();"><?php echo JText::_('COM_SFS_CLOSE')?></span>

<h1 class="title1">
	 Rate agreement for <?php echo $profile->name?>
</h1>
<div class="fs-16">
	 You can enter your negotiated prices for one way fares to any specific hotel
</div>

<div id="taxiRateFormWraper" class="fs-14">
	
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate">
				
		<table class="taxi-rate-table">				
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
		
		<div class="float-right" style="padding: 0 20px 10px 0;">			
			<button type="submit" class="validate small-button float-left" style="margin:0 0 0 15px;">
				<?php echo JText::_('COM_SFS_SAVE')?>
			</button>
		</div>	
		
		<input type="hidden" name="task" value="bus.saveRates" /> 
		<input type="hidden" name="option" value="com_sfs" />		
		<input type="hidden" name="profile_id" value="<?php echo $profile->id; ?>" />		
		
		<?php echo JHtml::_('form.token'); ?>
		
	</form>	
			
</div>

<?php endif;?>