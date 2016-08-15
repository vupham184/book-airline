<?php
defined('_JEXEC') or die;
?>
<form id="cancelVoucherFrom" name="cancelVoucherFrom" action="<?php echo JRoute::_('index.php?option=com_sfs&view=handler'); ?>" method="post">
	<div>
		<h3 style="padding-top: 0; margin-top:0"><?php echo JText::_('COM_SFS_WARNING')?></h3>
		<?php echo JText::_('COM_SFS_VOUCHER_CANCELLATION_NOTE')?>
		<div class="floatbox">
        
            <div class="s-button">        
				<a onclick="window.parent.SqueezeBox.close();"  class="s-button float-left"><?php echo JText::_('COM_SFS_BACK')?></a>
            </div>
            
            <div class="s-button float-right">
				<button type="submit" class="s-button float-right"><?php echo JText::_('COM_SFS_CONFIRM')?></button>
            </div>
            
		</div>
	</div>
	<input type="hidden" name="task" value="handler.cancelVoucher" />
	<input type="hidden" name="voucher_id" value="<?php echo $this->voucher_id?>" />
	<input type="hidden" name="blockcode" value="<?php echo $this->blockcode?>" />	
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />	
	<?php echo JHtml::_('form.token'); ?>
</form>