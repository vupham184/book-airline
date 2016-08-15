<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class SfsControllerGh extends JControllerForm
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
		
		$model		= $this->getModel('Gh','SfsModel');
		
		if( $model->newAdmin() ) {
			$this->setRedirect('index.php?option=com_sfs&view=gh&layout=newadmin&tmpl=component&id='.JRequest::getInt('id'));	
		} else {
			$msg = $model->getError();
			$this->setRedirect('index.php?option=com_sfs&view=gh&layout=newadmin&tmpl=component&id='.JRequest::getInt('id'),$msg);
		}		
		
		return true;
	}	
	
	public function approved()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$id = JRequest::getInt('id');
		$model		= $this->getModel('Gh','SfsModel');
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
		
		$airlineId = JRequest::getInt('gh_id');
		
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
		
		$this->setRedirect('index.php?option=com_sfs&view=gh&layout=systememails&id='.$airlineId);
		return $this;		
	}
	
}