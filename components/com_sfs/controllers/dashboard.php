<?php
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/controllers/sfscontroller.php';

class SfsControllerDashboard extends SfsController
{
	public function sendmailuser(){
		jimport('joomla.mail.helper');

		$user = JFactory::getUser();
        $airline = SFactory::getAirline();
				       
		$db = JFactory::getDbo();

		$query = 'SELECT a.grouptype,a.telephone,a.fax,c.name,c.code FROM #__sfs_contacts AS a'
				.' INNER JOIN #__sfs_airline_details AS b ON b.id = a.group_id'
				.' INNER JOIN #__sfs_iatacodes AS c ON c.id = b.iatacode_id'
				.' WHERE a.user_id =' . $user->id ;
		
      	$db->setQuery($query);
      	$result = $db->loadObjectList();      	
      	

        $query = "SELECT * FROM #__sfs_communication_partners WHERE airlineid=" .$user->id . " AND stationcode='".$airline->airport_code."' AND sitamessage='" .$_POST['emailTo']. "'";
        print_r($query); die();
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
            //$row->sitamessage     = $data['emailTo'];
            $row->sitamessage   = $_POST['emailTo'];
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
        
        $infoMail->name_airline	= $result[0]->name;
        $infoMail->emailTo 		= $_POST['emailTo'];
        $infoMail->emailFrom 	= $user->email;        
        $infoMail->info 		= $_POST['info'];
        $infoMail->status 		= 1;
        $infoMail->created 		= JFactory::getDate()->toSql();

        $db->insertObject('#__sfs_communication_mail', $infoMail);
		//JUtility::sendMail($user->email, $result[0]->name, "phamvu180485@gmail.com", "Notification", $data['info'],true);	       
		JUtility::sendMail($user->email, $result[0]->name, $_POST['emailTo'], "Notification", $_POST['info'],true);	
		$d = array('ok' => '1');
		echo json_encode($d); die();		  
    }

    public function updateStatus(){
    	$user = JFactory::getUser();
    	$db = JFactory::getDbo();

    	$query = 'SELECT * FROM #__sfs_communication_mail WHERE status = 1 AND emailTo = "'. $user->email . '"';
    	$db->setQuery($query);
      	$result = $db->loadObjectList();
      	
      	if(count($result) > 0 ){
      		$query = 'UPDATE #__sfs_communication_mail SET status = 0 WHERE emailTo = "'. $user->email . '"';
      		$db->setQuery($query)->execute(); 
      	}   
        
      echo "ok";die();  	
    } 

    public function resultNotifi(){
    	
    	$user = JFactory::getUser();
    	$db   = JFactory::getDbo();

	    $query = 'SELECT * FROM #__sfs_communication_mail WHERE status=1 AND emailTo = "' . $user->email . '"';
	    $db->setQuery($query);
	    $result = $db->loadObjectList();

	   	echo count($result); die();
    }   


    public function getFilter(){
        $user = JFactory::getUser();
        $db   = JFactory::getDbo();        
        
        $arrList = $_POST['info'];
        
        if(empty($arrList)){
            $query = "SELECT * FROM #__sfs_communication_mail WHERE emailTo = '" .$user->email. "'  ORDER BY id DESC";  

            unset($_SESSION['data']);  
        }else{            
            $strList = implode('|', $arrList);        
            $query = "SELECT * FROM #__sfs_communication_mail WHERE emailTo = '" .$user->email. "' AND info REGEXP '" .$strList. "' ORDER BY id DESC";    

            $_SESSION['data'] = json_encode($arrList);        
        }

        $db->setQuery($query);
        $result = $db->loadObjectList();
        
        echo json_encode($result); die();     
    }
}


