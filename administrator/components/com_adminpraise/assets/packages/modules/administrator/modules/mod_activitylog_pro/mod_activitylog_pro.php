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

if(!JPluginHelper::isEnabled('system', 'activitylogpro')) {
	return false;
}

// get module settings
$user = JFactory::getUser();
$hasAccess = false;
$access = (array) $params->get('access');
$authorisedGroups = $user->getAuthorisedGroups();

// since we cannot specify a default group that will have access to the module
// we assume that if no group is selected all users should have access to the module
if(count($access) == 0) {
	$hasAccess = true;
} else {
	foreach($authorisedGroups as $value) {
		if(in_array($value, $access)) {
			$hasAccess = true;
			break;
		}
	}
}


if($hasAccess) {
	require_once(dirname(__FILE__).DS.'helper.php');

	modActivityLogHelperHelper::initializeParams($params);

	$users = modActivityLogHelperHelper::getUsers();
	$options = modActivityLogHelperHelper::getOptions();
	$activities = modActivityLogHelperHelper::prepareData(modActivityLogHelperHelper::getActivity());
	require(JModuleHelper::getLayoutPath('mod_activitylog_pro', 'default'));
}