<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of state records.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_sfs
 * @since		1.6
 */
class SfsModelAirportservices extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Initialise variables.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('*');
		$query->from('`#__sfs_airport_services` AS a');
		//$query->order($db->escape($orderCol.' '.$orderDirn));
		
		return $query;
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Airport', $prefix = 'SfsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();
		$session = JFactory::getSession();		
		$airport_code = JRequest::getVar('airport_code', "");
		$this->setState('filter.airport_code', $airport_code);
	}

	public function getAirportServicesSelect(){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__sfs_airport_services AS a');		
		$db->setQuery($query);
    	$rows = $db->loadObjectList();
    	$result = array();
    	if($rows){
    		foreach($rows as $row){
    			$result[$row->airport_id][] = $row->service_id;
    		}
    	}
    	return $result;
	}

	public function getAirportCodes()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id,a.code');
		$query->from('#__sfs_iatacodes AS a');		
		$airport_code = $this->getState('filter.airport_code');
		if (!empty($airport_code)) {
			$airport_code = $db->Quote('%'.$db->escape($airport_code).'%');
			$query->where('(a.code LIKE '.$airport_code.')');
		}
		
		$query->where('a.type IN(2)');
		$db->setQuery($query);
    	$result = $db->loadObjectList();
		return $result;
	}
	
	public function getServices(){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__sfs_services AS a');
		//$query->where("a.parent_id IS NULL");
		$db->setQuery($query);
    	$result = $db->loadObjectList();
		return $result;
	}

	public function saveAirport($airport_id,$services){
		$db = $this->getDbo();
		foreach ($services as $value) {
			$query = $db->getQuery(true);
			$query = 'INSERT INTO #__sfs_airport_services (airport_id,service_id)
VALUES ("'.$airport_id.'","'.$value.'")';			
			$db->setQuery($query);
			$db->execute();	
		}
	}
	

	public function deleteAirportServices($list_services){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query = 'DELETE FROM #__sfs_airport_services ';
		if($list_services){
			$query .= " where airport_id in (".implode(",",$list_services).")";
		}		
		$db->setQuery($query);
		$db->execute();
	}
}