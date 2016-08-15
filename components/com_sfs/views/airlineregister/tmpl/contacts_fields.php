<?php    
defined('_JEXEC') or die;      		
$i 	  =  $this->contact_index;  
$item = $this->contact_item; 	                	
?>

<div class="block border orange">
<fieldset>
		<legend>
			<span class="text_legend">
			<?php 
				if($i<2){echo ( $i == 0  ) ? JText::_('COM_SFS_MAIN_CONTACT') : JText::_('COM_SFS_AIRLINE_YOUR_TEAM_MEMBERS') ;} 
				else {echo JText::_('COM_SFS_AIRLINE_ADDITIONAL_CONTACTS');} 

				$intro = SfsHelper::getTooltipTextEsc('main_contact_form', $text, 'airline');

				if($i > 0) {
					$intro = SfsHelper::getTooltipTextEsc('team_member_form', $text, 'airline');
				}
			?></span>
		</legend>
		<div class="col w80 pull-left p20" data-step="<?php echo ($i + 1)?>" data-intro="<?php echo $intro; ?>">
			<div class="form-group">
				<label for="job_title_<?php echo $i;?>"><?php echo JText::_('COM_SFS_JOB_TITLE')?>:</label>
				<div class="col w60">
					<input id="job_title_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][job_title]" class="required" value="<?php echo !empty($item) ? $item['job_title']:''?>" />
				</div>
			</div>

			<div class="form-group">
				<label for="name_<?php echo $i;?>"><?php echo JText::_('COM_SFS_FIRST_NAME')?>:</label>
				<div class="col w60">
					<input id="name_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][name]" class="required" value="<?php echo !empty($item) ? $item['name']:''?>" />
				</div>
			</div>             

			<div class="form-group">
				<label for="surname_<?php echo $i;?>"><?php echo JText::_('COM_SFS_SURNAME')?>:</label>
				<div class="col w60">
					<input id="surname_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][surname]" class="required" value="<?php echo !empty($item) ? $item['surname']:''?>" />
				</div>
			</div>             

			<div class="form-group">
				<label><?php echo JText::_('COM_SFS_GENDER')?></label>
				<div class="col w60">
					<select name="contact[<?php echo $i;?>][gender]" >
						<option value="<?php echo JText::_('COM_SFS_GENDER_MR')?>"<?php echo !empty($item) && $item['gender']==JText::_('COM_SFS_GENDER_MR') ? ' selected="selected"':'';?>><?php echo JText::_('COM_SFS_GENDER_MR')?></option>
						<option value="<?php echo JText::_('COM_SFS_GENDER_MS')?>"<?php echo !empty($item) && $item['gender']==JText::_('COM_SFS_GENDER_MS') ? ' selected="selected"':'';?>><?php echo JText::_('COM_SFS_GENDER_MS')?></option>                                    
						<option value="<?php echo JText::_('COM_SFS_GENDER_MRS')?>"<?php echo !empty($item) && $item['gender']==JText::_('COM_SFS_GENDER_MRS') ? ' selected="selected"':'';?>><?php echo JText::_('COM_SFS_GENDER_MRS')?></option>                                                       
					</select>
				</div>
			</div>             

			<div class="form-group">
				<label for="email_<?php echo $i;?>"><?php echo JText::_('COM_SFS_EMAIL')?></label>
				<div class="col w60">
					<input id="email_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][email]" class="validate-email required" value="<?php echo !empty($item) ? $item['email']:''?>" />
				</div>
			</div>             

			<div class="form-group">
				<label for="phone_code_<?php echo $i;?>"><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE')?>:</label>
				<div class="col w60">
					<div class="row r10 clearfix">
						<div class="col w20">
							<input id="phone_code_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][phone_code]" class="validate-numeric required smaller-size" value="<?php echo !empty($item) ? $item['phone_code']:''?>" />							
						</div>
						<div class="col w80">
							<input type="text" name="contact[<?php echo $i;?>][phone_number]" class="validate-numeric required short-size" value="<?php echo !empty($item) ? $item['phone_number']:''?>" />											
						</div>						
					</div>
					<small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
				</div>
			</div>             

			<div class="form-group">
				<label for="fax_code_<?php echo $i;?>"><?php echo JText::_('COM_SFS_DIRECT_FAX')?>:</label>
				<div class="col w60">
					<div class="row r10 clearfix">
						<div class="col w20">
							<input id="fax_code_<?php echo $i;?>" type="text" name="contact[<?php echo $i;?>][fax_code]" class="validate-numeric required smaller-size" value="<?php echo !empty($item) ? $item['fax_code']:''?>" />							
						</div>
						<div class="col w80">
							<input type="text" name="contact[<?php echo $i;?>][fax_number]" class="validate-numeric required short-size" value="<?php echo !empty($item) ? $item['fax_number']:''?>" />				
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
							<input type="text" name="contact[<?php echo $i;?>][mobile_code]" class="validate-numeric smaller-size" value="<?php echo !empty($item) ? $item['mobile_code']:''?>" />
						</div>
						<div class="col w80">
							<input type="text" name="contact[<?php echo $i;?>][mobile_number]" class="validate-numeric short-size" value="<?php echo !empty($item) ? $item['mobile_number']:''?>"  />
						</div>
					</div>
					<small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
				</div>
			</div>
		</div>		   
		 <input type="hidden" name="contact[<?php echo $i;?>][main_contact]" value="<?php echo (int)$this->main_contact ; ?>" />                                                                                                                                                                                 	
</fieldset>
</div>
