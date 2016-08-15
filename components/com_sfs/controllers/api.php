<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class SfsControllerApi extends JController {

	public function rawpostxml()
	{
		$data = array();//new stdClass();
		$rawpostxml = new Api_Rawpostxml();
		$fileds = $rawpostxml->fileds;
		
		foreach ( $fileds as $kfiled => $filed ) {
			$data[$kfiled] = 'sample data test';
		}
		
		$dataArr[] = $data;
		//print_r( $dataArr );die;
		$strXML = $rawpostxml->rawpostxml( $dataArr );
		$file = 'passenger-push.xml';
		$rawpostxml->fwriteFile("tmp/xml/$file", $strXML);
		header("Content-type: text/xml");
		die( $strXML );
	}
	
	
	public function loadxml()
	{
		$log_dir = JPATH_BASE . '/tmp/xml/';
		$file_name_log = $log_dir . date('Y-m-d-His-') . "api-passenger-push.log";
		$rawpostxml = new Api_Rawpostxml();
		if( !$_POST && isset($_GET['is_static'] ) && $_GET['is_static'] == 1 ) {
			$file_name = "passenger-push-new.xml";///$this->uploadFile();
			$file =  'tmp/xml/' . $file_name;
			$url = JUri::base() . $file;
			$xml_content = self::getDataFile( $url );
			//JPATH_BASE
			//JRoute::_(JUri::base(), false) . $file; //'http://sfs.dev/api/test/push-passengers.php';//
		}
		else {
			if(!$_POST){
				self::getError("-1");
			}
			$xml_content = file_get_contents("php://input");			
			
		}	
		
		//Ghi log
		$rawpostxml->fwriteFile( $file_name_log, $xml_content);
		$strS = array('firstname','firstName', 'lastName', 'emailAddress', 
						'bookingClass', 'bookingId', 'bookingNameId',
						'bookingNameItemId', 'cabinClass', 'officeId', 'ticketNumber',
						'ticketStatus', 'tourOperator', 'baggageStatus', 'checkInStatus',
						'IrregMessageSent', 'Irregreason', 'IrregReason',
						'phone', 'fqtvNumber', 'fqtvProgram', 'fqtvStatus'
						);
		$strRep = array('first_name','first_name', 'last_name', 'email_address', 
						'booking_class', 'booking_id_import', 'booking_name_id',
						'booking_name_item_id', 'cabin_class', 'office_id', 'ticket_number',
						'ticket_status', 'tour_operator', 'baggage_status', 'checkin_status',
						'irreg_message_sent', 'IrregReason', 'irreg_reason', 
						'phone_number', 'fqtv_number', 'fqtv_program', 'fqtv_status'
						);
		
		///$xml_content = strtolower($xml_content);
		$xml_content = str_replace( $strS, $strRep, $xml_content);
			//print_r( $xml_content );die;
		///$xml_content = $this->getDataFile( $url );
		///$rawpostxml = new Api_Rawpostxml();
		//$file = 'tmp/xml/passenger-push.xml';
		//$xml_content = file_get_contents( $file );
		if ( $xml_content == '' ) {
			$this->getError("304");
		}
		$xml = new SimpleXMLElement($xml_content);

		$method = $xml->method;
		
		if ( $method != 'passenger/push' ) {
			$this->getError('400');
		}
		
		$unique = trim( $xml->api_token );
		
		$airline_id = 0;
		$iatacode_id = 0;
		$dAir = $this->getAirlineID( $unique );
		$params = json_decode( $dAir->params );
		if( isset( $params->api_enabled ) && $params->api_enabled == '0' ){
			$this->getError('-2');
		}
		if ( $dAir != '' ) {
			$airline_id = $dAir->id;
			//$iatacode_id = $dAir->iatacode_id;
		}
		///$data = $xml->data->passengers->passenger;
		$data = $xml->passengers->passenger;
		
		//check column in table 
		$db 		= JFactory::getDbo();
		$query  = 'SHOW COLUMNS FROM #__sfs_trace_passengers';
        $db->setQuery($query);
        $dColumns = $db->loadObjectList();
		$dColumnArr = array();
		foreach ( $dColumns as $dColumn) {
			$vc = $dColumn->Field;
			if( $vc != 'priority' &&  $vc != 'emailaddress_ssr' &&  $vc != 'phonenumber_ssr' )//cong diem nen khg co tren xml
				$dColumnArr[$vc] = '';
		}
		$dataCheck = (array)$data[0];
		$strNotIn = array();
		foreach ( $dataCheck as $vcol => $v ) {
			if ( !isset($dColumnArr[$vcol]) ) {
				$strNotIn[] = $vcol;
			}
		}
		
		if( !empty( $strNotIn ) ){
			self::getError('403', implode(", ", $strNotIn) );
		}
		//End check column in table
		
		//check SSR type the add point
		$SSR_Type = self::getSSRType();
		//print_r( $SSR_Type );die;
		$fileds = $rawpostxml->fileds;
		$dataI = array();
		$data_trace_passengers = array();
		$id_insert = 0;
		///$historyArr = array();
		foreach ( $data as $vk => $v ) {			
			$dataI = (array)$v;
			
			///$historyArr[] = $dataI;
			
			$data_trace_passengers = (object)$dataI;
			///print_r( $data_trace_passengers );die;
			
			$data_trace_passengers->airline_id = $airline_id;
			$data_trace_passengers->created_date = date('Y-m-d');
			$data_trace_passengers->created = date('Y-m-d');			
			$data_trace_passengers->flight_iatacode = $iatacode_id;
			$data_trace_passengers->ssrs = json_encode($v->ssrs);
			$data_trace_passengers->priority = 0;
			
			$dPoint = self::checkPoint_priority($v->fqtv_status, 1);
			if( $dPoint != 'null' ){
				$data_trace_passengers->priority += $dPoint->point;
			}
			
			$dPoint = self::checkPoint_priority($v->irreg_reason, 2);
			if( $dPoint != 'null' ){
				$data_trace_passengers->priority += $dPoint->point;
			}
			
			/*switch( $v->fqtv_status ){
				case"PLAT":
				case"GOLD":
				case"FIRST":
					$data_trace_passengers->priority += 1;
				break;
			}
			switch( $v->irreg_reason ){
				case"TRFW":
				case"TRFA":
				case"INAD":
					$data_trace_passengers->priority += 1;
				break;
			}*/
			
			foreach ($v->ssrs->ssr as $vSsr) {
				$type = (string)$vSsr->type;
				$key = (string)$vSsr->key;
				
				$dPoint = self::checkPoint_priority($key, 3);
				if( $dPoint != 'null' ){
					$data_trace_passengers->priority += $dPoint->point;
				}
				
				/*if( isset( $SSR_Type[$type] ) ){
					$data_trace_passengers->priority += 1;
				}*/
				if( $type == 'CTCM' ){
					$key_p = str_replace($type . "-", "", $key);
					$key_pArr = explode("/", $key_p);
					$data_trace_passengers->phonenumber_ssr = $key_pArr[0];
				}
				if( $type == 'CTCE' ){
					$key_p = str_replace($type . "-", "", $key);
					$key_p = str_replace("//", '@', $key_p);
					$key_p = str_replace("/", '.', $key_p);
					$data_trace_passengers->emailaddress_ssr = $key_p;
				}
			}
			//print_r( $data_trace_passengers );
			//die;
			$data_passengers_airplus = new stdClass();
			$data_passengers_airplus->pnr = $data_trace_passengers->pnr;
			$data_passengers_airplus->airline_id = $data_trace_passengers->airline_id;
			$data_passengers_airplus->flight_number = $data_trace_passengers->flight_code;
			$connections = $data_trace_passengers->connections;
			//print_r($connections);die;
			
			$db 		= JFactory::getDbo();	
			$query  = 'SHOW COLUMNS FROM #__sfs_flightinfo';	
			$db->setQuery($query);	
			$dColumns = $db->loadObjectList();	
			$dColumnArr = array();
			foreach ( $dColumns as $dColumn) {	
				$vc = $dColumn->Field;	
				$dColumnArr[$vc] = '';				
			}
			//nhung column not unset when update
			$dColumnArr_notunset['std'] = '';
			$dColumnArr_notunset['sta'] = '';
			$dColumnArr_notunset['airline_id'] = '';
			$dColumnArr_notunset['fltref'] = '';
			$dColumnArr_notunset['carrier'] = '';
			$dColumnArr_notunset['flight_no'] = '';
			$dColumnArr_notunset['dep'] = '';
			$dColumnArr_notunset['arr'] = '';
			$dColumnArr_notunset['flight_date'] = '';
			$dColumnArr_notunset['delay'] = '';
			
			$connectionArr = array();
			foreach( $connections as $k => $connection ) {
				$connectionArr[][$k] = $connection;
			}
			
			if( $method == 'passenger/push' ) {
				
				foreach( $connectionArr as $connectionData ) {
					
					foreach( $connectionData as $k => $Dataconnection ) {
					
						if( $k == 'inboundconnection' ){
							$dColumnArr['std'] = (string)$Dataconnection->Std;
							$dColumnArr['sta'] = (string)$Dataconnection->Sta;
						}
						else {
							$dColumnArr['std'] = (string)$Dataconnection->std;
							$dColumnArr['sta'] = (string)$Dataconnection->sta;
						}
						
						if( $dColumnArr['std'] != ''  ){
										
							$dColumnArr['airline_id'] = $airline_id;
							$dColumnArr['fltref'] = (string)$Dataconnection->flightref;
							$dColumnArr['carrier'] = (string)$Dataconnection->carrier;
							$dColumnArr['flight_no'] = (string)$Dataconnection->fltno;
							$dColumnArr['dep'] = (string)$Dataconnection->dep;
							$dColumnArr['arr'] = (string)$Dataconnection->arr;					
							$dColumnArr['flight_date'] = $dColumnArr['std'];							
							
							$dColumnArr['delay'] = json_encode( array() );
							$data_flightinfo = (object)$dColumnArr;
							$data_flightinfo->date_timestamp = date('Y-m-d H:i:s');
							
							if( self::checkInsert_history_api_flightinfo( $data_flightinfo->fltref ) ) {
								foreach( $dColumnArr as $key_unset => $v_notunset ){
									if(  !isset( $dColumnArr_notunset[$key_unset] ) ) {	
										unset( $data_flightinfo->$key_unset );
									}
								}
								$flightinfo_id = self::insert_flightinfo( $data_flightinfo, true );
							}
							else {
								self::insert_history_api_flightinfo( $data_flightinfo );
								$flightinfo_id = self::insert_flightinfo( $data_flightinfo );		
							}
							
							///$flightinfo_id = self::insert_flightinfo( $data_flightinfo );	
						}
					}					
				}
				/*
				$data_history = new stdClass();
				$data_history->created = date('Y-m-d H:i:s');
				$data_history->contents = json_encode( $connectionArr );
				self::insert_history_api_flightinfo( $data_history );
				*/
				$data_trace_passengers->connections  = json_encode( $connectionArr );
				
				//print_r( $data_trace_passengers );die;
				$airplus_id = $this->insert_passengers_airplus( $data_passengers_airplus );
				
				$data_trace_passengers->airplus_id = $airplus_id;
				$data_trace_passengers->date_timestamp = date('Y-m-d H:i:s');
				
				$option['pnr'] = $data_trace_passengers->pnr;
				$option['first_name'] = $data_trace_passengers->first_name;
				$option['last_name'] = $data_trace_passengers->last_name;
				$option['type'] = $data_trace_passengers->type;
				
				if( self::checkHistory_api_passenger_push( $option ) != 'null' ) {	
					$d = self::checkTracePassengers( $option );		
					if( $d != 'null' ) {
						$data_trace_passengers->id = $d->id;
						$id_insert = $this->passenger_push( $data_trace_passengers, true ) ;
					}
				}
				else {
					$id_insert = self::passenger_push( $data_trace_passengers );
					self::insert_history_api_passenger_push( $data_trace_passengers );	
				}
			}			
		}
		/*
		$data_history = new stdClass();
		$data_history->created = date('Y-m-d H:i:s');
		$data_history->contents = json_encode( $historyArr );
		self::insert_history_api_passenger_push( $data_history );
		*/
		if ( $id_insert == 0 ) {
			$this->getError('02');
		}
		elseif( $id_insert > 0 ){
			$this->getError('200');
		}
		
	}
	
	public function getDataFile( $url )
	{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		 //$curl_errno= curl_errno($ch);
		  
		if( $http_status != 200 ){
			$this->getError( $http_status );
		}
		curl_close($ch);
		
		return $data;
	}
	
	public function uploadFile()
	{
		//echo JUri::base();die;
		// Upload file
		$file_name = time() . '-' . $_FILES['passenger_push']['name'];
		$fileTemp = $_FILES['passenger_push']['tmp_name'];
		$uploadPath = JPATH_SITE.DS.'tmp'.DS.'xml'.DS.$file_name;
 		if(JFile::upload($fileTemp, $uploadPath)) 
		{
			return $file_name;
		}
	}
	
	public function passenger_push( $dataObject, $is_upodate = false )
	{	
		///echo $is_upodate;
		///print_r( $dataObject );die;
		$db 		= JFactory::getDbo();
		if( $is_upodate ){
			$db->updateObject('#__sfs_trace_passengers', $dataObject, "id");
			return 1;
		}
		else {
			$db->insertObject('#__sfs_trace_passengers', $dataObject);
			return $db->insertid();
		}
	}
	
	public function insert_passengers_airplus( $dataObject )
	{	///print_r( $dataObject );die;
		$db 		= JFactory::getDbo();
		$db->insertObject('#__sfs_passengers_airplus', $dataObject);
		return $db->insertid();
	}
	
	public function insert_history_api_passenger_push( $dataObject )
	{	///print_r( $dataObject );die;
		$db 		= JFactory::getDbo();
		$db->insertObject('#__sfs_history_api_passenger_push', $dataObject);
		return true;
	}
	
	public function getAirlineID( $unique_token = '' )
	{
		$db 		= JFactory::getDbo();
		$query  = 'SELECT a.* FROM #__sfs_airline_details AS a';
        $query .= ' WHERE a.unique_token="' . $unique_token . '"';
        $db->setQuery($query);
        $d = $db->loadObject();
		if( !empty( $d ) ){
			return $d;
		}
		return '';
	}
	
	public function insert_flightinfo( $dataObject, $is_upodate = false )
	{	///print_r( $dataObject );die;
		$db 		= JFactory::getDbo();
		if( $is_upodate ){
			$db->updateObject('#__sfs_flightinfo', $dataObject, "fltref");
			return 1;
		}
		else {
			$db->insertObject('#__sfs_flightinfo', $dataObject);
			return $db->insertid();
		}
	}
	
	public function insert_history_api_flightinfo( $dataObject )
	{	//print_r( $dataObject );die;
		$db 		= JFactory::getDbo();
		$db->insertObject('#__sfs_history_api_flightinfo', $dataObject);
		return true;
	}
	
	public function checkInsert_history_api_flightinfo( $fltref )
	{
		$db 		= JFactory::getDbo();
		$query  = 'SELECT a.id FROM #__sfs_history_api_flightinfo AS a';
       	$query .= ' WHERE a.fltref="' . $fltref . '"';
        $db->setQuery($query);
        $d = $db->loadObject();
		if( !empty( $d ) ){
			return true;
		}
		return false;
	}
	
	public function checkPoint_priority( $name, $type_group )
	{
		$db 		= JFactory::getDbo();
		$query  = 'SELECT a.id, a.name, a.type_group, a.point FROM #__sfs_point_priority AS a';
       	$query .= ' WHERE a.name="' . $name . '" And a.type_group=' . (int)$type_group;
        $db->setQuery($query);
        $d = $db->loadObject();
		if( !empty( $d ) ){
			return $d;
		}
		return 'null';
	}
	
	public function update_point_priority( $dataObject )
	{	///print_r( $dataObject );die;
		$db 		= JFactory::getDbo();
		$db->updateObject('#__sfs_point_priority', $dataObject, "id");
		return 1;
	}
	
	public function checkTracePassengers( $option )
	{
		$db 		= JFactory::getDbo();
		$query  = 'SELECT a.id FROM #__sfs_trace_passengers AS a';
       	$query .= ' WHERE a.`pnr`="' . $option['pnr'] . '"';		
		$query .= ' AND a.`first_name`="' . $option['first_name'] . '"';
		$query .= ' AND a.`last_name`="' . $option['last_name'] . '"';
		$query .= ' AND a.`type`=' . (int)$option['type'];
        $db->setQuery($query);
        $d = $db->loadObject();
		if( !empty( $d ) ){
			return $d;
		}
		return 'null';
	}
	
	public function checkHistory_api_passenger_push( $option )
	{
		$db 		= JFactory::getDbo();
		$query  = 'SELECT a.id FROM #__sfs_history_api_passenger_push AS a';
       	$query .= ' WHERE a.`pnr`="' . $option['pnr'] . '"';		
		$query .= ' AND a.`first_name`="' . $option['first_name'] . '"';
		$query .= ' AND a.`last_name`="' . $option['last_name'] . '"';
		$query .= ' AND a.`type`=' . (int)$option['type'];
        $db->setQuery($query);
        $d = $db->loadObject();
		if( !empty( $d ) ){
			return $d;
		}
		return 'null';
	}
	
	public function getError( $type_err = 0, $textMore = NULL ){
		$rawpostxml = new Api_Rawpostxml();
		switch( $type_err ) {
			
			case"-1"://use
				$xmlError = $rawpostxml->rawpostxmlError('-1', 'Please use the method post');
				$rawpostxml->fwriteFile(JPATH_BASE . 'tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"-2"://use
				$xmlError = $rawpostxml->rawpostxmlError('-2', 'API is disabled Please contact to administration the Activate API');
				$rawpostxml->fwriteFile(JPATH_BASE . 'tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			
			case"02"://use
				$xmlError = $rawpostxml->rawpostxmlError('02', 'Not insert data');
				$rawpostxml->fwriteFile(JPATH_BASE . 'tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"200"://use
				$xmlError = $rawpostxml->rawpostxmlError('200', 'OK Success!');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"304"://use
				$xmlError = $rawpostxml->rawpostxmlError('304', 'Not Modified There was no new data to return.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"400"://use
				$xmlError = $rawpostxml->rawpostxmlError('400', 'Bad Request The request was invalid or cannot be otherwise served, requests without authentication are considered invalid and will yield this response');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"401":
				$xmlError = $rawpostxml->rawpostxmlError('401', 'Unauthorized Authentication credentials were missing or incorrect.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"403"://use
				$xmlError = $rawpostxml->rawpostxmlError('403', 'Unknown item "' . $textMore . '"');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"404":
				$xmlError = $rawpostxml->rawpostxmlError('404', 'Not Found The URI requested is invalid or the resource requested.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"406":
				$xmlError = $rawpostxml->rawpostxmlError('406', 'Not Acceptable Returned by the Search API when an invalid format is specified in the request.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"410":
				$xmlError = $rawpostxml->rawpostxmlError('410', 'Gone This resource is gone. Used to indicate that an API endpoint has been turned off. For example: “The REST API v1 will soon stop functioning. Please migrate to API v1.1.”');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"429":
				$xmlError = $rawpostxml->rawpostxmlError('429', 'Too Many Requests Returned in API v1.1 when a request cannot be served due to the application’s rate limit having been exhausted for the resource.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"500":
				$xmlError = $rawpostxml->rawpostxmlError('500', ' Internal Server Error Something is broken. Please send an email to the SFS dev team so we can investigate.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"502":
				$xmlError = $rawpostxml->rawpostxmlError('502', 'Bad Gateway Service is down or being upgraded.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"503":
				$xmlError = $rawpostxml->rawpostxmlError('503', 'Service Unavailable The servers are up, but overloaded with requests. Try again later.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"504"://use
				$xmlError = $rawpostxml->rawpostxmlError('504', 'Gateway timeout The servers are up, but the request couldn’t be serviced due to some failure within our stack. Try again later.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/xml/passenger-push-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
		}
	}
	
	public function getSSRType(){
		$SSR_TypeArr['UMNR'] = '';
		$SSR_TypeArr['WCHR'] = '';
		$SSR_TypeArr['WCHS'] = '';
		$SSR_TypeArr['WCHC'] = '';
		$SSR_TypeArr['WCBW'] = '';
		$SSR_TypeArr['WCLB'] = '';
		$SSR_TypeArr['WCBD'] = '';
		$SSR_TypeArr['WCMP'] = '';
		$SSR_TypeArr['WCOB'] = '';
		$SSR_TypeArr['BLND'] = '';
		$SSR_TypeArr['DEAF'] = '';
		$SSR_TypeArr['DPNA'] = '';
		$SSR_TypeArr['DPNU'] = '';
		$SSR_TypeArr['SVAN'] = '';
		$SSR_TypeArr['SITZ'] = '';
		$SSR_TypeArr['XBAG'] = '';
		$SSR_TypeArr['PREM'] = '';
		$SSR_TypeArr['BUSI'] = '';
		$SSR_TypeArr['GOLD'] = '';
		$SSR_TypeArr['PLAT'] = '';
		$SSR_TypeArr['EMER'] = '';
		$SSR_TypeArr['SAPH'] = '';
		$SSR_TypeArr['DOWN'] = '';
		$SSR_TypeArr['DEPA'] = '';
		$SSR_TypeArr['DEPU'] = '';
		$SSR_TypeArr['MEDA'] = '';
		$SSR_TypeArr['STCR'] = '';
		return $SSR_TypeArr;
	}
	
}

class Api_Rawpostxml{

	protected $_method = 'passenger/push';
	protected $_unique = '';
	public $fileds = array();
	
	public function __construct() {
		$filed_get_data = array();
		$filed_get_data['first_name'] = 'first_name';
		$filed_get_data['last_name'] = 'last_name';
		$filed_get_data['title'] = 'title';
		
		//add more 3
		$filed_get_data['rawname'] = 'rawname';
		$filed_get_data['fltno'] = 'fltno';
		$filed_get_data['connections'] = 'connections';
		$filed_get_data['irreg_message_sent'] = 'irreg_message_sent';
		$filed_get_data['irreg_reason'] = 'irreg_reason';
		
		
		//add more 2
		$filed_get_data['fltref'] = 'fltref';
		$filed_get_data['rebooked_fltno'] = 'rebooked_fltno';
		$filed_get_data['rebooked_fltref'] = 'rebooked_fltref';
		$filed_get_data['email_address'] = 'email_address';
		$filed_get_data['phone_number'] = 'phone_number';
		//End add more 2
		
		$filed_get_data['flight_code'] = 'flight_code';
		$filed_get_data['flight_iatacode'] = 'flight_iatacode';
		
		//add more
		$filed_get_data['pnr'] = 'pnr';
		$filed_get_data['booking_class'] = 'booking_class';
		$filed_get_data['booking_id_import'] = 'booking_id_import';//khi import file
		$filed_get_data['booking_name_id'] = 'booking_name_id';
		$filed_get_data['booking_name_item_id'] = 'booking_name_item_id';
		$filed_get_data['cabin_class'] = 'cabin_class';
		$filed_get_data['gender'] = 'gender';
		$filed_get_data['language'] = 'language';
		$filed_get_data['office_id'] = 'office_id';		
		$filed_get_data['ticket_number'] = 'ticket_number';
		$filed_get_data['ticket_status'] = 'ticket_status';
		$filed_get_data['tour_operator'] = 'tour_operator';
		
		$filed_get_data['ssrs'] = 'ssrs';//'ssr';
		//add more 2
		$filed_get_data['baggage_status'] = 'baggage_status';
		$filed_get_data['checkin_status'] = 'checkin_status';
		$filed_get_data['maas'] = 'maas';
		//End add more 2
		
			
		$filed_get_data['fqtv_number'] = 'fqtv_number';
		$filed_get_data['fqtv_program'] = 'fqtv_program';
		$filed_get_data['fqtv_status'] = 'fqtv_status';		
		//End add more
		
		//add again 
		$filed_get_data['value001'] = 'value001';
		$filed_get_data['value002'] = 'value002';
		$filed_get_data['value003'] = 'value003';
		$filed_get_data['value004'] = 'value004';
		$filed_get_data['value005'] = 'value005';
		$filed_get_data['value006'] = 'value006';
		
		//add 04/07/2016
		$filed_get_data['priority'] = 'priority';
		
		$this->fileds = $filed_get_data;
	}
	
	private function _headerXML()
	{
		$airline = SFactory::getAirline();
		$this->_unique = 'RKZ6Rs9PQjDYwSrWpgcQfZNWpYTiOTi2';//base64_encode( uniqid() . '-' . $airline->id );
		
		$str = '<?xml version="1.0" encoding="UTF-8"?>';
		$str .= "\n<request>\n";
		$str .= "\t<method>" . $this->_method . "</method>\n";
		$str .= "\t<api_token>" . $this->_unique . "</api_token>\n";
		$str .= "\t<data>\n";
		$str .= "\t\t<passengers>\n";
		return $str;
	}
	
	public function dataXML( $data = array() )
	{
		$str = "";
		foreach ( $data as $vk => $v ) {
		$str .="\n\t\t\t<passenger>\n";
		foreach ( $this->fileds as $vkS => $filed ) {
			$str .="\t\t\t\t<$vkS>$v[$vkS]</$vkS>\n";
		}
		$str .="\t\t\t</passenger>\n";
		}
		return $str ."\n";
	}
	
	private function _endXML()
	{
		$str = "\t\t</passengers>\n";
		$str .= "\t</data>\n";
		$str .= "</request>";
		return $str;
	}
	
	public function rawpostxml( $data = array () )
	{
		$str = self::_headerXML();
		$str .= self::dataXML( $data );
		$str .= self::_endXML();
		return $str;
	}
	
	public function rawpostxmlError( $errno = 01, $error_message = 'Error message', $method = 'passenger/push' )
	{
		$str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
		<response>
			<method>$method</method>
			<no>$errno</no>
			<error>$error_message</error>
		</response>";
		
		return $str;
	}
	
	public function fwriteFile( $filename = '', $response ){
		//echo $filename;die;
		$fp = fopen($filename, 'w');
		fwrite($fp, $response);
		fclose($fp);
	}
}