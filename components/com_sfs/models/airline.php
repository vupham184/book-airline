<?php
// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.model');

class SfsModelAirline extends JModel
{
	protected $_contacts = null;		
	protected $_airline = null;
	
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}
	
	public function getAirline()
	{
		if( $this->_airline === null ) 
		{
			$user = JFactory::getUser();
			$db = $this->getDbo();	
						
			
			$query = 'SELECT a.*,c.code,c.name,d.name AS country_name FROM #__sfs_airline_details AS a';
			$query .= ' INNER JOIN #__sfs_contacts AS b ON b.group_id=a.id AND b.grouptype=2';
			$query .= ' INNER JOIN #__sfs_iatacodes AS c ON c.id=a.iatacode_id';
			$query .= ' INNER JOIN #__sfs_country AS d ON d.id=a.office_country';
			$query .= ' WHERE b.user_id=' . (int) $user->id;					 
					 			
			$db->setQuery($query);			
			
			$this->_airline = $db->loadObject();	
									
		}
		return $this->_airline;				
	}	

	public function getContacts()
	{
		if( $this->_contacts === null ) 
		{
			$airline = $this->getAirline();
			$db = $this->getDbo();
			
			$query = 'SELECT a.*, u.email FROM #__sfs_contacts AS a';
			$query .= ' INNER JOIN #__users AS u ON u.id=a.user_id';
			$query .= ' WHERE a.group_id='.$airline->id.' AND a.grouptype='.constant('SFSCore::AIRLINE_GROUP');
			$query .= ' ORDER BY a.is_admin DESC, a.id ASC';
			$db->setQuery($query);

			$this->_contacts = $db->loadObjectList();
		}
		return $this->_contacts;
	}
}

