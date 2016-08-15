<?php
defined('_JEXEC') or die;
?>
<form id="cancelVoucherFrom" name="cancelVoucherFrom" action="<?php echo JRoute::_('index.php?option=com_sfs&view=match'); ?>" method="post">
	<h3><?php echo JText::_('COM_SFS_WARNING')?></h3>
	<?php echo JText::_('COM_SFS_VOUCHER_CANCELLATION_NOTE')?>
	
	<div class="form-group">
		<label>Comment:</label>
		<textarea style="width: 98%; height:120px;padding:5px" name="comment"></textarea>
	</div>
	
	<div class="form-group">        
		<a onclick="window.parent.SqueezeBox.close();"  class="btn orange sm pull-left"><?php echo JText::_('COM_SFS_BACK')?></a>            
		<button type="submit" class="btn orange sm pull-right"><?php echo JText::_('COM_SFS_CONFIRM')?></button>            
	</div>
	<input type="hidden" name="task" value="match.cancelVoucher" />
	<input type="hidden" name="voucher_id" value="<?php echo $this->voucher_id?>" />
	<input type="hidden" name="blockcode" value="<?php echo $this->blockcode?>" />	
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />	
	<?php echo JHtml::_('form.token'); ?>
</form>