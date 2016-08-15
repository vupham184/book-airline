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
jimport('joomla.database.table');
jimport('joomla.error.log');

class adminpraiseDefaultMenu
{

    public function deleteAdminPraiseMenu()
    {
        //$this->log->addEntry(array('comment' => 'Deleting AdminPraise Menu Type and Items ' ));
        $this->executeQuery("DELETE FROM #__adminpraise_menu");
        $this->executeQuery("DELETE FROM #__adminpraise_menu_types");
    }

    public function executeQuery($query)
    {
        $db = JFactory::getDBO();
        $db->setQuery($query);
        $db->query();
    }

    /**
     * if we are updating we need to update the table structure
     */
    public function updateTo20()
    {

        // now let us create the new tables
        $db = JFactory::getDbo();

        $sqlFile = JPATH_COMPONENT_ADMINISTRATOR . '/sql/updates/2.0.sql';
        $buffer = file_get_contents($sqlFile);

        // Graceful exit and rollback if read not successful
        if ($buffer === false) {
            JError::raiseWarning(1, JText::_('JLIB_INSTALLER_ERROR_SQL_READBUFFER'));
        }

        // Create an array of queries from the sql file
        $queries = $db->splitSql($buffer);

        if (count($queries) == 0) {
            // No queries to process
            return 0;
        }

        // Process each query in the $queries array (split out of sql file).
        foreach ($queries as $query) {
            $query = trim($query);

            if ($query != '' && $query{0} != '#') {
                $db->setQuery($query);

                if (!$db->query()) {
                    JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));

                    return false;
                }
            }
        }

        return true;
    }

    public function addMenuData()
    {
        $db = JFactory::getDbo();

        $sqlFile = JPATH_COMPONENT_ADMINISTRATOR . '/sql/menu.sql';
        $buffer = file_get_contents($sqlFile);

        // Graceful exit and rollback if read not successful
        if ($buffer === false) {
            JError::raiseWarning(1, JText::_('JLIB_INSTALLER_ERROR_SQL_READBUFFER'));
        }

        // Create an array of queries from the sql file
        $queries = $db->splitSql($buffer);

        if (count($queries) == 0) {
            // No queries to process
            return 0;
        }

        // Process each query in the $queries array (split out of sql file).
        foreach ($queries as $query) {
            $query = trim($query);
            $query = str_replace('##PREVIEW_SITE##', JURI::root(), $query);
            $query = str_replace('##CONTROL_PANEL##', JURI::base(), $query);

            if ($query != '' && $query{0} != '#') {
                $db->setQuery($query);

                if (!$db->query()) {
                    JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * rebuild structure - lft, rgt, level etc
     */
    public function rebuild()
    {
        JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_adminpraise/tables');
        $table = JTable::getInstance('AdminpraiseMenu', 'AdminpraiseTable');
        $table->rebuild();
    }

    /**
     *
     */
    public function syncJoomlaToAdminpraise()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        // get all the component menus
        $query->select('m.*, e.element as component_name')->from('#__menu AS m')
            ->leftJoin('#__extensions as e ON m.component_id = e.extension_id')
            ->where('(m.menutype = ' . $db->quote('main') . ' OR menutype = ' . $db->quote('menu') . ')')
            ->where('m.client_id = 1');
        $db->setQuery($query);

        $jItems = $db->loadObjectList('id');

        $query = $db->getQuery(true);
        $query->select('*')->from('#__adminpraise_menu')
            ->where('import_id IN (' . implode(',', array_keys($jItems)) . ')');

        $db->setQuery($query);

        $aItems = $db->loadObjectList('import_id');


        $insert = array();
// let us check if the adminpraise menu is missing items
        foreach ($jItems as $key => $value) {
            if (!isset($aItems[$key])) {
                $insert[$key] = $value;
            }
        }

//      if we have missing items, let us add them to adminpraise_menu
        if (count($insert)) {
            // create an array with component names
            foreach ($insert as $component) {
                if ($component->component_name) {
                    $components[$component->component_id] = $component->component_name;
                }

            }
            // load the menu languages
            $language = JFactory::getLanguage();
            foreach ($components as $value) {
                $language->load($value . '.menu');
                $language->load($value . '.sys');
            }

            foreach ($insert as $value) {
                // try to get a translated component name
                if (isset($components[$value->component_id]) && $language->hasKey($components[$value->component_id])) {
                    $componentName = JText::_($components[$value->component_id]);
                } else {
                    $componentName = $value->title;
                }

                $values[strtolower($componentName)][] = $db->quote($value->title)
                    . ',' . $db->quote($value->alias)
                    . ',' . $db->quote($value->note)
                    . ',' . $db->quote($value->link)
                    . ',' . $db->quote($value->type)
                    . ',' . $db->quote('main')
                    . ', 1'
                    . ', "*"'
                    . ',' . $db->quote($value->component_id)
                    . ',' . $db->quote($value->parent_id)
                    . ',' . $db->quote($value->id)
                    . ', 3';
            }
            // sort the components
            ksort($values, SORT_STRING);

            // prepare the array for insert + adds an ordering value(a trick needed for rebuild later on)
            $i = 0;
            foreach ($values as $value) {
                foreach ($value as $menuItem) {
                    $toInsert[] = $menuItem . ', ' . $i++;
                }

            }

            $query = $db->getQuery(true);
            $query->insert('#__adminpraise_menu')
                ->columns('title, alias, note, link, type, menutype, published, language, component_id, parent_id, import_id, access, ordering')
                ->values($toInsert);

            $db->setQuery($query);
            $db->query();
        }


// create proper levels, lft, right

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id,parent_id, import_id')->from('#__adminpraise_menu')
            ->where('updated_parent = 0');
        $db->setQuery($query);
        $items = $db->loadObjectList();

        $query = $db->getQuery(true);
        $query->select('id,parent_id, import_id')->from('#__adminpraise_menu')
            ->where('updated_parent = 0');
        $db->setQuery($query);
        $imported = $db->loadObjectList('import_id');

        foreach ($items as $key => $value) {
            if ($value->parent_id != 1) {
                $items[$key]->new_parent = $imported[$value->parent_id]->id;
            } else {
                $items[$key]->new_parent = 5;
            }

        }

        /**
         * update menu set parent_id = id ot import_id
         */
        $updates = array();
        foreach ($items as $value) {
            $updates[] = 'UPDATE #__adminpraise_menu '
                . ' SET parent_id = ' . $db->quote($value->new_parent) . ','
                . ' updated_parent = 1'
                . ' where id = ' . $db->quote($value->id);
        }

        if (count($updates)) {
            foreach ($updates as $update) {
                $db->setQuery($update);
                $db->query();
            }
        }

    }

    /**
     *
     */
    public function syncAdminpraiseToJoomla()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__menu')
            ->where('menutype = ' . $db->quote('main'));
        $db->setQuery($query);
        $jItems = $db->loadObjectList('id');


        //      now let us get all adminpraise menus
        $query = $db->getQuery(true);
        $query->select('*')->from('#__adminpraise_menu')
            ->where('menutype = ' . $db->quote('main'))
            ->where('import_id != 0');

        $db->setQuery($query);

        $aItems = $db->loadObjectList('import_id');


        //        now let us check if the adminpraise menu has items that are not present in the joomla menu
        $missing = array();
        foreach ($aItems as $key => $value) {
            if (!isset($jItems[$key])) {
                $missing[$key] = $value;
            }
        }

        //        if we have missing components in the joomla menu, then we will need to delete them
        //        from adminpraise_menu as well
        if (count($missing)) {
            $query = $db->getQuery(true);
            $query->delete('#__adminpraise_menu')
                ->where('import_id IN (' . implode(',', array_keys($missing)) . ')');
            $db->setQuery($query);
            $db->query();
        }
    }
}
