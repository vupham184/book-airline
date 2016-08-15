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
jimport('joomla.filesystem.file');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'library' . DS . 'joomla' . DS . 'html' . DS . 'parameter.php');

class AdminpraiseViewLogs extends AdminpraiseView {
	public function display() {
		$ini = JPATH_ADMINISTRATOR . DS . 'templates'.DS.'adminpraise3'.DS.'params.ini';

		$this->assignRef('templateFile', $ini);
		parent::display();
	}

	public function setToolBar() {

		$layout = JRequest::getVar('layout', 'default');

		if ($layout == "default") {
			JToolBarHelper::customX( 'Download', 'download.png', 'download_f2.png', 'Download', true );
			JToolBarHelper::trash();
		}else{
			JToolBarHelper::back('Back' , 'index.php?option=com_adminpraise&view=logs');
		}

		JToolBarHelper::divider();
	}
}
