<?php
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/controllers/sfscontroller.php';

class SfsControllerAirlineProfile extends SfsController
{
	public function save(){		
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		// Initialise some variables
		$app		= JFactory::getApplication();
		
		if( SFSAccess::isMainContact() == 0 ) {
			//not allow edit
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=airlineprofile&Itemid='.JRequest::getInt('Itemid'), false)); 
			return false;
		}
										
		$model = $this->getModel('AirlineProfile');						
		$result = $model->saveAirline();

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
		
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=airlineprofile&Itemid='.JRequest::getInt('Itemid'), false));		
	}
	
	public function saveContacts()
	{
        $app		= JFactory::getApplication();
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		if( SFSAccess::isMainContact() == 0 ) {
			//not allow edit
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=airlineprofile&Itemid='.JRequest::getInt('Itemid'), false)); 
			return false;
		}
										
		$model = $this->getModel('AirlineProfile');		
		$result = $model->saveContacts();	
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
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=airlineprofile&layout=contacts&Itemid='.JRequest::getInt('Itemid'), false));		
	}
	
	public function addContact()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));		
		$app		= JFactory::getApplication();
		$db 		= JFactory::getDbo();		
		$session 	= JFactory::getSession();	
				
		$contact = JRequest::getVar('contact', array(), 'post', 'array');				
	
		$model = $this->getModel('AirlineProfile');		
		$result = $model->addContact($contact);	
		$session->clear('errorAirContact');							
		$this->setRedirect('index.php?option=com_sfs&view=close&tmpl=component&closetype=pairlineaddcontact&Itemid='.JRequest::getInt('Itemid'));
	}		
	
	public function changeAirline()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		$model = $this->getModel('AirlineProfile');		
		$result = $model->changeAirline();
		$this->setRedirect( JRoute::_( SfsHelperRoute::getSFSRoute('dashboard') ,false) );
	}
	
}


