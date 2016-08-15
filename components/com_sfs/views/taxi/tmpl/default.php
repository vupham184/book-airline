<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>


	 
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::_('COM_SFS_TAXI_DETAILS');?></h3>
    </div>
</div>

<div class="main">
    <div class="sfs-orange-wrapper">
    	
    	<div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
    		<?php echo $this->loadTemplate('taxies');?>
    	</div>
    	
    	<div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
    		<?php echo $this->loadTemplate('address');?>
    	</div>
    	
    	<div class="sfs-white-wrapper floatbox" style="margin-bottom:0;">
    		<?php echo $this->loadTemplate('billing');?>
    	</div>
    	
	</div>
    <div class="main-bottom-block">
        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid')) ?>" class="btn orange sm"><?php echo JText::_('COM_SFS_BACK');?></a>
        <?php if($this->airline->allowEditTaxiDetails() ) : ?>
            <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=taxi&layout=edit&Itemid='.JRequest::getInt('Itemid')) ?>" class="btn orange sm pull-right"><?php echo JText::_('COM_SFS_EDIT');?></a>
        <?php endif;?>
    </div>
</div>

	