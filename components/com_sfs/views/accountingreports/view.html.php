<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class SfsViewAccountingreports extends JView
{    
    protected $params;
    protected $state;

    public function display($tpl = null)
    {
		
    	$app  = JFactory::getApplication();
		
	   if( ! SFSAccess::isAirline($this->user) ) {			
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'),false) );
            return false;					
		} 
		
		if( SFSAccess::check($this->user, 'gh') ) {
			$airline = SFactory::getAirline();
							
			if( (int)$airline->iatacode_id == 0 ) {
				$app->redirect( JRoute::_('index.php?option=com_sfs&view=airlineprofile&layout=changeairline&Itemid=128',false) );
            	return false;	
			}	
			
		}
		
		if($this->getLayout() == 'accountingreports_detail')
        {
            $this->Item  = $this->get('AirplusVoucherDetail');
        }
		else
		$this->passengers = $this->get('Passengers');
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
		
        parent::display($tpl);
	}
     
}
