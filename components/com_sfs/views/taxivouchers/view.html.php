<?php
defined('_JEXEC') or die();

class SfsViewTaxivouchers extends JViewLegacy
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
		
		$airline = SFactory::getAirline();
		
		if( ! $airline->allowTaxiVoucher() )
		{
			$msg = 'The taxi service is not available for your account. Contact SFS Administrator for more details.';
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'),false), $msg );
            return false;			
		}
		
		
		$this->airline 			= $airline;		
		$this->reservations		= $this->get('Reservations');
		
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

