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
require_once('framework.php');
$allowedActions = array('unpublishModule');

$action = JRequest::getVar('action');

if(!in_array($action, $allowedActions)) {
	die('Invalid action');
}

switch($action) {
	case 'unpublishModule':
		unpublishModule();
		break;
}

function unpublishModule() {
	$moduleId = JRequest::getInt('id');
	require_once 'modules.php';
	if (AdminPraiseModules::unpublishModule($moduleId) != true) {
		echo JText::_('There was a problem deleting the module');
	}
}
?>
