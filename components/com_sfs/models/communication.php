<?php
defined('_JEXEC') or die;
jimport('joomla.mail.helper');
class SfsModelCommunication extends JModelLegacy
{	
	public function getListMailNotifi(){
      $user = JFactory::getUser();
      $db   = JFactory::getDbo();

      $query = 'SELECT * FROM #__sfs_communication_mail WHERE emailTo = "' . $user->email . '" ORDER BY id DESC';
      $db->setQuery($query);
      $result = $db->loadObjectList();

      return $result;
    }

    public function getDetailEmail(){
		
		$id = JRequest::getVar('id');		
		$user = JFactory::getUser();
      	$db   = JFactory::getDbo();

      	$query = 'SELECT * FROM #__sfs_communication_mail WHERE id =' . $id;
      	$db->setQuery($query);
      	$result = $db->loadObject();
      	
      	if(JRequest::getVar('status') > 0){
      		$query = 'UPDATE #__sfs_communication_mail SET status = 0 WHERE id = "'. $id .'"';
      		$db->setQuery($query)->execute();
      	}
      	
      	echo json_encode($result); die();
	}

	public function sendmailuser(){
		jimport('joomla.mail.helper');

		$user = JFactory::getUser();
    $airline = SFactory::getAirline();
		$db = JFactory::getDbo();

    $data = JRequest::getVar('emailTo');

    $query = 'SELECT a.grouptype,a.telephone,a.fax,c.name,c.code FROM #__sfs_contacts AS a'
        .' INNER JOIN #__sfs_airline_details AS b ON b.id = a.group_id'
        .' INNER JOIN #__sfs_iatacodes AS c ON c.id = b.iatacode_id'
        .' WHERE a.user_id =' . $user->id ;    
    $db->setQuery($query);
    $result = $db->loadObjectList();  

    foreach ($data as $key => $value) {
        $query = "SELECT * FROM #__sfs_communication_partners WHERE airlineid=" .$airline->id . " AND stationcode='".$airline->airport_code."' AND sitamessage='" .$value. "'";
        $db->setQuery($query);
        $checkResult = $db->loadObject(); 

        if(empty($checkResult)){
            $row = new stdClass();
            $row->airlineid     = $airline->id;
            $row->stationcode   = $airline->airport_code;
            $row->companytype   = $result[0]->grouptype;
            $row->companyname   = $result[0]->name;
            $row->name          = $user->name;
            $row->email         = $user->email;        
            $row->sitamessage   = $value;
            $row->phone         = $result[0]->telephone;
            $row->fax           = $result[0]->fax;
            $row->created       = JFactory::getDate()->toSql();
            $db->insertObject('#__sfs_communication_partners', $row);          
        }else{
            $rowUp = new stdClass();  
            $rowUp->id =  $checkResult->id;         
            $rowUp->created =  JFactory::getDate()->toSql();
            $db->updateObject('#__sfs_communication_partners', $rowUp, 'id');
        }

        $infoMail = new stdClass();
        
        $infoMail->name_airline = $result[0]->name;
        $infoMail->emailTo      = $value;
        $infoMail->emailFrom    = $user->email;        
        $infoMail->info         = $_POST['info'];
        $infoMail->status       = 1;
        $infoMail->created      = JFactory::getDate()->toSql();

        $db->insertObject('#__sfs_communication_mail', $infoMail);
        JUtility::sendMail($user->email, $result[0]->name, $_POST['emailTo'], "Notification", $value,true);
    }

		$d = array('ok' => '1');
		echo json_encode($d); die();		
	}

	public function getListEmailHotel(){
    $user = JFactory::getUser();
    $db = JFactory::getDbo();
    
    $query_ = 'SELECT group_id FROM #__sfs_contacts WHERE user_id =' . $user->id ;
    $db->setQuery($query_);
    $result_ = $db->loadObjectList();

    $query = 'SELECT d.email, e.code'        
        . ' FROM #__sfs_hotel_airports AS a'
        . ' INNER JOIN #__sfs_contacts AS b ON b.user_id = a.hotel_id'
        . ' INNER JOIN #__sfs_airline_airport AS c ON c.airport_id = a.airport_id'
        . ' INNER JOIN #__users AS d ON d.id = b.user_id'
        . ' INNER JOIN #__sfs_iatacodes AS e ON e.id = c.airport_id'          
        . ' WHERE c.airline_detail_id = '.$result_[0]->group_id.' AND b.grouptype = 1';
    
    $db->setQuery($query);
    $result = $db->loadObjectList();

    return $result;
  }

  

  public function getListEmailSend(){
    $user = JFactory::getUser();
    $db = JFactory::getDbo();
    $airline = SFactory::getAirline();
    
    $query = 'SELECT * FROM #__sfs_communication_partners WHERE airlineid =' . $airline->id .' AND sitamessage != "" GROUP BY stationcode,sitamessage';   
    $db->setQuery($query);
    $result = $db->loadObjectList();

    return $result;
  }

  public function sortDataCommunication(){
    $user = JFactory::getUser();
    $db   = JFactory::getDbo();

    if($_POST['val'] == 'byDate'){
        $query = 'SELECT * FROM #__sfs_communication_mail WHERE emailTo = "' . $user->email . '" ORDER BY id ' .$_POST['sort'];
    }

    if($_POST['val'] == 'newest'){
        $query = 'SELECT * FROM #__sfs_communication_mail WHERE emailTo = "' . $user->email . '" ORDER BY  DATE_FORMAT( created, "%h-%i" ) ' .$_POST['sort'];
    }
    
    $db->setQuery($query);
    $result = $db->loadObjectList();

    echo json_encode($result);die();
  }

  public function getDataPassengerReport(){
      $airline = SFactory::getAirline();
      $db = JFactory::getDbo();      

      $query = 'SELECT * FROM #__sfs_flightinfo WHERE airline_id='.$airline->id. ' AND dep ="'.$airline->airport_code.'" GROUP BY carrier, flight_no';

      $db->setQuery($query);
      $result = $db->loadObjectList();     
      
      return $result;
  }


  public function getChangeFlight(){
      $airline = SFactory::getAirline();
      $db = JFactory::getDbo();
      
      $flight = $_POST['flight'];
      // $query = 'SELECT id, DATE_FORMAT(flight_date, "%Y-%m-%d") as flightDate FROM #__sfs_flightinfo WHERE airline_id = '.$airline->id.' AND CONCAT( `carrier` , `flight_no` ) = "'.$flight.'" AND dep ="' .$airline->airport_code. '"';

      $query = 'SELECT id, DATE_FORMAT(flight_date, "%Y-%m-%d") as flightDate FROM #__sfs_flightinfo WHERE airline_id = '.$airline->id.' AND CONCAT( `carrier` , `flight_no` ) = "'.$flight. '" GROUP BY DATE_FORMAT(flight_date, "%Y-%m-%d")';
     
      $db->setQuery($query);
      $result = $db->loadObjectList();     
      
      echo json_encode($result);die();
  }

  public function getFillterChange(){
           
      $dateFill = $_POST['date'];
      $result = $this->getFillterWithChangeDate( $dateFill );
      
      echo json_encode($result);die();
  }

  protected function getFillterWithChangeDate($id){
      $airline = SFactory::getAirline();
      $db = JFactory::getDbo();           
      $countAuthen = array();
      $countIssue = array();
      $query = 'SELECT * FROM #__sfs_flightinfo WHERE id=' . $id;

      $db->setQuery($query);
      $result = $db->loadObject();   

      if(!empty($result)){
        $query = 'SELECT id,title,rawname,pnr,first_name,last_name,flight_code,irreg_reason FROM #__sfs_trace_passengers';
        $query .= ' WHERE fltref = "'.$result->fltref.'" AND airline_id ='.$airline->id ;
        $db->setQuery($query);
        $dataPassenger = $db->loadObjectList();       
      
        foreach ($dataPassenger as $key => $value) {
          $query = 'SELECT b.id, b.name_service, a.passenger_id, a.block_code, a.price_per_person FROM #__sfs_passenger_service AS a';
          $query .= ' INNER JOIN #__sfs_services AS b ON b.id = a.service_id'  ;
          $query .= ' WHERE a.passenger_id = '.$value->id ;
          $db->setQuery($query);
          $dataService = $db->loadObjectList();
          
          foreach ($dataService as $k => $valService) {              

              if( (int)$valService->id == 1){
                  $query = 'SELECT voucher_id FROM #__sfs_trace_passengers WHERE fltref = "' . $result->fltref .'"';
                  $db->setQuery($query);
                  $tracepassenger_ = $db->loadObject();
                  
                  if( (int)$tracepassenger_->voucher_id > 0){
                      $query = 'SELECT c.name FROM #__sfs_voucher_codes AS a';
                      $query .= ' LEFT JOIN #__sfs_reservations AS b ON b.id = a.booking_id';
                      $query .= ' LEFT JOIN #__sfs_hotel AS c ON c.id = b.hotel_id';
                      $query .= ' WHERE a.id = ' . $tracepassenger_->voucher_id;

                      $db->setQuery($query);
                      $hotel_name = $db->loadObject();
                      $valService->hotel_name = $hotel_name;
                      
                  }else{
                      $valService->hotel_name = '';                                        
                  }
              }
          }

          $value->name_service = $dataService;
        }       

        foreach ($dataPassenger as $k => $val) {
          foreach($val->name_service as $key => $value){
            if( (int)$value->id == 1 ){
                if( !empty($value->hotel_name) ){
                    array_push($countIssue,$value->name_service);
                }

                array_push($countAuthen,$value->name_service);
            }else{
                if( !empty($value->price_per_person) ){
                    array_push($countIssue,$value->name_service);
                }

                array_push($countAuthen,$value->name_service);
            }
          }
            
        }

        $result->passengerInfo = $dataPassenger;       

      }

      
      $result->countIssue =  array_count_values($countIssue);
      $result->countAuthen =  array_count_values($countAuthen);
      // print_r($result); die();
      return $result;
  }

  public function sendmessage(){
    $user = JFactory::getUser();
    $text_1 = $_POST['text_1'];
    $text_2 = $_POST['text_2'];
    $dateId = $_POST['dateId'];
    $subject = $_POST['subject'];
    $data = $this->getFillterWithChangeDate($dateId);

    //$filename = JPATH_COMPONENT.strtotime("now").'.xls';
    $filename = JPATH_COMPONENT.'/upload/export_excel.xls';
   
    $fp = fopen($filename, "w");

    ob_start();
        require_once JPATH_COMPONENT.'/libraries/emails/sendmessage_communication.php';
    $bodyData = ob_get_clean();

    fputs($fp, $bodyData);
    fclose($fp);
    
    $link = JPATH_COMPONENT.'/upload/export_excel.xls';
    // JFactory::getMailer()->sendMail($user->email, 'Stranded Flight Solutions', 'phamvu180485@gmail.com', $subject, $bodyData, $mode = 1, null, null, $link);
    $mailbcc = implode(", ",$_POST['listmailbcc']);

    $db = JFactory::getDbo();

    $query = "SELECT sitamessage,fax,typ FROM #__sfs_communication_partners WHERE id IN (" .implode(", ",$_POST['listmail']). ")";
    
    $db->setQuery($query);
    $result = $db->loadObjectList();   

    foreach ($result as $k => $data_) {
      if(strtolower($data_->typ) == "email" ){        
        JFactory::getMailer()->sendMail($user->email, 'Stranded Flight Solutions', $data_->sitamessage, $subject, $bodyData, $mode = 1, null, $mailbcc, $link);
        // JUtility::sendMail($user->email, 'Stranded Flight Solutions', $value, 'Report Passenger' , $bodyData, $mode = 1, null, null, $link);               
      }else{
        $numberFax = trim(SfsHelper::formatPhone( $data_->fax, 2));
        JFactory::getMailer()->sendMail('airline_support@sfs-web.com', 'Stranded Flight Solutions',$numberFax.'@efaxsend.com', $subject, $bodyData, true,null,null,$link);
      }
    }
    
    
    $d = array('status' => 'ok');
    echo json_encode($d); die();
  }

  public function sendmessageovb(){
    $user = JFactory::getUser();
    $text_1 = $_POST['text_1'];
    $arr_two = array();
    $dateId = $_POST['dateId'];
    $subject = $_POST['subject'];
    $data = $this->getFillterWithChangeDate($dateId);
    
    //$filename = JPATH_COMPONENT.strtotime("now").'.xls';
    $filename = JPATH_COMPONENT.'/upload/export_excel_ovb.xls';
   
    $fp = fopen($filename, "w");

    ob_start();
        require_once JPATH_COMPONENT.'/libraries/emails/sendmessage_communication_ovb.php';
    $bodyData = ob_get_clean();

    $arr_one = array("{text_1}","{text_2}","{text_3}","{text_4}","{text_5}");
    $arr_two = $_POST['body_'];

    $bodyData  = str_replace($arr_one,$arr_two, $bodyData);

    fputs($fp, $bodyData);
    fclose($fp);
    
    $link = JPATH_COMPONENT.'/upload/export_excel_ovb.xls';
    // JFactory::getMailer()->sendMail($user->email, 'Stranded Flight Solutions', 'phamvu180485@gmail.com', $subject, $bodyData, $mode = 1, null, null, $link);
    $mailbcc = implode(", ",$_POST['listmailbcc']);

    
    
    $db = JFactory::getDbo();

    $query = "SELECT sitamessage,fax,typ FROM #__sfs_communication_partners WHERE id IN (" .implode(", ",$_POST['listmail']). ")";
    
    $db->setQuery($query);
    $result = $db->loadObjectList();   

    foreach ($result as $k => $data_) {
      if(strtolower($data_->typ) == "email" ){        
        JFactory::getMailer()->sendMail($user->email, 'Stranded Flight Solutions', $data_->sitamessage, $subject, $bodyData, $mode = 1, null, $mailbcc, $link);
        // JUtility::sendMail($user->email, 'Stranded Flight Solutions', $value, 'Report Passenger' , $bodyData, $mode = 1, null, null, $link);               
      }else{
        $numberFax = trim(SfsHelper::formatPhone( $data_->fax, 2));
        JFactory::getMailer()->sendMail('airline_support@sfs-web.com', 'Stranded Flight Solutions',$numberFax.'@efaxsend.com', $subject, $bodyData, true,null,null,$link);
      }
    }
    
    
    $d = array('status' => 'ok');
    echo json_encode($d); die();
  }


}
