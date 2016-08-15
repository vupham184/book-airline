<?php
// No direct access.
defined('_JEXEC') or die;
?>
<div class="width-100">
	<form action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>"
		method="post">


		<h3>Are you sure to approve?</h3>

		<button type="submit">Confirm</button>

		<input type="hidden" name="id"
			value="<?php echo JRequest::getInt('id')?>" /> <input type="hidden"
			name="task" value="airline.approved" />
			<?php echo JHtml::_('form.token'); ?>

	</form>
</div>
