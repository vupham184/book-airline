<?php
defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldIatacodeEdit extends JFormFieldList
{

	protected $type = 'IatacodeEdit';

	
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();
        $groundhandler_id = JRequest::getInt('id');

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
        $query = "SELECT ia.id AS value, ia.code AS text
                FROM #__sfs_iatacodes AS ia
                INNER JOIN #__sfs_airline_details AS a ON a.iatacode_id = ia.id
                WHERE ia.type=1 AND a.gh_airline=1
                AND ia.id NOT IN (
                    SELECT airline_id
                    FROM #__sfs_groundhandlers_airlines
                    WHERE ground_id <> ".$groundhandler_id."
                )
                ORDER BY code ASC";

		$db->setQuery($query);


		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		array_unshift($options, JHtml::_('select.option', '0','Select Airline Code'));

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
