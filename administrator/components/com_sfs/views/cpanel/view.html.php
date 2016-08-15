<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


class SfsViewCpanel extends JView
{	

	public function display($tpl = null)
	{	
		
		$this->state	= $this->get('State');

		$this->total_airline = $this->getTotalAirline();
		$this->total_airline_contact = $this->getTotalAirlineContact();
		
		$this->total_gh = $this->getTotalGH();
		$this->total_gh_contact = $this->getTotalGHContact();
		
		$this->total_hotel = $this->getTotalHotel();
		$this->total_hotel_contact = $this->getTotalHotelContact();
		
		$this->latest_airlines = $this->getLatestAirlines();
		$this->latest_ghs = $this->getLatestGHs();
		$this->latest_hotels = $this->getLatestHotels();
		
		$this->reservations = $this->get('Reservations');
		
		$this->addToolbar();				
		parent::display($tpl);
		
	}

	protected function addToolbar()
	{			
		JToolBarHelper::title('SFS Control Panel', 'cpanel.png');				
		JToolBarHelper::preferences('com_sfs');	
	}
	
	protected function getTotalAirline() 
	{
		$db = JFactory::getDbo();
		$query = 'SELECT COUNT(id) FROM #__sfs_airline_details WHERE iatacode_id>0';
		$db->setQuery($query);
		$result = $db->loadResult();
		return (int) $result;
	}
	protected function getTotalAirlineContact() 
	{
		$db = JFactory::getDbo();
		$query = 'SELECT COUNT(id) FROM #__sfs_contacts WHERE grouptype=2';
		$db->setQuery($query);
		$result = $db->loadResult();
		return (int) $result;
	}
	
	protected function getTotalGH() 
	{
		$db = JFactory::getDbo();
		$query = 'SELECT COUNT(id) FROM #__sfs_airline_details WHERE iatacode_id = 0';
		$db->setQuery($query);
		$result = $db->loadResult();
		return (int) $result;
	}
		
	protected function getTotalGHContact() 
	{
		$db = JFactory::getDbo();
		$query = 'SELECT COUNT(id) FROM #__sfs_contacts WHERE grouptype=3';
		$db->setQuery($query);
		$result = $db->loadResult();
		return (int) $result;
	}
	
	
	protected function getTotalHotel() 
	{
		$db = JFactory::getDbo();
		$query = 'SELECT COUNT(id) FROM #__sfs_hotel';
		$db->setQuery($query);
		$result = $db->loadResult();
		return (int) $result;
	}
	protected function getTotalHotelContact() 
	{
		$db = JFactory::getDbo();
		$query = 'SELECT COUNT(id) FROM #__sfs_contacts WHERE grouptype=1';
		$db->setQuery($query);
		$result = $db->loadResult();
		return (int) $result;
	}	
	
	protected function getLatestAirlines()
	{
		$db = JFactory::getDbo();
		
		$query = 'SELECT a.id,a.approved, b.name, u.name AS created_by,u.id AS user_id FROM #__sfs_airline_details AS a'.
				 ' INNER JOIN #__sfs_iatacodes AS b ON b.id=a.iatacode_id'.
				 ' INNER JOIN #__users AS u ON u.id=a.created_by'.
				 ' WHERE a.iatacode_id > 0'.
				 ' ORDER BY a.created_date DESC';
				 
								 
		$db->setQuery($query,0,10);
		$result = $db->loadObjectList();
		
		
		return $result;		
	}
	
	protected function getLatestGHs()
	{
		$db = JFactory::getDbo();
		
		$query = 'SELECT a.id,a.approved,a.company_name, u.name AS created_by,u.id AS user_id FROM #__sfs_airline_details AS a'.				 
				 ' INNER JOIN #__users AS u ON u.id=a.created_by'.
				 ' WHERE a.iatacode_id = 0'.
				 ' ORDER BY a.created_date DESC';
								 
		$db->setQuery($query,0,10);
		$result = $db->loadObjectList();
		return $result;		
	}
	
	protected function getLatestHotels() 
	{
		$db = JFactory::getDbo();
		$query = 'SELECT a.id,a.name,u.name AS created_by,u.id AS user_id FROM #__sfs_hotel AS a INNER JOIN #__users AS u ON u.id=a.created_by ORDER BY a.created_date DESC';
		$db->setQuery($query,0,10);
		$result = $db->loadObjectList();
		return $result;		
	}
	
}

