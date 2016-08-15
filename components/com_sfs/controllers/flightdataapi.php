<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class SfsControllerFlightdataapi extends JController {

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
		$file = 'flight-data.xml';
		$rawpostxml->fwriteFile("tmp/flight-data-api/$file", $strXML);
		header("Content-type: text/xml");
		die( $strXML );
	}
	
	
	public function loadxml()
	{
		$log_dir = JPATH_BASE . '/tmp/flight-data-api/';
		$file_name_log = $log_dir . date('Y-m-d-His-') . "api-flight-data.log";
			
		$rawpostxml = new Api_Rawpostxml();
		if( !$_POST && isset($_GET['is_static'] ) && $_GET['is_static'] == 1 ) {
			$file_name = "flight-data.xml";///$this->uploadFile();
			$file =  'tmp/flight-data-api/' . $file_name;
			$url = JUri::base() . $file;
			$xml_content = self::getDataFile( $url );
		}
		else {
			
			if(!$_POST){
				self::getError("-1");
			}
			$xml_content = file_get_contents("php://input");
			
			
			/*$url = JUri::base() . $file;
			$xml_content = self::getDataFile( $url );
			$rawpostxml = new Api_Rawpostxml();
			*/
			
		}
		
		//Ghi log
		$rawpostxml->fwriteFile( $file_name_log, $xml_content);
			
		$strS = array('encoding="UTF-8">','<xml','FlightDate','fltref', 'Carrier', 'FlightNoSuffix', 
						'FlightNo', 'Registration', 'AcType',
 						'AcOperator', 'AcSeatsTotal', 'Dep', 'Arr',
						'Div', 'ReturnToRamp', 'Std',
						'Sta', 'Etd', 'Eta', 'Atd',
 						'Ata', 'Delay', 'ServiceType',
						'PaxExpTotal','IrregReason','IrregMessage', 'GateInfo');
		
		$strRep = array('encoding="UTF-8"?>','<?xml','flight_date','fltref', 'carrier', 'flight_no_suffix', 
						'flight_no', 'registration', 'ac_type',
						'ac_operator', 'ac_seats_total', 'dep', 'arr',
						'div', 'return_to_ramp', 'std', 'sta',
						'etd', 'eta', 'atd', 
						'ata', 'delay', 'service_type', 
						'pax_exp_total','irreg_reason', 'irreg_message', 'gate_info');
		///$xml_content = strtolower($xml_content);
		$xml_content = str_replace( $strS, $strRep, $xml_content);
		///print_r( $xml_content );die;
							
		if ( $xml_content == '' ) {
			self::getError("304");
		}
		$xml = new SimpleXMLElement($xml_content);
		///print_r( $xml );die;
		$method = $xml->method;
		
		if ( $method != 'flight/push' ) {
			$this->getError('400');
		}
		
		$unique = trim( $xml->api_token );

		$airline_id = 0;
		$iatacode_id = 0;
		$dAir = $this->getAirlineID( $unique );
		///print_r( $dAir );
		if ( $dAir != '' ) {
			$airline_id = $dAir->id;
			//$iatacode_id = $dAir->iatacode_id;
		}
		else {
			self::getError('01');
		}
		
		$data = $xml->data->leg;
		
		///print_r( $data );die;
		//check column in table 
		$db 		= JFactory::getDbo();	
		$query  = 'SHOW COLUMNS FROM #__sfs_flightinfo';	
		$db->setQuery($query);	
		$dColumns = $db->loadObjectList();	
		$dColumnArr = array();	
		foreach ( $dColumns as $dColumn) {	
			$vc = $dColumn->Field;	
			$dColumnArr[$vc] = '';	
		}	
		$dataCheck = (array)$data[0];	
		$strNotIn = array();	
		foreach ( $dataCheck as $vcol => $v ) {	
			if ( !isset($dColumnArr[$vcol]) ) {	
				$strNotIn[] = '&lt;' . $vcol . '&gt;';	
			}	
		}
		///print_r( $strNotIn );die;
		if( !empty( $strNotIn ) ){
			self::getError('403', implode(", ", $strNotIn) );
		}
		//End check column in table
		
		$fileds = $rawpostxml->fileds;
		$dataI = array();
		$id_insert = 0;
		$historyArr = array();
		foreach ( $data as $vk => $v ) {
			$dataI = (array)$v;
			$historyArr[] = $dataI;
			
			$data_flightinfo = (object)$dataI;	
			$data_flightinfo->airline_id = $airline_id;
			$delayArr = array();
			foreach( $v->delay as $delay){
				$delayArr[] = $delay;
			}
			$data_flightinfo->delay = json_encode( $delayArr);
			$data_flightinfo->date_timestamp = date('Y-m-d H:i:s');
			
			if( self::checkInsert_history_api_flightinfo( $data_flightinfo->fltref ) ) {
				
				$flightinfo_id = self::insert_flightinfo( $data_flightinfo, true );
			}
			else {
				self::insert_history_api_flightinfo( $data_flightinfo );
				$flightinfo_id = self::insert_flightinfo( $data_flightinfo );		
			}
		}
		/*
		$data_history = new stdClass();
		$data_history->created = date('Y-m-d H:i:s');
		$data_history->contents = json_encode( $historyArr );
		self::insert_history_api_flightinfo( $data_history );
		*/
		if ( $flightinfo_id == 0 ) {
			self::getError('406');
		}
		elseif( $flightinfo_id > 0 ){
			self::getError('200');
		}
		
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
	
	public function getDataFile( $url )
	{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	public function uploadFile()
	{
		//echo JUri::base();die;
		// Upload file
		$file_name = time() . '-' . $_FILES['flightinfo']['name'];
		$fileTemp = $_FILES['flightinfo']['tmp_name'];
		$uploadPath = JPATH_SITE.DS.'tmp'.DS.'xml'.DS.$file_name;
 		if(JFile::upload($fileTemp, $uploadPath)) 
		{
			return $file_name;
		}
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
	
	public function getError( $type_err = 0, $textMore = NULL ){
		$rawpostxml = new Api_Rawpostxml();
		switch( $type_err ) {
			
			case"-1"://use
				$xmlError = $rawpostxml->rawpostxmlError('-1', 'Please use the method post');
				$rawpostxml->fwriteFile(JPATH_BASE . 'tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"01"://use
				$xmlError = $rawpostxml->rawpostxmlError('01', 'Not find access token');
				$rawpostxml->fwriteFile(JPATH_BASE . 'tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"406"://use
				$xmlError = $rawpostxml->rawpostxmlError('406', 'Not accepted, your xml scheme is not according to the specifications');
				$rawpostxml->fwriteFile(JPATH_BASE . 'tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"200"://use
				$xmlError = $rawpostxml->rawpostxmlError('200', 'OK Success!');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"304"://use
				$xmlError = $rawpostxml->rawpostxmlError('304', 'Not Modified There was no new data to return.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"400"://use
				$xmlError = $rawpostxml->rawpostxmlError('400', 'Bad Request The request was invalid or cannot be otherwise served, requests without authentication are considered invalid and will yield this response');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"401":
				$xmlError = $rawpostxml->rawpostxmlError('401', 'Unauthorized Authentication credentials were missing or incorrect.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"403"://use
				///$xmlError = $rawpostxml->rawpostxmlError('403', 'Forbidden The request is understood, but it has been refused also returned when the requested format is not supported by the requested method. An accompanying error message will explain why.');
				$xmlError = $rawpostxml->rawpostxmlError('403', 'Bad Request The request was invalid or cannot be otherwise served, requests without authentication are considered invalid and will yield this response; element ' . $textMore . ' does not have the correct format');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"404":
				$xmlError = $rawpostxml->rawpostxmlError('404', 'Not Found The URI requested is invalid or the resource requested.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"406":
				$xmlError = $rawpostxml->rawpostxmlError('406', 'Not Acceptable Returned by the Search API when an invalid format is specified in the request.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"410":
				$xmlError = $rawpostxml->rawpostxmlError('410', 'Gone This resource is gone. Used to indicate that an API endpoint has been turned off. For example: “The REST API v1 will soon stop functioning. Please migrate to API v1.1.”');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"429":
				$xmlError = $rawpostxml->rawpostxmlError('429', 'Too Many Requests Returned in API v1.1 when a request cannot be served due to the application’s rate limit having been exhausted for the resource.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"500":
				$xmlError = $rawpostxml->rawpostxmlError('500', ' Internal Server Error Something is broken. Please send an email to the SFS dev team so we can investigate.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"502":
				$xmlError = $rawpostxml->rawpostxmlError('502', 'Bad Gateway Service is down or being upgraded.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"503":
				$xmlError = $rawpostxml->rawpostxmlError('503', 'Service Unavailable The servers are up, but overloaded with requests. Try again later.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"504"://use
				$xmlError = $rawpostxml->rawpostxmlError('504', 'Gateway timeout The servers are up, but the request couldn’t be serviced due to some failure within our stack. Try again later.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/flight-data-api/flight-data-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
		}
	}
}

class Api_Rawpostxml{

	protected $_method = '';
	protected $_unique = '';
	public $fileds = array();
	
	public function __construct() {
		$filed_get_data = array();
		$filed_get_data['flight_date'] = 'flight_date';
		$filed_get_data['fltref'] = 'fltref';
		$filed_get_data['carrier'] = 'carrier';
		$filed_get_data['flight_no'] = 'flight_no';
		$filed_get_data['flight_no_suffix'] = 'flight_no_suffix';
		$filed_get_data['registration'] = 'registration';
		$filed_get_data['ac_type'] = 'ac_type';
		$filed_get_data['ac_operator'] = 'ac_operator';
		$filed_get_data['ac_seats_total'] = 'ac_seats_total';
		$filed_get_data['dep'] = 'dep';
		$filed_get_data['arr'] = 'arr';
		$filed_get_data['div'] = 'div';
		
		//add more
		$filed_get_data['return_to_ramp'] = 'return_to_ramp';
		//End add more
		
		$filed_get_data['std'] = 'std';
		$filed_get_data['sta'] = 'sta';		
		$filed_get_data['etd'] = 'etd';
		$filed_get_data['eta'] = 'eta';
		$filed_get_data['atd'] = 'atd';
		$filed_get_data['ata'] = 'ata';
		
		//add more 
		$filed_get_data['delay'] = 'delay';	
		//End add more
		
		$filed_get_data['service_type'] = 'service_type';
		$filed_get_data['pax_exp_total'] = 'pax_exp_total';
		
		//add more 
		$filed_get_data['irreg_reason'] = 'irreg_reason';		
		$filed_get_data['irreg_message'] = 'irreg_message';			
		$filed_get_data['gate_info'] = 'gate_info';	
		//End add more
		
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
	
	public function rawpostxmlError( $errno = 01, $error_message = 'Error message', $method = 'insert flightinfo' )
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
		$fp = fopen($filename, 'w');
		fwrite($fp, $response);
		fclose($fp);
	}
}