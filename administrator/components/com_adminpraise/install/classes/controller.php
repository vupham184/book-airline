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
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

/**
 * The Live Update MVC controller
 */
class AdminpraiseInstallController extends JController
{
    private $jversion = '15';

    /**
     * Object contructor
     * @param array $config
     *
     * @return AdminpraiseInstallController
     */
    public function __construct($config = array())
    {
        parent::__construct();

        // Do we have Joomla! 1.6?
        if (version_compare(JVERSION, '1.6.0', 'ge')) {
            $this->jversion = '16';
        }

        $basePath = dirname(__FILE__);
        $this->basePath = $basePath;

        jimport('joomla.installer.installer');

    }


    /**
     * Displays the current view
     * @param bool $cachable Ignored!
     */
    public final function display($cachable = false)
    {
        $viewLayout = JRequest::getCmd('layout', 'default');

        $view = $this->getThisView();

        // Get/Create the model
        $model = $this->getThisModel();
        $view->setModel($model, true);

        // Set the layout
        $view->setLayout($viewLayout);

        // Display the view
        $view->display();
    }

    public final function getThisView()
    {
        static $view = null;

        if (is_null($view)) {
            $basePath = $this->basePath;
            $tPath = dirname(__FILE__) . '/tmpl';

            require_once('view.php');
            $view = new AdminpraiseInstallView(array('base_path' => $basePath, 'template_path' => $tPath));
        }

        return $view;
    }

    public final function getThisModel()
    {
        static $model = null;

        if (is_null($model)) {
            require_once('model.php');
            $model = new AdminpraiseInstallModel();
            $task = $this->task;

            $model->setState('task', $task);

            $app = JFactory::getApplication();
            $menu = $app->getMenu();
            if (is_object($menu)) {
                if ($item = $menu->getActive()) {
                    $params =& $menu->getParams($item->id);
                    // Set Default State Data
                    $model->setState('parameters.menu', $params);
                }
            }

        }

        return $model;
    }

    /**
     * Requirements
     *
     * @return    none
     * @since    1.0.0
     */
    function requirements()
    {
        $model = $this->getThisModel();

        $rq = $model->determineRequirements();

        // Init requirements array
        $error = array();

        /**
         * Compare the PHP version
         */
        if (version_compare($rq['phpMust'], $rq['phpIs'], '<')) {
            $error['php5'] = true;
        } else {
            $error['php5'] = false;
        }

        /**
         * Compare the MYSQL version
         */
        if (version_compare($rq['mysqlMust'], $rq['mysqlIs'])) {
            $error['mysql'] = true;
        } else {
            $error['mysql'] = false;
        }

        /**
         * Check if fopen is enabled
         */
        if (function_exists('fopen')) {
            $error['fopen'] = true;
        } else {
            $error['fopen'] = false;
        }

        /**
         * Check if fopen is enabled for remote connections
         */
        if (ini_get('allow_url_fopen')) {
            $error['allow_url_fopen'] = true;
        } else {
            $error['allow_url_fopen'] = false;
        }

        $return = json_encode($error);
        echo $return;

    } //end function

    /**
     * Compatibility
     *
     * @return    none
     * @since    1.0.0
     */
    public function compatibility()
    {
        $folder = JPATH_COMPONENT_ADMINISTRATOR . '/install/classes/compatibility';

        JLoader::discover('AdminpraiseCompatibility', $folder);

        $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'index.html');
        $files = JFolder::files($folder, '.', false, false, $exclude);

        $needToAbort = false;

        foreach ($files as $file) {
            $className = 'AdminpraiseCompatibility' . ucfirst(str_replace('.php', '', $file));
            $class = new $className;
            $status[] = $class->check();
        }

        foreach ($status as $value) {
            if ($value['status'] == false) {
                $messages[] = $value['message'];
                $needToAbort = true;
            } else {
                $messages[] = $value['message'];
            }
        }

        echo json_encode(array('status' => $needToAbort, 'message' => implode('\n', $messages)));

        jexit();

    } //end function

    /**
     * Install plugins
     *
     * @return    none
     * @since    1.0.0
     */
    function plugins()
    {
        $plugins = array(
            'plg_adminpraisesearch_adminmenu' => 1,
            'plg_adminpraisesearch_content' => 1,
            'plg_adminpraisesearch_menu' => 1,
            'plg_editors_sce' => 1,
            'plg_system_activitylogpro' => 1,
            'plg_system_adminpraiseautoeditor' => 1,
            'plg_extension_adminpraise' => 1,
        );
        $src = JPATH_COMPONENT_ADMINISTRATOR . '/assets/packages';

        $db = JFactory::getDbo();
        $status = array();

        foreach ($plugins as $plugin => $published) {
            $parts = explode('_', $plugin);
            $pluginType = $parts[1];
            $pluginName = $parts[2];

            $path = $src . "/plugins/$pluginType/$pluginName";

            $query = "SELECT COUNT(*) FROM  #__extensions WHERE element=" . $db->Quote($pluginName) . " AND folder=" . $db->Quote($pluginType);

            $db->setQuery($query);
            $count = $db->loadResult();

            $installer = new JInstaller;
            $result = $installer->install($path);
            $status[] = array('name' => $plugin, 'group' => $pluginType, 'result' => $result);

            if ($published && !$count) {
                $query = "UPDATE #__extensions SET enabled=1 WHERE element=" . $db->Quote($pluginName) . " AND folder=" . $db->Quote($pluginType);
                $db->setQuery($query);
                $db->query();
            }
        }

        $translations = array(
            'plg_adminpraisesearch_adminmenu' => array(
                'success' => 'COM_ADMINPRAISE_PLG_ADMINMENUSEARCH_SUCCESS_INSTALLATION',
                'failure' => 'COM_ADMINPRAISE_PLG_MENUSEARCH_ERROR_INSTALLATION'
            ),
            'plg_adminpraisesearch_content' => array(
                'success' => 'COM_ADMINPRAISE_PLG_CONTENTSEARCH_SUCCESS_INSTALLATION',
                'failure' => 'COM_ADMINPRAISE_PLG_CONTENTSEARCH_ERROR_INSTALLATION'
            ),
            'plg_adminpraisesearch_menu' => array(
                'success' => 'COM_ADMINPRAISE_PLG_MENUSEARCH_SUCCESS_INSTALLATION',
                'failure' => 'COM_ADMINPRAISE_PLG_MENUSEARCH_ERROR_INSTALLATION'
            ),
            'plg_editors_sce' => array(
                'success' => 'COM_ADMINPRAISE_PLG_SCE_SUCCESS_INSTALLATION',
                'failure' => 'COM_ADMINPRAISE_PLG_SCE_ERROR_INSTALLATION'
            ),
            'plg_system_activitylogpro' => array(
                'success' => 'COM_ADMINPRAISE_PLG_ACTIVITY_LOG_SUCCESS_INSTALLATION',
                'failure' => 'COM_ADMINPRAISE_PLG_ACTIVITY_LOG_ERROR_INSTALLATION'
            ),
            'plg_adminpraise_autoeditor' => array(
                'success' => 'COM_ADMINPRAISE_PLG_AUTOEDITOR_SUCCESS_INSTALLATION',
                'failure' => 'COM_ADMINPRAISE_PLG_AUTOEDITOR_ERROR_INSTALLATION'
            ),
            'plg_extension_adminpraise' => array(
                'success' => 'COM_ADMINPRAISE_PLG_EXTENSION_ADMINPRAISE_SUCCESS_INSTALLATION',
                'failure' => 'COM_ADMINPRAISE_PLG_EXTENSION_ADMINPRAISE_ERROR_INSTALLATION'
            ),
        );
        foreach ($status as $value) {
            echo "<div class='step" . ($value['result'] ? '' : '_false') . "'>";
            if ($value['result']) {
                echo JText::_($translations[$value['name']]['success']);
            } else {
                echo JText::_($translations[$value['name']]['failure']);
            }

            echo '</div>';
        }

    } //end function

    /**
     * Install modules
     *
     * @return    none
     * @since    1.0.0
     */
    function modules()
    {
        require_once(JPATH_COMPONENT . '/controllers/adminitems.php');
        $db = JFactory::getDbo();
        /**
         * Install menu system
         */
        $query = 'SELECT count(id) as count FROM ' . $db->quoteName('#__adminpraise_menu');
        $db->setQuery($query);

        if (!$db->loadObject()->count) {
            $firstTimeInstall = true;

            $menu = new AdminpraiseControllerAdminitems();
            $menu->reset();
        } else {

            $query = 'SELECT count(type) as count FROM ' . $db->quoteName('#__adminpraise_menu')
                . ' WHERE type ="dynamic"';
            $db->setQuery($query);

            // need to update
            if ($db->loadObject()->count) {
                $menu = new AdminpraiseControllerAdminitems();
                $menu->reset(true);
            }
        }

        $modulesToInstall = array(
            'admin' => array(
                'mod_activitylog_pro' => array('cpanel-top', 1),
                'mod_adminpraise_cpanel' => array('adminpraise_cpanel_main', 1),
                'mod_adminpraise_spotlight' => array('adminpraise_search', 1),
                'mod_myeditor' => array('adminpraise_search', 1),
                'mod_quickitem_pro' => array('cpanel-top', 1)

            )
        );
        $src = JPATH_COMPONENT_ADMINISTRATOR . '/assets/packages/modules';
        $status = array();
        // Modules installation
        if (count($modulesToInstall)) {
            foreach ($modulesToInstall as $folder => $modules) {

                if (count($modules)) {
                    foreach ($modules as $module => $modulePreferences) {
                        // Install the module
                        if (empty($folder)) {
                            $folder = 'site';
                        }

                        $path = "$src/$module";
                        if ($folder == 'admin') {
                            $path = "$src/administrator/modules/$module";
                        }
                        if (!is_dir($path)) {
                            continue;
                        }
                        $db = JFactory::getDbo();
                        // Was the module alrady installed?
                        $sql = 'SELECT COUNT(*) FROM #__modules WHERE `module`=' . $db->Quote($module);
                        $db->setQuery($sql);
                        $count = $db->loadResult();
                        $installer = new JInstaller;
                        $result = $installer->install($path);
                        $status[] = array('name' => $module, 'client' => $folder, 'result' => $result);
                        // Modify where it's published and its published state
                        if (!$count) {
                            list($modulePosition, $modulePublished) = $modulePreferences;
                            $sql = "UPDATE #__modules SET position=" . $db->Quote($modulePosition);
                            if ($modulePublished) $sql .= ', published=1';
                            $sql .= ', params = ' . $db->quote($installer->getParams());
                            $sql .= ' WHERE `module`=' . $db->Quote($module);
                            $db->setQuery($sql);
                            $db->query();

//	                        get module id
                            $db->setQuery('SELECT id FROM #__modules WHERE module = ' . $db->quote($module));
                            $moduleId = $db->loadObject()->id;

                            // insert the module on all pages, otherwise we can't use it
                            $query = 'INSERT INTO #__modules_menu(moduleid, menuid) VALUES (' . $db->quote($moduleId) . ' ,0 );';
                            $db->setQuery($query);

                            $db->query();
                        }
                    }
                }
            }
        }

        $translations = array(
            'mod_activitylog_pro' => array(
                'success' => 'COM_ADMINPRAISE_MODULE_ACTIVITYLOG_SUCCESS_INSTALLATION',
                'failure' => 'COM_ADMINPRAISE_MODULE_ACTIVITYLOG_ERROR_INSTALLATION'
            ),
            'mod_adminpraise_cpanel' => array(
                'success' => 'COM_ADMINPRAISE_MODULE_CPANEL_SUCCESS_INSTALLATION',
                'failure' => 'COM_ADMINPRAISE_MODULE_CPANEL_ERROR_INSTALLATION'
            ),
            'mod_adminpraise_spotlight' => array(
                'success' => 'COM_ADMINPRAISE_MODULE_SPOTLIGHT_SUCCESS_INSTALLATION',
                'failure' => 'COM_ADMINPRAISE_MODULE_SPOTLIGHT_ERROR_INSTALLATION'
            ),
            'mod_myeditor' => array(
                'success' => 'COM_ADMINPRAISE_MODULE_MYEDITOR_SUCCESS_INSTALLATION',
                'failure' => 'COM_ADMINPRAISE_MODULE_MYEDITOR_ERROR_INSTALLATION'
            ),
            'mod_quickitem_pro' => array(
                'success' => 'COM_ADMINPRAISE_MODULE_QUICKITEM_SUCCESS_INSTALLATION',
                'failure' => 'COM_ADMINPRAISE_MODULE_QUICKITEM_ERROR_INSTALLATION'
            )
        );

        foreach ($status as $value) {
            echo "<div class='step" . ($value['result'] ? '' : '_false') . "'>";
            if ($value['result']) {
                echo JText::_($translations[$value['name']]['success']);
            } else {
                echo JText::_($translations[$value['name']]['failure']);
            }

            echo '</div>';
        }

    } //end function

    /**
     * Install template
     *
     * @return    none
     * @since    1.0.0
     */
    function template()
    {
        /**
         * Initialize
         */
        $installer = new JInstaller;
        $error = array();

        /**
         * Install adminpraiseTemplate
         */
        $adminpraiseTemplate = JPATH_COMPONENT_ADMINISTRATOR . '/assets/packages/templates/administrator/templates/adminpraise3';

        if ($installer->install($adminpraiseTemplate)) {
            echo "<div class='step'>" . JText::_('COM_ADMINPRAISE_TEMPLATE_SUCCESS_INSTALLATION') . "</div>";
        } else {
            echo "<div class='step_false'>" . JText::_('COM_ADMINPRAISE_TEMPLATE_ERROR_INSTALLATION') . "</div>";
            $error['activitylog'] = false;
        }

    } //end function

    /**
     * Install done
     *
     * @return    none
     * @since    1.0.0
     */
    function done()
    {
        jimport('joomla.filesystem.file');
        JFile::delete(JPATH_COMPONENT . DS . 'installer.dummy.ini');
    }
}