<?php
defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldAirportEdit extends JFormFieldList
{

	protected $type = 'AirportEdit';

	
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$db->setQuery("SELECT id AS value, CONCAT(code, ' - ' ,name) AS text FROM #__sfs_iatacodes WHERE type=2 ORDER BY code ASC");

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		array_unshift($options, JHtml::_('select.option', '0','Select Airport Code'));

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
