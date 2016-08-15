<?php
/**
 * @version		$Id: default_login.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
$app = JFactory::getApplication();
$app->redirect(JRoute::_('index.php?option=com_sfsuser&view=login&Itemid=104', false));
return;
?>
<div class="sfs-main-wrapper"><div class="sfs-orange-wrapper"><div class="sfs-white-wrapper">

<h2>Login Form</h2>
<div class="login<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>

	<?php if ($this->params->get('logindescription_show') == 1 || $this->params->get('login_image') != '') : ?>
	<div class="login-description">
	<?php endif ; ?>

		<?php if($this->params->get('logindescription_show') == 1) : ?>
			<?php echo $this->params->get('login_description'); ?>
		<?php endif; ?>

		<?php if (($this->params->get('login_image')!='')) :?>
			<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USER_LOGIN_IMAGE_ALT')?>"/>
		<?php endif; ?>

	<?php if ($this->params->get('logindescription_show') == 1 || $this->params->get('login_image') != '') : ?>
	</div>
	<?php endif ; ?>

	<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" name="loginForm">

		<fieldset>
			<?php foreach ($this->form->getFieldset('credentials') as $field): ?>
				<?php if (!$field->hidden): ?>
					<div class="login-fields"><?php echo $field->label; ?>
					<?php echo $field->input; ?></div>
				<?php endif; ?>
			<?php endforeach; ?>
			
            <div style="margin:5px 5px 0 0;" class="clearfix">
			<a class="button-primary float-right" href="#" onclick="document.loginForm.submit();"><span><?php echo JText::_('JLOGIN'); ?></span></a>
			</div>
			
			
			<input type="hidden" name="return" value="<?php echo base64_encode('index.php?option=com_sfs&view=dashboard&Itemid=103'); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</form>
</div>

<div>
	<ul>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
			<?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a>
		</li>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
			<?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?></a>
		</li>		
	</ul>
</div>


<div class="width100 float-left">
    <ul class="button-primary-wrap">
        <li><a class="button-primary" href="index.php?option=com_sfs&amp;view=airlineregister&amp;Itemid=127"><span>Airline sign up</span></a></li>
        <li><a class="button-primary" href="index.php?option=com_sfs&amp;view=ghregister&amp;Itemid=127"><span>Ground handlers sign up</span></a></li>    
        <li><a class="button-primary" href="index.php?option=com_sfs&amp;view=hotelregister&amp;Itemid=127"><span>Hotel sign up</span></a></li>    
    </ul>
</div>

</div></div></div>