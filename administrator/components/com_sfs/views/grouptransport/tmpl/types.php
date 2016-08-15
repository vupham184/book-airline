<?php
defined('_JEXEC') or die;
//JHtml::_('behavior.mootools');
JHtml::_('behavior.framework');
JHtml::_('behavior.keepalive');
?>

<div>	
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" id="groupTransportTypeForm" name="groupTransportTypeForm">
	
		<fieldset>
			<div class="fltrt">				
				<button onclick="window.parent.location.href=window.parent.location.href;window.parent.SqueezeBox.close();" type="button">
					Close
				</button>
			</div>
			<div class="configuration">
				<?php
				$title = '';; 				
				if($this->groupTransport){
					$title .= $this->groupTransport->name.': Types';	
				} 
				echo $title;			
				?>
			</div>
		</fieldset>
		
		<div style="overflow:hidden;padding:20px;background-color: #FFFFFF;box-shadow: none;clear: both;">
	
			<div style="margin-bottom:20px;" id="updateResult">
			<?php if( count($this->groupTransport->types) ) : ?>			
			<table class="adminlist">
				<tr>
					<th width="5%">ID</th>
					<th width="22%">Name</th>
					<th width="10%">seats</th>	
					<th width="20%">Rate for first 50 km</th>
					<th width="20%">Rate 50 to 100 (km)</th>
					<th width="23%">Rate 100 to 150 (km)</th>						
				</tr>
				<?php foreach ($this->groupTransport->types as $type) : ?>
				<tr>
					<td>
						<?php echo $type->id;?>
					</td>
					<td>
						<?php echo $type->name;?>
					</td>
					<td>
						<?php echo $type->seats;?>
					</td>
					
					<?php $rates = json_decode($type->rate); foreach($rates as $rate) :?>
						<td>
							<?php echo $rate->rate_three;?>
						</td>
					<?php endforeach; ?>
					
													
				</tr>
				<?php endforeach;?>
			</table>		
			<?php endif;?>
			</div>
			
			<table>			
				<tr>
					<td>
						Name: <input type="text" name="name" value="" class="inputbox required">
					</td>
					<td>
						Seats: <input type="text" name="seats" value="" class="inputbox required">
					</td>
					<td>
						Rate for first 50 KM: <input type="text" name="rate_first" value="" class="inputbox required">
					</td>
					<td>
						Rate 50 to 100 (km): <input type="text" name="rate_second" value="" class="inputbox required">
					</td>
					<td>
						Rate 100 to 150 (km): <input type="text" name="rate_three" value="" class="inputbox required">
					</td>									
				</tr>
				<tr style="height: 20px;">&#160;</tr>
				<tr>
					<td colspan="5">
						<button type="submit" id="addGroupType">Add</button>
					</td>
				</tr>
			</table>			
							
		</div>
		
		<!-- <input type="hidden" name="task" value="ajax.saveTranportTypes" />  -->
		<input type="hidden" name="task" value="grouptransport.addType" /> 
		<input type="hidden" name="option" value="com_sfs" />
		<!-- <input type="hidden" name="format" value="raw" />		 -->
		<input type="hidden" name="id" value="<?php echo JRequest::getInt('id')?>" />
				
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
	</form>	
			
</div>


