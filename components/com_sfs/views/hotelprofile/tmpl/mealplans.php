<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
$this->hotel->currency_name = $this->hotel->getTaxes()->currency_name;
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo $this->hotel->name; ?>: <?php echo JText::_('COM_SFS_LABLE_FB'); ?></h3>
	</div>
</div>

<div id="sfs-wrapper" class="main">
<div class="hotel-mealplans fs-14">
    <div class="">
        <div class="sfs-orange-wrapper hotel-form">
            <div class="sfs-white-wrapper floatbox">
                <?php echo $this->loadTemplate('breakfast')?>
            </div>        	            
            <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
            	<?php echo $this->loadTemplate('lunch')?>
            </div>
            <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
                <?php echo $this->loadTemplate('dinner')?>
            </div>
        </div>
    </div>
	<div class="sfs-below-main">
		<div class="s-button float-left">
			<a href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('hotelprofile') );?>" class="s-button">
				<?php echo JText::_('COM_SFS_BACK');?>
			</a>
		</div>
		<div class="s-button float-right">
			<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formmealplans&Itemid='.JRequest::getInt('Itemid'));?>" class="s-button"><?php echo JText::_('COM_SFS_EDIT');?></a>
        </div>

    </div>
	<div class="clear"></div>
</div>

</div>
