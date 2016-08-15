<?php
defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldAirportEditLocation extends JFormFieldList
{

	protected $type = 'AirportEditLocation';

	
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$db->setQuery("SELECT a.id AS value, CONCAT(a.code, ' - ' ,a.name,'(',b.name,')') AS text FROM #__sfs_iatacodes as a left join #__sfs_country as b on a.country_id=b.id WHERE type=2 ORDER BY a.code ASC");

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
