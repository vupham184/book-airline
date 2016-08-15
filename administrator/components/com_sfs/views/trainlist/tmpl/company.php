<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
$objtrain = $this->train;
//print_r($objtrain);
?>

<div>
	
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate" id="adminForm">

		<fieldset>
			<div class="fltrt">
				<button type="submit">
					Save
				</button>			
				<button onclick="  window.parent.SqueezeBox.close();" type="button">
					Close
				</button>
			</div>
		</fieldset>
		
		<div style="overflow:hidden;padding:20px;background-color: #FFFFFF;box-shadow: none;clear: both;">
			<div>				
				<ul class="adminformlist">
					<li>
						
						<?php if(isset($objtrain->iata_airportcode)): ?>
							<label name='iata_airportcode' value='<?php echo $objtrain->iata_airportcode;?>'>
								Iata Airportcode: <?php echo $objtrain->iata_airportcode.' - '.$objtrain->airlineName;?>
							</label>
						<?php else: ?>
							<label>Iata Airportcode</label>
							<select name='id'>
								<?php foreach ($this->listairline as $airline):?>
									<option value="<?php echo $airline->id; ?>"><?php echo $airline->code.' - '.$airline->name;?></option>
								<?php endforeach;?>
							</select>
						<?php endif ?>
					</li>
					<li>
						<label>Stationname</label>
						<input type="text" name="stationname" id="stationname" value='<?php echo isset($objtrain->stationname)?$objtrain->stationname:''; ?>'size="40" required="required" />
					</li>
					<li>
						<label>Cityname</label>
						<input type="text" name="cityname" id="cityname" value='<?php echo isset($objtrain->cityname)?$objtrain->cityname:''; ?>'size="40" required="required"/>
					</li>
					
					<li>
						<label>Status</label>							
						<select name="status" class="required">								
							<option value="1" selected="selected">Enable</option>
							<option value="0">Disabled</option>
						</select>			
					</li>
				</ul>
			</div>	

		</div>
		<?php if (isset($objtrain->id)): ?>
			<input type="hidden" name="id" value="<?php echo $objtrain->id; ?>" />
			<input type="hidden" name="task" value="trainlist.editAirlineTrain" />	
		<?php else: ?>	
			<input type="hidden" name="task" value="trainlist.saveAirlineTrain" /> 
		<?php endif ?>	
		<input type="hidden" name="option" value="com_sfs" />				
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
	</form>	

</div>

