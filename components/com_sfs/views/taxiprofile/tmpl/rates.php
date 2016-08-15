<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>

<script type="text/javascript">
<!--
window.addEvent('domready', function(){
	
});
//-->
</script>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Rate agreement</h3>
    </div>
</div>
<div id="sfs-wrapper" class="main fs-14">
<form id="taxiRateForm" name="taxiRateForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=taxiprofile&layout=rates');?>" method="post" class="form-validate">
<div class="sfs-orange-wrapper">

	<?php if( $this->taxi->getParam('enable_night_fare') == 1 || $this->taxi->getParam('enable_weekend_fare') == 1 ) : ?>
    <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
    	<?php echo $this->loadTemplate('period');?>
    </div>
    <?php endif;?>
    
    <div class="sfs-white-wrapper floatbox">
    	<?php echo $this->loadTemplate('hotels');?>
    </div>
            
</div>

<div class="sfs-below-main">
	<div class="s-button float-left">
	        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid')) ?>" class="s-button"><?php echo JText::_('COM_SFS_BACK');?></a>
    </div>
    <div class="s-button float-right">
        <input type="submit" class="validate s-button" value="<?php echo JText::_('JSAVE');?>">
    </div>           
</div>

<input type="hidden" name="task" value="taxiprofile.saveRates" />
<input type="hidden" name="option" value="com_sfs" />
<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
        
<?php echo JHtml::_('form.token'); ?> 

</form>
	
</div>