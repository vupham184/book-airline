<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
?>

<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::sprintf('COM_SFS_STEP', 6); ?>Terms &amp; conditions</h3>
    </div>
</div>

<div id="sfs-wrapper" class="fs-14">
    <div class="main">
        <h1 class="page-title" style="text-align:center"><?php echo $this->hotel->name; ?></h1>

        <?php echo $this->progressBar(5); ?>
        <div class="clear"></div>

        <p>
        	<?php echo JText::_('COM_SFS_HOTEL_TERM_DESC');?>
        </p>

        <form name="hotelRegisterForm" id="hotelRegisterForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile'); ?>" method="post">
            
            <div class="sfs-main-wrapper-none">
                <div class="sfs-orange-wrapper">
                <div class="sfs-white-wrapper">
                    <div class="fieldset-fields" style="padding:40px;">

                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_FIRST_NAME')?>:</label>
                            <?php echo $this->hotel_admin->name;?>
                        </div>
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_SURNAME')?>:</label>
                            <?php echo $this->hotel_admin->surname;?>
                        </div>
                        <div class="register-field clear floatbox">
                            <label>Job title:</label>
                            <?php echo $this->hotel_admin->job_title;?>
                        </div>

                        <div class="floatbox">
                            <input type="checkbox" name="agree" value="1" /> <?php echo JText::_('COM_SFS_HOTEL_TERM_CONFIRM_TEXT');?>
                            <br /><br />
    	                    <a href="index.php?option=com_sfs&view=article&id=83&tmpl=component" rel="{handler: 'iframe', size: {x: 675, y: 400}}" class="modal"><?php echo JText::_('COM_SFS_HOTEL_TERM_CLICK_HERE');?></a>
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
            <input type="hidden" name="task" value="hotelprofile.confirm" />
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>

