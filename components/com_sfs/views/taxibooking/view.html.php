<?php
defined('_JEXEC') or die();

class SfsViewTaxibooking extends JViewLegacy
{
	protected $state;
	protected $params;
	protected $user;
	
	function display($tpl = null) 
	{				
		$app	= JFactory::getApplication();
		
		$this->state	=  $this->get('state');
		$this->params	= $this->state->get('params');						
      	$this->user 	= JFactory::getUser();		
      	
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
		
		$airline = SFactory::getAirline();
		
		if( ! $airline->allowTaxiVoucher() )
		{
			$msg = 'The taxi service is not available for your account. Contact SFS Administrator for more details.';
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'),false), $msg );
            return false;			
		}
		
		$this->terminals 		= $this->get('Terminals');		
		$this->hotels	 		= $this->get('Hotels');
		$this->taxiCompanies 	= $this->get('TaxiCompanies');
		$this->taxiReservation  = $this->get('TaxiReservation');
			
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}			
				
		parent::display($tpl);		
	}
		
}
?>

