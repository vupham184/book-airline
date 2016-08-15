<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controllers/sfscontroller.php';
class SfsControllerGetdistance extends SFSController
{		
	public function __construct($config = array())
	{
		parent::__construct($config);						
	}		
		
	public function getDistance() 
	{
		$hotel = SFactory::getHotel();
		//$airport_id 	 = JRequest::getInt('airport_id' , 0);
		//$airport = SfsHelperField::getAirportDefaultLocation( $airport_id );
		//echo $airport->geo_lat;
		//echo '<br>';
		//echo $airport->geo_lon;
		//die;
		//$from = JRequest::getVar('from_location' , "");
		

		$lat = JRequest::getVar('lat' , "");
		$long = JRequest::getVar('long' , "");
		
		//truong hop tao mới chưa có $lat, $long
		if( $lat == '' && $long == '' ){
			
			$airport_id 	 = JRequest::getInt('airport_id' , 0);
			$airport = SfsHelperField::getAirportDefaultLocation( $airport_id );
			$lat = $airport->geo_lat;
			$long = $airport->geo_lon;
			
		}

		$locationHotel = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$long."&sensor=true");
		$jsonLocation = json_decode($locationHotel);
		$locaHotel = $jsonLocation->results[0]->formatted_address;
		
		$from 	= $locaHotel;
		$to 	= JRequest::getVar('to_location' , "");
		
		$from 	 = urlencode($from);
		$to 	 = urlencode($to);
		$dataOrg = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
		
		$data = json_decode($dataOrg);
		
		if( $data->status != "OK") {
			echo $dataOrg;
			exit;
		}
		else {
			$_text = explode(" ", $data->rows[0]->elements[0]->distance->text );
			
			$arr = array('status' => 'OK', 'distance' => 
				array(
				'text' => str_replace(",", ".", $_text[0] ), 
				'textKM' => strtolower( $_text[1] ), 
				'value' => $data->rows[0]->elements[0]->duration->value,
				
				),
				'status_sub' => $data->rows[0]->elements[0]->status
			);
			echo json_encode($arr);
		}
		/*
		$time = 0;
		$distance = 0;
		foreach($data->rows[0]->elements as $road) {
			$time += $road->duration->value;
			$distance += $road->distance->value;
		}
		print_r( $data );
		echo "To: ".$data->destination_addresses[0];
		echo "<br/>";
		echo "From: ".$data->origin_addresses[0];
		echo "<br/>";
		echo "Time: ".$time." seconds";
		echo "<br/>";
		echo "Distance: ".$distance." meters";
		*/
		exit;
	}

	
}