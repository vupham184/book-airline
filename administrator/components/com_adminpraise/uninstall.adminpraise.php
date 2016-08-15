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

class adminPraiseUninstallation {

	private $db;

	public function __construct() {
		$this->db = JFactory::getDBO();
	}

	public function dropTables() {
		$db = JFactory::getDBO();
		$appl = JFactory::getApplication();

		$query = 'DROP TABLE ' . $db->quoteName('#__adminpraise_menu');
		$db->setQuery($query);
		if ($db->query()) {
			$appl->enqueueMessage(JText::sprintf('COM_ADMINPRAISE_DROPED_TABLE', $db->replacePrefix('#__adminpraise_menu')));
		} else {
			$appl->enqueueMessage(JText::sprintf('COM_ADMINPRAISE_DROPED_TABLE_FAILURE', $db->replacePrefix('#__adminpraise_menu')));
		}
		$query = 'DROP TABLE ' . $db->quoteName('#__adminpraise_menu_types');
		$db->setQuery($query);
		if ($db->query()) {
			$appl->enqueueMessage(JText::sprintf('COM_ADMINPRAISE_DROPED_TABLE', $db->replacePrefix('#__adminpraise_menu_types')));
		} else {
			$appl->enqueueMessage(JText::sprintf('COM_ADMINPRAISE_DROPED_TABLE_FAILURE', $db->replacePrefix('#__adminpraise_menu_types')));
		}
	}

	public function uninstallTemplate() {

		$query = 'SELECT extension_id FROM ' . $this->db->quoteName('#__extensions')
				. ' WHERE element = ' . $this->db->Quote('adminpraise3');
		$this->db->setQuery($query);
		$template_id = $this->db->loadResult();

		$installer = new JInstaller;
		return $installer->uninstall('template', $template_id);
	}

	public function uninstallModules() {
		$uninstallStatus = array();

	  /**
	  * Uninstall cpanelModule
	  */
		$installer = new JInstaller;
		$query = 'SELECT extension_id FROM ' . $this->db->quoteName('#__extensions')
				. ' WHERE element = ' . $this->db->Quote('mod_adminpraise_cpanel');
		$this->db->setQuery($query);
		$module_id = $this->db->loadResult();

		if (count($module_id)) {
			$uninstallStatus[] = $installer->uninstall('module', $module_id);
		}

	  /**
	  * Uninstall activityLogModule
	  */
		$installer = new JInstaller;
		$query = 'SELECT extension_id FROM ' . $this->db->quoteName('#__extensions')
				. ' WHERE element = ' . $this->db->Quote('mod_activitylog_pro');
		$this->db->setQuery($query);
		$module_id = $this->db->loadResult();

		if (count($module_id)) {
			$uninstallStatus[] = $installer->uninstall('module', $module_id);
		}

	  /**
	  * Uninstall quickitemModule
	  */
		$installer = new JInstaller;
		$query = 'SELECT extension_id FROM ' . $this->db->quoteName('#__extensions')
				. ' WHERE element = ' . $this->db->Quote('mod_quickitem_pro');
		$this->db->setQuery($query);
		$module_id = $this->db->loadResult();

		if (count($module_id)) {
			$uninstallStatus[] = $installer->uninstall('module', $module_id);
		}

	  /**
	  * Uninstall menuModule
	  */
		$installer = new JInstaller;
		$query = 'SELECT extension_id FROM ' . $this->db->quoteName('#__extensions')
				. ' WHERE element = ' . $this->db->Quote('mod_adminpraise_menu');
		$this->db->setQuery($query);
		$module_id = $this->db->loadResult();

		if (count($module_id)) {
			$uninstallStatus[] = $installer->uninstall('module', $module_id);
		}
		
		/**
	  * Uninstall spotlightModule
	  */
		$installer = new JInstaller;
		$query = 'SELECT extension_id FROM ' . $this->db->quoteName('#__extensions')
				. ' WHERE element = ' . $this->db->Quote('mod_adminpraise_spotlight');
		$this->db->setQuery($query);
		$module_id = $this->db->loadResult();

		if (count($module_id)) {
			$uninstallStatus[] = $installer->uninstall('module', $module_id);
		}

        /**
         * Uninstall mod_myeditor
         */
        $installer = new JInstaller;
        $query = 'SELECT extension_id FROM ' . $this->db->quoteName('#__extensions')
            . ' WHERE element = ' . $this->db->Quote('mod_myeditor');
        $this->db->setQuery($query);
        $module_id = $this->db->loadResult();

        if (count($module_id)) {
            $uninstallStatus[] = $installer->uninstall('module', $module_id);
        }

		return $uninstallStatus;
	}

	public function uninstallPlugins() {
		$uninstallStatus = array();

        /**
         * Uninstall extension - adminpraise
         */
        $installer = new JInstaller;
        $query = 'SELECT extension_id FROM ' . $this->db->quoteName('#__extensions')
            . ' WHERE element = ' . $this->db->Quote('adminpraise')
            . ' AND folder = ' . $this->db->Quote('extension');
        $this->db->setQuery($query);

        $plugins = $this->db->loadResult();

        if (count($plugins)) {
            $uninstallStatus[] = $installer->uninstall('plugin', $plugins);
        }

	  /**
	  * Uninstall activityLogPlugin
	  */
		$installer = new JInstaller;
		$query = 'SELECT extension_id FROM ' . $this->db->quoteName('#__extensions')
				. ' WHERE element = ' . $this->db->Quote('activitylogpro');
		$this->db->setQuery($query);

		$plugins = $this->db->loadResult();

		if (count($plugins)) {
			$uninstallStatus[] = $installer->uninstall('plugin', $plugins);
		}

	  /**
	  * Uninstall mcePlugin
	  */
		$installer = new JInstaller;
		$query = 'SELECT extension_id FROM ' . $this->db->quoteName('#__extensions')
				. ' WHERE element = ' . $this->db->Quote('sce');
		$this->db->setQuery($query);

		$plugins = $this->db->loadResult();

		if (count($plugins)) {
			$uninstallStatus[] = $installer->uninstall('plugin', $plugins);
		}

	  /**
	  * Uninstall autoeditorPlugin
	  */
		$installer = new JInstaller;
		$query = 'SELECT extension_id FROM ' . $this->db->quoteName('#__extensions')
				. ' WHERE element = ' . $this->db->Quote('adminpraiseautoeditor');
		$this->db->setQuery($query);

		$plugins = $this->db->loadResult();

		if (count($plugins)) {
			$uninstallStatus[] = $installer->uninstall('plugin', $plugins);
		}
		
		/**
	  * Uninstall searchContentPlugin
	  */
		$installer = new JInstaller;
		$query = 'SELECT extension_id FROM ' . $this->db->quoteName('#__extensions')
				. ' WHERE element = ' . $this->db->Quote('content')
				. ' AND folder = ' . $this->db->Quote('adminpraisesearch');
		$this->db->setQuery($query);

		$plugins = $this->db->loadResult();

		if (count($plugins)) {
			$uninstallStatus[] = $installer->uninstall('plugin', $plugins);
		}
		
		/**
	  * Uninstall searchMenuPlugin
	  */
		$installer = new JInstaller;
		$query = 'SELECT extension_id FROM ' . $this->db->quoteName('#__extensions')
				. ' WHERE element = ' . $this->db->Quote('menu')
				. ' AND folder = ' . $this->db->Quote('adminpraisesearch');
		$this->db->setQuery($query);

		$plugins = $this->db->loadResult();

		if (count($plugins)) {
			$uninstallStatus[] = $installer->uninstall('plugin', $plugins);
		}
		
		/**
	  * Uninstall searchAdminMenuPlugin
	  */
		$installer = new JInstaller;
		$query = 'SELECT extension_id FROM ' . $this->db->quoteName('#__extensions')
				. ' WHERE element = ' . $this->db->Quote('adminmenu')
				. ' AND folder = ' . $this->db->Quote('adminpraisesearch');
		$this->db->setQuery($query);

		$plugins = $this->db->loadResult();

		if (count($plugins)) {
			$uninstallStatus[] = $installer->uninstall('plugin', $plugins);
		}
		


		return $uninstallStatus;
	}

}