<?php
defined('_JEXEC') or die;
JHTML::_('stylesheet', JURI::root() . 'components/com_sfs/assets/css/style.gzip.php');
$session = JFactory::getSession();
?>
<div>
	<p style="font-size:14px;font-family:Arial;">You are trying to log in with an username that is allready logged in Sfs at this moment.</p>
	<p style="font-size:14px;font-family:Arial;">
	If you are sure you are the only user you can choose to log in and this will terminale the session in use. If someone else is logged in with this username please use the cancel button.
	</p>
	<form action="<?php echo JRoute::_('index.php?option=com_sfsuser&task=user.login'); ?>" method="post" name="loginForm" id="loginForm">
		<div class="float-right" style="padding: 0 20px 10px 0;">			
			<button type="submit" class="validate small-button float-left" style="margin:0 0 0 15px;">
				<?php echo JText::_('JLOGIN')?>
			</button>
		</div>
		<div class="float-right" style="padding: 0 0px 10px 0;">			
			<button type="button" onclick="window.parent.SqueezeBox.close();" class="validate small-button float-left" style="margin:0 0 0 15px;">
				Cancel
			</button>
		</div>		
		<input type="text" value="<?php echo $session->get('sfsUsername')?>" name="username" style="display: none;">
		<input type="text" value="<?php echo base64_encode($session->get('sfsPassword'))?>" name="password" style="display: none;">
		<input type="hidden" name="is_popup" value="1" />
		<input type="hidden" name="return" value="<?php echo base64_encode('index.php?option=com_sfs&view=dashboard&Itemid=103'); ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>