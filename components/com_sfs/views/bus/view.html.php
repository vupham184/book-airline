<?php
defined('_JEXEC') or die();

class SfsViewBus extends JViewLegacy
{
	protected $params;
	protected $state;
	
	function display($tpl = null) 
	{	
		$app 			= JFactory::getApplication();
		$this->user 	= JFactory::getUser();
		
		if( ! SFSAccess::isBus() ) {			
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'),false) );
            return false;					
		} 
						    	    	
		$this->state    = $this->get('State');
        $this->params   = $this->state->get('params');		
		$this->bus		= $this->get('Bus');
		$this->busAirport = $this->get('ListAirport');
		        		
        $this->prepareDocument($this->getLayout());  
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        } 
        
		parent::display($tpl);
	}
		
	protected function prepareDocument($layout)
    {
    	switch ($layout)
    	{
    		case 'profiles':
    			$title = 'Bus - Rates';
    			$this->profiles = $this->get('Profiles'); 
    			break;
			case 'editprofile':    			
    			$this->profiles = $this->get('Profiles');    			
    			if( !$this->profiles  )
    			{
    				$this->setLayout('editprofile');
    			}
    			$title = 'Bus - Rates';
    			break;
			case 'removeprofile':
				$title = 'Bus - Remove Profile';
				$this->profile_id = JRequest::getInt('profile_id',0);
				$this->profiles   = $this->get('Profiles');
				break;
			case 'removelinerate':
				$title = 'Bus - Remove Profile';
				$this->profile_id = JRequest::getInt('profile_id',0);
				$this->profiles   = $this->get('Profiles');
				break;
			case 'rate':
				$title = 'Bus - Rates';				
				$this->profile_id = JRequest::getInt('profile_id',0);
				$this->profiles   = $this->get('Profiles');
				$this->rates   	  = $this->get('Rates');
				break;	
			case 'bookings':		
				$title = 'Bus - Bookings';		
				$this->reservations   	= $this->get('Bookings');
				$this->pagination  		= $this->get('Pagination');				
				break;	
			case 'removeprofilefixed':
				$title = 'Bus - Remove Profile';
				$this->profile_id = JRequest::getInt('profile_id',0);
				$this->profiles   = $this->get('Profiles');
				break;
			case 'removelineratefixed':
				$title = 'Bus - Remove Profile';
				$this->profile_id = JRequest::getInt('profile_id',0);
				$this->profiles   = $this->get('Profiles');
				break;		
    		default :
    			$title = 'Bus - Company Data';        
    			break;
    	}
        
        $this->document->setTitle($title);
    }
    		   
}

?>