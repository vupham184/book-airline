<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
JHtml::_('behavior.formvalidation');
?>

<style type="text/css">
    .titleHeader{
        float: left;
        width: 100%;
        margin: 10px 0 30px 0;
    }
    h3{
        float: left;
        width: 70%;
    }
    p.descript-txt{
        float: left;
        width: 30%;       
    }

    p.descript-txt a {
        color: #ffffff;
        font-weight: bold;
        background: #ff8806;
        padding: 6px 15px;
        float: right;  
        position: absolute;
        top: 0;
        right: 10px;
        font-size: 14px;     
    }
</style>
<!-- <div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3>Add new staff member</h3>
        <p class="descript-txt"><a onclick="window.parent.SqueezeBox.close();" style="cursor:pointer;">Close</a></p>
    </div>
</div> -->
<div class="titleHeader">
    <h3>Add new staff member</h3>
    <p class="descript-txt"><a onclick="window.parent.SqueezeBox.close();" style="cursor:pointer;">Close</a></p>
</div>
<div id="sfs-wrapper">
<form id="hotelForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelregister'); ?>" method="post" class="form-validate">
	<div class="fieldset-fields">    	
        <div class="register-field clear floatbox">
            <label><?php echo JText::_('COM_SFS_JOB_TITLE');?>:</label>
            <input type="text" name="job_title" value="" class="required" />
        </div>
        <div class="register-field clear floatbox">
            <label><?php echo JText::_('COM_SFS_NAME');?>:</label>
            <input type="text" value="" name="name" class="required" />
        </div>
        <div class="register-field clear floatbox">
            <label><?php echo JText::_('COM_SFS_SURNAME');?>:</label>
            <input type="text" value="" name="surname"  />
        </div>
        <div class="register-field clear floatbox">
            <label>Title:</label>
            <select size="1" class="inputbox" name="gender">
              <option value="Mr">Mr</option>
              <option value="Mrs">Mrs</option>
              <option value="Ms">Ms</option>
            </select>
        </div>
        <div class="register-field clear floatbox">
            <label>Email:</label>
            <input type="text" value="" name="email" class="validate-email required"  />
        </div>
        <div class="register-field clear floatbox">
            <label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE'); ?>:</label>
            <div class="float-left">
                <input type="text" class="validate-numeric required smaller-size" value="" name="tel_int">&nbsp;<input type="text" class="validate-numeric required short-size" value="" name="tel_num">
                <br />
                <div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>
            </div>
        </div>
        <div class="register-field clear floatbox">
            <label><?php echo JText::_('COM_SFS_DIRECT_FAX'); ?>:</label>
            <div class="float-left">
                <input type="text" class="validate-numeric smaller-size" value="" name="fax_int">&nbsp;<input type="text" class="validate-numeric short-size" value="" name="fax_num">
                <br />
                <div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>
            </div>
        </div>
        <div class="register-field clear floatbox">
            <label><?php echo JText::_('COM_SFS_MOBILE'); ?>:</label>
            <div class="float-left">
                <input type="text" class="validate-numeric smaller-size" value="" name="mobile_int">&nbsp;<input type="text" class="validate-numeric short-size" value="" name="mobile_num">
                <br />
                <div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>
            </div>
        </div>
                    
    </div>
    
    <div style="float:right">
		<div class="s-button"><input type="submit" class="validate s-button" value="Save and close"></div>
        <input type="hidden" name="task" value="hotelregister.addcontact" />
        <input type="hidden" name="ctype" value="<?php echo $this->hotel->step_completed;?>" />
        <input type="hidden" name="hotel_id" value="<?php echo $this->hotel->id;?>" />
        <?php echo JHtml::_('form.token');?>
    </div>
</form>
</div>