<?php
class Model{
	public $Local = 'localhost';
	public $User = 'root';
	public $Password = '';
	public $DBname = '';
	public $conn;
	public $dbprefix = 'jos_';
	private function Conn(){
		$this->conn = mysqli_connect( $this->Local, $this->User, $this->Password, $this->DBname) or die('Error');
	}
	
	public function getDB( $sql ){
		$this->Conn();
		$data = array();
		$sql = str_replace("#__", $this->dbprefix, $sql );
		//echo $sql . '<br>';
		//die;// = " Select * From $tabl $strWhere";die;
		$res = mysqli_query($this->conn, $sql );
		while ( $row = mysqli_fetch_object( $res  ) ) {
			$data[] = $row;
		}
		
		return $data;
	}
	
	public function fwriteJson( $filename = '' ){
		$fp = fopen($filename, 'w');
		fwrite($fp, json_encode($response));
		fclose($fp);
	}
	
	public function getServicesOfPassenger( $passenger_id ){
		$rows = $this->getDB('Select a.* From #__sfs_services as a INNER JOIN #__sfs_passenger_service AS b ON b.service_id=a.id WHERE b.passenger_id = ' . $passenger_id );
		
		
		return $rows;
	}
	
	public function getHotelOfPassenger( $passenger_id, $is_one = true ){
		$rows = $this->getDB('
		Select a.id,a.name,a.address,a.telephone,c.code,b.blockcode From #__sfs_hotel as a 
		INNER JOIN #__sfs_reservations AS b ON b.hotel_id=a.id 
		INNER JOIN #__sfs_voucher_codes AS c ON c.booking_id=b.id 
		INNER JOIN #__sfs_trace_passengers AS d ON d.voucher_id=c.id 
		WHERE d.id = ' . $passenger_id );
		if( $is_one == true & !empty( $rows ) )
			return $rows[0];
		return $rows;
	}
	
	public function getHotelAirports( $hotel_id, $is_one = true ){
		$rows = $this->getDB('
		Select a.name From #__sfs_iatacodes as a 
		INNER JOIN #__sfs_hotel_airports AS b ON b.airport_id =a.id 
		WHERE a.type = 2 And b.hotel_id = ' . $hotel_id );
		if( $is_one == true & !empty( $rows ) )
			return $rows[0];
		return $rows;
	}
	
	public function getMealplanRefreshmentsOfPassenger ( $passenger_id, $is_one = true ){
		$rows = $this->getDB('
		Select a.* From #__sfs_issue_refreshment as a 
		WHERE a.passenger_id = ' . $passenger_id );
		if( $is_one == true & !empty( $rows ) )
			return $rows[0];
		return $rows;
	}
	
	public function getGroupTransportOfPassenger ( $passenger_id, $is_one = true ){
		$rows = $this->getDB('
		Select a.*, d.name as g_name From #__sfs_group_transport_company as a 
		INNER JOIN #__sfs_passenger_group_transport_company_map as b On a.id = b.passenger_group_transport_company_id 
		INNER JOIN #__sfs_group_transportation_types as c On a.group_transportation_types_id = c.id 
		INNER JOIN #__sfs_group_transportations as d On c.group_transportation_id = d.id WHERE b.passenger_id = ' . $passenger_id );
		if( $is_one == true & !empty( $rows ) )
			return $rows[0];
		return $rows;
	}
	
	public function getAirport ( $airport_id, $is_one = true ){
		$rows = $this->getDB('
		Select a.* From #__sfs_iatacodes as a WHERE a.type = 2 And a.id = ' . $airport_id );
		if( $is_one == true & !empty( $rows ) )
			return $rows[0];
		return $rows;
	}
	
	public function getHotelAirportGroup(  $hotel_id, $is_one = true ){      
		$query = "SELECT a.id,a.name FROM #__sfs_hotel as a ";
		$query .= " WHERE a.id = " . $hotel_id;
		$rows = $this->getDB( $query );
		if( $is_one == true & !empty( $rows ) )
			return $rows[0];
		return $rows;
	}
	
	public function getInternalComment( $passenger_id, $comment, $airline_id ){
		
		$sql = " INSERT INTO #__sfs_internal_comment (`id`, `passenger_id`, `comment`, `airline_id`, `user_id`, `created_date`) VALUES (NULL, $passenger_id, '$comment', '$airline_id', '0', '" . date('Y-m-d'). "')";
		$this->Conn();
		$data = array();
		$sql = str_replace("#__", $this->dbprefix, $sql );
		$res = mysqli_query($this->conn, $sql );
	}
	
	public function getDataRentalCar($passenger_id){
		$rows = $this->getDB('SELECT b.logo, a.blockdate, a.pick_up, a.drop_off, a.group_id FROM #__sfs_service_rental_car AS a LEFT JOIN #__sfs_company_rental_car AS b ON b.id = a.rental_id WHERE a.passenger_id = '.$passenger_id );

		$location = $this->getDB('SELECT b.code, a.id FROM #__sfs_rental_car_location AS a LEFT JOIN #__sfs_iatacodes AS b ON b.id = a.airportcode WHERE a.id IN ('.$rows[0]->pick_up.','.$rows[0]->drop_off.')');
		$rows[0]->location = $location;

		$group_name = $this->getDB('SELECT last_name, first_name FROM #__sfs_detail_group_share_room WHERE group_id = '.$rows[0]->group_id );
		$rows[0]->group_name = $group_name;

		return $rows;
	}
	
}