<?php
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
JHtml::_('behavior.formvalidation');

$session 	= JFactory::getSession();	
$eContact = $session->get('errorContact');
?>
<style>
    .sfs-form.form-vertical .form-group label{
        width: 120px;
    }
</style>
<?php if (isset($eContact)) : ?>
<div style="background:#E6C8A6;border-bottom: 3px solid #FFBB00;border-top: 3px solid #FFBB00;margin-bottom: 10px;padding: 10px;color: #CC0000;">
E-Mail: <?php echo $eContact['email'];?> in use
</div>
<?php endif;?>

<div class="main" style="padding: 5px 5px">
<div id="airline-registration" style="margin-top:10px;">
    <form name="airlineRegisterForm" action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" class="form-validate sfs-form form-vertical register-form">
        <div class="block-group">
            <div class="block border orange">
                <fieldset>
                    <legend style="margin-bottom: 0">Add a staffmember<div style="width:100px; float:right; text-align:right"><a onclick="window.parent.SqueezeBox.close();" style="cursor:pointer;text-decoration:underline; font-size: 20px">Close</a></div></legend>

                    <div style="padding-bottom:20px; clear:both; margin-top: 10px">Only add staffmembers that are allowed to change the roomrates and availability<br /></div>
                    <div class="col w80 pull-left">
                        <div class="fieldset-fields">
                            <div class="form-group">
                                <label>Job title</label>
                                <div class="col w60">
                                    <input type="text" name="contact[job_title]" class="required" value="<?php echo isset($eContact) ? $eContact['job_title']:'';?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?php echo JText::_('COM_SFS_FIRST_NAME')?></label>
                                <div class="col w60">
                                    <input type="text" name="contact[name]" class="required" value="<?php echo isset($eContact) ? $eContact['name']:'';?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?php echo JText::_('COM_SFS_SURNAME')?></label>
                                <div class="col w60">
                                    <input type="text" name="contact[surname]" value="<?php echo isset($eContact) ? $eContact['surname']:'';?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <div class="col w60">
                                    <select name="contact[gender]" >
                                        <option value="Mr"<?php echo isset($eContact) && $eContact['gender']=='Mr' ? ' selected="selected"':'';?>>Mr</option>
                                        <option value="Ms"<?php echo isset($eContact) && $eContact['gender']=='Ms' ? ' selected="selected"':'';?>>Ms</option>
                                        <option value="Mrs"<?php echo isset($eContact) && $eContact['gender']=='Mrs' ? ' selected="selected"':'';?>>Mrs</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <div class="col w60">
                                    <input type="text" name="contact[email]" class="validate-email required" value="<?php echo isset($eContact) ? $eContact['email']:'';?>"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE')?></label>
                                <div class="col w60">
                                    <div class="row r10 clearfix">
                                        <div class="col w30">
                                            <input type="text" name="contact[phone_code]" class="validate-numeric required smaller-size" value="<?php echo isset($eContact) ? $eContact['phone_code']:'';?>" />
                                        </div>
                                        <div class="col w70">
                                            <input type="text" name="contact[phone_number]" class="validate-numeric required short-size" value="<?php echo isset($eContact) ? $eContact['phone_number']:'';?>" />
                                        </div>
                                    </div>
                                    <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE');?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Direct fax</label>
                                <div class="col w60">
                                    <div class="row r10 clearfix">
                                        <div class="col w30">
                                            <input type="text" name="contact[fax_code]" class="validate-numeric required smaller-size" value="<?php echo isset($eContact) ? $eContact['fax_code']:'';?>" />
                                        </div>
                                        <div class="col w70">
                                            <input type="text" name="contact[fax_number]" class="validate-numeric required short-size" value="<?php echo isset($eContact) ? $eContact['fax_number']:'';?>" />
                                        </div>
                                    </div>
                                    <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE');?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Mobile</label>
                                <div class="col w60">
                                    <div class="row r10 clearfix">
                                        <div class="col w30">
                                            <input type="text" name="contact[mobile_code]" class="validate-numeric smaller-size" value="<?php echo isset($eContact) ? $eContact['mobile_code']:'';?>" />
                                        </div>
                                        <div class="col w70">
                                            <input type="text" name="contact[mobile_number]" class="validate-numeric short-size" value="<?php echo isset($eContact) ? $eContact['mobile_number']:'';?>" />
                                        </div>
                                    </div>
                                    <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE');?></small>
                                </div>
                            </div>

                             <input type="hidden" name="contact[main_contact]" value="0" />
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div>
        	<input type="submit" class="validate button btn orange" value="Save" style="margin-top:0;" />
        </div>
        
       	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
        <input type="hidden" name="task" value="ghregister.addcontact" />
        <?php echo JHtml::_('form.token'); ?>            
        
    </form>

</div>
</div>