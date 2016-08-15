<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
?>

<form action="index.php?option=com_sfs" method="post" name="updateForm">
	<button type="submit">Update</button>
	<input type="hidden" name="task" value="update.update" />		
	<?php echo JHtml::_('form.token'); ?>
</form>