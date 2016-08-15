<?php
// No direct access.
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
?>

<div class="width-100">
	<h2>free release percentage</h2>	
	<form action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" name="editFreeRelease" id="editFreeRelease" class="form-validate">
		<div>
			<input type="text" name="percent_release_policy" value="<?php echo (int)$this->taxes->percent_release_policy;?>" class="inputbox required">	   
		</div>
		
		<button type="submit" class="button validate">Save</button>
		<input type="hidden" name="id" value="<?php echo JRequest::getInt('id')?>" />
		<input type="hidden" name="task" value="hotel.saveFreeRelease" />	
		<?php echo JHtml::_('form.token'); ?>
	</form>
	
	
</div>


