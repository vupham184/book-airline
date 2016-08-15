<?php
defined('_JEXEC') or die();

class SfsViewCommunication extends JViewLegacy
{
	protected $params;
	protected $emailNotifi;
	protected $emailHotel;
	protected $emailDSend;
	protected $dataPassengerReport;
	
	function display($tpl = null) 
	{	
		$app 			= JFactory::getApplication();
		$this->user 	= JFactory::getUser();			
		$this->emailNotifi  		= $this->get('ListMailNotifi');
		$this->emailDSend  			= $this->get('ListEmailSend');
		$this->dataPassengerReport 	= $this->get('DataPassengerReport');
		// $this->emailHotel   = $this->get('ListEmailHotel');      		
        
		if( ! SFSAccess::isAirline($this->user) ) {			
			$app->redirect( JRoute::_('index.php?option=com_sfsuser&view=login&Itemid=104',false) );
            return false;					
		}

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        } 
        
		parent::display($tpl);
	}
			
    		   
}

?>