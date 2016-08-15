<?php
/**
 * @package		AdminPraise3
 * @author		AdminPraise http://www.adminpraise.com
 * @copyright	Copyright (c) 2008 - 2012 Pixel Praise LLC. All rights reserved.
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

/**
 * @package     Square One
 * @link        www.squareonecms.org
 * @copyright   Copyright 2011 Square One and Open Source Matters. All Rights Reserved.
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.controlleradmin' );

/**
 * The Menu Item Controller
 *
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @since		1.6
 */
class AdminpraiseControllerAdminitems extends JControllerAdmin
{
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->registerTask('unsetDefault',	'setDefault');

    }


    /**
     * Proxy for getModel
     * @since	1.6
     */
    function getModel($name = 'Adminitem', $prefix = 'AdminpraiseModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    /**
     * Rebuild the nested set tree.
     *
     * @return	bool	False on failure or error, true on success.
     * @since	1.6
     */
    public function rebuild()
    {
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $this->setRedirect('index.php?option=com_adminpraise&view=adminitems');

        // Initialise variables.
        $model = $this->getModel();

        if ($model->rebuild()) {
            // Reorder succeeded.
            $this->setMessage(JText::_('COM_ADMINPRAISE_ITEMS_REBUILD_SUCCESS'));
            return true;
        } else {
            // Rebuild failed.
            $this->setMessage(JText::sprintf('COM_ADMINPRAISE_ITEMS_REBUILD_FAILED'));
            return false;
        }
    }

    public function saveorder()
    {
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Get the arrays from the Request
        $order	= JRequest::getVar('order',	null,	'post',	'array');
        $originalOrder = explode(',', JRequest::getString('original_order_values'));

        // Make sure something has changed
        if (!($order === $originalOrder))
        {
            parent::saveorder();
        }
        else
        {
            // Nothing to reorder
            $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view=adminlist', false));
            return true;
        }
    }

    /**
     * Method to set the home property for a list of items
     *
     * @since	1.6
     */
    function setDefault()
    {
        // Check for request forgeries
        JRequest::checkToken('default') or die(JText::_('JINVALID_TOKEN'));

        // Get items to publish from the request.
        $cid	= JRequest::getVar('cid', array(), '', 'array');
        $data	= array('setDefault' => 1, ' Default' => 0);
        $task 	= $this->getTask();
        $value	= JArrayHelper::getValue($data, $task, 0, 'int');

        if (empty($cid)) {
            JError::raiseWarning(500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
        } else {
            // Get the model.
            $model = $this->getModel();

            // Make sure the item ids are integers
            JArrayHelper::toInteger($cid);

            // Publish the items.
            if (!$model->setHome($cid, $value)) {
                JError::raiseWarning(500, $model->getError());
            } else {
                if ($value == 1) {
                    $ntext = 'COM_ADMINPRAISE_ITEMS_SET_HOME';
                }
                else {
                    $ntext = 'COM_ADMINPRAISE_ITEMS_UNSET_HOME';
                }
                $this->setMessage(JText::plural($ntext, count($cid)));
            }
        }

        $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view=adminitems', false));
    }


    /**
     * @param bool $drop - set to true if we are updating
     * @return bool
     */
    public function reset($drop = false) {
        jimport('joomla.installer.helper');
        jimport('joomla.application.module.helper');
        jimport('joomla.installer.installer');
        require_once(JPATH_COMPONENT_ADMINISTRATOR .'/assets/menu/defaultMenu.php');
        $moduleInstalled = false;
        $appl = JFactory::getApplication();
        $menu = new adminpraiseDefaultMenu();
        $menu->deleteAdminPraiseMenu();
        if($drop) {
            $menu->updateTo20();
        }
        $menu->addMenuData();
        $menu->syncJoomlaToAdminpraise();
        $menu->rebuild();

        $db = JFactory::getDBO();

        $adminpraise_menu = JModuleHelper::getModule('adminpraise_menu');

        //check if the mod_adminpraise_menu is installed and if not install it
        if (!is_object($adminpraise_menu) || $drop) {

            $this->installer = new JInstaller;

            $menuModulePath = JPATH_COMPONENT_ADMINISTRATOR.'/assets/packages/modules/administrator/modules/mod_adminpraise_menu';

            if ($this->installer->install($menuModulePath)) {
                $moduleInstalled = true;
                $appl->enqueueMessage(JText::_('COM_ADMINPRAISE_MODULE_MENU_SUCCESS_INSTALLATION'));
            } else {
                $appl->enqueueMessage(JText::_('COM_ADMINPRAISE_MODULE_MENU_INSTALLATION_FAILURE'));
            }

        } else {
            $moduleInstalled = true;
        }

        if ($moduleInstalled == true) {

            // Setting the path for the module
            $menuModulePath = JPATH_ADMINISTRATOR.'/modules/mod_adminpraise_menu/mod_adminpraise_menu.xml';

            // Getting the menutypes
            $query = 'SELECT id, title FROM ' . $db->quoteName('#__adminpraise_menu_types');
            $db->setQuery($query);
            $menuTypes = $db->loadObjectList('title');

            /*
          * Insert Adminpraise Panel Menu
                */
            $data = array();
            $data['menutype'] = 'panel';
            $data['moduleid_css'] = 'ap-menu-panel';

            $params = json_encode($data);

            $this->_insertAPMenu('Adminpraise Panel Menu', 'adminpraise_panel', $params);

            /*
          * Insert Adminpraise Tools Menu
                */
            $data = array();
            $data['menutype'] = 'tools';
            $data['moduleid_css'] = 'ap-menu-tools';

            $params = json_encode($data);

            $this->_insertAPMenu('Adminpraise Tools Menu', 'adminpraise_tools', $params);

            /*
          * Insert Adminpraise Top Menu
                */
            $data = array();
            $data['menutype'] = 'main';
            $data['moduleid_css'] = 'ap-menu-top';

            $params = json_encode($data);

            $this->_insertAPMenu('Adminpraise Top Menu', 'adminpraise_menu', $params);

            return true;
        } else {
            return false;
        }
    }

    public function _insertAPMenu($title, $position, $params) {

        $db = JFactory::getDBO();

        $query = 'SELECT id FROM' . $db->quoteName('#__modules')
            . ' WHERE module=' . $db->Quote('mod_adminpraise_menu')
            . ' AND position=' . $db->Quote($position);
        $db->setQuery($query, 0, 1);
        $id = $db->loadResult();

        if ($id) {
            $query = 'UPDATE ' . $db->quoteName('#__modules')
                . ' SET params = ' . $db->Quote($params) . ','
                . ' published=' . $db->Quote(1)
                . ' WHERE module=' . $db->Quote('mod_adminpraise_menu')
                . ' AND position=' . $db->Quote($position);
            $db->setQuery($query);
            if (!$db->query()) {
                JError::raiseWarning( 500, $db->errorMsg() );
            }

        } else {
            $query = 'INSERT INTO ' . $db->quoteName('#__modules')
                . ' (title, position, published, module, access, params, client_id, language)'
                . ' VALUES ('
                . $db->Quote($title) . ','
                . $db->Quote($position) . ','
                . $db->Quote(1) . ','
                . $db->Quote('mod_adminpraise_menu') . ','
                . $db->Quote(1) . ','
                . $db->Quote($params) . ','
                . $db->Quote(1) . ','
                . $db->Quote('*')
                . ')';
            $db->setQuery($query);
            if (!$db->query()) {
                JError::raiseWarning( 500, $db->errorMsg() );
            }

            $lastid = $db->insertid();

            $query = 'INSERT INTO ' . $db->quoteName('#__modules_menu')
                . ' (moduleid, menuid)'
                . ' VALUES ('
                . $lastid . ', 0)';

            $db->setQuery($query);
            if (!$db->query()) {
                JError::raiseWarning( 500, $db->errorMsg() );
            }

        }
    }
}
