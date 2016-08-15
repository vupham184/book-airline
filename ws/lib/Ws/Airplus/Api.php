<?php

class Ws_Airplus_Api {
	
	protected $_url = 'https://aidatest2.airplus.com/Athena2/XMLServlet';
	
	protected $_company = '';
	
	protected $_password = '';
	
	protected $_user = '';
	
	protected $_card = '';
	
	protected $_cardExpiry = '';
	
	public function __construct($config) {
		if(empty($config['company'])) {
			throw new Exception('Empty company');
		}
		
		if(empty($config['user'])) {
			throw new Exception('Empty user');
		}
		
		if(empty($config['password'])) {
			throw new Exception('Empty password');
		}
		
		if(empty($config['card'])) {
			throw new Exception('Empty card');
		}
		
		if(empty($config['cardExpiry'])) {
			throw new Exception('Empty card expiry');
		}
		
		$this->_company = $config['company'];
		$this->_password = $config['password'];
		$this->_user = $config['user'];
		$this->_card = $config['card'];
		$this->_cardExpiry = $config['cardExpiry'];
	}
	
	public function request(Ws_Airplus_Request $request){
		$authXml = $this->_getAuthXml();
		$preScreenXml = $this->_getPreScreening();
		$dbiXml = $this->_getDbiXml($request);
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<OrbiscomRequest IssuerId=\"1\">
	{$authXml}
	{$preScreenXml}
	{$dbiXml}
</OrbiscomRequest>
				";
        $obj = $this->_callApi($xml);
		return $obj;
	}
	
	private function _getAuthXml() {
		return "<AuthenticationBE
 AppendResponse=\"false\"
 Company=\"{$this->_company}\"
 Password=\"{$this->_password}\"
 User=\"{$this->_user}\">
 </AuthenticationBE>";
	}
	
	private function _getPreScreening() {
		return "<PreScreening
 RequestType=\"BookingEngine\"
 AppendResponse=\"false\"
 ExpiryDate=\"{$this->_cardExpiry}\"
 UATPCardNumber=\"{$this->_card}\">
</PreScreening>";
	}
	
	// $options['dbi']
	// $options['taxi']
	// $options['meal']
	// $options['passenger']['first_name']
	// $options['passenter']['last_name']
	private function _getDbiXml(Ws_Airplus_Request $request) {
		#$dbi 	= @$options['dbi'];
		#$taxi 	= @$options['taxi'];
		#$meal 	= @$options['meal'];
		
		#$fee = $dbi['ChargeFee'];
		
		$str = "<SubmitDBIBE";
		$str .= "
 ChargeFee=\"{$request->ChargeFee}\"
 AE=\"{$request->AE}\"
 AK=\"{$request->AK}\"
 AU=\"{$request->AU}\"
 BD=\"{$request->BD}\"
 DS=\"{$request->DS}\"
 IK=\"{$request->IK}\"
 KS=\"{$request->KS}\"
 PK=\"{$request->PK}\"
 PR=\"{$request->PR}\"
 RZ=\"{$request->RZ}\"
 CID=\"{$request->CID}\"
 COD=\"{$request->COD}\"
 GN=\"{$request->GN}\"
 RN=\"{$request->RN}\"
 TransType=\"{$request->TransType}\"
 BookedForUserId=\"{$request->BookForUserId}\"
 BookedForUsername=\"{$request->BookForUserName}\"
 CPNType=\"{$request->CPNType}\">
 		";
 		$str .= '</SubmitDBIBE>';

        $str .= '<ValidityPeriodControl ValidFrom="' 
        		. $request->ValidFrom 
        		. '" ValidTo="' 
        		. $request->ValidTo . '"/> <VelocityControl CumulativeLimit="' 
        		. $request->CumulativeLimit . '" MaxTrans="' 
        		. $request->MaxTrans 
        		. '"/> <TransactionCurrencyControl>';
        
        foreach($request->CurrencyCodes as $id) {
        	$str .= '<Currency Code="'. $id .'"/>';
        }
        
        $str .= '</TransactionCurrencyControl>';
        
        return $str;
	}
	
	private function _callApi($xml) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml );
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_NOBODY, false);
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
		"Accept: text/html, */*",
		"Host: aidatest2.airplus.com:443",
		"Accept-Encoding: identity",
		'Content-Type: text/plain',
		"User-Agent: Mozilla/3.0 (compatible; Indy Library)"
		) );
		
		$xmlResult = curl_exec($ch);

        #die($xml);
		return $this->_parseApiResult($xmlResult);
	}
	
	/** 
	 * <?xml version="1.0" encoding="UTF-8"?>
	 * <OrbiscomResponse IssuerId="1" ReturnCode="Success">
	 * 	<Session SessionId="3F927C62405153807ED611ECA21CA954CAC63AB8"/>
	 * 	<AVSData>
	 * 		<ZIP>63263</ZIP>
	 * 		<City>Neu-Isenburg</City>
	 * 		<CardholderName>Lufthansa AirPlus Servi</CardholderName>
	 * 		<Address>Odenwaldstr. 19</Address>
	 * 	</AVSData>
	 * 	<ResponseCode>00</ResponseCode>
	 * 	<CPN AVV="988" Expiry="1603" From="1509" PAN="5317306238557936"/>
	 * 	</OrbiscomResponse>
	 * 
	 * @property SimpleXMLElement $sessionXml
	 */
	private function _parseApiResult($xml) {
		
		/** @var $sessionXml SimpleXMLElement */
		/** @var $cpnXml SimpleXMLElement  */
		#die($xml);
		
        //Hardcode for response airplus services
        $fake = array(
        	'avv' => rand(100, 1000),
        	'from' => date('ym'),
        	'pan' => rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999),
        	'name' => 'John Doe ' . rand(1000, 9999)
        );
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <OrbiscomResponse IssuerId="1" ReturnCode="Success">
            <Session SessionId="3F927C62405153807ED611ECA21CA954CAC63AB8"/>
            <AVSData>
                <ZIP>63263</ZIP>
                <City>Neu-Isenburg</City>
                <CardholderName>' . $fake['name'] . '</CardholderName>
                <Address>Odenwaldstr. 19</Address>
            </AVSData>
            <ResponseCode>00</ResponseCode>
            <CPN AVV="' . $fake['avv'] . '" Expiry="1603" From="'.$fake['from'].'" PAN="'.$fake['pan'].'"/>
        </OrbiscomResponse>';
        
		$xml = new SimpleXMLElement($xml);
		$obj = new Ws_Airplus_Result();
		
		$sessionXmls = $xml->xpath('Session');
        #print_r($sessionXmls);die();
		if(empty($sessionXmls)) {
			throw new Exception('Airplus call error');
		}
		$cpnXmls = $xml->xpath('CPN');
		if(empty($cpnXmls)) {
			throw new Exception('Airplus call error');
		}
		$avsData = $xml->xpath('AVSData');
        #print_r($avsData);die();
		if(empty($avsData)) {
			throw new Exception('Airplus no card holder return');
		}
		$sessionArray 	= (array)$sessionXmls[0]->attributes();
		$cpnArray 		= (array)$cpnXmls[0]->attributes();
        $avsArray      = (array)$avsData[0];

        #print_r($sessionArray);die();
        #print_r($sessionArray['@attributes']);die();
		$obj->SessionId 		= $sessionArray['@attributes']['SessionId'];
		$obj->CVC 				= $cpnArray['@attributes']['AVV'];
		$obj->CardNumber 		= $cpnArray['@attributes']['PAN'];
		$obj->ValidThru			= $cpnArray['@attributes']['Expiry'];
		$obj->ValidFrom			= $cpnArray['@attributes']['From'];
		$obj->PassengerName 	= strval($avsArray['CardholderName']);
		
		return $obj;
	}
}