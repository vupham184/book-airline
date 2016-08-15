<?php
defined('_JEXEC') or die;

class SfsControllerMatch extends JControllerLegacy
{		
	
	public function __construct($config = array())
	{
		parent::__construct($config);								
	}	

	public function check()
	{
		$db				= JFactory::getDbo();
		$user 			= JFactory::getUser();
		$airline		= SFactory::getAirline();
		$reservationId  = JRequest::getInt('reservationId');
		$seats 			= JRequest::getInt('seats',0);
		
		$modelMatch		= $this->getModel('Match','SfsModel');
		
		if( ! SFSAccess::isAirline($user) ) {
			JFactory::getApplication()->close();			
		}
				
		$response = array(
			'allowMatch' => '1',
			'seats' => $seats,
			'showSelectRooms' => 0,
			'single_room_available' => 0,
			'quad_room_available' => 0							
		);
		
		///if($seats >= 4 ) {
		if($seats >= 2 ) {
			$reservation = SReservation::getInstance($reservationId);			
			if($reservation->id)
			{
				$HotelBackendParams = $modelMatch->getHotelBackendParams( $reservation->hotel_id );
				$single_room_available = (int)$HotelBackendParams->single_room_available;
				$quad_room_available = (int)$HotelBackendParams->quad_room_available;
				
				$availableRooms = array();
				$availableRooms[1] = (int) ( $reservation->s_room - $reservation->s_room_issued );
				$availableRooms[2] = (int) ( $reservation->sd_room - $reservation->sd_room_issued );
				$availableRooms[3] = (int) ( $reservation->t_room - $reservation->t_room_issued );
				$availableRooms[4] = (int) ( $reservation->q_room - $reservation->q_room_issued );
				
				$seatLimit = $availableRooms[1] + 2*$availableRooms[2] + 3*$availableRooms[3]+ 4*$availableRooms[4];
				
				if($seatLimit>=$seats)
				{	
					//if( $seats == 4 && $availableRooms[4] > 0 )
					if( $seats == 1 && $availableRooms[4] > 0 )
					{
						$response['allowMatch'] = 1; 
					} else {
						$response['allowMatch'] = 0; 						
						$response['showSelectRooms'] = 1;
						$response['s_room']  = $availableRooms[1];
						$response['sd_room'] = $availableRooms[2];
						$response['t_room']  = $availableRooms[3];
						$response['q_room']  = $availableRooms[4];
						$response['single_room_available'] = $single_room_available;
						$response['quad_room_available'] = $quad_room_available;						
					}	
					
				} else {
					$response['allowMatch'] = 0; 						
					$response['showSelectRooms'] = 0;
					$response['error'] = 'Number rooms not enough for '.$seats.' seats'; 
					$response['single_room_available'] = $single_room_available;
					$response['quad_room_available'] = 0;						
				}
				
			}
		}
		
				
		echo json_encode($response);
		JFactory::getApplication()->close();
	}
	
	public function testRooms()
	{
		$db				= JFactory::getDbo();
		$user 			= JFactory::getUser();
		$airline		= SFactory::getAirline();
		$reservationId  = JRequest::getInt('reservationId',0);
		$seats 			= JRequest::getInt('seats',0);
		
		if( ! SFSAccess::isAirline($user) ) {
			JFactory::getApplication()->close();			
		}		
		$response = array(
			'allowMatch' => '0'								
		);
		
		///if( $seats < 4 )
		if( $seats < 2 )
		{
			$response['error'] = 'Please select more than 2 passengers for group voucher';
			echo json_encode($response);
			JFactory::getApplication()->close();
		}		
		$reservation = SReservation::getInstance($reservationId);
					
		if(!$reservation->id)
		{
			$response['error'] = 'Your session be expired. Contact SFS Administrator for more details';
			echo json_encode($response);
			JFactory::getApplication()->close();
		}
		
		$availableRooms = array();
		$availableRooms[1] = (int) ( $reservation->s_room - $reservation->s_room_issued );
		$availableRooms[2] = (int) ( $reservation->sd_room - $reservation->sd_room_issued );
		$availableRooms[3] = (int) ( $reservation->t_room - $reservation->t_room_issued );
		$availableRooms[4] = (int) ( $reservation->q_room - $reservation->q_room_issued );
		
		$seatLimit = $availableRooms[1] + 2*$availableRooms[2] + 3*$availableRooms[3]+ 4*$availableRooms[4];
		
		if($seatLimit < $seats)
		{
			$response['error'] = 'Number rooms not enough for '.$seats.' seats';
			echo json_encode($response);
			JFactory::getApplication()->close();
		}
				
		$total_single_rooms = JRequest::getInt('total_single_rooms',0);
		$total_double_rooms = JRequest::getInt('total_double_rooms',0);
		$total_triple_rooms = JRequest::getInt('total_triple_rooms',0);
		$total_quad_rooms   = JRequest::getInt('total_quad_rooms',0);	
		
		$totalSelectRooms   = $total_single_rooms + $total_double_rooms + $total_triple_rooms + $total_quad_rooms;
		
		if( $totalSelectRooms == 0 )
		{
			$response['error'] = 'Please select the number of rooms';
			echo json_encode($response);
			JFactory::getApplication()->close();
		}

		if( $total_single_rooms > $availableRooms[1] ){
			$response['error'] = 'Number single rooms not enough';
			echo json_encode($response);
			JFactory::getApplication()->close();
		}
		if( $total_double_rooms > $availableRooms[2] ){
			$response['error'] = 'Number double rooms not enough';
			echo json_encode($response);
			JFactory::getApplication()->close();
		}
		if( $total_triple_rooms > $availableRooms[3] ){
			$response['error'] = 'Number triple rooms not enough';
			echo json_encode($response);
			JFactory::getApplication()->close();
		}
		if( $total_quad_rooms > $availableRooms[4] ){
			$response['error'] = 'Number quad rooms not enough';
			echo json_encode($response);
			JFactory::getApplication()->close();
		}					
		
		$totalSelectedSeats = $total_single_rooms + 2*$total_double_rooms + 3*$total_triple_rooms + 4*$total_quad_rooms;
		
		if( $totalSelectedSeats >= $seats )
		{
			$response['allowMatch'] = 1; 				
		} else {
			$response['error'] = 'Your selection rooms not enough for '.$seats.' passengers';
			echo json_encode($response);
			JFactory::getApplication()->close();
		}
		
		echo json_encode($response);
		JFactory::getApplication()->close();
	}
	
}

