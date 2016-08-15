<?php
// no direct access
defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_sfs/libraries/access.php';

abstract class modSfsUserHelper
{
	static function getPersonalData()
	{	
		$result = null;
				
		$user = JFactory::getUser();
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);	

		if( SFSAccess::isAirline($user) ) {
			$query->select('c.code AS name,d.name AS country_name');
			$query->from('#__sfs_contacts AS a');
			$query->innerJoin('#__sfs_airline_details AS b ON b.id=a.group_id');
			$query->innerJoin('#__sfs_iatacodes AS c ON c.id=b.iatacode_id');
			$query->innerJoin('#__sfs_country AS d ON d.id=b.country_id');
			$query->where('a.user_id='.(int)$user->id.' AND a.grouptype=2');	
			$report_link = JRoute::_('index.php?option=com_sfs&view=report&layout=airline&Itemid='.JRequest::getInt('Itemid'));
		} else if ( SFSAccess::isHotel($user) ) {			
			$query->select('b.name,d.name AS country_name');
			$query->from('#__sfs_contacts AS a');
			$query->innerJoin('#__sfs_hotel AS b ON b.id=a.group_id');
			$query->leftJoin('#__sfs_country AS d ON d.id=b.country_id');
			$query->where('a.user_id='.(int)$user->id.' AND a.grouptype=1');		
			$report_link = JRoute::_('index.php?option=com_sfs&view=report&layout=hotel&Itemid='.JRequest::getInt('Itemid'));							
		}
		
		$db->setQuery($query,0,1);
		$result = $db->loadObject();
		$result->report_link = $report_link;
		return $result;
	}
}
