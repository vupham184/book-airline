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
jimport('joomla.html.pane');

class AdminpraiseViewMenu extends AdminpraiseView {
	public function display() {
		$model = $this->getModel();
		$menuItems = $model->getData();
		
		$lists = $this->_getViewLists();
		
		$pagination	= &$this->get('Pagination');
		
		$ordering = ($lists['order'] == 'm.ordering');
		
		$types = $model->getAllMenuTypes();

		$menutype = JRequest::getInt('menutype');
		
		$this->assignRef('types', $types);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('items', $menuItems);
		$this->assignRef('ordering', $ordering);
		$this->assignRef('lists', $lists);
		$this->assignRef('menutype', $menutype);
		parent::display();
	}
	
	public function setToolbar() {
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::custom( 'move', 'move.png', 'move_f2.png', 'Move', true );
		JToolBarHelper::custom( 'copy', 'copy.png', 'copy_f2.png', 'Copy', true );
		JToolBarHelper::trash();
		JToolBarHelper::editListX();
		JToolBarHelper::custom( 'reset', 'copy.png', 'copy_f2.png', 'Reset to default', false );
		JToolBarHelper::addNewX('newItem');
	}
	
	private function &_getViewLists() {
		$mainframe = JFactory::getApplication();
		$db		=& JFactory::getDBO();

		$filter_order		= $mainframe->getUserStateFromRequest( "com_adminpraise.menu.filter_order",		'filter_order',		'm.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( "com_adminpraise.menu.filter_order_Dir",	'filter_order_Dir',	'ASC',			'word' );
		$filter_state		= $mainframe->getUserStateFromRequest( "com_adminpraise.menu.filter_state",		'filter_state',		'',				'word' );
		$levellimit			= $mainframe->getUserStateFromRequest( "com_adminpraise.menu.levellimit",		'levellimit',		10,				'int' );
		$search				= $mainframe->getUserStateFromRequest( "com_adminpraise.menu.search",			'search',			'',				'string' );
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);

		// ensure $filter_order has a good value
		if (!in_array($filter_order, array('m.title', 'm.published', 'm.ordering', 'm.type', 'm.id'))) {
			$filter_order = 'm.ordering';
		}

		if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC', ''))) {
			$filter_order_Dir = 'ASC';
		}

		// level limit filter
		$lists['levellist'] = JHTML::_('select.integerlist',    1, 20, 1, 'levellimit', 'size="1" onchange="document.adminForm.submit();"', $levellimit );

		// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search']= $search;

		return $lists;
	}
	
	public function edit() {
		JRequest::setVar( 'hidemainmenu', 1 );

		global $mainframe;

		$lang =& JFactory::getLanguage();
		$this->_layout = 'form';

		$item = &$this->get('Item');
//		var_dump($item);
		
		// clean item data
		JFilterOutput::objectHTMLSafe( $item, ENT_QUOTES, '' );

		// Set toolbar items for the page
		if (!$item->id) {
			JToolBarHelper::title( JText::_( 'COM_ADMINPRAISE_MENU_ITEM' ) .': <small><small>[ '. JText::_( 'COM_ADMINPRAISE_NEW' ) .' ]</small></small>', 'menu.png' );
		} else {
			JToolBarHelper::title( JText::_( 'COM_ADMINPRAISE_MENU_ITEM' ) .': <small><small>[ '. JText::_( 'COM_ADMINPRAISE_EDIT' ) .' ]</small></small>', 'menu.png' );
		}
		JToolBarHelper::save();
		JToolBarHelper::apply();
		if ($item->id) {
//		for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		} else {
			JToolBarHelper::cancel('cancel');
		}
		JToolBarHelper::help( 'screen.menus.edit' );


		// Initialize variables
		$params			= $this->get( 'StateParams' );
		$name			= $this->get( 'StateName' );
		$description	= $this->get( 'StateDescription' );
		$menuTypes 		= AdminpraiseMenuHelper::getMenuTypes();
		
		JHTML::_('behavior.tooltip');

		$document = & JFactory::getDocument();
		if ($item->id) {
			$document->setTitle(JText::_( 'COM_ADMINPRAISE_MENU_ITEM' ) .': ['. JText::_( 'COM_ADMINPRAISE_EDIT' ) .']');
		} else {
			$document->setTitle(JText::_( 'COM_ADMINPRAISE_MENU_ITEM' ) .': ['. JText::_( 'COM_ADMINPRAISE_NEW' ) .']');
		}

		// Was showing up null in some cases....
		if (!$item->published) {
			$item->published = 0;
		}
		$lists = new stdClass();
		$lists->published = AdminpraiseHelper::published($item);
		$lists->disabled = ($item->type != 'url' ? 'readonly="true"' : '');

		$this->assignRef('lists'	, $lists);
		$this->assignRef('item'		, $item);
		$this->assignRef('params'	, $params);
		$this->assignRef('name'		, $name);
		$this->assignRef('description', $description);
		$this->assignRef('menuTypes', $menuTypes);

		// Add slider pane
        // TODO: allowAllClose should default true in J!1.6, so remove the array when it does.
		$pane = &JPane::getInstance('sliders', array('allowAllClose' => true));
		$this->assignRef('pane', $pane);
		
		parent::display();
	}
	
	public function type($tpl = null) {
		JRequest::setVar( 'hidemainmenu', 1 );

		$mainframe = JFactory::getApplication();

		$lang =& JFactory::getLanguage();
		$this->_layout = 'type';

		$item = &$this->get('Item');

		// Set toolbar items for the page
		if (!$item->id) {
			JToolBarHelper::title(  JText::_( 'COM_ADMINPRAISE_MENU_ITEM' ) .': <small><small>[ '. JText::_( 'COM_ADMINPRAISE_NEW' ) .' ]</small></small>', 'menu.png' );
		} else {
			JToolBarHelper::title(  JText::_( 'COM_ADMINPRAISE_CHANGE_MENU_ITEM' ), 'menu.png' );
		}

		// Set toolbar items for the page
		JToolBarHelper::cancel('cancel');

		// Add scripts and stylesheets to the document
		$document	= & JFactory::getDocument();

		if($lang->isRTL()){
			$document->addStyleSheet('components/com_adminpraise/assets/type_rtl.css');
		} else {
			$document->addStyleSheet('components/com_menus/assets/type.css');
		}
		JHTML::_('behavior.tooltip');

		// Load component language files
		$dynamicLinks = AdminpraiseMenuHelper::getDynamicLinks();

		// Initialize variables
		$item			= &$this->get('Item');

		$menuTypes 		= AdminpraiseMenuHelper::getMenuTypeList();

		// Set document title
		if ($item->id) {
			$document->setTitle(JText::_( 'COM_ADMINPRAISE_MENU_ITEM' ) .': ['. JText::_( 'COM_ADMINPRAISE_EDIT' ) .']');
		} else {
			$document->setTitle(JText::_( 'COM_ADMINPRAISE_MENU_ITEM' ) .': ['. JText::_( 'COM_ADMINPRAISE_NEW' ) .']');
		}

		$this->assignRef('item',		$item);
		$this->assignRef('links',	$dynamicLinks);

		parent::display($tpl);
	}
	
	public function newMenuType($tpl=null) {
		$item = &$this->get('MenuType');

		JToolBarHelper::save('saveMenuType');
		JToolBarHelper::apply('applyMenuType');
		JToolBarHelper::cancel('cancel');
		
		$this->assignRef('row', $item);
		parent::display($tpl);
	}
	
	public function copyForm($tpl=null) {
		$mainframe = JFactory::getApplication();

		$this->_layout = 'copy';

		/*
		 * Set toolbar items for the page
		 */
		JToolBarHelper::title( JText::_( 'COM_ADMINPRAISE_MENU_ITEMS' ) . ': <small><small>[ '. JText::_( 'COM_ADMINPRAISE_COPY' ) .' ]</small></small>' );
		JToolBarHelper::custom( 'doCopy', 'copy.png', 'copy_f2.png', 'Copy', false );
		JToolBarHelper::cancel('cancelItem');
		JToolBarHelper::help( 'screen.menus' );

		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('COM_ADMINPRAISE_COPY_MENU_ITEMS'));

		$menutype 	= $mainframe->getUserStateFromRequest('com_menus.menutype', 'menutype', 'mainmenu', 'menutype');

		// Build the menutypes select list
		$menuTypes 	= AdminpraiseMenuHelper::getMenuTypes();

		$MenuList = JHTML::_('select.genericlist',   $menuTypes, 'menu', 'class="inputbox" size="10"', 'id', 'title', null );
						
		$items = &$this->get('ItemsFromRequest');

		$this->assignRef('menutype', $menutype);
		$this->assignRef('items', $items);
		$this->assignRef('menutypes', $menuTypes);
		$this->assignRef('MenuList', $MenuList);

		parent::display($tpl);
	}
	
	public function moveForm($tpl=null) {
		$mainframe = JFactory::getApplication();

		$this->_layout = 'move';

		/*
		 * Set toolbar items for the page
		 */
		JToolBarHelper::title( JText::_( 'COM_ADMINPRAISE_MENU_ITEMS' ) . ': <small><small>[ '. JText::_( 'COM_ADMINPRAISE_MOVE' ) .' ]</small></small>' );
		JToolBarHelper::custom( 'doMove', 'move.png', 'move_f2.png', 'Move', false );
		JToolBarHelper::cancel('cancelItem');
		JToolBarHelper::help( 'screen.menus' );

		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('COM_ADMINPRAISE_MOVE_MENU_ITEMS'));

		$menutype 	= $mainframe->getUserStateFromRequest('com_menus.menutype', 'menutype', 'mainmenu', 'menutype');

		// Build the menutypes select list
		$menuTypes 	= AdminpraiseMenuHelper::getMenuTypes();

		$MenuList = JHTML::_('select.genericlist',   $menuTypes, 'menu', 'class="inputbox" size="10"', 'id', 'title', null );
					
		$items = &$this->get('ItemsFromRequest');

		$this->assignRef('menutype', $menutype);
		$this->assignRef('items', $items);
		$this->assignRef('menutypes', $menuTypes);
		$this->assignRef('MenuList', $MenuList);

		parent::display($tpl);
	}
}

?>
