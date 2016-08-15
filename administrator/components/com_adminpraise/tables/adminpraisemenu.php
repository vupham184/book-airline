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
jimport('joomla.database.tablenested');

class AdminpraiseTableAdminpraiseMenu extends JTableNested {


	public function __construct($db) {
		parent::__construct('#__adminpraise_menu', 'id', $db);
        // Set the default access level.
        $this->access = (int) JFactory::getConfig()->get('access');
	}


    /**
     * Overloaded bind function
     *
     * @param   array  $array   Named array
     * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
     *
     * @return  mixed  Null if operation was satisfactory, otherwise returns an error
     *
     * @see     JTable::bind
     * @since   11.1
     */
    public function bind($array, $ignore = '')
    {
        // Verify that the default home menu is not unset
        if ($this->home == '1' && $this->language == '*' && ($array['home'] == '0'))
        {
            $this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_CANNOT_UNSET_DEFAULT_DEFAULT'));
            return false;
        }
        //Verify that the default home menu set to "all" languages" is not unset
        if ($this->home == '1' && $this->language == '*' && ($array['language'] != '*'))
        {
            $this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_CANNOT_UNSET_DEFAULT'));
            return false;
        }

        // Verify that the default home menu is not unpublished
        if ($this->home == '1' && $this->language == '*' && $array['published'] != '1')
        {
            $this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_UNPUBLISH_DEFAULT_HOME'));
            return false;
        }

        if (isset($array['params']) && is_array($array['params']))
        {
            $registry = new JRegistry;
            $registry->loadArray($array['params']);
            $array['params'] = (string) $registry;
        }

        return parent::bind($array, $ignore);
    }

    /**
     * Overloaded check function
     *
     * @return  boolean  True on success
     *
     * @see     JTable::check
     * @since   11.1
     */
    public function check()
    {
        // If the alias field is empty, set it to the title.
        $this->alias = trim($this->alias);
        if ((empty($this->alias)) && ($this->type != 'alias' && $this->type != 'url'))
        {
            $this->alias = $this->title;
        }

        // Make the alias URL safe.
        $this->alias = JApplication::stringURLSafe($this->alias);
        if (trim(str_replace('-', '', $this->alias)) == '')
        {
            $this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
        }

        // Cast the home property to an int for checking.
        $this->home = (int) $this->home;


        // Verify that a first level menu item alias is not 'component'.
        if ($this->parent_id == 1 && $this->alias == 'component')
        {
            $this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_ROOT_ALIAS_COMPONENT'));
            return false;
        }

        // Verify that a first level menu item alias is not the name of a folder.
        jimport('joomla.filesystem.folders');
        if ($this->parent_id == 1 && in_array($this->alias, JFolder::folders(JPATH_ROOT)))
        {
            $this->setError(JText::sprintf('JLIB_DATABASE_ERROR_MENU_ROOT_ALIAS_FOLDER', $this->alias, $this->alias));
            return false;
        }

        // Verify that the home item a component.
        if ($this->home && $this->type != 'component')
        {
            $this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_HOME_NOT_COMPONENT'));
            return false;
        }

        return true;
    }

    /**
     * Overloaded store function
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  mixed  False on failure, positive integer on success.
     *
     * @see     JTable::store
     * @since   11.1
     */
    public function store($updateNulls = false)
    {

        $db = JFactory::getDBO();
        // Verify that the alias is unique
        $table = JTable::getInstance('AdminpraiseMenu', 'AdminpraiseTable');
        if ($table->load(array('alias' => $this->alias, 'parent_id' => $this->parent_id, 'client_id' => $this->client_id, 'language' => $this->language))
            && ($table->id != $this->id || $this->id == 0))
        {
            if ($this->menutype == $table->menutype)
            {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_UNIQUE_ALIAS'));
            }
            else
            {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_UNIQUE_ALIAS_ROOT'));
            }
            return false;
        }
        // Verify that the home page for this language is unique
        if ($this->home == '1')
        {
            $table = JTable::getInstance('Menu', 'JTable');
            if ($table->load(array('home' => '1', 'language' => $this->language)))
            {
                if ($table->checked_out && $table->checked_out != $this->checked_out)
                {
                    $this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_DEFAULT_CHECKIN_USER_MISMATCH'));
                    return false;
                }
                $table->home = 0;
                $table->checked_out = 0;
                $table->checked_out_time = $db->getNullDate();
                $table->store();
            }
            // Verify that the home page for this menu is unique.
            if ($table->load(array('home' => '1', 'menutype' => $this->menutype)) && ($table->id != $this->id || $this->id == 0))
            {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_HOME_NOT_UNIQUE_IN_MENU'));
                return false;
            }
        }
        if (!parent::store($updateNulls))
        {
            return false;
        }
        // Get the new path in case the node was moved
        $pathNodes = $this->getPath();
        $segments = array();
        foreach ($pathNodes as $node)
        {
            // Don't include root in path
            if ($node->alias != 'root')
            {
                $segments[] = $node->alias;
            }
        }
        $newPath = trim(implode('/', $segments), ' /\\');
        // Use new path for partial rebuild of table
        // Rebuild will return positive integer on success, false on failure
        return ($this->rebuild($this->{$this->_tbl_key}, $this->lft, $this->level, $newPath) > 0);
    }


    /**
     * We need to override the function and modify it slightly to get the result we want
     *
     * Method to recursively rebuild the whole nested set tree.
     *
     * @param   integer  $parentId  The root of the tree to rebuild.
     * @param   integer  $leftId    The left id to start with in building the tree.
     * @param   integer  $level     The level to assign to the current nodes.
     * @param   string   $path      The path to the current nodes.
     *
     * @return  integer  1 + value of root rgt on success, false on failure
     *
     * @link    http://docs.joomla.org/JTableNested/rebuild
     * @since   11.1
     */
    public function rebuild($parentId = null, $leftId = 0, $level = 0, $path = '')
    {
        // If no parent is provided, try to find it.
        if ($parentId === null)
        {
            // Get the root item.
            $parentId = $this->getRootId();
            if ($parentId === false)
            {
                return false;
            }

        }

        // Build the structure of the recursive query.
        if (!isset($this->_cache['rebuild.sql']))
        {
            $query = $this->_db->getQuery(true);
            $query->select($this->_tbl_key . ', alias');
            $query->from($this->_tbl);
            $query->where('parent_id = %d');
            //sort first by lft and then ordering (needed because we assemble the menu...)
            $query->order('parent_id, lft, ordering');

            $this->_cache['rebuild.sql'] = (string) $query;
        }

        // Make a shortcut to database object.

        // Assemble the query to find all children of this node.
        $this->_db->setQuery(sprintf($this->_cache['rebuild.sql'], (int) $parentId));
        $children = $this->_db->loadObjectList();

        // The right value of this node is the left value + 1
        $rightId = $leftId + 1;

        // execute this function recursively over all children
        foreach ($children as $node)
        {
            // $rightId is the current right value, which is incremented on recursion return.
            // Increment the level for the children.
            // Add this item's alias to the path (but avoid a leading /)
            $rightId = $this->rebuild($node->{$this->_tbl_key}, $rightId, $level + 1, $path . (empty($path) ? '' : '/') . $node->alias);

            // If there is an update failure, return false to break out of the recursion.
            if ($rightId === false)
            {
                return false;
            }
        }

        // We've got the left value, and now that we've processed
        // the children of this node we also know the right value.
        $query = $this->_db->getQuery(true);
        $query->update($this->_tbl);
        $query->set('lft = ' . (int) $leftId);
        $query->set('rgt = ' . (int) $rightId);
        $query->set('level = ' . (int) $level);
        $query->set('path = ' . $this->_db->quote($path));
        $query->where($this->_tbl_key . ' = ' . (int) $parentId);
        $this->_db->setQuery($query);

        // If there is an update failure, return false to break out of the recursion.
        if (!$this->_db->query())
        {
            $e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_REBUILD_FAILED', get_class($this), $this->_db->getErrorMsg()));
            $this->setError($e);
            return false;
        }

        // Return the right value of this node + 1.
        return $rightId + 1;
    }

}
