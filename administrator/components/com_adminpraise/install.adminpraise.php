<?php
/**
 * @package        AdminPraise3
 * @author        AdminPraise http://www.adminpraise.com
 * @copyright    Copyright (c) 2008 - 2011 Pixel Praise LLC. All rights reserved.
 * @license        GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


/**
 * Script file of HelloWorld component
 */
class com_adminpraiseInstallerScript
{
    private $componentName = 'com_adminpraise';
    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent)
    {
        $this->realInstall($parent);
    }

    function realInstall($parent)
    {
        // Import filesystem libraries
        jimport('joomla.filesystem.file');
        $buffer = 'installing';
        // Create the install.dummy file
        $file = JFile::write(JPATH_ADMINISTRATOR . '/components/com_adminpraise/installer.dummy.ini', $buffer);
        $componentPath = JPATH_ADMINISTRATOR . '/components/com_adminpraise/';
        $path = $parent->getParent()->getPath('source');

        JFolder::copy($path . '/administrator/modules/', $componentPath . '/assets/packages/modules/administrator/modules', '', true);
        JFolder::copy($path . '/plugins/', $componentPath . '/assets/packages/plugins', '', true);
        JFolder::copy($path . '/administrator/templates/', $componentPath . '/assets/packages/templates/administrator/templates', '', true);
    }

    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent)
    {
        require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_adminpraise' . DS . 'uninstall.adminpraise.php');

        $appl = JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $lang->load('com_adminpraise', JPATH_BASE, null, true);

        $uninstaller = new adminPraiseUninstallation();

//      uninstall plugins first because of the Extension - adminpraise and the adminpraise menu
        $plugins = $uninstaller->uninstallPlugins();
        if (!in_array(false, $plugins)) {
            $appl->enqueueMessage(JText::_('COM_ADMINPRAISE_UNINSTALL_PLUGINS_SUCCESS'));
            $plugins = true;
        } else {
            $appl->enqueueMessage(JText::_('COM_ADMINPRAISE_UNINSTALL_PLUGINS_FAILURE'));
            $plugins = false;
        }

        $uninstaller->dropTables();

        $template = $uninstaller->uninstallTemplate();

        if ($template) {
            $appl->enqueueMessage(JText::_('COM_ADMINPRAISE_UNINSTALL_TEMPLATE_SUCCESS'));
        } else {
            $appl->enqueueMessage(JText::_('COM_ADMINPRAISE_UNINSTALL_TEMPLATE_FAILURE'));
        }

        $modules = $uninstaller->uninstallModules();
        if (!in_array(false, $modules)) {
            $appl->enqueueMessage(JText::_('COM_ADMINPRAISE_UNINSTALL_MODULES_SUCCESS'));
            $modules = true;
        } else {
            $appl->enqueueMessage(JText::_('COM_ADMINPRAISE_UNINSTALL_MODULES_FAILURE'));
            $modules = false;
        }


        if ($template && $modules && $plugins) {
            return true;
        }

        return false;
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent)
    {
        $this->realInstall($parent);
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @param $type
     * @param $parent
     * @return void
     */
    public function preflight($type, $parent)
    {
        $path = $parent->getParent()->getPath('source');
        $language = JFactory::getLanguage();
        $language->load('com_adminpraise', $path.'/administrator/', 'en-GB', true);
        $language->load('com_adminpraise', $path.'/administrator/', $language->getDefault(), true);
        $language->load('com_adminpraise', $path.'/administrator/', null, true);

        $jversion = new JVersion();

        // Manifest file minimum Joomla version
        $this->minJoomlaRelease = $parent->get("manifest")->attributes()->version;

        // abort if the current Joomla release is older
        if (version_compare($jversion->getShortVersion(), $this->minJoomlaRelease, 'lt')) {
            Jerror::raiseWarning(null, JText::sprintf('COM_ADMINPRAISE_CANNOT_INSTALL_ON_VERSIONS_PRIOR_TO', $this->minJoomlaRelease));
            return false;
        }

        return true;
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent)
    {
        $appl = JFactory::getApplication();
        $appl->redirect('index.php?option=com_adminpraise');
    }

    /**
     * get a variable from the manifest file (actually, from the manifest cache).
     */
    public function getParam($name)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('manifest_cache')->from('#__extensions')
            ->where('name = ' . $db->quote('com_adminpraise'));
        $manifest = json_decode($db->loadResult(), true);
        return $manifest[$name];
    }
}