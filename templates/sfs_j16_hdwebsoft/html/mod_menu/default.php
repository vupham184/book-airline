<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

//load sfs application
error_reporting(0);
ini_set('display_errors', 0);
require_once JPATH_ROOT.'/components/com_sfs/libraries/core.php';
SFSCore::getInstance()->render( true );



//lchung
$airline = SFactory::getAirline();

//End lchung
?>

<ul class="menu<?php echo $class_sfx;?>"<?php
	$tag = '';
	if ($params->get('tag_id')!=NULL) {
		$tag = $params->get('tag_id').'';
		echo ' id="'.$tag.'"';
	}
?>>
<?php
foreach ($list as $i => &$item) :
	//lchung
	$is_show_menu = 1;
	if( !empty( $airline->params ) ){//120 == Match / Voucher [menu]
		if( $airline->params['api_enabled'] == 1 ) {
			if( $item->id == 120 ) {
				$is_show_menu = 0;
			}
		}
	}
	
	if ( $is_show_menu ) { //End lchung
		$class = 'item-'.$item->id;
		if ($item->id == $active_id) {
			$class .= ' current';
		}
	
		if (in_array($item->id, $path)) {
			$class .= ' active';
		}
		elseif ($item->type == 'alias') {
			$aliasToId = $item->params->get('aliasoptions');
			if (count($path) > 0 && $aliasToId == $path[count($path)-1]) {
				$class .= ' active';
			}
			elseif (in_array($aliasToId, $path)) {
				$class .= ' alias-parent-active';
			}
		}
	
		if ($item->deeper) {
			$class .= ' deeper';
		}
	
		if ($item->parent) {
			$class .= ' parent';
		}
	
		if (!empty($class)) {
			$class = ' class="'.trim($class) .'"';
		}
	
		echo '<li'.$class.'>';
	
		// Render the menu item.
		switch ($item->type) :
			case 'separator':
			case 'url':
			case 'component':
				require JModuleHelper::getLayoutPath('mod_menu', 'default_'.$item->type);
				break;
	
			default:
				require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
				break;
		endswitch;
	
		// The next item is deeper.
		if ($item->deeper) {
			echo '<ul>';
		}
		// The next item is shallower.
		elseif ($item->shallower) {
			echo '</li>';
			echo str_repeat('</ul></li>', $item->level_diff);
		}
		// The next item is on the same level.
		else {
			echo '</li>';
		}
	}//End if is_show_menu //End lchung
endforeach;
?></ul>
