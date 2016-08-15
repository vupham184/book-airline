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

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Adminpraise extension plugin.
 *
 */
class plgExtensionAdminpraise extends JPlugin
{
    /**
     * @var        integer Extension Identifier
     * @since    1.6
     */
    private $eid = 0;

    /**
     * @var        JInstaller Installer object
     * @since    1.6
     */
    private $installer = null;

    private $adminpraiseInstalled = false;

    private static $stopSync = false;

    /**
     * Constructor
     *
     * @access      protected
     * @param       object  $subject The object to observe
     * @param       array   $config  An array that holds the plugin configuration
     * @since       1.5
     */
    public function __construct(& $subject, $config)
    {

        parent::__construct($subject, $config);
        $this->loadLanguage();
        $this->loadLanguage('com_adminpraise');

        $menu = JPATH_ADMINISTRATOR . '/components/com_adminpraise/assets/menu/defaultMenu.php';
        if(JFile::exists($menu)) {
            require_once($menu);
            $this->menu = new adminpraiseDefaultMenu;
            $this->adminpraiseInstalled = true;
        }
    }

    /**
     * Called before uninstall
     *
     * @param    string    the extension identifier
     */
    public function onExtensionBeforeUninstall($eid)
    {
        $db = JFactory::getDBO();
        $query = 'SELECT element FROM ' . $db->quoteName('#__extensions')
            . ' WHERE extension_id = ' . $db->Quote($eid);
        $db->setQuery($query, 0, 1);

        $extension = $db->loadObject();

        if ($extension->element == 'com_adminpraise') {
            $query = 'SELECT home FROM ' . $db->quoteName('#__template_styles')
                . ' WHERE template = ' . $db->Quote('adminpraise3');
            $db->setQuery($query, 0, 1);

            $template = $db->loadObject();

            if (is_object($template)) {
                if ($template->home == 1) {
                    $appl = JFactory::getApplication();
                    $appl->enqueueMessage(JText::_('COM_ADMINPRAISE_CHANGE_TEMPLATE_FIRST'));
                    $appl->enqueueMessage(JText::_('COM_ADMINPRAISE_UNINSTALL_FAILED'));
                    $appl->redirect('index.php?option=com_installer&view=manage');
                    return false;
                }
            }

            self::$stopSync = true;
        }
        return false;

    }

    public function onExtensionAfterInstall($installer, $eid)
    {
        if($this->adminpraiseInstalled && !self::$stopSync) {
            //      let us first clean up our tables
//	    there some extensions that delete stuff from jos_menus on installation
//	    we need to be prepared for this
            $this->menu->syncAdminpraiseToJoomla();
//          now let us check what joomla has for us
            $this->menu->syncJoomlaToAdminpraise();

            $this->menu->rebuild();
        }

    }

    public function onExtensionAfterUninstall($installer, $eid, $result)
    {

        if($this->adminpraiseInstalled && !self::$stopSync) {
            $this->menu->syncAdminpraiseToJoomla();
//          now let us check what joomla has for us
            $this->menu->rebuild();
        }

    }


}
