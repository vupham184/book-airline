<?php
defined('_JEXEC') or die();

class SfsViewBooking extends JViewLegacy
{
	
	protected $state;
	protected $data;
	protected $user;
	
	function display($tpl = null)
	{		
		$app = JFactory::getApplication();
		
        $this->user = JFactory::getUser();

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
		
		if( ! SFSAccess::check( $this->user, 'a.admin') ) {
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false) );
            return false;
		}
		
		if( $this->getLayout() == 'close' )
		{
			$bookingerror = JRequest::getInt('bookingerror');
			
			if($bookingerror)
			{
				$rooms = JRequest::getInt('rooms');
				$date_start = JRequest::getVar('date_start');
				$date_end = JRequest::getVar('date_end');
				$returnUrl = 'index.php?option=com_sfs&view=search&Itemid=119&rooms='.(int)$rooms.'&date_start='.$date_start.'&date_end='.$date_end;
				$returnUrl = JURI::base().$returnUrl;			
				JFactory::getDocument()->addScriptDeclaration('
					window.parent.location.href="'.$returnUrl.'";
					window.parent.SqueezeBox.close();
				');
			} else {
				
				$total_booked_rooms  = JRequest::getInt('total_booked_rooms',0);
				$issuesinglevoucher  = JRequest::getInt('issuesinglevoucher',0);
				$is_ws = JRequest::getInt('is_ws', 0);
				
				if( $total_booked_rooms == 1 && $issuesinglevoucher == 1 || $is_ws)
				{
					$rooms = JRequest::getInt('rooms');
					$reservation_id = JRequest::getInt('reservation_id');
					$date_start = JRequest::getVar('date_start');
					$date_end = JRequest::getVar('date_end');
					
					$returnUrl = 'index.php?option=com_sfs&view=search&Itemid=119&rooms='.(int)$rooms.'&date_start='.$date_start.'&date_end='.$date_end;
					$returnUrl = $returnUrl.'&reservation_id='.$reservation_id;
					$returnUrl = JURI::base().$returnUrl;
								
					JFactory::getDocument()->addScriptDeclaration('
						window.parent.location.href="'.$returnUrl.'";
						window.parent.SqueezeBox.close();
					');
				} 
				else {
					$airline = SFactory::getAirline();
					if(JRequest::getString('pass_issue_hotel') && $airline->params['airline_ws_status']==1){						
						$thankUrl = JURI::base().'index.php?option=com_sfs&view=passengersimport&pass_issue_hotel='.JRequest::getString('pass_issue_hotel').'&room_book='.JRequest::getString('room_book').'&reservation_id='.JRequest::getInt('reservation_id');
					}elseif(JRequest::getString('pass_detail_hotel') && $airline->params['airline_ws_status']==1){						
						$thankUrl = JURI::base().'index.php?option=com_sfs&view=tracepassenger&layout=default_individualpassengerpage&passenger_id='.JRequest::getString('pass_detail_hotel').'&show=hotel';						
					}else{
						$thankUrl = JURI::base().'index.php?option=com_sfs&view=booking&layout=confirm&hotel_id='.(int) JRequest::getInt('hotel_id').'&association_id='.(int) JRequest::getInt('association_id');						
					}
					JFactory::getDocument()->addScriptDeclaration('
						window.parent.location.href="'.$thankUrl.'";
						window.parent.SqueezeBox.close();
					');					
				}
				
				
			}
			
			return;
		}

		// Assign data to the view
		$this->state	  = $this->get('State');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		if( $this->getLayout() == 'form' )
		{
			$this->prepareFormDataFromRequest();	
		}
		
		if( $this->getLayout() == 'bookingformpopup') {
			JFactory::getDocument()->addScriptDeclaration('
				window.parent.SqueezeBox.close();
			');
		}
		
		if( $this->getLayout() == 'confirm' )
		{
			$this->contact = SFactory::getContact( $this->user->id );
			$associationId = JRequest::getInt('association_id',0);
					
			if( $associationId > 0 ) {
				$association = SFactory::getAssociation($associationId);
				$db  = $association->db;			 
			} else {
				$db 	= JFactory::getDbo();	
			}
			$hotelId		= JRequest::getInt('hotel_id');
			$this->hotel	=  new SHotel($hotelId,$db);
		}
		
		
		// Display the view
		parent::display($tpl);
	}
	
	protected function prepareFormDataFromRequest()
	{
		$associationId = JRequest::getInt('association_id',0);
				
		if( $associationId > 0 ) {
			$association = SFactory::getAssociation($associationId);
			$db  = $association->db;			 
		} else {
			$db 	= JFactory::getDbo();	
		}
		
		$roomId = JRequest::getInt('roomid',0);
		
		$query = 'SELECT * FROM #__sfs_room_inventory WHERE id='.$roomId;
		$db->setQuery($query);
		
		$this->inventory = $db->loadObject();
		
		$this->associationId = $associationId;
		
		if($this->inventory)
		{
			$hotelId 		= (int) $this->inventory->hotel_id;
			$this->hotel	=  new SHotel($hotelId,$db);
			
			if( $this->associationId == 0 ){
				
				$airline = SFactory::getAirline();
				
				$query =  $db->getQuery(true);
				
				$query->select('a.sd_rate,a.t_rate,a.s_rate,a.q_rate');
				$query->from('#__sfs_contractedrates AS a');
				
				$query->select('b.hotel_id AS ex_hotel_id');
				$query->leftJoin('#__sfs_contractedrates_exclusions AS b ON b.airline_id=a.airline_id AND b.hotel_id=a.hotel_id AND b.date=a.date');
				
				$query->where('a.airline_id='.$airline->id);
				$query->where('a.hotel_id='.$hotelId);
				$query->where('a.date='. $db->quote($this->inventory->date));
				
				$db->setQuery($query);
				
				$row = $db->loadObject();
				
				$this->contracted_sd_rate = $this->contracted_t_rate = $this->contracted_s_rate = $this->contracted_q_rate = 0;			 
				
				if( $row && empty($row->ex_hotel_id) )
				{
					$this->contracted_sd_rate = $row->sd_rate;
					$this->contracted_t_rate = $row->t_rate;
					$this->contracted_s_rate = $row->s_rate;
					$this->contracted_q_rate = $row->q_rate;
				}			
				
				# webservice PreBooking
				$prebooking_token = JRequest::getVar('ws_prebooking_token', '');
				if($prebooking_token) {
					$session = JFactory::getSession();
					$wsRoomTypes = unserialize($session->get('ws_rooms_data.' . $prebooking_token));
					$wsPreBook = $session->get('ws_prebook_data.' . $prebooking_token);
					$isWS = !empty($wsRoomTypes);
					$this->isWS = $isWS;
					$this->wsRoomTypes = $wsRoomTypes;
					$this->wsPreBookString = $wsPreBook;
					$this->wsPreBook = Ws_Do_PreBook_Response::fromString($wsPreBook);
				}
				# END webservice PreBooking
		
			}			
			
		}
		
	}
	
}


