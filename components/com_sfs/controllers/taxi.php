<?php
defined('_JEXEC') or die;

class SfsControllerTaxi extends JControllerLegacy
{
	
	public function save()
	{		
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
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
										
		$model  = $this->getModel('Taxi', 'SfsModel');		
						
		$result = $model->saveDetails();

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
		
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=taxi&Itemid='.JRequest::getInt('Itemid'), false));
	}
	
	public function saveRate()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		// Initialise some variables
		$app		= JFactory::getApplication();
		$airline	= SFactory::getAirline();
		
		$taxi_id	= JRequest::getInt('taxi_id');
		
		if( !SFSAccess::isAirline() ) 
		{
			//not allow edit
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=close&tmpl=component', false)); 
			return false;
		}
		
		if( SFSAccess::isMainContact() == 0 ) {
			//not allow edit
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=close&tmpl=component', false)); 
			return false;
		}	
		
		$model  = $this->getModel('Taxi', 'SfsModel');		
						
		$result = $model->saveRate();
		
		$msg = '';
		
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
		} else {
			$msg = 'Successfully Saved';
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=taxi&tmpl=component&layout=rate&taxi_id='.$taxi_id.'&Itemid='.JRequest::getInt('Itemid'), false), $msg);
	}
	
	public function removeTaxi()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		// Initialise some variables
		$app		= JFactory::getApplication();
		$airline	= SFactory::getAirline();
		
		if( !SFSAccess::isAirline() ) 
		{
			//not allow edit
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=close&tmpl=component', false)); 
			return false;
		}
		
		if( SFSAccess::isMainContact() == 0 ) {
			//not allow edit
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=close&tmpl=component', false)); 
			return false;
		}	
		
		$model  = $this->getModel('Taxi', 'SfsModel');		
						
		$model->removeTaxi();
		
		$url = 'index.php?option=com_sfs&view=close&tmpl=component&closetype=removetaxi&Itemid='.JRequest::getInt('Itemid');
		
		$this->setRedirect( JRoute::_($url, false) );
	}
	
	
}


