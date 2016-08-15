<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');

class SfsModelCodecanyon extends JModelItem
{
	
	public function getItem(){
		$file = JRequest::getVar('path_file', null, 'files', 'array');
		$comment = JRequest::getvar('textcomment');
		$type = JRequest::getvar('type');

		$date = new DateTime();
		$dateFormat = $date->format('Y-m-d H:i:s');

		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$columns = array('type', 'image', 'comment', 'created');

		$fields = array(
		    $db->quoteName('image') . ' = ' . $db->quote($file['name']),
		    $db->quoteName('comment') . ' = ' . $db->quote($comment)
		);
		$conditions = array(
		    $db->quoteName('id') . ' = 1'
		);
 
 		$query->update($db->quoteName('#__sfs_codecanyon'))->set($fields)->where($conditions);


		// // Insert values.
		// $values = array($db->quote($type), $db->quote($file['name']), $db->quote($comment), $db->quote($dateFormat));
		 
		// // Prepare the insert query.
		// $query
		//     ->insert($db->quoteName('#__sfs_codecanyon'))
		//     ->columns($db->quoteName($columns))
		//     ->values(implode(',', $values));
		 
		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		$db->execute();

		return true;
	}
	
}


