<?php
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
JHtml::_('behavior.formvalidation');

$session 	= JFactory::getSession();	
$eContact = $session->get('errorContact');
?>
<?php if (isset($eContact)) : ?>
<div style="background:#E6C8A6;border-bottom: 3px solid #FFBB00;border-top: 3px solid #FFBB00;margin-bottom: 10px;padding: 10px;color: #CC0000;">
E-Mail: <?php echo $eContact['email'];?> in use
</div>
<?php endif;?>

<div id="sfs-wrapper">
<div id="airline-registration" style="margin-top:10px;">

	<div style="width:400px; float:left;"><h3 style="margin-top:0;">Add a staffmember</h3></div><div style="width:100px; float:right; text-align:right"><a onclick="window.parent.SqueezeBox.close();" style="cursor:pointer;text-decoration:underline">Close</a></div>
    <div style="padding-bottom:20px; clear:both;">Only add staffmembers that are allowed to change the roomrates and availability<br /></div>
	
    <form name="airlineRegisterForm" action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" class="form-validate">
           
            <fieldset>                    
                
                <div class="fieldset-fields">
                
                    <div class="register-field clear floatbox">
                        <label>Job title:</label>
                        <input type="text" name="contact[job_title]" class="required" value="<?php echo isset($eContact) ? $eContact['job_title']:'';?>" />
                    </div>
                    <div class="register-field clear floatbox">
                        <label><?php echo JText::_('COM_SFS_FIRST_NAME')?>:</label>
                        <input type="text" name="contact[name]" class="required" value="<?php echo isset($eContact) ? $eContact['name']:'';?>" />
                    </div>             
                    <div class="register-field clear floatbox">
                        <label><?php echo JText::_('COM_SFS_SURNAME')?>:</label>
                        <input type="text" name="contact[surname]" class="required" value="<?php echo isset($eContact) ? $eContact['surname']:'';?>" />
                    </div>             
                    <div class="register-field clear floatbox">
                        <label>Gender</label>
                        <select name="contact[gender]" >
                            <option value="Mr"<?php echo isset($eContact) && $eContact['gender']=='Mr' ? ' selected="selected"':'';?>>Mr</option>
                            <option value="Ms"<?php echo isset($eContact) && $eContact['gender']=='Ms' ? ' selected="selected"':'';?>>Ms</option>                                    
                            <option value="Mrs"<?php echo isset($eContact) && $eContact['gender']=='Mrs' ? ' selected="selected"':'';?>>Mrs</option>                                                       
                        </select>
                    </div>             
                    <div class="register-field clear floatbox">
                        <label>Email</label>
                        <input type="text" name="contact[email]" class="validate-email required" value="<?php echo isset($eContact) ? $eContact['email']:'';?>"  />
                    </div>             
                    <div class="register-field clear floatbox">
                        <label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE')?>:</label>
                        <div class="float-left">
                            <input type="text" name="contact[phone_code]" class="validate-numeric required smaller-size" value="<?php echo isset($eContact) ? $eContact['phone_code']:'';?>" />&nbsp;<input type="text" name="contact[phone_number]" class="validate-numeric required short-size" value="<?php echo isset($eContact) ? $eContact['phone_number']:'';?>" />
                            <br />
                            <div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>                                    
                        </div>
                    </div>             
                    <div class="register-field clear floatbox">
                        <label>Direct fax:</label>
                        <div class="float-left">
                            <input type="text" name="contact[fax_code]" class="validate-numeric required smaller-size" value="<?php echo isset($eContact) ? $eContact['fax_code']:'';?>" />&nbsp;<input type="text" name="contact[fax_number]" class="validate-numeric required short-size" value="<?php echo isset($eContact) ? $eContact['fax_number']:'';?>" />
                            <br />
                            <div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>  
                        </div>
                    </div>      
                    <div class="register-field clear floatbox">
                        <label>Mobile:</label>
                        <div class="float-left">
                            <input type="text" name="contact[mobile_code]" class="validate-numeric smaller-size" value="<?php echo isset($eContact) ? $eContact['mobile_code']:'';?>" />&nbsp;<input type="text" name="contact[mobile_number]" class="validate-numeric short-size" value="<?php echo isset($eContact) ? $eContact['mobile_number']:'';?>" />
                            <br />
                            <div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>
                        </div>
                    </div>
                               
                     <input type="hidden" name="contact[main_contact]" value="0" />                                                                                                                                                                                 
                </div>                        
            </fieldset>
       
    
        <div>
        	<input type="submit" class="validate button btn orange" value="Save" style="margin-top:0;" />
        </div>
        
       	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
        <input type="hidden" name="task" value="airlineregister.addcontact" />
        <?php echo JHtml::_('form.token'); ?>            
        
    </form>

</div>
</div>
<?php
$session->clear('errorContact');		 
?>