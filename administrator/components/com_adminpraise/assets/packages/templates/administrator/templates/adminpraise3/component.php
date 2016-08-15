<?php
/**
 * @package		AdminPraise3
 * @author		AdminPraise http://www.adminpraise.com
 * @copyright	Copyright (c) 2008 - 2011 Pixel Praise LLC. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */
 
 /**
 *    This file is part of AdminPraise.
 *    
 *    AdminPraise is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with AdminPraise.  If not, see <http://www.gnu.org/licenses/>.
 *
 **/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$template_path = dirname(__FILE__);
require_once($template_path.'/lib/stainless.php');

$mainframe = &JFactory::getApplication();
$stainless = &AdminPraise3Tools::getInstance();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo  $this->language; ?>" lang="<?php echo  $this->language; ?>" dir="<?php echo  $this->direction; ?>" >
<head>
<jdoc:include type="head" />

<link href="templates/<?php echo  $this->template ?>/css/template.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="templates/<?php echo  $this->template; ?>/js/ap-component.js"></script>
</head>
<body class="contentpane ap-modal ap-modal-<?php echo $stainless->get('option'). " ap-modal-view-" . $stainless->get('view') . " " .$stainless->get('templateTheme');?>">
	<jdoc:include type="message" />
	<?php if(($stainless->get('option') == "com_admin") || ($stainless->get('option') == "com_config")) { ?>
	<jdoc:include type="modules" name="submenu" id="submenu-box" />
	<?php } ?>
	<div id="ap-modal-content">
		<jdoc:include type="component" />
	</div>
</body>
</html>
