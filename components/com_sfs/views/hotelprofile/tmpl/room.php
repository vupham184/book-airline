<?php
defined('_JEXEC') or die;

JHTML::_('script', JURI::root() . 'administrator/components/com_sfs/assets/js/jquery.validate.min.js');
?>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery("#roomForm").validate({
        errorClass: "jquery_error",
        errorElement: "div",
        wrapper: "div",  // a wrapper around the error message
        errorPlacement: function(error, element) {
            offset = element.offset();
            error.insertBefore(element)
            error.addClass('message');  // add a class to the wrapper
            error.css('position', 'absolute');
            error.css('left', '546px');
            //error.css('top', offset.top);
        },
        rules: {
            "standard": {
                required: true,
                digits: true,
                max: function(element){
                    return parseInt( jQuery('input[name="total"]').val() );
                }
            }
        }
    });
});
</script>
<div class="registration<?php echo $this->pageclass_sfx?>">
    <div class="com-hotel">
        <div id="form-signup">
            <?php if ($this->params->get('show_page_heading')) : ?>
                <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
            <?php endif; ?>
            
            <h1 class="hotel_name"><?php echo $this->data->name; ?></h1>
            <?php echo SfsHelper::hotel_step(2); ?>
            
            <h1 class="hotel_sigup_title"><?php echo JText::sprintf('COM_SFS_LABEL_WELCOME', $this->user->name); ?></h1>
            <p><?php echo SfsHelper::getArticle(99, 1, 1); ?></p>
            <div class="clear"></div>
            
            <h1 class="hotel_sigup_title">
                <?php echo JText::sprintf('COM_SFS_STEP', 2); ?>
                <?php echo JText::_('COM_SFS_LABLE_ROOMS'); ?>
            </h1>
            <div class="clear"></div>
            
            <!-- Nearest airport form -->
            <form id="roomForm" name="roomForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile'); ?>" method="post" class="form-validate">
                <div class="hotel-area">
                    <div class="hotel-management hotel-form">
                        <fieldset class="airport" style="padding: 40px 60px !important; width: 770px;">
                            <table cellpadding="6" cellspacing="6">
                                <tr>
                                    <td style="width: 200px;"><?php echo JText::_('COM_SFS_LABEL_TOTAL_ROOMS'); ?></td>
                                    <td style="width: 30px;">
                                        <input class="required digits" value="<?php echo $this->room->total; ?>" name="total" size="6" style="width: 60px;" />
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="width: 200px;"><?php echo JText::_('COM_SFS_LABEL_NUMBER_OF_STANDARD_ROOMS'); ?></td>
                                    <td style="width: 30px;">
                                        <input class="" value="<?php echo $this->room->standard; ?>" name="standard" size="6" style="width: 60px;" />
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="width: 200px;"><?php echo JText::_('COM_SFS_LABEL_AVERAGE_SIZE_STANDARD_ROOMS'); ?></td>
                                    <td style="width: 30px;">
                                        <input class="required digits" value="<?php echo $this->room->standard_size; ?>" name="standard_size" size="6" style="width: 60px;" />
                                    </td>
                                    <td>
                                        <?php
                                        $square = array(
                                            array('val'=>'square_feet', 'txt'=>'Square feet'),
                                            array('val'=>'square_meters', 'txt'=>'Square meters')
                                        );
                                        echo JHTML::_('select.genericlist', $square, 'standard_size_unit', 'class="inputbox"', 'val', 'txt', $this->room->standard_size_unit);
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                </div>
                <div class="hotel-message">
                    <?php echo JText::_("COM_SFS_HOTEL_ROOMS_NOTE"); ?>
                </div>
                <div class="hotel-button multi-button">
                
                    <input type="submit" class="button" value="<?php echo JText::_('COM_SFS_NEXT_STEP');?>">                    
                    
                    <input type="hidden" name="id" value="<?php echo $this->room->id; ?>" />
                    <input type="hidden" name="task" value="hotelprofile.saveRooms" />
                </div>
                <?php echo JHtml::_('form.token'); ?>
            </form>
        <!-- End nearest airport form -->
        </div>
    </div>
</div>
