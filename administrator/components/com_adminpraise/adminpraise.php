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

defined('_JEXEC') or die('Restricted access');

if(file_exists( JPATH_COMPONENT_ADMINISTRATOR . '/installer.dummy.ini') ) {
	require_once (JPATH_COMPONENT_ADMINISTRATOR.'/install/adminpraiseinstall.php');
	AdminpraiseInstall::handleRequest();
	return;
}
//load language with a little black magic
$jlang = JFactory::getLanguage();
$jlang->load('com_adminpraise', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_adminpraise', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_adminpraise', JPATH_ADMINISTRATOR, null, true);

/**
 * Calling ALU to live updating
 * @since	0.3.3
 */
require_once JPATH_COMPONENT_ADMINISTRATOR.'/library/liveupdate/liveupdate.php';
if(JRequest::getCmd('view','') == 'liveupdate') {
    LiveUpdate::handleRequest();
    return;
}

require_once(JPATH_COMPONENT .'/library/joomla/application/component/view.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/helper.php');

// check if the adminpraise3 template is on and output a message if not - 
// should be displayed all over the component, that is why we put it here
// not a nice OOP, but works..
if(JRequest::getCmd('view','') != 'activity') {
	AdminpraiseHelper::turnAdminpraiseOn();
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JController::getInstance('Adminpraise');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();