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
defined('_JEXEC') or die();

class AdminpraiseInstall {

	public static function handleRequest() {
		// Load translations
		$basePath = dirname(__FILE__);
		$jlang = & JFactory::getLanguage();
		$jlang->load('adminpraiseinstall', $basePath, 'en-GB', true); // Load English (British)
		$jlang->load('adminpraiseinstall', $basePath, $jlang->getDefault(), true); // Load the site's default language
		$jlang->load('adminpraiseinstall', $basePath, null, true); // Load the currently selected language
		// Load the controller and let it run the show
		require_once dirname(__FILE__) . '/classes/controller.php';
		$controller = new AdminpraiseInstallController();
		$controller->execute(JRequest::getCmd('task', 'step1'));
		$controller->redirect();
	}

}
