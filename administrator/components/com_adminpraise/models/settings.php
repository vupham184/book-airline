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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.modeladmin' );

class AdminpraiseModelSettings extends JModelAdmin {

	/**
	 * Item cache.
	 */
	private $_cache = array();

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{

		// Get the form.
		$form = $this->loadForm('com_adminpraise.settings', 'settings', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) {
			return false;
		}

		$data = $this->getItem();

		// Load the data into the form after the plugins have operated.
		$form->bind($data->params);
		//echo str_replace("=>","&#8658;",str_replace("Array","<font color=\"red\"><b>Array</b></font>",nl2br(str_replace(" "," &nbsp; ",print_r($form,true)))));

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_adminpraise.edit.settings.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function getItem()
	{
		// Get a row instance.
		$table = $this->getTable();

		// Attempt to load the row.
		$return = $table->load(array('template' => 'adminpraise3'));

		// Check for a table object error.
		if ($return === false && $table->getError()) {
			$this->setError($table->getError());
			return $false;
		}

		// Convert to the JObject before adding other data.
		$properties = $table->getProperties(1);
		$this->_cache['adminpraise3'] = JArrayHelper::toObject($properties, 'JObject');

		// Convert the params field to an array.
		$registry = new JRegistry;
		$registry->loadString($table->params);
		$this->_cache['adminpraise3']->params = $registry->toArray();

		// Get the template XML.
		$path	= JPath::clean(JPATH_ADMINISTRATOR.'/templates/'.$table->template.'/templateDetails.xml');

		if (file_exists($path)) {
			$this->_cache['adminpraise3']->xml = simplexml_load_file($path);
		}
		else {
			$this->_cache['adminpraise3']->xml = null;
		}

		return $this->_cache['adminpraise3'];
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	*/
	public function getTable($type = 'Style', $prefix = 'TemplatesTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
}
