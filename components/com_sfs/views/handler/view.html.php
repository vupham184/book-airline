<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class SfsViewHandler extends JView
{
	protected $state;
	protected $params;
	protected $user;
	protected $flights_seats = null;
	protected $hotels = null;
	protected $airlinetrains;
	function display($tpl = null) 
	{				
		$app	= JFactory::getApplication();
		
		$this->state =  $this->get('state');
		$this->airlinetrains = $this->get('AirlineTrains');
		$this->params	= $this->state->get('params');
						
      	$this->user = JFactory::getUser();				
		
		
		if( SFSAccess::isAirline($this->user) ) {	
			if( SFSAccess::check($this->user, 'gh') ) {
				$airline = SFactory::getAirline();
					
				if( (int)$airline->iatacode_id == 0 ) {
					$app->redirect( JRoute::_('index.php?option=com_sfs&view=airlineprofile&layout=changeairline&Itemid=128',false) );
	            	return false;	
				}	
			}					
			$this->loadLayout( $this->getLayout() );							
		} else {
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false) );
            return false;			
		}
			
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}			
				
		// Display the template
		parent::display($tpl);		
	}
	
	protected function loadLayout ( $layout ) 
	{
		switch ( $layout ) 
		{
			case 'overview':
				$this->_overview();
				break;
			case 'bookingconfirm':
				$this->_booking_confirm();
				break;													
			default:
				break;								
		}
	}
	
	private function _overview() 
	{				
		$booked_hotels 	  = $this->get('BookedRooms');
						
		$flights_seats	  = $this->get('FlightsSeats');
			
		$this->assignRef('hotels', $booked_hotels );
		$this->assignRef('flights_seats', $flights_seats );
		
		$matchModel = JModel::getInstance('Match','SfsModel');
		
		$nightdate = JRequest::getVar('nightdate');
						
		$matchModel->setState('match.nightdate',$nightdate);
		
		$this->night		 	  = $matchModel->getNightDate();
			
		$this->nextNight		  = $matchModel->getNextNight();
		$this->nextNightUrl		  = 'index.php?option=com_sfs&view=handler&layout=overview&nightdate='.$this->nextNight.'&Itemid='.JRequest::getInt('Itemid');
		
		$this->prevNight		  = $matchModel->getPrevNight();
		if($this->prevNight)
		{
			$this->prevNightUrl		  = 'index.php?option=com_sfs&view=handler&layout=overview&nightdate='.$this->prevNight.'&Itemid='.JRequest::getInt('Itemid');	
		}
		
		return true;
	}
	
	
	
	private function _booking_confirm ()
	{
		$hotel_id = JRequest::getInt('hotel_id');		
		
		$this->hotel   = SFactory::getHotel($hotel_id);		
		$this->contact = SFactory::getContact( $this->user->id );
	}
	
	
}
?>

