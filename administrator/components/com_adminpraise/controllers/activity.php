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
jimport('joomla.application.component.controller');

class AdminpraiseControllerActivity extends AdminpraiseController {
	
	public function __construct() {
		parent::__construct();
		$language = JFactory::getLanguage();
		$language->load('mod_activitylog_pro', JPATH_BASE, null, true);
	}

	public function reset() {
		$user = JFactory::getUser();
		$view = &$this->getView('activity', 'raw');
		$success = false;
		$model = &$this->getModel('activity');
		$view->setModel($model, true);
		
		if($model->reset()) {
			$success = true;
		}
		
		if($model->save()) {
			$success = true;
		}

		$view->reset($success);
	}
}
?>
