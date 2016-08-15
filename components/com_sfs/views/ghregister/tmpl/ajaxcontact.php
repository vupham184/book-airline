<?php
defined('_JEXEC') or die;

$session = JFactory::getSession();
$contacts = $session->get('gh_additional_contact');
if( ! isset($contacts) ) return;

foreach ($contacts as $i => $contact) :
?>
<div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">         
		   
	<fieldset>                    
		<div class="fieldset-title float-left">
			<span><?php echo JText::_('COM_SFS_AIRLINE_ADDITIONAL_CONTACTS');?></span> 
		</div>  
		
		<div class="fieldset-fields float-left">
		
			<div class="register-field clear floatbox">
				<label for="job_title_<?php echo $i;?>">
					<?php echo JText::_('COM_SFS_JOB_TITLE')?>:
				</label>
				<input id="job_title_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][job_title]" class="required" value="<?php echo $contact['job_title']?>" />
			</div>
			
			<div class="register-field clear floatbox">
				<label for="name_<?php echo $i;?>">
					<?php echo JText::_('COM_SFS_FIRST_NAME')?>:
				</label>
				<input id="name_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][name]" class="required" value="<?php echo $contact['name']?>" />
			</div>      
			       
			<div class="register-field clear floatbox">
				<label for="surname_<?php echo $i;?>">
					<?php echo JText::_('COM_SFS_SURNAME')?>:
				</label>
				<input id="surname_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][surname]" class="required" value="<?php echo $contact['surname']?>" />
			</div>
			             
			<div class="register-field clear floatbox">
				<label>
					<?php echo JText::_('COM_SFS_GENDER')?>
				</label>
				<select name="contact[<?php echo $i;?>][gender]" >
					<option value="<?php echo JText::_('COM_SFS_GENDER_MR')?>"<?php echo $contact['gender']==JText::_('COM_SFS_GENDER_MR') ? ' selected="selected"':'';?>><?php echo JText::_('COM_SFS_GENDER_MR')?></option>
					<option value="<?php echo JText::_('COM_SFS_GENDER_MS')?>"<?php echo $contact['gender']==JText::_('COM_SFS_GENDER_MS') ? ' selected="selected"':'';?>><?php echo JText::_('COM_SFS_GENDER_MS')?></option>                                    
					<option value="<?php echo JText::_('COM_SFS_GENDER_MRS')?>"<?php echo $contact['gender']==JText::_('COM_SFS_GENDER_MRS') ? ' selected="selected"':'';?>><?php echo JText::_('COM_SFS_GENDER_MRS')?></option>                                                       
				</select>
			</div>   
			          
			<div class="register-field clear floatbox">
				<label for="email_<?php echo $i;?>">
					<?php echo JText::_('COM_SFS_EMAIL')?>
				</label>
				<input id="email_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][email]" value="<?php echo $contact['email']?>" class="validate-email required" />
			</div> 
			            
			<div class="register-field clear floatbox">
				<label for="phone_code_<?php echo $i;?>">
					<?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE')?>:
				</label>
				<div class="float-left">
					<input id="phone_code_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][phone_code]" class="validate-numeric required smaller-size" value="<?php echo $contact['phone_code']?>" />&nbsp;<input type="text" name="contact[<?php echo $i;?>][phone_number]" class="validate-numeric required short-size" value="<?php echo $contact['phone_number']?>" />
					<br />
					<div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>                                    
				</div>
			</div>             
			
			<div class="register-field clear floatbox">
				<label for="fax_code_<?php echo $i;?>">
					<?php echo JText::_('COM_SFS_DIRECT_FAX')?>:
				</label>
				<div class="float-left">
					<input id="fax_code_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][fax_code]" class="validate-numeric required smaller-size" value="<?php echo $contact['fax_code']?>" />&nbsp;<input type="text" name="contact[<?php echo $i;?>][fax_number]" class="validate-numeric required short-size" value="<?php echo $contact['fax_number']?>" />
					<br />  
					<div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>
				</div>
			</div>      
			<div class="register-field clear floatbox">
				<label><?php echo JText::_('COM_SFS_MOBILE')?>:</label>
				<div class="float-left">
					<input type="text" name="contact[<?php echo $i;?>][mobile_code]" class="validate-numeric smaller-size" value="<?php echo $contact['mobile_code']?>" />&nbsp;<input type="text" name="contact[<?php echo $i;?>][mobile_number]" class="validate-numeric short-size" value="<?php echo $contact['mobile_number']?>"  />
					<br />
					<div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>
				</div>
			</div>					   
			<input type="hidden" name="contact[<?php echo $i;?>][main_contact]" value="0" />                                                                                                                                                                                 
		</div>                        
	</fieldset>
</div>
<?php 
endforeach;
?>
