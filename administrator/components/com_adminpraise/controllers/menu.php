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

class AdminpraiseControllerMenu extends AdminpraiseController {

	public function __construct() {
		parent::__construct();
		$this->registerTask('apply', 'save');
		$this->registerTask('applyMenuType', 'saveMenuType');
	}

	/**
	 * If we have a menutype we'll just execute the parent display function.
	 * If we don't have, we have to try to match for a menu type, since each
	 * menu item has to have one. 
	 * 
	 * TODO: this is lame, there needs to be a better way for this...
	 */
	public function display() {
		if (JRequest::getInt('menutype')) {
			parent::display();
		} else {
			$model = & $this->getModel('Menu');
			$justAMenu = $model->getAMenuType();
			/**
			 * we need at least one menu type, send the user 
			 * to the menu type creation form
			 */
			if ($justAMenu == 0) {
				$mainframe = JFactory::getApplication();
				$mainframe->enqueueMessage(JText::_('COM_ADMINPRAISE_NEED_AT_LEST_ONE_MENU_TYPE'), 'warning');
				return $this->newMenuType();
			}
			$this->setRedirect('index.php?option=com_adminpraise&view=menu&menutype=' . $justAMenu);
		}
	}

	public function newItem() {
		JRequest::setVar('edit', false);
		$document = & JFactory::getDocument();

		$viewType = $document->getType();

		$model = & $this->getModel('Menu');
		$view = & $this->getView('Menu', $viewType);
		$view->setModel($model, true);

		// Set the layout and display
		$view->setLayout('type');
		$view->type();
	}

	public function type() {
		JRequest::setVar('edit', true);
		$model = & $this->getModel('Menu');
		$view = & $this->getView('Menu', 'html');
		$view->setModel($model, true);

		// Set the layout and display
		$view->setLayout('type');

		$view->type();
	}

	public function edit() {
		$viewName = JRequest::getCmd('view', 'menu');

		// Set the default layout and view name
		$layout = JRequest::getCmd('layout', 'form');

		// Get the document object
		$document = & JFactory::getDocument();

		// Get the view type
		$viewType = $document->getType();

		// Get the view
		$view = & $this->getView($viewName, $viewType);

		$model = & $this->getModel($viewName);

		if ($model) {
			$view->setModel($model, $viewName);
		}

		// Set the layout
		$view->setLayout($layout);

		// Display the view
		$view->edit();

		// Display Toolbar. View must have setToolBar method
		if (method_exists($view, 'setEditToolBar')) {
			$view->setToolBar();
		}
	}

	public function remove() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get some variables from the request
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);

		if (!count($cid)) {
			$this->setRedirect(
					'index.php?option=com_adminpraise&view&menu', JText::_('COM_ADMINPRAISE_NO_ITEMS_SELECTED')
			);
			return false;
		}

		$model = & $this->getModel('menu');
		if ($n = $model->remove($cid)) {
			$msg = JText::sprintf('COM_ADMINPRAISE_ITEMS_REMOVED', $n);
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect(
				'index.php?option=com_adminpraise&view=menu', $msg
		);
	}

	/**
	 * Save the item(s) to the menu selected
	 */
	function publish() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get some variables from the request
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);

		$model = & $this->getModel('menu');
		if ($model->setItemState($cid, 1)) {
			$msg = JText::sprintf('COM_ADMINPRAISE_MENU_ITEMS_PUBLISHED', count($cid));
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_adminpraise&view=menu', $msg);
	}

	/**
	 * Save the item(s) to the menu selected
	 */
	public function unpublish() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get some variables from the request
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);

		$model = & $this->getModel('menu');
		if ($model->setItemState($cid, 0)) {
			$msg = JText::sprintf('COM_ADMINPRAISE_MENU_ITEMS_UNPUBLISHED', count($cid));
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_adminpraise&view=menu', $msg);
	}

	/**
	 * Save the item(s) to the menu selected
	 */
	public function orderUp() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$menutype = JRequest::getInt('menutype');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect(
					'index.php?option=com_adminpraise&view=menu&menutype=' . $menutype, JText::_('COM_ADMINPRAISE_NO_ITEMS_SELECTED')
			);
			return false;
		}

		$model = & $this->getModel('menu');
		if ($model->orderItem($id, -1)) {
			$msg = JText::_('COM_ADMINPRAISE_MENU_ITEMS_MOVED_UP');
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_adminpraise&view=menu&menutype=' . $menutype, $msg);
	}

	/**
	 * Save the item(s) to the menu selected
	 */
	public function orderDown() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$menutype = JRequest::getInt('menutype');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect(
					'index.php?option=com_adminpraise&view=menu&menutype=' . $menutype, JText::_('COM_ADMINPRAISE_NO_ITEMS_SELECTED')
			);
			return false;
		}

		$model = & $this->getModel('menu');
		if ($model->orderItem($id, 1)) {
			$msg = JText::_('COM_ADMINPRAISE_MENU_ITEMS_MOVED_DOWN');
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_adminpraise&view=menu&menutype=' . $menutype, $msg);
	}

	/**
	 * Save the item(s) to the menu selected
	 */
	public function saveOrder() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$menutype = JRequest::getInt('menutype');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);

		$model = & $this->getModel('menu');
		if ($model->setOrder($cid)) {
			$msg = JText::_('COM_ADMINPRAISE_NEW_ORDERING_SAVED');
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_adminpraise&view=menu&menutype=' . $menutype, $msg);
	}

	/**
	 * Save the item(s) to the menu selected
	 */
	public function accessManager() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get some variables from the request
		$menutype = JRequest::getInt('menutype');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);

		$model = & $this->getModel('menu');
		if ($model->setAccess($cid, 0)) {
			$msg = JText::sprintf('COM_ADMINPRAISE_MENU_ITEMS_SET_PUBLIC', count($cid));
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_adminpraise&view=menu&menutype=' . $menutype, $msg);
	}

	/**
	 * Save the item(s) to the menu selected
	 */
	public function accessAdministrator() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get some variables from the request
		$menutype = JRequest::getInt('menutype');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);

		$model = & $this->getModel('menu');
		if ($model->setAccess($cid, 1)) {
			$msg = JText::sprintf('COM_ADMINPRAISE_MENU_ITEMS_SET_REGISTERED', count($cid));
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_adminpraise&view=menu&menutype=' . $menutype, $msg);
	}

	/**
	 * Save the item(s) to the menu selected
	 */
	public function accessSuperUser() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get some variables from the request
		$menutype = JRequest::getInt('menutype');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);

		$model = & $this->getModel('menu');
		if ($model->setAccess($cid, 2)) {
			$msg = JText::sprintf('COM_ADMINPRAISE_MENU_ITEMS_SET_SPECIAL', count($cid));
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_adminpraise&view=menu&menutype=' . $menutype, $msg);
	}

	/**
	 * Saves a menu item
	 */
	public function save() {

		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$model = & $this->getModel('menu');
		$post = JRequest::get('post');
		// allow name only to contain html
		$post['name'] = JRequest::getVar('name', '', 'post', 'string', JREQUEST_ALLOWHTML);
		$model->setState('request', $post);

		if ($model->store()) {
			$msg = JText::_('COM_ADMINPRAISE_MENU_ITEMS_SAVED');
		} else {
			$msg = JText::_('COM_ADMINPRAISE_ERROR_SAVING_MENU_ITEM');
		}

		$item = & $model->getItem();
		switch ($this->getTask()) {
			case 'apply':
				$this->setRedirect(
						'index.php?option=com_adminpraise&view=menu&task=edit&menutype='
						. $item->menutype . '&cid[]=' . $item->id, $msg
				);
				break;

			case 'save':
			default:
				$this->setRedirect(
						'index.php?option=com_adminpraise&view=menu&menutype=' . $item->menutype, $msg);
				break;
		}
	}

	public function cancel() {
		$menutype = JRequest::getInt('menutype');
		$this->setRedirect('index.php?option=com_adminpraise&view=menu&menutype=' . $menutype);
	}

	public function newMenuType() {
		JRequest::setVar('edit', false);
		$document = & JFactory::getDocument();

		$viewType = $document->getType();

		$model = & $this->getModel('Menu');
		$view = & $this->getView('Menu', $viewType);
		$view->setModel($model, true);

		// Set the layout and display
		$view->setLayout('menutypeform');
		$view->newMenuType();
	}

	public function editMenuType() {
		JRequest::setVar('edit', true);
		$document = & JFactory::getDocument();

		$viewType = $document->getType();

		$model = & $this->getModel('Menu');
		$view = & $this->getView('Menu', $viewType);
		$view->setModel($model, true);

		// Set the layout and display
		$view->setLayout('menutypeform');
		$view->newMenuType();
	}

	public function saveMenuType() {
		JRequest::checkToken() or jexit('Invalid Token');

		$model = & $this->getModel('menu');
		$post = JRequest::get('post');
		$model->setState('request', $post);

		if ($model->storeMenuType()) {
			$msg = JText::_('COM_ADMINPRAISE_MENU_TYPE_SAVED');
		} else {
			$msg = JText::_('COM_ADMINPRAISE_ERROR_SAVING_MENU_TYPE');
		}

		$item = & $model->getMenuType();


		switch ($this->getTask()) {
			case 'applyMenuType':
				$this->setRedirect(
						'index.php?option=com_adminpraise&view=menu&task=editMenuType&cid[]=' . $item->id, $msg
				);
				break;

			case 'saveMenuType':
			default:
				$this->setRedirect(
						'index.php?option=com_adminpraise&view=menu&menutype=' . $item->id, $msg);
				break;
		}
	}

	public function removeMenuType() {
		// Check for request forgeries
		JRequest::checkToken('get') or jexit('Invalid Token');

		// Get some variables from the request
		$id = JRequest::getInt('id');
		$menutype = JRequest::getInt('menutype');

		if (!count($id)) {
			$this->setRedirect(
					'index.php?option=com_adminpraise&view=menu&menutype=' . $menutype, JText::_('COM_ADMINPRAISE_NO_MENU_ITEM_DELETE_SELECTED')
			);
			return false;
		}

		$model = & $this->getModel('menu');
		if ($model->removeMenuType($id)) {
			$msg = JText::sprintf('COM_ADMINPRAISE_MENU_TYPE_ALL_MENU_ITEMS_REMOVED');
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect(
				'index.php?option=com_adminpraise&view=menu', $msg
		);
	}

	/**
	 * Form for copying item(s) to a specific menu
	 */
	public function copy() {
		$model = & $this->getModel('Menu');
		$view = & $this->getView('Menu', 'html');
		$view->setModel($model, true);
		$view->copyForm();
	}

	/**
	 * Save the item(s) to the menu selected
	 */
	public function doCopy() {
		$mainframe = JFactory::getApplication();

		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get some variables from the request
		$menu = JRequest::getVar('menu', '', 'post', 'menutype');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');


		JArrayHelper::toInteger($cid);

		//Check to see of a menu was selected to copy the items too
		if (empty($menu)) {
			$msg = JText::_('COM_ADMINPRAISE_SELECT_MENU_LIST');
			$mainframe->enqueueMessage($msg, 'message');
			return $this->execute('copy');
		}

		$model = & $this->getModel('Menu');
		JRequest::setVar('cid', array($menu));
		$menuTitle = $model->getMenuType()->title;


		if ($model->copy($cid, $menu)) {
			$msg = JText::sprintf('COM_ADMINPRAISE_MENU_ITEMS_COPIED_TO', count($cid), $menuTitle);
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_adminpraise&view=menu&menutype=' . $menu, $msg);
	}

	/**
	 * Form for moving item(s) to a specific menu
	 */
	public function move() {
		$model = & $this->getModel('Menu');
		$view = & $this->getView('Menu', 'html');
		$view->setModel($model, true);
		$view->moveForm();
	}

	/**
	 * Save the item(s) to the menu selected
	 */
	public function doMove() {
		$mainframe = JFactory::getApplication();

		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get some variables from the request
		$menu = JRequest::getVar('menu', '', 'post', 'menutype');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);

		//Check to see if a menu was selected to copy the items too
		if (empty($menu)) {
			$msg = JText::_('COM_ADMINPRAISE_SELECT_MENU_LIST');
			$mainframe->enqueueMessage($msg, 'message');
			return $this->execute('move');
		}

		$model = & $this->getModel('Menu');
		JRequest::setVar('cid', array($menu));
		$menuTitle = $model->getMenuType()->title;

		if ($model->move($cid, $menu)) {
			$msg = JText::sprintf('COM_ADMINPRAISE_MENU_ITEMS_MOVED_TO', count($cid), $menuTitle);
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_adminpraise&view=menu&menutype=' . $menu, $msg);
	}

	public function reset() {
		(JRequest::checkToken() || JRequest::checkToken('get')) or jexit('Invalid Token');
		$appl = JFactory::getApplication();
		$model = & $this->getModel('Menu');
		if($model->reset()) {
			$appl->enqueueMessage(JText::_('COM_ADMINPRAISE_MENU_SUCCESSFULLY_RESET_TO_DEFAULT'));
		} else {
			$appl->enqueueMessage(JText::_('COM_ADMINPRAISE_MENU_RESET_FAILURE'));
		}
		

		$this->setRedirect('index.php?option=com_adminpraise&view=menu');
	}

}

?>