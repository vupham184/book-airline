<?php
defined('_JEXEC') or die();

class SfsViewTaxiprofile extends JViewLegacy
{
	protected $params;
	protected $state;
	
	function display($tpl = null) 
	{	
		$app 			= JFactory::getApplication();
		$this->user 	= JFactory::getUser();
		
		if( ! SFSAccess::isTaxi() ) {			
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'),false) );
            return false;					
		} 
						    	    	
		$this->state    = $this->get('State');
        $this->params   = $this->state->get('params');		
		
        $this->taxi		= $this->get('Taxi');
		        		
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
			case 'rates':
				$title = 'Taxi - Rates';								
				$this->rates = $this->get('Rates');
				break;	
			case 'bookings':		
				$title = 'Taxi - Bookings';		
				$this->reservations   	= $this->get('Bookings');
				$this->pagination  		= $this->get('Pagination');				
				break;			
    		default :
    			$title = 'Taxi - Company Data';        
    			break;
    	}
        
        $this->document->setTitle($title);
    }
    		   
}

?>