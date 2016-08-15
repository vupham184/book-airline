<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SfsModelMessages extends JModelList
{

	protected function populateState($ordering = 'posted_date', $direction = 'ASC')
	{
		$app = JFactory::getApplication();
				
		$params = $app->getParams();
		$this->setState('params', $params);
	}

	
	function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.*');
		$query->from('#__sfs_messages AS a');
		
		$blockId = (int)$this->getState('block.id');
		$query->where('block_id='.$blockId);

		// Add the list ordering clause.
		$query->order('a.posted_date DESC');

		return $query;
	}

}
