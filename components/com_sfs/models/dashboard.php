<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class SfsModelDashboard extends JModel
{   	
	private $_hotel = null;
	
    protected function populateState()
    {
        // Get the application object.
        $app    = JFactory::getApplication();       
        $params    = $app->getParams('com_sfs');
   		
        $session = JFactory::getSession();
        $user = JFactory::getUser();        
		
       	if( SFSAccess::check( $user, 'hotel' ) ) {
       		$hotel_id = $session->get('hotel_id',0);       		
			if ( empty($hotel_id) ) {
				$hotelProfile = $session->get('hotel_profile');		
				$this->setState('hotel.profiles',$hotelProfile);				
			}
			$this->setState('hotel.id',$hotel_id);						 	       		
       	}       	
        
       	if( SFSAccess::check( $user, 'airline' ) ) {
       		$airline_id = $session->get('airline_id',0);       		       		
			if ( empty($airline_id) ) {
				$airlineProfile = $session->get('airline_profile');		
				$this->setState('airline.profiles',$airlineProfile);				
			}
			$this->setState('airline.id',$airline_id);						 	       		
       	}        	
       	
        // Load the parameters.
        $this->setState('params', $params);
    }   

    public function getListEmailHotel(){
      $user = JFactory::getUser();
      $db = JFactory::getDbo();
      
      $query_ = 'SELECT group_id FROM #__sfs_contacts WHERE user_id =' . $user->id ;
      $db->setQuery($query_);
      $result_ = $db->loadObjectList();

      $query = 'SELECT d.email, e.code'        
          . ' FROM #__sfs_hotel_airports AS a'
          . ' INNER JOIN #__sfs_contacts AS b ON b.user_id = a.hotel_id'
          . ' INNER JOIN #__sfs_airline_airport AS c ON c.airport_id = a.airport_id'
          . ' INNER JOIN #__users AS d ON d.id = b.user_id'
          . ' INNER JOIN #__sfs_iatacodes AS e ON e.id = c.airport_id'          
          . ' WHERE c.airline_detail_id = '.(int)$result_[0]->group_id.' AND b.grouptype = 1';
      
      $db->setQuery($query);
      $result = $db->loadObjectList();

      return $result;
    }  
    
    public function getListMailNotifi(){
      $user = JFactory::getUser();
      $db   = JFactory::getDbo();

      $query = 'SELECT * FROM #__sfs_communication_mail WHERE emailTo = "' . $user->email . '" ORDER BY id DESC';
      $db->setQuery($query);
      $result = $db->loadObjectList();

      return $result;
    }
}
