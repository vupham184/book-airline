<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
?>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Confirm</h3>
    </div>
</div>
<div id="sfs-wrapper" class="main fs-14">
    <form name="busRegisterForm" id="busRegisterForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=busregister'); ?>" method="post">
    
        <div class="sfs-above-main">
            <h2><?php echo $this->busdetails['name']; ?>: Terms &amp; conditions</h2>
        </div>
        
        <div class="sfs-main-wrapper-none">
            <div class="sfs-orange-wrapper">
            <div class="sfs-white-wrapper">
                <div class="fieldset-fields" style="padding:20px 30px;">

                    <div class="register-field clear floatbox">
                        <label><?php echo JText::_('COM_SFS_FIRST_NAME')?>:</label>
                        <?php echo $this->accountdetails['first_name'];?>
                    </div>
                    <div class="register-field clear floatbox">
                        <label><?php echo JText::_('COM_SFS_SURNAME')?>:</label>
                        <?php echo $this->accountdetails['last_name'];?>
                    </div>                    
                    <div class="floatbox">
                        <input type="checkbox" name="accept_term" value="1" /> <?php echo JText::_('COM_SFS_HOTEL_TERM_CONFIRM_TEXT');?>
                        <br /><br />
	                    <a href="index.php?option=com_sfs&view=article&id=83&tmpl=component" rel="{handler: 'iframe', size: {x: 750, y: 450}}" class="modal"><?php echo JText::_('COM_SFS_HOTEL_TERM_CLICK_HERE');?></a>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <div class="sfs-below-main">
        	<div class="s-button float-right">
            	<input type="submit" class="s-button" value="Accept">
            </div>
        </div>

        <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
        <input type="hidden" name="task" value="busregister.register" />
        <?php echo JHtml::_('form.token'); ?>
    </form>

</div>

