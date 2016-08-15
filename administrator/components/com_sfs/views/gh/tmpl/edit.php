<?php
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'gh.cancel' || document.formvalidator.isValid(document.id('gh-form'))) {			
			Joomla.submitform(task, document.getElementById('gh-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>
<?php if(!$this->item->approved):?>

<a href="index.php?option=com_sfs&view=gh&tmpl=component&layout=approve&id=<?php echo $this->item->id;?>" class="modal" style="display:block;width:170px;background:green;float:right;color:#fff;font-size:12px; text-align:center;padding:5px 0;" rel="{handler: 'iframe', size: {x:220, y: 120}, onClose: function() {}}">Click here to approve</a>

<?php endif;?>

<form action="<?php echo JRoute::_('index.php?option=com_sfs&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="gh-form" class="form-validate">
	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend>Groundhandler Details</legend>
			<ul class="adminformlist">
			
				<li>
					<?php echo $this->form->getLabel('logo'); ?>
					<?php echo $this->form->getInput('logo'); ?>
				</li>
				
				<li><?php echo $this->form->getLabel('company_name'); ?>
				<?php echo $this->form->getInput('company_name'); ?></li>
				
				<li><?php echo $this->form->getLabel('iatacodes'); ?>
				<?php echo $this->form->getInput('iatacodes'); ?></li>
												
				<li>					
					<label>Airport location code:</label>
					<input type="text" size="50" readonly="readonly" class="readonly" value="<?php echo $this->item->airport_code.' ('.$this->item->airport_name.')';?>" name="airport_code">
					<input type="hidden" name="jform[airport_id]" value="<?php echo $this->item->airport_id;?>" />
				</li>

                <li>
                    <?php echo $this->form->getLabel('partner_limit_for_extra_search'); ?>
                    <?php echo $this->form->getInput('partner_limit_for_extra_search'); ?>
                </li>

				<li>
					<?php echo $this->form->getLabel('time_zone'); ?>
					<?php echo $this->form->getInput('time_zone'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('created_date'); ?>
					<?php echo $this->form->getInput('created_date'); ?>
				</li>
				
				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
																															
			</ul>
			<div class="clr"></div>		
			<div style="border-top:solid 1px #CCCCCC;padding-top:10px;">	
				<div style="margin-bottom: 10px;font-size: 1.091em;">
					GH Admins: 
					<?php
					$i = 0; 
					foreach ($this->admins as $admin) : 
					?>
					<a href="index.php?option=com_users&task=user.edit&id=<?php echo $admin->user_id?>"><?php echo $admin->username;?></a>						
					<?php
					if( $i < (count($this->admins) - 1) ) echo ' , '; 
					$i++;
					endforeach;
					?>
				</div>
				<div class="clr"></div>		
				<a rel="{handler: 'iframe', size: {x: 600, y: 440}, onClose: function() {}}" href="<?php echo JRoute::_('index.php?option=com_sfs&view=gh&layout=newadmin&tmpl=component&id='.$this->item->id);?>" class="modal icon-16-user" style="background-position: left 50%;background-repeat: no-repeat;color: #333333; padding-left: 25px;">Add New GH Admin</a>					
			</div>		
		</fieldset>
		
		<fieldset class="adminform">
			<legend>Local offive visiting details</legend>
			<ul class="adminformlist">								
				<li><?php echo $this->form->getLabel('address'); ?>
				<?php echo $this->form->getInput('address'); ?></li>
				
				<li><?php echo $this->form->getLabel('address2'); ?>				
				<?php echo $this->form->getInput('address2'); ?></li>
				
				<li><?php echo $this->form->getLabel('city'); ?>
				<?php echo $this->form->getInput('city'); ?></li>
				
				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>
				
				<li><?php echo $this->form->getLabel('zipcode'); ?>
				<?php echo $this->form->getInput('zipcode'); ?></li>
				
				<li><?php echo $this->form->getLabel('country_id'); ?>
				<?php echo $this->form->getInput('country_id'); ?></li>
				
				<li><?php echo $this->form->getLabel('telephone'); ?>
				<?php echo $this->form->getInput('telephone'); ?></li>
																																					
			</ul>
			<div class="clr"></div>	
		</fieldset>		
		
	
		<fieldset class="adminform">
			<legend>Billing Details</legend>
			<ul class="adminformlist">
			<?php foreach($this->form->getFieldset('billing_details') as $field): ?>
				<?php if ($field->hidden): ?>
					<?php echo $field->input; ?>
				<?php else: ?>
					<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
				<?php endif; ?>
			<?php endforeach; ?>
			</ul>
		</fieldset>
		
		<fieldset class="adminform">
			<legend>Ground Admin</legend>
			<?php $contacts = $this->item->contacts;?>
			<ul class="adminformlist">
				<li>
					<label for="">Job title:</label>
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->job_title;?>" readonly="readonly" class="readonly">
				</li>		
				<li>
					<label for="">Gender:</label>
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->gender;?>" readonly="readonly" class="readonly">
				</li>										
				<li>
					<label for="">Name:</label>
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->name;?>" readonly="readonly" class="readonly">
				</li>
				<li>
					<label for="">Surname:</label>
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->surname;?>" readonly="readonly" class="readonly">
				</li>		
				<li>
					<label for="">Email:</label>
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->email;?>" readonly="readonly" class="readonly">
				</li>	
				<li>
					<label for="">Direct office telephone:</label>					
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->telephone;?>" readonly="readonly" class="readonly">
				</li>
				<li>
					<label for="">Direct fax:</label>
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->fax;?>" readonly="readonly" class="readonly">
				</li>
				<li>
					<label for="">Mobile:</label>
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->mobile;?>" readonly="readonly" class="readonly">
				</li>																																														
			</ul>
			
			<?php 						
			if( count($contacts) ) : 
			?>			
				<div class="clr"></div>
				<hr />
				<a href="index.php?option=com_sfs&view=gh&layout=contacts&id=<?php echo $this->item->id;?>&tmpl=component" rel="{handler: 'iframe', size: {x: 875, y: 550}, onClose: function() {}}" class="modal">Click here to see more contacts</a>
			<?php endif;?>
	
		</fieldset>			
		
	</div>	
	<div class="width-50 fltrt">		
		<fieldset class="adminform">
			<legend>Global Params</legend>
			<ul class="adminformlist">
				<li>
					<label for="">Private Email:</label>
					<input type="text" size="30" value="<?php echo isset($this->item->params['private_email']) ? $this->item->params['private_email'] : ''; ?>" name="ghparams[private_email]">
				</li>
				<li>
					<label>Enable payment by passenger:</label>		
					<fieldset class="radio">			
						<input type="radio" <?php echo (int)$this->item->params['enable_passenger_payment']==0 ? 'checked="checked"':'';?> value="0" name="ghparams[enable_passenger_payment]">
						<label>No</label>
						<input type="radio" <?php echo (int)$this->item->params['enable_passenger_payment']==1 ? 'checked="checked"':'';?> value="1" name="ghparams[enable_passenger_payment]">
						<label>Yes</label>
					</fieldset>
				</li>
                <li>
                    <label>Enable add hotels:</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->params['enable_add_hotel']==0 ? 'checked="checked"':'';?> value="0" name="ghparams[enable_add_hotel]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->params['enable_add_hotel']==1 ? 'checked="checked"':'';?> value="1" name="ghparams[enable_add_hotel]">
                        <label>Yes</label>
                    </fieldset>
                </li>
                <li>
                    <label for="">Sender Title:</label>
                    <input type="text" size="30" value="<?php echo isset($this->item->params['sender_title']) ? $this->item->params['sender_title'] : ''; ?>" name="ghparams[sender_title]">
                </li>
                <li>
                    <label>Enable send SMS message:</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->params['send_sms_message']==0 ? 'checked="checked"':'';?> value="0" name="ghparams[send_sms_message]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->params['send_sms_message']==1 ? 'checked="checked"':'';?> value="1" name="ghparams[send_sms_message]">
                        <label>Yes</label>
                    </fieldset>
                </li>
				<li>
					<label for="">Show comment on flight form:</label>		
					<fieldset class="radio">			
						<input type="radio" <?php echo (int)$this->item->params['show_addpassengercomment']==0 ? 'checked="checked"':'';?> value="0" name="ghparams[show_addpassengercomment]">
						<label>Hide</label>
						<input type="radio" <?php echo (int)$this->item->params['show_addpassengercomment']==1 ? 'checked="checked"':'';?> value="1" name="ghparams[show_addpassengercomment]">
						<label>Show</label>
					</fieldset>
				</li>
				<li>
					<label for="">Show comment on match page:</label>		
					<fieldset class="radio">			
						<input type="radio" <?php echo (int)$this->item->params['show_vouchercomment']==0 ? 'checked="checked"':'';?> value="0" name="ghparams[show_vouchercomment]">
						<label>Hide</label>
						<input type="radio" <?php echo (int)$this->item->params['show_vouchercomment']==1 ? 'checked="checked"':'';?> value="1" name="ghparams[show_vouchercomment]">
						<label>Show</label>
					</fieldset>
				</li>	
				<li>
					<label>Voucher format:</label>		
					<fieldset class="radio">			
						<input type="radio" <?php echo (int)$this->item->params['voucher_format']==0 ? 'checked="checked"':'';?> value="0" name="ghparams[voucher_format]">
						<label>Image</label>
						<input type="radio" <?php echo (int)$this->item->params['voucher_format']==1 ? 'checked="checked"':'';?> value="1" name="ghparams[voucher_format]">
						<label>HTML</label>
					</fieldset>
				</li>
				<li>
					<label>Enable Taxi Voucher:</label>		
					<fieldset class="radio">			
						<input type="radio" <?php echo (int)$this->item->params['enable_taxi_voucher']==0 ? 'checked="checked"':'';?> value="0" name="ghparams[enable_taxi_voucher]">
						<label>No</label>
						<input type="radio" <?php echo (int)$this->item->params['enable_taxi_voucher']==1 ? 'checked="checked"':'';?> value="1" name="ghparams[enable_taxi_voucher]">
						<label>Yes</label>
					</fieldset>
				</li>	
				<li>
					<label>VAT comment line on Voucher:</label>		
					<textarea name="ghparams[voucher_vat_comment_line]"><?php echo isset($this->item->params['voucher_vat_comment_line']) ? $this->item->params['voucher_vat_comment_line'] :'';?></textarea>
				</li>
				<li>
					<label for="">Return flight details on match page and voucher:</label>		
					<fieldset class="radio">			
						<input type="radio" <?php echo (int)$this->item->params['enable_return_flight']==0 ? 'checked="checked"':'';?> value="0" name="ghparams[enable_return_flight]">
						<label>No</label>
						<input type="radio" <?php echo (int)$this->item->params['enable_return_flight']==1 ? 'checked="checked"':'';?> value="1" name="ghparams[enable_return_flight]">
						<label>Yes</label>
					</fieldset>
				</li>
				<li>
					<label for="">Enable group (bus) transport for Airline:</label>		
					<fieldset class="radio">			
						<input type="radio" <?php echo (int)$this->item->params['enable_group_transport']==0 ? 'checked="checked"':'';?> value="0" name="ghparams[enable_group_transport]">
						<label>No</label>
						<input type="radio" <?php echo (int)$this->item->params['enable_group_transport']==1 ? 'checked="checked"':'';?> value="1" name="ghparams[enable_group_transport]">
						<label>Yes</label>
					</fieldset>
				</li>		
				
				<li>
					<label for="">Update block status after X days: </label>		
					<input type="text" name="ghparams[number_day_update_status]" value="<?php echo isset($this->item->params['number_day_update_status']) ? $this->item->params['number_day_update_status'] :'';?>">
				</li>	
											
			</ul>		
		</fieldset>	
		<fieldset class="adminform">
			<legend>Latest Reservations</legend>
			<?php if(count($this->reservations)):?>
			<table class="adminlist">
				<thead>
					<tr>
						<th><strong>Blockcode</strong></th>
						<th><strong>Airline</strong></th>
						<th><strong>Hotel</strong></th>
						<th><strong>Initial</strong></th>
						<th><strong>Claimed</strong></th>
						<th><strong>SD Rate</strong></th>
						<th><strong>T Rate</strong></th>
						<th><strong>Revenue</strong></th>
						<th width="5"><strong></strong></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->reservations as $value) : ?>
					<tr>
						<td>
							<a href="index.php?option=com_sfs&view=reservation&id=<?php echo $value->id;?>">
								<?php echo $value->blockcode;?>
							</a>
						</td>
						<td><?php echo $value->airline_name;?></td>
						<td>
							<a href="index.php?option=com_sfs&view=hotel&layout=edit&id=<?php echo $value->hotel_id;?>" target="_blank">
								<?php echo $value->hotel_name;?>
							</a>
						</td>
						<td><?php echo $value->sd_room+$value->t_room;?></td>
						<td><?php echo $value->claimed_rooms?></td>
						<td><?php echo $value->currency.$value->sd_rate?></td>	
						<td><?php echo $value->currency.$value->t_rate?></td>
						<td>
							<?php 
							if( $value->status == 'A' || $value->status =='R' ) {
								echo $value->currency.$value->revenue_booked;
							}
							?>
						</td>						
						<td><?php echo $value->status?></td>							
					</tr>		
					<?php endforeach;?>
				</tbody>
			</table>
			<p style="text-align:right;">
				<br />
				<a href="index.php?option=com_sfs&view=reservations&rtype=gh&gid=<?php echo $this->item->id;?>">More Reservations</a>
			</p>
			<?php else :?>
				<h3>No Reservations</h3>
			<?php endif;?>			
		</fieldset>	
				
	</div>	

	<div class="clr"></div>
	
	<div>
		<input type="hidden" name="task" value="" />		
		<?php echo JHtml::_('form.token'); ?>
	</div>
	
</form>

