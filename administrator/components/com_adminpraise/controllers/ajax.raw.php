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

// No direct access.
defined('_JEXEC') or die;

/**
 * Ajax Controller
 *
 * @package		Adminpraise
 * @subpackage	Joomla
 */
class AdminpraiseControllerAjax extends JController
{	
	public function changePosition() {	

		$get = JRequest::get( 'get' );

		$id_from = substr($get['from'], 7);
		$id_to = substr($get['to'], 7);

		$model =& $this->getModel( 'ajax' );

		if ($model->changePosition($id_from, $id_to)) {
			$msg = JText::sprintf( 'COM_ADMINPRAISE_POSITION_CHANGED');
			exit;
		} else {
			$msg = $model->getError();
		}

	}
	
	public function unpublishModule() {
		$moduleId = JRequest::getInt('id');
		$model =& $this->getModel( 'ajax' );
		
		if ($model->unpublishModule($moduleId) != true) {
			echo JText::_('COM_ADMINPRAISE_UNPUBLISH_MODULE_PROBLEM');
		}
	}

	public function changeDefaultEditor() {

		$editor = JRequest::getVar('editor');
		$user = JFactory::getUser();
		echo $editor;
		$user->setParam('editor', $editor);
		$user->save();

	}
}
