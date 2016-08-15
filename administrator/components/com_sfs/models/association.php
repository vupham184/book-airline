<?php
defined('_JEXEC') or die;

class SfsModelAssociation extends JModelLegacy
{
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the parameters.
		$params = JComponentHelper::getParams('com_sfs');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.name', 'asc');
	}
	
	public function getItems()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		
		$query->select('a.*');
		$query->from('#__sfs_airport_associations AS a');
		
		$db->setQuery($query);
		
		$items = $db->loadObjectList();
						
		return $items;
	}
	

}


