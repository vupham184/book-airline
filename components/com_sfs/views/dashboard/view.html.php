<?php
defined('_JEXEC') or die;

class SfsViewDashboard extends JViewLegacy
{    
    protected $params;
    protected $state;       

    public function display($tpl = null)
    {    	
    	$app = JFactory::getApplication();
    		
    	$this->user		   = JFactory::getUser();
    	
		$this->state        = $this->get('State');
        $this->params       = $this->state->get('params');
        $this->emailHotel   = $this->get('ListEmailHotel');
        $this->emailNotifi  = $this->get('ListMailNotifi');

        if(SFSAccess::isHotel($this->user)) {
	        $hotel_id = $this->state->get('hotel.id');	       
	        if( empty($hotel_id) ) {
	        	$this->setLayout('multiple');
	        }   
	        $hotel = SFactory::getHotel();
	        if( ! $hotel->isRegisterComplete() ) {
    			$url = JRoute::_('index.php?option=com_sfs&view=hotelprofile&Itemid=109',false);
    			$app->redirect( $url );        		
        		return false;
        	}    	          	
        }                
    	if(SFSAccess::isAirline($this->user)) {
	        $airline_id = $this->state->get('airline.id');	       
	        if( empty($airline_id) ) {	        
	        	$this->setLayout('multiple');
	        }        	
        }  
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
      
        parent::display($tpl);
    }
     
  
}
