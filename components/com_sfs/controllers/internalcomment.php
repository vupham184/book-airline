<?php
defined('_JEXEC') or die;

class SfsControllerInternalcomment extends JController
{
	
	public function save()
	{		
		// Initialise some variables
		$app		= JFactory::getApplication();
		$airline	= SFactory::getAirline();
		
		if( !SFSAccess::isAirline() ) 
		{
			//not allow edit
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=taxi&Itemid='.JRequest::getInt('Itemid'), false)); 
			return false;
		}
		
		if( SFSAccess::isMainContact() == 0 ) {
			//not allow edit
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=taxi&Itemid='.JRequest::getInt('Itemid'), false)); 
			return false;
		}	
										
		$model  = $this->getModel('Tracepassenger', 'SfsModel');		
		$data['internal_comment'] = JRequest::getVar('internal_comment');
		$data['passenger_id'] = JRequest::getInt('passenger_id');
		$data['airline_id'] = $airline->id;
		$result = $result = $model->saveInternalcommentAjax( $data );
		///$result = $model->saveInternalcomment( $data );
		if( ! $result ) 
		{
			$errors	= $model->getErrors();			
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}		
		}
		///echo 'successful';
		exit;
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=taxi&Itemid='.JRequest::getInt('Itemid'), false));
	}
	
	public function saveAjx()
	{		
		// Initialise some variables
		$app		= JFactory::getApplication();
		$airline	= SFactory::getAirline();
		
		if( !SFSAccess::isAirline() ) 
		{
			//not allow edit
			$arr = array('successful' => "0", 'errorcode' => 'maxl', 'errormessage' => 'Not access');
			echo json_encode( $arr );
			exit;
		}
								
		$model  = $this->getModel('Tracepassenger', 'SfsModel');
		$passenger_id = JRequest::getVar('passenger_id');
		$data['internal_comment'] = JRequest::getVar('internal_comment');
		if( $passenger_id != '' ) {
			$passenger_idArr = explode(",", $passenger_id);
			foreach ( $passenger_idArr as $v ) {
				if( $v != '' ) {
					$data['passenger_id'] = $v;
					$result = $model->saveInternalcomment( $data );
				}
			}
		}
		else {
			$arr = array('successful' => "0", 'errorcode' => 'maxl', 'errormessage' => 'Not find passenger id');
			echo json_encode( $arr );
			exit;
		}
		
		if( ! $result ) 
		{
			$strErr = '';
			$errors	= $model->getErrors();			
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$strErr .= $errors[$i]->getMessage();
				} else {
					$strErr .= $errors[$i];
				}
			}
			
			//not allow edit
			$arr = array('successful' => "0", 'errorcode' => 'maxl', 'errormessage' => $strErr);
			echo json_encode( $arr );
			exit;		
		}
		else {
			$arr = array('successful' => "1", 'errorcode' => 'maxl', 'errormessage' => "save comment success");
			echo json_encode( $arr );
			exit;	
		}
		exit;
	}
	
	public function sendSMS()
	{
		$pass_ids  = JRequest::getVar('pass_ids', array() , 'post', 'array');
		$airline	= SFactory::getAirline();

		$sender_title = $airline->params['sender_title'];
		if(count($pass_ids)>0){
			$model   = $this->getModel('Passengersimport','SfsModel');
			$passengers = $model->getListPassengerByIds(implode(",",$pass_ids),'phone_number');
			foreach ($passengers as $key => $value){
				$url_code = SfsUtil::getRandomString(5);
				$link_mobile = JUri::root(). 'mobile/?code=' . $url_code . '&tmp=' . $airline->id;
				$textSMS = 'View your booking details on \\n' . $link_mobile;
				$t = true;
				$this->create_multipassengerFileLog($value,$url_code);
				$flight_number = $value->flight_number;
				$std = $value->std;
				$etd = $value->etd;
				$data_passengers_ = JRequest::getVar('data_passengers');
				$text_messageS = '';
				//chi du cho truong hop page detail passengers 
				if( $data_passengers_ == '' ) {
					///$text_messageS = "SMS: " . date('d-m-Y') . " user: " . $sender_title . " \n ";
					///$text_messageS .= "Your onward filght $flight_number to Berlin in rescheduled to Departure: $std from Praag airport Ruzyne \n ";
				}
				$text_message .= $textSMS;
				$data_tel = $value->phone_number;//JRequest::getVar('data_tel');
				if( strlen( $text_message ) > 150 ){
					$t = false;
					$arr = array('successful' => 0, 'errorcode' => 'maxl', 'errormessage' => 'Max 150 characters');
					echo json_encode( $arr );
					exit;
				}

				if( strlen( $text_message ) == 0 ){
					$t = false;
					$arr = array('successful' => 0, 'errorcode' => 'maxl', 'errormessage' => 'please enter text message');
					echo json_encode( $arr );
					exit;
				}
				if( $data_tel == '' || strlen( $data_tel ) <= 8 ){
					$t = false;
					$arr = array('successful' => 0, 'errorcode' => 'maxl', 'errormessage' => 'Not find phone number or phone number is not valid the send SMS');
					echo json_encode( $arr );
					exit;
				}
				$text_messageS .= $text_message;
				if( $t == true ) {//the send
					$url = 'http://gateway.bizzsms.nl/api/xml_send';
					$ch = curl_init();
					curl_setopt( $ch, CURLOPT_URL, $url );
					curl_setopt( $ch, CURLOPT_POST, true );
					curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $this->dataXml( $text_messageS, $data_tel ) );
					$result = curl_exec($ch);
					$this->loadxml( $result );
					curl_close($ch);

				}
				else {//the send
					$tel = ( $value->phone_number != '' ) ? $value->phone_number : "";
					if( $tel == "" ) {
					$arr = array('successful' => 0, 'errorcode' => 'maxl', 'errormessage' => 'Not find phone number the send message');
						echo json_encode( $arr );
						exit;
					}
					else {
						$phoneArr = explode(",", $tel);
						foreach ( $phoneArr as $vk => $telS ) {
							if( $telS != '' ) {
								$url = 'http://gateway.bizzsms.nl/api/xml_send';
								$ch = curl_init();
								curl_setopt( $ch, CURLOPT_URL, $url );
								curl_setopt( $ch, CURLOPT_POST, true );
								curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
								curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
								curl_setopt( $ch, CURLOPT_POSTFIELDS, $this->dataXml( $text_message, $telS ) );
								$result = curl_exec($ch);
								$this->loadxml( $result );
								curl_close($ch);
							}//Ens if
							
						}//End foreach
						
					}//End else
					

				}
			}			
		}else{
			$t = true;
			$this->create_passengerFileLog();
			
			$flight_number = JRequest::getVar('flight_number');
			$std = JRequest::getVar('std');
			$etd = JRequest::getVar('etd');
			$data_passengers_ = JRequest::getVar('data_passengers');
			$text_messageS = '';
			//chi du cho truong hop page detail passengers 
			if( $data_passengers_ == '' ) {
				///$text_messageS = "SMS: " . date('d-m-Y') . " user: " . $sender_title . " \n ";
				///$text_messageS .= "Your onward filght $flight_number to Berlin in rescheduled to Departure: $std from Praag airport Ruzyne \n ";
			}
			$text_message .= JRequest::getVar('text_message');
			$data_tel = JRequest::getVar('data_tel');
			if( strlen( $text_message ) > 150 ){
				$t = false;
				$arr = array('successful' => 0, 'errorcode' => 'maxl', 'errormessage' => 'Max 150 characters');
				echo json_encode( $arr );
				exit;
			}

			if( strlen( $text_message ) == 0 ){
				$t = false;
				$arr = array('successful' => 0, 'errorcode' => 'maxl', 'errormessage' => 'please enter text message');
				echo json_encode( $arr );
				exit;
			}
			if( $data_tel == '' || strlen( $data_tel ) <= 8 ){
				$t = false;
				$arr = array('successful' => 0, 'errorcode' => 'maxl', 'errormessage' => 'Not find phone number or phone number is not valid the send SMS');
				echo json_encode( $arr );
				exit;
			}
			$text_messageS .= $text_message;
			if( $t == true ) {//the send
				$url = 'http://gateway.bizzsms.nl/api/xml_send';
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, $url );
				curl_setopt( $ch, CURLOPT_POST, true );
				curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $this->dataXml( $text_messageS, $data_tel ) );
				$result = curl_exec($ch);
				$this->loadxml( $result );
				curl_close($ch);

			}
			else {//the send
				$tel = ( JRequest::getVar('text_tel') != '' ) ? JRequest::getVar('text_tel') : "";
				if( $tel == "" ) {
				$arr = array('successful' => 0, 'errorcode' => 'maxl', 'errormessage' => 'Not find phone number the send message');
					echo json_encode( $arr );
					exit;
				}
				else {
					$phoneArr = explode(",", $tel);
					foreach ( $phoneArr as $vk => $telS ) {
						if( $telS != '' ) {
							$url = 'http://gateway.bizzsms.nl/api/xml_send';
							$ch = curl_init();
							curl_setopt( $ch, CURLOPT_URL, $url );
							curl_setopt( $ch, CURLOPT_POST, true );
							curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
							curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
							curl_setopt( $ch, CURLOPT_POSTFIELDS, $this->dataXml( $text_message, $telS ) );
							$result = curl_exec($ch);
							$this->loadxml( $result );
							curl_close($ch);
						}//Ens if
						
					}//End foreach
					
				}//End else
				

			}
		}
		
		
		exit;
	}
	

	public function dataXml( $message = null, $tel = "841627097161" ){
		$tel = "841627097161";
		$string = "";
		$string = "<smsdata>
					<user>
						<username>sfs-web</username>
						<password>irimi</password>
					</user>
					<messages>
					<sms id=\"1\"> 	
						<message><![CDATA[ $message ]]></message>
						<timestamp>" . time() . "</timestamp>
						<sender>sender</sender>
							<receivers>
								<phonenumber ref=\"101\">$tel</phonenumber>
							</receivers>
					</sms>					
					</messages>
				</smsdata>";
		return $string;
	}
	
	public function loadxml( $xml_content )
	{
		$xml = new SimpleXMLElement($xml_content);	
		echo json_encode( $xml->response );			
	}
	// Minh Tran
	public function saveComment(){
		$app		= JFactory::getApplication();
		$airline	= SFactory::getAirline();
		
		if( !SFSAccess::isAirline() ) 
		{
			//not allow edit
			$arr = array('successful' => "0", 'errorcode' => 'maxl', 'errormessage' => 'Not access');
			echo json_encode( $arr );
			exit;
		}		
		$model  = $this->getModel('Tracepassenger', 'SfsModel');	
		$passenger_id = JRequest::getVar('passenger_id');
		$data['internal_comment'] = JRequest::getVar('internal_comment');
		$data['airline_id']=$airline->iatacode_id;
		if( $passenger_id != '' && $data['internal_comment']!='' ) {
			$passenger_idArr = explode(",", $passenger_id);
			foreach ( $passenger_idArr as $v ) {
				if( $v != '' ) {
					$data['passenger_id'] = $v;
					$result = $model->saveInternalcommentAjax( $data );
					if( ! $result ) 
					{
						$strErr = '';
						$errors	= $model->getErrors();			
						// Push up to three validation messages out to the user.
						for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
							if (JError::isError($errors[$i])) {
								$strErr .= $errors[$i]->getMessage();
							} else {
								$strErr .= $errors[$i];
							}
						}
						
						//not allow edit
						$arr = array('successful' => "0", 'errorcode' => 'maxl', 'errormessage' => $strErr);
						echo json_encode( $arr );
						exit;		
					}
				}
			}
		}
		else {
			$arr = array('successful' => "0", 'errorcode' => 'maxl', 'errormessage' => 'Not find passenger id');
			echo json_encode( $arr );
			exit;
		}
		
		
		if($result) {
			$arr = array('successful' => "1", 'errorcode' => 'maxl', 'errormessage' => "save comment success");
			echo json_encode( $arr );
			exit;	
		}
		exit;

	}
	public function create_multipassengerFileLog ($passenger,$url_code){
		//lchung
		$code = $url_code;
		$data_passengers = $passenger;
		if ( $data_passengers != '' ) {
			//$data_passengersArr = json_decode( "[" . str_replace("'", '"', $data_passengers_ ) ."]" );
//			$data_passengers = $data_passengersArr[0];
			$firstname = $data_passengers->first_name;
			$lastname = $data_passengers->last_name;
			$DS = '\\';
			if( DS != '' )
				$DS = DS;
			
			$arrInfo['flight_number']=$data_passengers->flight_number;
			$arrInfo['pnr']=$data_passengers->pnr;
			$arrInfo['code']=$data_passengers->code;
			$arrInfo['dep']=$data_passengers->dep;
			$arrInfo['arr']=$data_passengers->arr;
			$arrInfo['std']=$data_passengers->std;
			$arrInfo['etd']=$data_passengers->etd;
			$arrInfo['passenger_id']=$data_passengers->id;
			$arrInfo['firstname'] = $firstname;
			$arrInfo['lastname'] = $lastname;
			$wfilePath = JPATH_SITE . $DS .'tmp' . $DS . 'mobile' . $DS . $code . '.log';
			$this->fwriteJson(  $wfilePath, $arrInfo );
		}
        return;
    }

	//end Minh Tran
	
	public function create_passengerFileLog (){
		//lchung
		$code = JRequest::getVar('data_url_code');
		$data_passengers_ = JRequest::getVar('data_passengers');
		if ( $data_passengers_ != '' ) {
			$data_passengersArr = json_decode( "[" . str_replace("'", '"', $data_passengers_ ) ."]" );
			$data_passengers = $data_passengersArr[0];
			$firstname = $data_passengers->first_name;
			$lastname = $data_passengers->last_name;
			$DS = '\\';
			if( DS != '' )
				$DS = DS;
			
			$arrInfo['flight_number']=$data_passengers->flight_number;
			$arrInfo['pnr']=$data_passengers->pnr;
			$arrInfo['code']=$data_passengers->code;
			$arrInfo['dep']=$data_passengers->dep;
			$arrInfo['arr']=$data_passengers->arr;
			$arrInfo['std']=$data_passengers->std;
			$arrInfo['etd']=$data_passengers->etd;
			$arrInfo['passenger_id']=$data_passengers->passenger_id;
			$arrInfo['firstname'] = $firstname;
			$arrInfo['lastname'] = $lastname;
			
			$wfilePath = JPATH_SITE . $DS .'tmp' . $DS . 'mobile' . $DS . $code . '.log';
			$this->fwriteJson(  $wfilePath, $arrInfo );
		}
        return;
    }

	public function fwriteJson( $filename = '', $response ){
		$fp = fopen($filename, 'w');
		fwrite($fp, json_encode($response));
		fclose($fp);
	}
	//End lchung
}

//0031612345678
