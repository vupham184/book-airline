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
jimport('joomla.application.component.view');

class AdminpraiseViewActivity extends AdminpraiseView {
	public function display() {
		require_once(JPATH_ADMINISTRATOR .'/modules./mod_activitylog_pro/helper.php');
		$model = $this->getModel('activity');
		$data = $model->getData();
		
		$this->assignRef('data', modActivityLogHelperHelper::prepareData($data));
		parent::display();
	}
	
	public function reset($success) {
		
		$model = $this->getModel('activity');
		$data = $model->getData();
		
		$this->assignRef('success', $success);
		$this->assignRef('data', $data);
		$this->assignRef('output', $output);
		parent::display('reset');
	}
}

?>