<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controllers/sfscontroller.php';

class SfsControllerHandler extends SfsController
{	
	
	public function __construct($config = array())
	{
		parent::__construct($config);		
		$this->registerTask('addflight', 'addFlightsSeats');
	}

	/**
	 * Method to provide a way for the airline to delete seats and flights.
	 * 
	 */		
	public function deleteSeats()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		$app		= JFactory::getApplication();	
		$user		= JFactory::getUser();
		
		//make sure only the airline handle on this task.
		if( ! SFSAccess::isAirline($user) ) {
			JError::raiseError(403, JText::_('Sorry you can not access to this page'));
		}
		
		$model = $this->getModel('Handler','SfsModel');		
		$result = $model->deleteSeats();
		
		if($result) {			
			//$msg = $model->get('successMsg');			
			$msg = '';
			$link = JRoute::_('index.php?option=com_sfs&view=handler&layout=overview&Itemid='.JRequest::getInt('Itemid'), false);
			$this->setRedirect($link, $msg);			
			return true;		
		} else {
			$msg = (string)$model->getError();							
			$this->setRedirect( JRoute::_('index.php?option=com_sfs&view=handler&layout=overview&Itemid='.JRequest::getInt('Itemid'), false) , $msg);
			return false;			
		}		
		
	}
	
	/**
	 * Method to provide a way for the airline can assign the flight numbers/ seats to the hotels from the roomblock.
	 * 
	 * @return	void
	 * @since	1.6.0
	 */			
	public function match() 
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise some variables
		$app		= JFactory::getApplication();	
		$user		= JFactory::getUser();
			
		//make sure that only the airline group can book the hotel.
		if( ! SFSAccess::isAirline($user) ) {
			JError::raiseError(403, JText::_('Sorry you can not access to this page'));
		}
		
		$model = $this->getModel('Handler','SfsModel');		
		$result = $model->match();
		
		if($result) {			
			$msg = $model->get('successMsg');			
			$link = JRoute::_('index.php?option=com_sfs&view=handler&layout=match&voucher='.$result.'&Itemid='.JRequest::getInt('Itemid'), false);
			$this->setRedirect($link, $msg);			
			return true;		
		} else {
			$msg = (string)$model->getError();							
			$this->setRedirect( JRoute::_('index.php?option=com_sfs&view=handler&layout=match&Itemid='.JRequest::getInt('Itemid'), false) , $msg);
			return false;			
		} 				
	}	
	
	/**
	 * Method to book the hotel.
	 *
	 * @return	void
	 * @since	1.6.0
	 */		
	public function booking()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		// Initialise some variables
		$app		= JFactory::getApplication();		
		$user = JFactory::getUser();

		//make sure that only the airline group can book the hotel.
		if( ! SFSAccess::isAirline($user) ) {
			JError::raiseError(403, JText::_('Sorry you can not access to this page'));
		}						
		// get the model and process booking the room.
		$model = $this->getModel('Handler');
				
		$result = $model->booking(); 
		
		if($result) {
			// successfully finished booking
			$app->setUserState('com_sfs.booking.status', 'success');						
			$link = JRoute::_('index.php?option=com_sfs&view=handler&layout=bookingconfirm&hotel_id='.(int) JRequest::getInt('hotel_id'), false);
			$this->setRedirect($link, $msg);						
		} else {
			// Get the validation messages.
			$errors	= $model->getErrors();			
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}									
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=handler&layout=search', false));				
		} 		
	}
	
	/**
	 * Method allow the airline administrator to add flight numbers to the system.
	 *
	 * @return	void
	 * 
	 * @since	1.6.0
	 */			
	public function addFlightsSeats()
	{		
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
				
		// Initialise some variables
		$app		= JFactory::getApplication();				
		$user 		= JFactory::getUser();
		
		//make sure that only the airline group can book the hotel.
		if( ! SFSAccess::isAirline($user) ) {
			JError::raiseError(403, JText::_('Sorry you can not access to this page'));
		}			
		
		// get the model
		$model = $this->getModel('Handler');
				
		$result = $model->addFlightsSeats(); 		
				
		
		if( $result ) 
		{			
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=match&Itemid=120',false));					
		} else {
			$msg = $model->getError();
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=handler&layout=flightform&Itemid='.JRequest::getInt('Itemid'),false),$msg);
			return false;			
		}
		return true;			
	}
	
	public function printvoucher()
	{
		//need to check if its airline and check if voucher is created by this airline. 
		//Will work for this later
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		$id = JRequest::getInt('id');
		$db = JFactory::getDbo();
		$airline = SFactory::getAirline();
		if($id) {
			$query = 'SELECT a.*,b.airline_id FROM #__sfs_voucher_codes AS a';
			$query .=' INNER JOIN #__sfs_reservations AS b ON b.id=a.booking_id';
			$query .=' WHERE a.id='.$id.' AND b.airline_id='.$airline->id;
			$db->setQuery($query);
			$result = $db->loadObject();
			
			if (isset($result)) {
				$query = 'UPDATE #__sfs_voucher_codes SET status=1 WHERE id='.$id;
				$db->setQuery($query);
				$db->query();
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=handler&layout=vouchers&bookingid='.$result->booking_id.'&Itemid='.JRequest::getInt('Itemid'),false));				
			}
						
		}				
	}
	
	/**
	 * Method to allow the airline cancel a voucher.
	 * 
	 */	
	public function cancelVoucher()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$airline = SFactory::getAirline();
		
		if( SFSAccess::isAirline() && $airline->id ) {
		
			$id = JRequest::getInt('voucher_id');			
			$blockcode = JRequest::getVar('blockcode');
			
			$reservation = SReservation::getInstance($blockcode,false,'code');
			
			if($reservation->airline_id != $airline->id ) {
				return false;
			}			
			if( (int) $id == 0 ) {
				return false;	
			}
			
			$db = JFactory::getDbo();
			$date = JFactory::getDate();		

			$voucher = $reservation->getVoucher($id);
			
			
			if (isset($voucher) && $voucher->id) {	

				/*
				
				if($voucher->vgroup) {
					if( (int)$voucher->room_type < 3 ) {
						$query = 'UPDATE #__sfs_room_inventory SET sd_room_total = sd_room_total + '.$voucher->seats.' WHERE id='.(int)$reservation->room_id;
					} else {
						$query = 'UPDATE #__sfs_room_inventory SET t_room_total = t_room_total + '.$voucher->seats.' WHERE id='.(int)$reservation->room_id;
					}						
				} else {
					if( (int)$voucher->room_type < 3 ) {
						$query = 'UPDATE #__sfs_room_inventory SET sd_room_total = sd_room_total + 1 WHERE id='.(int)$reservation->room_id;	
					} else {
						$query = 'UPDATE #__sfs_room_inventory SET t_room_total = t_room_total + 1 WHERE id='.(int)$reservation->room_id;
					}						
				}							
				$db->setQuery($query);
				if(!$db->query()){
					throw new Exception($db->getErrorMsg());
				}				
				*/
						
				$query = 'UPDATE #__sfs_voucher_codes SET status=3,handled_date='.$db->Quote($date->toSql()).' WHERE id='.$voucher->id;
				$db->setQuery($query);
				
				if(!$db->query()){
					throw new Exception($db->getErrorMsg());
				}
				
				if( (int)$voucher->vgroup == 0 ){
					if( $voucher->room_type < 3 ) {
						$query = 'UPDATE #__sfs_reservations SET sd_room_issued=sd_room_issued-1 WHERE id='.$reservation->id.' AND airline_id='.$airline->id;
						$db->setQuery($query);
						if(!$db->query()){
							throw new Exception($db->getErrorMsg());
						}				
					} else {
						$query = 'UPDATE #__sfs_reservations SET t_room_issued=t_room_issued-1 WHERE id='.$reservation->id.' AND airline_id='.$airline->id;
						$db->setQuery($query);
						if(!$db->query()){
							throw new Exception($db->getErrorMsg());
						}						
					}
				} else {
					if($voucher->room_type < 3) {
						$query = 'UPDATE #__sfs_reservations SET sd_room_issued=sd_room_issued-'.$voucher->seats.' WHERE id='.$reservation->id.' AND airline_id='.$airline->id;
						$db->setQuery($query);
						if(!$db->query()){
							throw new Exception($db->getErrorMsg());
						}
					} else if($voucher->room_type == 3) {
						$query = 'UPDATE #__sfs_reservations SET t_room_issued=t_room_issued-'.$voucher->seats.' WHERE id='.$reservation->id.' AND airline_id='.$airline->id;
						$db->setQuery($query);
						if(!$db->query()){
							throw new Exception($db->getErrorMsg());
						}
					} else {
						$query = 'UPDATE #__sfs_reservations SET t_room_issued=t_room_issued-'.$voucher->troom.',sd_room_issued=sd_room_issued-'.$voucher->sdroom.' WHERE id='.$reservation->id.' AND airline_id='.$airline->id;
						$db->setQuery($query);
						if(!$db->query()){
							throw new Exception($db->getErrorMsg());
						}
					}
				}

				SEmail::cancelVoucher($reservation,$voucher);
				
				$this->setRedirect('index.php?option=com_sfs&view=close&tmpl=component&closetype=cancelvoucher&bookingid='.$reservation->id.'&Itemid='.JRequest::getInt('Itemid'));
			}
						
		
		}
	}
	
}

