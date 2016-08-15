<?php
defined('_JEXEC') or die;

class SfsViewMessage extends JViewLegacy
{    
	protected $params;
	protected $state;
	
    public function display($tpl = null)
    {
        // Get the view data.                              
		$this->state       = $this->get('State');
        $this->params      = $this->state->get('params');
		$this->user = JFactory::getUser();
		
    	if( SFSAccess::isAirline( $this->user ) ){
    		$this->reservation  = $this->get('Reservation');
	    	if( (int)$this->reservation->association_id > 0 ) {
				$association = SFactory::getAssociation($this->reservation->association_id);
				$db = $association->db;			 
			} else {
				$db = JFactory::getDbo();	
			}
			$this->hotel	=  new SHotel($this->reservation->hotel_id,$db);    		
    		$latestMessage = $this->get('LatestMessage');
    	} else if( SFSAccess::isHotel( $this->user ) ) {
    		$this->reservation = $this->get('Reservation');
    		$this->airline 	   = $this->get('Airline');
    		$this->contact 	   = $this->airline->contact_details;
    	}   else {
    		JError::raiseWarning(500, 'You can access to this page');
    		return false;	
    	}     
        
    	$this->message_count = $this->get('MessageCount');
    	
        parent::display($tpl);
    }

 
     
}
