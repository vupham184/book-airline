<?php
/**
 * @author Daniel Dimitrov - compojoom.com
 * @date: 08.06.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
class AdminpraiseCompatibilityActivitylog
{
    public function check()
    {
        // Init
        $db =& JFactory::getDBO();
        $appl = JFactory::getApplication();
        $installer = & JInstaller::getInstance();

        /**
         * Uninstall activityLogPlugin
         */
        $query = 'SELECT * FROM ' . $db->quoteName('#__extensions')
            . ' WHERE element = ' . $db->Quote('activitylog_pro');
        $db->setQuery($query);

        $plugins = $db->loadObjectList();

        if (count($plugins)) {
            foreach ($plugins as $key => $value) {
                $status = $installer->uninstall('plugin', $value->extension_id, 1);
            }
            if ($status) {
                return array('status' => true, 'message' => 'uninstalling activitylog success');
            }
            return array('status' => false, 'message' => 'Something went wrong. Could not uninstall activitylog');
        } else {
            return array('status' => true, 'message' => 'Plugin was not found. No actions taken');
        }


    }
}
