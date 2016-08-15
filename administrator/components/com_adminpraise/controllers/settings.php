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
jimport('joomla.application.component.controllerform');

class AdminpraiseControllerSettings extends JControllerForm {
	
	public function __construct() {
		parent::__construct();
		$this->registerTask('apply', 'save');
	}
	
	public function save() {
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$mainframe = JFactory::getApplication();
		$template = 'adminpraise3';
		$option		= JRequest::getVar('option', '', '', 'cmd');

		$params		= JRequest::getVar('jform', array(), 'post', 'array');
		$default	= JRequest::getBool('default');

		$model		= $this->getModel();
		$table		= $model->getTable();

		$params = json_encode($params);

		$table->load(array('template' => $template));
		$table->params = $params;
		if(!$table->store()) {
			$mainframe->redirect('index.php?option='.$option, JText::_('COM_ADMINPRAISE_OPERAIOIN_FAILED'));
		}

		$task = JRequest::getCmd('task');
		if($task == 'apply') {
			$mainframe->redirect('index.php?option='.$option.'&view=settings');
		} else {
			$mainframe->redirect('index.php?option='.$option);
		}
	}
	
	private function uploadImage(){						
		$path_temp = JPATH_ROOT.DS.'media'.DS.'com_adminpraise'.DS.'images' .DS.'adminpraise3' . DS;
		if (!is_dir($path_temp)) {
			@ JFolder::create($path_temp);
			$content = '';
			JFile::write($path_temp.DS.'index.html', $content);
		}
		
		$prefix = JRequest::getVar('prefix');
		$id = $prefix.'adminpraise-image';
		
		$image = $_FILES[$id]['name'];
		
		$tmp_dest = $path_temp .$image;

		$userfile = $_FILES[$id];
		
		// Build the appropriate paths
		$tmp_src	= $userfile['tmp_name'];
	
		$uploaded = JFile::upload($tmp_src, $tmp_dest);

		if(!$uploaded) {
			return JText::_('COM_ADMINPRAISE_UPLOAD_FALSE');
		}
		return true;
	}
	
	public function updateImages() {
		
	}
	
	public function publishTemplate() {
		// Check for request forgeries
		JRequest::checkToken('get') or jexit('Invalid Token');
		$db = JFactory::getDBO();
		$appl = JFactory::getApplication();
		
		$query = "UPDATE `#__template_styles` SET `home` = '0' WHERE client_id = 1 AND home = 1";
		$db->setQuery($query);
		$db->query();

		$query = "UPDATE `#__template_styles` SET `home` = '1' WHERE template = ".$db->Quote('adminpraise3');
		$db->setQuery($query);
		$db->query();
		
		$msg = JText::_('COM_ADMINPRAISE_TEMPLATE_ENABLED');
		$appl->redirect('index.php?option=com_adminpraise', $msg);
	}

	public function back() {
		$this->setRedirect( 'index.php?option=com_adminpraise' );
	}
}
