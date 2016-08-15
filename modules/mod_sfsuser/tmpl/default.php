<?php
defined('_JEXEC') or die;

?>
<div class="wellcomepart">
<div class="namecus">Welcome back:<span class="infoname"> <?php echo $user->name;?></span></div>
<div class="namecountry"><?php echo $item->name;?> <?php echo $item->country_name ? '('.$item->country_name.')':'';?></div>
<div class="datetime"><span class="date">Date: <?php echo JHTML::_('date',JFactory::getDate()->toSQL(), JText::_('DATE_FORMAT_LC3'));?></span> <span class="time">Time: <?php echo JHTML::_('date',JFactory::getDate()->toSQL(), JText::_('H:i'));?></span></div>
</div>