<?php

$url = 'https://aidatest2.airplus.com/Athena2/XMLServlet';

function _arrayToXML($array, $xml) {
	foreach($array as $key => $value) {
		if(is_array($value)) {
			if(!is_numeric($key)){
				$subnode = $xml->addChild("$key");
				$this->_arrayToXML($value, $subnode);
			}
			else{
				$this->_arrayToXML($value, $xml);
			}
		}
		else {
			$xml->addChild("$key",htmlspecialchars("$value"));
		}
	}
}

function _buildWsXml($array) {
	foreach($array as $key => $value) {
		$xml = new SimpleXMLElement("<{$key}></{$key}>");
		_arrayToXML($value, $xml);
		return $xml->saveXML();
	}
	return '';
}

$array = array();

$xmlString = '<?xml version="1.0" encoding="UTF-8"?>
<OrbiscomRequest IssuerId="1">
 <AuthenticationBE
 AppendResponse="false"
 Company="BOOKINGENGINES"
 Password="3mr0CQOg"
 User="SFS_01">
 </AuthenticationBE>
<PreScreening
 RequestType="BookingEngine"
 AppendResponse="false"
 ExpiryDate="1217"
 UATPCardNumber="1220002003630860">
</PreScreening>
 <SubmitDBIBE
 ChargeFee="1"
 AE="1"
 AK="1"
 AU="1"
 BD="15SEP15"
 DS="1"
 IK="1"
 KS="1"
 PK="1"
 PR="1"
 RZ="1"
 CID="15SEP15"
 COD="20SEP15"
 GN="Dat Giang"
 RN="2"
 TransType="Hotel"
 BookedForUserId="SFS_02"
 BookedForUsername="Dat Test"
 CPNType="SP">
 </SubmitDBIBE>
 <ValidityPeriodControl ValidFrom="2015-09-14 23:59:59" ValidTo="2015-09-22 23:59:59"/>
 <VelocityControl CumulativeLimit="500" MaxTrans="5"/>
 <TransactionCurrencyControl>
<Currency Code="978"/>
<Currency Code="840"/>
<Currency Code="756"/>
 </TransactionCurrencyControl>
</OrbiscomRequest>';

$xmlObject = new SimpleXMLElement($xmlString);
#$xmlString = (string)$xmlObject;

$xmlString2  = htmlspecialchars($xmlString);
#$xmlString = $xmlString2;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlString );
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);
#$f = fopen(dirname(__FILE__) . '/request.txt', 'w');
#curl_setopt($ch,CURLOPT_VERBOSE,true);
#curl_setopt($ch,CURLOPT_STDERR ,$f);
curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
		"Accept: text/html, */*",
		"Host: aidatest2.airplus.com:443",
		"Accept-Encoding: identity",
		'Content-Type: text/plain',
		"User-Agent: Mozilla/3.0 (compatible; Indy Library)"
) );

$xml = curl_exec($ch);

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$information = curl_getinfo($ch);

echo '<h1>Request</h1>';
echo $xmlString2;
echo '<h1>Response</h1>';

curl_close($ch);
echo '<pre>';
echo $httpcode, "\n\n";
print_r( $information); 
echo "\n\n";
echo htmlspecialchars($xml);