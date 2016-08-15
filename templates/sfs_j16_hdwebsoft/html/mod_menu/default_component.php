<?php
/**
 * @version		$Id: default_component.php 20423 2011-01-24 10:22:44Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
$user = JFactory::getUser();
if($user->id) {
	if($item->title=='Login') {
		$item->title = 'Logout';
		?>
		<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post">
            <button type="submit" class="button"><?php echo $item->title; ?></button>
            <input type="hidden" name="return" value="<?php echo base64_encode(JRoute::_('index.php')); ?>" />
            <?php echo JHtml::_('form.token'); ?>
		</form>		
		<?php 	
		return;	
	}
}

$pageclass_sfx = $item->params->get('pageclass_sfx', '' );
if($pageclass_sfx=='grouptransport')
{	
	$airline = SFactory::getAirline();	
	if(!$airline->allowGroupTransportation())
	{
		return;
	}
}
// Note. It is important to remove spaces between elements.
$class = $item->anchor_css ? 'class="'.$item->anchor_css.'" ' : '';
$title = $item->anchor_title ? 'title="'.$item->anchor_title.'" ' : '';

if ($item->menu_image) {
		$item->params->get('menu_text', 1 ) ? 
		$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" /><span class="image-title">'.$item->title.'</span> ' :
		$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" />';
} 
else { $linktype = $item->title;
}



switch ($item->browserNav) :
	default:
	case 0:
?><a <?php echo $class; ?>href="<?php echo $item->flink; ?>" <?php echo $title; ?>><?php echo $linktype; ?></a><?php
		break;
	case 1:
		// _blank
?><a <?php echo $class; ?>href="<?php echo $item->flink; ?>" target="_blank" <?php echo $title; ?>><?php echo $linktype; ?></a><?php
		break;
	case 2:
	// window.open
?><a <?php echo $class; ?>href="<?php echo $item->flink; ?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');return false;" <?php echo $title; ?>><?php echo $linktype; ?></a>
<?php
		break;
endswitch;