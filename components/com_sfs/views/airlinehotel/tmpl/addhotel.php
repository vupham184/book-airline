<?php
    defined('_JEXEC') or die();
    JHtml::_('behavior.keepalive');
    JHtml::_('behavior.tooltip');
?>
<script type="text/javascript">
window.addEvent('domready', function(){
	var hotelRegisterForm = document.id('hotelRegistraionForm');
	hotelRegisterForm.getElements('[type=text], select').each(function(el){
    	new OverText(el);
	});
	new Form.Validator(hotelRegisterForm);
});
jQuery.noConflict();
jQuery(function($){
    $(document).ready(function(){
        $('.ui.dropdown')
            .dropdown()
        ;
    })
})
</script>
<style>
    i.star{
        background: none;
        margin-right: 0 !important;
    }
    .help-block{
        font-size: 11px;
    }
    .pull-left.p10{
        margin-left: 10%;
    }
</style>

<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::_('COM_SFS_ADD_CONTRACTED_HOTEL'); ?></h3>
    </div>
</div>

<div class="main">
    <div id="hotel-registraion">
        <form id="hotelRegistraionForm" action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" class="sfs-form form-vertical register-form">
            <div class="block-group">
                <div class="block border orange">
                    <!-- Form to add hotel information-->
                    <?php echo $this->loadTemplate('information'); ?>
                </div>
                <div class="block border orange">
                    <!-- Form to add room inventory information-->
                    <?php echo $this->loadTemplate('roomloading'); ?>
                </div>
                <div class="block border orange">
                    <!-- Form to add meal plan information-->
                    <?php echo $this->loadTemplate('mealplan');?>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn orange lg pull-right">Add Hotel</button>
            </div>
            <input type="hidden" name="option" value="com_sfs" />
            <input type="hidden" name="task" value="airlinehotel.add" />
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>