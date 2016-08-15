<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo JText::_('COM_SFS_PERSONAL_CONTACT_TITLE')?></h3>
	</div>
</div>
<div id="sfs-wrapper" class="main">
<div id="sfs-contact-form">

	<form id="contactForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=contact'); ?>" method="post" class="form-validate">
	
	<div class="">
	<div class="sfs-orange-wrapper">
	
		<div class="sfs-white-wrapper floatbox">
			Hi <?php echo $this->user->name;?>,<br />
			<?php echo JText::_('COM_SFS_PERSONAL_CONTACT_NOTE')?>
		</div>
	
		<div class="sfs-white-wrapper floatbox" style="margin-top: 25px;">
	
			<div class="fieldset-fields">
				<fieldset>
					<div class="fieldset-fields">
	
						<div class="register-field clear floatbox">
							<label><?php echo JText::_('COM_SFS_JOB_TITLE')?>:</label>
							<input type="text" name="contact[job_title]" class="required" value="<?php echo $this->contact->job_title;?>" />
						</div>
						<div class="register-field clear floatbox">
							<label><?php echo JText::_('COM_SFS_FIRST_NAME')?>:</label>
							<input type="text" name="contact[name]" class="required" value="<?php echo $this->contact->name;?>" />
						</div>
						<div class="register-field clear floatbox">
							<label><?php echo JText::_('COM_SFS_SURNAME')?>:</label>
							<input type="text" name="contact[surname]" value="<?php echo $this->contact->surname;?>" />
						</div>
						<div class="register-field clear floatbox">
							<label><?php echo JText::_('COM_SFS_GENDER')?></label>
							<select name="contact[gender]" style="width:50px;">
								<option value="Mr">Mr</option>
								<option value="Ms">Ms</option>
								<option value="Mrs">Mrs</option>
							</select>
						</div>
						
						<div class="register-field clear floatbox">
							<label><?php echo JText::_('COM_SFS_EMAIL')?></label>
							<input type="text" name="contact[email]" class="validate-email required" value="<?php echo $this->contact->email;?>" />
						</div>
						
						<div class="register-field clear floatbox">
							<label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE')?>:</label>
							<div class="float-left">
								<input type="text" name="contact[phone_code]"
									class="validate-numeric required smaller-size" value="<?php echo SfsHelper::formatPhone($this->contact->telephone,1);?>" />&nbsp;<input
									type="text" name="contact[phone_number]"
									class="validate-numeric required" value="<?php echo SfsHelper::formatPhone($this->contact->telephone,2);?>" /> <br />
									<div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>
							</div>
						</div>
						<div class="register-field clear floatbox">
							<label><?php echo JText::_('COM_SFS_DIRECT_FAX')?>:</label>
							<div class="float-left">
								<input type="text" name="contact[fax_code]"
									class="validate-numeric required smaller-size" value="<?php echo SfsHelper::formatPhone($this->contact->fax,1);?>" />&nbsp;<input
									type="text" name="contact[fax_number]"
									class="validate-numeric required" value="<?php echo SfsHelper::formatPhone($this->contact->fax,2);?>" />
									<br />
									<div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>
							</div>
						</div>
						<div class="register-field clear floatbox">
							<label><?php echo JText::_('COM_SFS_MOBILE')?>:</label>
							<div class="float-left">
								<input type="text" name="contact[mobile_code]"
									class="validate-numeric smaller-size" value="<?php echo SfsHelper::formatPhone($this->contact->mobile,1);?>" />&nbsp;<input
									type="text" name="contact[mobile_number]"
									class="validate-numeric" value="<?php echo SfsHelper::formatPhone($this->contact->mobile,2);?>" />
									<br />
									<div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>
							</div>
						</div>
					</div>
				</fieldset>
	
			</div>
	
		</div>
	
	
		</div>
	</div>
	
	
	<div class="sfs-below-main">
		<div class="s-button float-right">
			<input type="submit" class="validate s-button" value="<?php echo JText::_('COM_SFS_SAVE')?>" />
		</div>
	</div>
	
	    <input type="hidden" name="option" value="com_sfs" />
	    <input type="hidden" name="task" value="contact.save" />
	    <input type="hidden" name="id" value="<?php echo $this->contact->id;?>" />
	    <?php echo JHtml::_('form.token'); ?>
	
	</form>

</div>

</div>