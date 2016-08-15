<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/* @var $reservation SReservation */
class SfsControllerBooking extends JController
{	
		
	public function __construct()
	{
		parent::__construct();		
	}	
	
	public function getModel($name = 'Booking', $prefix = 'SfsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
	public function process()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise some variables
		$app	 = JFactory::getApplication();		
		$user 	 = JFactory::getUser();
		$airline = SFactory::getAirline();
		
		if( ! SFSAccess::check($user, 'a.admin') )  {			
			JError::raiseError(404, JText::_('Restricted Access'));
			return false;	
		}
			
		$model = $this->getModel();
		$state = $model->getState();
		
		// Initialize Booking
		if( !$model->initialize() ) 
		{			
			$error = $model->getError();
			
			JError::raiseWarning(403, $error);
			
			$url = 'index.php?option=com_sfs&view=search&Itemid=119&rooms='.(int)$state->get('filter.rooms').'&date_start='.$state->get('filter.date_start').'&date_end='.$state->get('filter.date_end');
			if(JRequest::getString('pass_issue_hotel')){
				$url .= '&pass_issue_hotel='.JRequest::getString('pass_issue_hotel');
				$url .= '&tb_share_room='.JRequest::getString('tb_share_room');
				$url .= '&room_book='.JRequest::getString('room_book');
		    }
		    if(JRequest::getString('pass_detail_hotel')){
		    	$url .= '&pass_detail_hotel='.JRequest::getString('pass_detail_hotel');
		    }
			$this->setRedirect(JRoute::_($url, false));
			return false;
		}		
				
		if( !$model->process() )
		{			
			$error = $model->getError();		
			//JError::raiseWarning(403, $error);
			
			$session = JFactory::getSession();
			$session->set('booking.error',$error);
			
			$url = 'index.php?option=com_sfs&view=booking&layout=close&tmpl=component&bookingerror=1';
			$url .= '&rooms='.(int)$state->get('filter.rooms');
			$url .= '&date_start='.$state->get('filter.date_start');
			$url .= '&date_end='.$state->get('filter.date_end');
			if(JRequest::getString('pass_issue_hotel')){
				$url .= '&pass_issue_hotel='.JRequest::getString('pass_issue_hotel');
				$url .= '&tb_share_room='.JRequest::getString('tb_share_room');
				$url .= '&room_book='.JRequest::getString('room_book');
		    }			
		    if(JRequest::getString('pass_detail_hotel')){
				$url .= '&pass_detail_hotel='.JRequest::getString('pass_detail_hotel');
		    }	
			$this->setRedirect(JRoute::_($url, false));
			return false;
		}
				
		// successfully finished booking
		$app->setUserState('com_sfs.booking.status', 'success');
		
		$reservation = $model->getReservation();
		
		$url  = 'index.php?option=com_sfs&view=booking&layout=close&tmpl=component&hotel_id='.$reservation->hotel_id;
		$url .= '&association_id='.$reservation->association_id;
		
		$totalBookedRooms 	 = $reservation->s_room + $reservation->sd_room + $reservation->t_room + $reservation->q_room + (int)$reservation->ws_room;		
		$issuesinglevoucher  = JRequest::getInt('issuesinglevoucher',0);
		
		if( !empty($reservation->ws_prebooking) ) {
			$url .= '&reservation_id='.$reservation->id;
			$url .= '&rooms='.(int)$state->get('filter.rooms');
			$url .= '&date_start='.$state->get('filter.date_start');
			$url .= '&date_end='.$state->get('filter.date_end');
			$url .= '&is_ws=1';
		}
		else if( (int)$totalBookedRooms == 1 && $issuesinglevoucher == 1){
			$url .= '&reservation_id='.$reservation->id;
			$url .= '&total_booked_rooms='.(int)$totalBookedRooms;	
			$url .= '&rooms='.(int)$state->get('filter.rooms');
			$url .= '&date_start='.$state->get('filter.date_start');
			$url .= '&date_end='.$state->get('filter.date_end');
			$url .= '&issuesinglevoucher='.(int)$issuesinglevoucher;				
		}
		if(JRequest::getString('pass_issue_hotel')){
				$url .= '&pass_issue_hotel='.JRequest::getString('pass_issue_hotel');
				$url .= '&tb_share_room='.JRequest::getString('tb_share_room').'&room_book='.JRequest::getString('room_book').'&reservation_id='.$reservation->id;
		}
		if(JRequest::getString('pass_detail_hotel')){
				$url .= '&pass_detail_hotel='.JRequest::getString('pass_detail_hotel');
		    }	
		$link = JRoute::_($url, false);		
		$this->setRedirect($link, $msg);	
		return true;
	}
	
	public function wsPreBookSession(){
		$session = JFactory::getSession();
		$rooms = JRequest::getVar('rooms', array());
		
		if(!is_array($rooms)) {
			$rooms = array();
		}
		
		$json = array();
		
		$prebook = null;
		$roomTypes = array();
		$roomTypeData = array();
		foreach($rooms as &$r){
			$rt = Ws_Do_Search_RoomTypeResult::fromString($r['roomType']);
			$rt->NumberOfRooms = $r['number'];
			$roomTypes[] = $rt;
		}
		
		$prebook = SfsWs::preBook($roomTypes);

		$prebooktoken = '';
		
		if(empty($prebook)) {
			$json = array(
				'error' => 1,
				'message' => 'Could not do pre booking request. Please try again.'
			);
		} else {
			$prebooktoken = $prebook->PreBookingToken;
			$session->set('ws_rooms_data.' . $prebooktoken, serialize($rooms));
			$session->set('ws_prebook_data.' . $prebooktoken, $prebook->toString());
			$json = array(
				'prebooking_token' => $prebooktoken
			);
		}
		
		echo json_encode($json);
		exit(0);
	}
}


