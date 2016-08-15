<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class SfsControllerAirline extends JControllerForm
{

	protected function allowAdd($data = array())
	{
		return parent::allowAdd($data);
	}

	protected function allowEdit($data = array(), $key = 'id')
	{		
		return parent::allowEdit($data, $key);		
	}
	
	public function newadmin() {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$model		= $this->getModel('Airline','SfsModel');
		
		if( $model->newAdmin() ) {
			$this->setRedirect('index.php?option=com_sfs&view=airline&layout=newadmin&tmpl=component&id='.JRequest::getInt('id'));	
		} else {
			$msg = $model->getError();
			$this->setRedirect('index.php?option=com_sfs&view=airline&layout=newadmin&tmpl=component&id='.JRequest::getInt('id'),$msg);
		}		
		
		return true;
	}

    public function newStationUser() {
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $model		= $this->getModel('Airline','SfsModel');

        if( $model->newStationUser() ) {
            $this->setRedirect('index.php?option=com_sfs&view=airline&layout=newstationuser&tmpl=component&id='.JRequest::getInt('id'));
        } else {
            $msg = $model->getError();
            $this->setRedirect('index.php?option=com_sfs&view=airline&layout=newstationuser&tmpl=component&id='.JRequest::getInt('id'),$msg);
        }

        return true;
    }

    public function approved()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$id = JRequest::getInt('id');
		$model		= $this->getModel('Airline','SfsModel');
		$result = $model->approved($id);
		
		if($result) {
			echo '<span style="color:green;">Successfully Approved</span><br /><br />';
			echo '<button onclick="window.parent.SqueezeBox.close();" class="button" type="button">Close</button>';			
		}
		die;
	}
	
	public function saveSystemEmails()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$airlineId = JRequest::getInt('airline_id');
		
		$contacts  = JRequest::getVar('contacts', array(), 'post', 'array');
		$semail    = JRequest::getVar('semail', array(), 'post', 'array');
				
		$db = JFactory::getDbo();
		
		if( count($contacts) ) {
			foreach ($contacts as $contactId){
				if (count($semail) && isset($semail[$contactId]) && count($semail[$contactId]))
				{										
					$register = new JRegistry();
					$register->loadArray($semail[$contactId]);
					$emails = (string)$register;					
					$query = 'UPDATE #__sfs_contacts SET systemEmails='.$db->quote($emails).' WHERE id='.(int)$contactId;
					$db->setQuery($query);
					$db->query();					
				} else {
					$query = 'UPDATE #__sfs_contacts SET systemEmails='.$db->quote('').' WHERE id='.(int)$contactId;
					$db->setQuery($query);
					$db->query();	
				}
			}	
		}
		
		$this->setRedirect('index.php?option=com_sfs&view=airline&layout=systememails&id='.$airlineId);
		return $this;		
	}

    public function copyAirline()
    {
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $model		= $this->getModel('Airline','SfsModel');


        $link = 'index.php?option=com_sfs&view=airline&layout=copyairline&id='.JRequest::getInt('id');

        if ( JRequest::getVar('tmpl') =='component' ) {
            $link .='&tmpl=component';
        }
        if( $model->copyAirline() ) {
            $msg = 'Copy successfully!';
            $this->setRedirect($link, $msg);
        } else {
            $msg = $model->getError();
            $this->setRedirect($link,$msg, 'error');
        }

        return true;
    }
	
	//lchung
	public function getAirportEdit( ){
		$user_id = JRequest::getInt('user_id', 0);
		$airline_id = JRequest::getInt('airline_id', 0);
		$model		= $this->getModel('Airline','SfsModel');
		$str = $model->getAirportEdit( $user_id, $airline_id);
		$arr = array('data' => $str );
		echo json_encode( $arr );
		exit;
	}
	//End lchung
	
}
