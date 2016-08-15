<?php
defined('_JEXEC') or die;
$session = JFactory::getSession();
$contacts = $session->get('airline_additional_contact');
if( ! isset($contacts) ) return;

foreach ($contacts as $i => $contact) :
?>
<div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;" xmlns="http://www.w3.org/1999/html">
		   
	<fieldset>                    
		<legend><?php echo JText::_('COM_SFS_AIRLINE_ADDITIONAL_CONTACTS');?></legend>
		
		<div class="col w80 pull-left p20">
		
			<div class="form-group">
				<label for="job_title_<?php echo $i;?>">
					<?php echo JText::_('COM_SFS_JOB_TITLE')?>:
				</label>
                <div class="col w60">
				    <input id="job_title_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][job_title]" class="required" value="<?php echo $contact['job_title']?>" />
                </div>
			</div>
			
			<div class="form-group">
				<label for="name_<?php echo $i;?>">
					<?php echo JText::_('COM_SFS_FIRST_NAME')?>:
				</label>
                <div class="col w60">
				    <input id="name_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][name]" class="required" value="<?php echo $contact['name']?>" />
                </div>
			</div>      
			       
			<div class="form-group">
				<label for="surname_<?php echo $i;?>">
					<?php echo JText::_('COM_SFS_SURNAME')?>:
				</label>
                <div class="col w60">
				    <input id="surname_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][surname]" class="required" value="<?php echo $contact['surname']?>" />
                </div>
			</div>
			             
			<div class="form-group">
				<label>
					<?php echo JText::_('COM_SFS_GENDER')?>
				</label>
                <div class="col w60">
                    <select name="contact[<?php echo $i;?>][gender]" >
                        <option value="<?php echo JText::_('COM_SFS_GENDER_MR')?>"<?php echo $contact['gender']==JText::_('COM_SFS_GENDER_MR') ? ' selected="selected"':'';?>><?php echo JText::_('COM_SFS_GENDER_MR')?></option>
                        <option value="<?php echo JText::_('COM_SFS_GENDER_MS')?>"<?php echo $contact['gender']==JText::_('COM_SFS_GENDER_MS') ? ' selected="selected"':'';?>><?php echo JText::_('COM_SFS_GENDER_MS')?></option>
                        <option value="<?php echo JText::_('COM_SFS_GENDER_MRS')?>"<?php echo $contact['gender']==JText::_('COM_SFS_GENDER_MRS') ? ' selected="selected"':'';?>><?php echo JText::_('COM_SFS_GENDER_MRS')?></option>
                    </select>
                </div>
			</div>   
			          
			<div class="form-group">
				<label for="email_<?php echo $i;?>">
					<?php echo JText::_('COM_SFS_EMAIL')?>
				</label>
                <div class="col w60">
				    <input id="email_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][email]" value="<?php echo $contact['email']?>" class="validate-email required" />
                </div>
			</div> 
			            
			<div class="form-group">
				<label for="phone_code_<?php echo $i;?>">
					<?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE')?>:
				</label>
                <div class="col w60">
                    <div class="row r10 clearfix">
                        <div class="col w20">
                            <input id="phone_code_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][phone_code]" class="validate-numeric required smaller-size" value="<?php echo $contact['phone_code']?>" />
                        </div>
                        <div class="col w80">
                            <input type="text" name="contact[<?php echo $i;?>][phone_number]" class="validate-numeric required short-size" value="<?php echo $contact['phone_number']?>" />
                        </div>
                    </div>
                    <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
                </div>
			</div>             
			
			<div class="form-group">
				<label for="fax_code_<?php echo $i;?>">
					<?php echo JText::_('COM_SFS_DIRECT_FAX')?>:
				</label>
                <div class="col w60">
                    <div class="row r10 clearfix">
                        <div class="col w20">
					        <input id="fax_code_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][fax_code]" class="validate-numeric required smaller-size" value="<?php echo $contact['fax_code']?>" />
                        </div>
                        <div class="col w80">
                            <input type="text" name="contact[<?php echo $i;?>][fax_number]" class="validate-numeric required short-size" value="<?php echo $contact['fax_number']?>" />
                        </div>
                    </div>
                    <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
				</div>
			</div>      
			<div class="form-group">
				<label><?php echo JText::_('COM_SFS_MOBILE')?>:</label>
                <div class="col w60">
                    <div class="row r10 clearfix">
                        <div class="col w20">
					        <input type="text" name="contact[<?php echo $i;?>][mobile_code]" class="validate-numeric smaller-size" value="<?php echo $contact['mobile_code']?>" />
                        </div>
                        <div class="col w80">
                            <input type="text" name="contact[<?php echo $i;?>][mobile_number]" class="validate-numeric short-size" value="<?php echo $contact['mobile_number']?>"  />
                        </div>
                    </div>
                    <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
                </div>
			</div>					   
			<input type="hidden" name="contact[<?php echo $i;?>][main_contact]" value="0" />                                                                                                                                                                                 
		</div>                        
	</fieldset>
</div>
<?php
endforeach;  
?>
