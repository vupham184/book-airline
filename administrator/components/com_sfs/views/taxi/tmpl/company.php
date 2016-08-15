<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
$airlineId = JRequest::getInt('airline_id',0);
$airportId = JRequest::getInt('airport_id',0);
?>
<style type="text/css">
	.part-push-left{
		overflow:hidden;
		padding:20px;
		background-color: #FFFFFF;
		box-shadow: none;
		width:40%; 
		float:left
	}
	.part-push-right{
		overflow:hidden;
		padding:20px;
		background-color: #FFFFFF;
		box-shadow: none;
		width:40%; 
		float:right;
	}
	.push-left{
		padding: 20px;
		background-color: #FFFFFF;
		box-shadow: none;
		width:90%; 
		float:right;
	}
</style>
<div >
	
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
			<div class="configuration">
				<?php
				$title = '';; 
				if($this->airline){
					$title = !empty($this->airline->company_name) ? $this->airline->company_name : $this->airline->airline_name;
					$title .= ' - ';
				}
				if($this->taxi){
					$title .= $this->taxi->name.': Edit';	
				} else {
					$title .= 'New Taxi Company';	
				}	
				echo $title;			
				?>
			</div>
		</fieldset>
		<div>
			<div class="push-left">
				<!-- begin add Airline -->
				<?php if($airlineId ):?> 
					<div >				
						<ul class="adminformlist">
							<li>
								<label><?php echo JText::_('Company Name');?></label>
								<input type="text" name="name" id="name" class="required" value="<?php echo isset($this->taxi)? $this->taxi->name : ''; ?>" size="40" />
							</li>
							<li>
								<label>Email address</label>
								<input type="text" name="email" id="email" value="<?php echo isset($this->taxi)? $this->taxi->email:''; ?>" size="40" />
							</li>
							<li>
								<label>Phone number</label>
								<input type="text" name="telephone" id="telephone" value="<?php echo isset($this->taxi)?  $this->taxi->telephone:''; ?>" size="40" />
							</li>
							<li>
								<label>Fax number</label>
								<input type="text" name="fax" id="fax" value="<?php echo isset($this->taxi)?  $this->taxi->fax : ''; ?>" size="40" />
							</li>
							<li>
								<label>Sending booking email</label>
								<?php
								$checked = 'checked=checked'; 
								if( isset($this->taxi) && (int)$this->taxi->sendMail == 0 ) {
									$checked = '';
								}
								?>
								<input type="checkbox" <?php echo $checked;?> name="sendMail" id="sendMail" value="1" size="40" />
							</li>
							<li>
								<?php
								$checked = 'checked=checked'; 
								if( isset($this->taxi) && (int)$this->taxi->sendFax == 0 ) {
									$checked = '';
								}
								?>
								<label>Sending booking fax</label>
								<input type="checkbox" <?php echo $checked ?> name="sendFax" id="sendFax" value="1" size="40" />
							</li>
							<li>
								<?php
								$checked = 'checked=checked'; 
								if( isset($this->taxi) && (int)$this->taxi->sendSMS == 0 ) {
									$checked = '';
								}
								?>
								<label>Sending booking sms</label>
								<input type="checkbox" <?php echo $checked ?> name="sendSMS" id="sendSMS" value="1" size="40" />
							</li>
							<li>
								<label>Status</label>							
								<select name="published" class="required">
									<option value="">Select</option>
									<?php if( isset($this->taxi) ) : ?>
										<option value="1"<?php if(isset($this->taxi) && $this->taxi->published == 1) echo ' selected="selected"'; ?>>Published</option>
										<option value="0"<?php if(isset($this->taxi) && $this->taxi->published == 0) echo ' selected="selected"'; ?>>Disabled</option>
										<option value="-2"<?php if(isset($this->taxi) && $this->taxi->published == -2) echo ' selected="selected"'; ?>>Trashed</option>
									<?php else: ?>								
										<option value="1" selected="selected">Published</option>
										<option value="0">Disabled</option>
										<option value="-2">Trashed</option>
									<?php endif;?>
								</select>	

							</li>
							<li>
								<label >Airports</label>
								<select name="airport_id" class="required" id="airport">
									<option value="">Select Airport</option>
									<?php foreach ($this->airport as $key => $value): ?>
										<?php if ($value->id == $airportId): ?>
											<option value="<?php echo $value->id?>" selected><?php echo $value->code.'-'.$value->name; ?></option>
										<?php else: ?>
											<option value="<?php echo $value->id?>"><?php echo $value->code.'-'.$value->name; ?></option>
										<?php endif ?>
									<?php endforeach ?>
								</select>
							</li>
						</ul>
					</div>
					<!-- end add airline -->
					<?php 
					else :
						echo '<div class="part-push-left">';
					echo '<h1>Select Airline</h1>';
					$db = JFactory::getDbo();
					$query  = 'SELECT a.*,b.name AS airline_name FROM #__sfs_airline_details AS a';
					$query .= ' LEFT JOIN #__sfs_iatacodes AS b ON b.id=a.iatacode_id AND b.type=1';
					$db->setQuery($query);
					$airlines = $db->loadObjectList();
					foreach ($airlines as $airline) :
						?>
					<a href="index.php?option=com_sfs&view=taxi&layout=company&tmpl=component&airline_id=<?php echo $airline->id?>">
						<?php echo $airline->company_name ? $airline->company_name : $airline->airline_name;?>
					</a>
					<br />
					<?php
					endforeach; ?>
				</div>
			<?php endif;?>		
		</div>


	</div>
		<input type="hidden" name="task" value="taxi.saveCompany" /> 
		<input type="hidden" name="option" value="com_sfs" />		
		<input type="hidden" name="taxi_id" value="<?php echo $this->taxi->taxi_id; ?>" />
		<input type="hidden" name="airline_id" value="<?php echo $airlineId; ?>" />	

			<input type="hidden" name="airport_id" class="airport_id" value="<?php echo $airportId; ?>" />	

	
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
</form>	

</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.inputNum').keypress(function (event) {
			return isNumber(event, this)
		});

	var id_airport = $('.airport option:selected').val();
	$('#airport').on('change', function() {
		$('.airport_id').val($(this).val());
	});

	function isNumber(evt, element) {
		var charCode = (evt.which) ? evt.which : event.keyCode;

		if ((charCode != 46 || $(element).val().indexOf('.') != -1) && (charCode < 48 || charCode > 57))
			return false;

		return true;
	}    

		
	});

</script>