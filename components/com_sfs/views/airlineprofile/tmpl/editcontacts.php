<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
JHtml::_('behavior.formvalidation');
?>

<div class="heading-block clearfix">
        <div class="heading-block-wrap">
            <h3>Edit Contact</h3>
        </div>
    </div>

<div id="sfs-wrapper" class="main">
<div id="airline-registration">


    <div class="airline-contact-details-top">
    	<h1><?php echo JText::_('COM_SFS_AIRLINE_CONTACT_DETAILS');?></h1>
        <div style="font-size:14px;">
	       	<?php echo JText::_('COM_SFS_AIRLINE_CONTACT_DETAILS_DESC');?>
        </div>
    </div>
        
    <form name="airlineRegisterForm" action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" class="form-validate">
        <div class="sfs-above-main">
            <h2><?php echo JText::_('COM_SFS_AIRLINE_CONTACT_PERSONS');?></h2>
        </div>
	    <div class="sfs-main-wrapper-none">
	        <div class="sfs-orange-wrapper">
	                    
	        
	            <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
	            	<?php echo JText::_('COM_SFS_AIRLINE_CONTACT_PERSONS_DESC');?>
	            </div>
	                        
	            <?php
	            foreach ( $this->contacts as $contact )  : ?>
	            
	            <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
	                       
	                <fieldset>
	                    <div class="fieldset-title float-left">
	                        <span><?php echo ( $contact->is_admin == 1  ) ? JText::_('COM_SFS_MAIN_CONTACT') : JText::_('COM_SFS_AIRLINE_YOUR_TEAM_MEMBERS') ; ?></span>
	                    </div>
	                    
	                    <div class="fieldset-fields float-left">
	                    
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_JOB_TITLE');?>:</label>
	                            <input type="text" name="contact[<?php echo $contact->id;?>][job_title]" class="required" value="<?php echo $contact->job_title;?>" />
	                        </div>
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_FIRST_NAME')?>:</label>
	                            <input type="text" name="contact[<?php echo $contact->id;?>][name]" class="required" value="<?php echo $contact->name;?>" />
	                        </div>
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_SURNAME');?>:</label>
	                            <input type="text" name="contact[<?php echo $contact->id;?>][surname]" value="<?php echo $contact->surname;?>" />
	                        </div>
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_GENDER')?></label>
	                            <select name="contact[<?php echo $contact->id;?>][gender]" >
		                            <option value="<?php echo JText::_('COM_SFS_GENDER_MR')?>"<?php echo $contact->gender==JText::_('COM_SFS_GENDER_MR') ? ' selected="selected"':'';?>><?php echo JText::_('COM_SFS_GENDER_MR')?></option>
		                            <option value="<?php echo JText::_('COM_SFS_GENDER_MS')?>"<?php echo $contact->gender==JText::_('COM_SFS_GENDER_MS') ? ' selected="selected"':'';?>><?php echo JText::_('COM_SFS_GENDER_MS')?></option>
		                            <option value="<?php echo JText::_('COM_SFS_GENDER_MRS')?>"<?php echo $contact->gender==JText::_('COM_SFS_GENDER_MRS') ? ' selected="selected"':'';?>><?php echo JText::_('COM_SFS_GENDER_MRS')?></option>
	                            </select>
	                        </div>
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_EMAIL')?></label>
	                            <input type="text" name="contact[<?php echo $contact->id;?>][email]" class="validate-email required" value="<?php echo $contact->email;?>" />
	                        </div>
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE')?>:</label>
	                            <div class="float-left">
	                            	<input type="text" name="contact[<?php echo $contact->id;?>][phone_code]" class="validate-numeric required smaller-size" value="<?php echo SfsHelper::formatPhone($contact->telephone, 1);?>" />&nbsp;<input type="text" name="contact[<?php echo $contact->id;?>][phone_number]" class="validate-numeric required short-size" value="<?php echo SfsHelper::formatPhone($contact->telephone, 2); ?>" />
									<br />
	                                <div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>
	                            </div>
	                        </div>
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_DIRECT_FAX')?>:</label>
	                                <div class="float-left">
	                                    <input type="text" name="contact[<?php echo $contact->id;?>][fax_code]" class="validate-numeric required smaller-size" value="<?php echo SfsHelper::formatPhone($contact->fax, 1);?>" />&nbsp;<input type="text" name="contact[<?php echo $contact->id;?>][fax_number]" class="validate-numeric required short-size" value="<?php echo SfsHelper::formatPhone($contact->fax, 2);?>" />
	                                    <br />
	                                    <div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>
	                                </div>
	                        </div>
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_MOBILE')?>:</label>
	                            <div class="float-left">
	                             	<input type="text" name="contact[<?php echo $contact->id;?>][mobile_code]" class="validate-numeric smaller-size" value="<?php echo SfsHelper::formatPhone($contact->mobile, 1);?>" />&nbsp;<input type="text" name="contact[<?php echo $contact->id;?>][mobile_number]" class="validate-numeric short-size" value="<?php echo SfsHelper::formatPhone($contact->mobile, 2);?>"  />
	                                <br />
	                                <div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>
	                            </div>
	                        </div>
	                                                                                                                                                                                                                                                 
	                    </div>
	                </fieldset>
	            </div>
	            <?php endforeach; ?>
	            
	            <div class="sfs-white-wrapper floatbox">
                	<div class="s-button">
					<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=airlineprofile&layout=addcontact&tmpl=component&Itemid='.JRequest::getInt('Itemid'));?>" rel="{handler: 'iframe', size: {x: 550, y: 470}}" class="modal s-button">
			        	<?php echo JText::_('COM_SFS_AIRLINE_ADDITIONAL_CONTACT_BUTTON')?>
			        </a>
                    </div>
		        </div>
	            
	            
	        </div>
	    </div>
    
    
        <div class="sfs-below-main floatbox">
            <div class="s-button float-right">
                <input type="submit" class="validate s-button" value="<?php echo JText::_('Save')?>">
            </div>
        </div>
    
    	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
    	<input type="hidden" name="task" value="airlineprofile.savecontacts" />
        <?php echo JHtml::_('form.token'); ?>
    </form>

</div>
</div>