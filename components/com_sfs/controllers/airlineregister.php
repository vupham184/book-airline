<?php
// No direct access to this file
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

class SfsControllerAirlineregister extends JController
{

	public function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->registerTask('saveairline',		'saveAirline');
		$this->registerTask('savecontacts',		'saveContacts');
		$this->registerTask('addcontact',		'addContact');					
	}	
	
	public function saveAirline()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();				
		$session	= JFactory::getSession();			
		$post 	    = JRequest::get('post');
						
		$row = JTable::getInstance('airline', 'JTable');
			
		if ( ! $row->bind( $post ) ) 
		{ 
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n"; 
			exit(); 
		}
		
		$row->telephone = SfsHelper::getPhoneString($post['phone_code'],$post['phone_number']);		
		
		if( ! $row->check() )
		{
			$link  =  JRoute::_('index.php?option=com_sfs&view=airlineregister&Itemid='.$post['Itemid'] , false );

			$errors	= $row->getErrors();			
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}			
			$session->clear('airline');
			$this->setRedirect($link); 		
			return ;			
		}
				
		$properties = $row->getProperties();		
		$airlineData = array();
				
		foreach ( $properties as $key => $value ) {
			if( $value ) $airlineData[$key] = $value;
		}
						
		$session->set('airline',$airlineData);
		
		
		$billing = JRequest::getVar('billing', array(), 'post', 'array');
		
		$session->set('abilling',$billing);
		
		
		$link = JRoute::_('index.php?option=com_sfs&view=airlineregister&layout=contacts&Itemid='.$post['Itemid'],false);
		$this->setRedirect( $link );	
	}
	
	public function saveContacts()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();
		$db 		= JFactory::getDbo();		
		$session 	= JFactory::getSession();	
				
		$contacts = JRequest::getVar('contact', array(), 'post', 'array');	
					
		$errorChk = false;

		if ( count($contacts) ) {

			//check for duplicate emails
			$emails = array();
			foreach ( $contacts as $key => $contact ) {
				$contact['email'] = trim($contact['email']);
				if( !isset( $emails[ $contact['email'] ] ) ) {
					$emails[ $contact['email'] ] = 0;
				}
				$emails[ $contact['email'] ] = $emails[ $contact['email'] ] + 1;
			}
			
			foreach ($emails as $k => $email) {
				if ( $email > 1) {
					$app->enqueueMessage('E-Mail: '.$k.' is duplicated' , 'warning');
					$errorChk = true;	
				}
			}
			$session->set('airContacts',$contacts);			
		} else {
			$errorChk = true;
		}
		
		$session->clear('airline_additional_contact');		
				
		if( $errorChk ) {		
			$link = JRoute::_('index.php?option=com_sfs&view=airlineregister&layout=contacts&Itemid='.JRequest::getInt('Itemid'),false);
												
		} else {
			$link = JRoute::_('index.php?option=com_sfs&view=airlineregister&layout=confirm&Itemid='.JRequest::getInt('Itemid'),false);					
		}
		$this->setRedirect($link);									
	}
	
	/*
	public function addContact()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();
		$db 		= JFactory::getDbo();		
		$session 	= JFactory::getSession();	

		$msg = '';
		
		$contact = JRequest::getVar('contact', array(), 'post', 'array');
		
		$query = 'SELECT COUNT(*) FROM #__users WHERE email='.$db->Quote( trim($contact['email']) ) ;
		
		$db->setQuery($query);
		$result = (int)$db->loadResult();		
		
		if( ! $result ) {
			$contacts = $session->get('airAdditionalContacts');									
			if( count($contacts) ) {
				$contacts[count($contacts)] = $contact; 
			} else {
				$contacts = array();
				$contacts[0] = $contact;
			}			
			$session->set('airAdditionalContacts',$contacts);
			$session->clear('errorContact');							
			$this->setRedirect('index.php?option=com_sfs&view=close&tmpl=component&closetype=airaddcontact');	
		} else {
			$session->set('errorContact',$contact);			
			$link =  JRoute::_('index.php?option=com_sfs&view=airlineregister&layout=addcontact&tmpl=component&Itemid='.JRequest::getInt('Itemid') );
			$this->setRedirect($link);	
		}			
	}
	*/
	
	public function addContact()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();
		$db 		= JFactory::getDbo();		
		$session 	= JFactory::getSession();	

		$msg = '';
		
		$contact = JRequest::getVar('contact', array(), 'post', 'array');
		
		$query = 'SELECT COUNT(*) FROM #__users WHERE email='.$db->Quote( trim($contact['email']) ) ;
		
		$db->setQuery($query);
		$result = (int)$db->loadResult();		
		
		if( ! $result ) {
			$session->clear('errorContact');	
			$aContacts = null;
			if( ! $session->has('airline_additional_contact') ) {
				$aContacts = array();
			} else {
				$aContacts = $session->get('airline_additional_contact');
			}		
			$randomKey = SfsHelper::createRandomPassword(9);
			$aContacts[$randomKey] = $contact;				
			$session->set('airline_additional_contact',$aContacts);						
			$this->setRedirect('index.php?option=com_sfs&view=close');	
		} else {
			$session->set('errorContact',$contact);			
			$link =  JRoute::_('index.php?option=com_sfs&view=airlineregister&layout=addcontact&tmpl=component&Itemid='.JRequest::getInt('Itemid') );
			$this->setRedirect($link);	
		}			
	}
	
	public function confirm() 
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();	
		$post['confirm'] = JRequest::getVar('confirm');
		
		if( empty($post['confirm']) ) {
			//$app->setUserState('register.confirm', 'false');
			$app->enqueueMessage(JText::_('COM_SFS_AIRLINE_REGISTER_CONFIRM_ERROR_MSG'), 'warning');		
			$link = JRoute::_('index.php?option=com_sfs&view=airlineregister&layout=confirm&Itemid='.JRequest::getInt('Itemid'),false);			
			$this->setRedirect($link);
			return;			
		}
								
		$post['Itemid'] = JRequest::getInt('Itemid');
		
		$model = $this->getModel('Airlineregister','SfsModel');
		
		$result = $model->save();
		
		if( $result ) {
			$link = JRoute::_('index.php?option=com_sfs&view=airlineregister&layout=thankyou&Itemid='.$post['Itemid'],false);
			$this->setRedirect($link);	
		} else {
			
			$errors	= $model->getErrors();			
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}				
			
			$link = JRoute::_('index.php?option=com_sfs&view=airlineregister&Itemid='.$post['Itemid'],false);
			$this->setRedirect($link);			
		}
		
	}
			
}

