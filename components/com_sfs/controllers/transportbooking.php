<?php
defined('_JEXEC') or die;

class SfsControllerTransportbooking extends JControllerLegacy
{	
		
	public function __construct()
	{
		parent::__construct();		
	}	
	
	public function getModel($name = 'Transportbooking', $prefix = 'SfsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
	public function booking()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		// Initialise some variables
		$app	 = JFactory::getApplication();		
		$user 	 = JFactory::getUser();
		$airline = SFactory::getAirline();
		
		if( ! SFSAccess::check($user, 'a.admin') )  {			
			JError::raiseError(404, JText::_('Restricted Access'));
			return false;	
		}
			
		$model = $this->getModel();
					
		if( ! $model->booking() )
		{			
			$error = $model->getError();		
			
			$url = 'index.php?option=com_sfs&view=transportbooking&Itemid='.JRequest::getInt('Itemid');
			
			$this->setRedirect(JRoute::_($url, false), $error);
			return false;
		}
				
		
		$url = 'index.php?option=com_sfs&view=transportbooking&layout=confirm&Itemid='.JRequest::getInt('Itemid');
		
		$this->setRedirect(JRoute::_($url, false));
			
		return true;
	}
	
	public function accepted()
	{		
		// Initialise some variables
		$app	 = JFactory::getApplication();				
		$model = $this->getModel();				
		
		$errorMsg = '';					
		if( ! $model->updateStatus('accepted') )
		{			
			$errorMsg = $model->getError();			
		}
				
		$reference_number = JRequest::getVar('reference_number');
		$confirmLink = 'index.php?option=com_sfs&view=transportbooking&layout=accepted&reference_number='.$reference_number;				
		
		$userKey = $model->getUserKey();
		if( $userKey )
		{			
			$link = 'index.php?option=com_sfsuser&uk='.$userKey;
			$link .= '&return_link='.base64_encode($confirmLink);
			$this->setRedirect($link);	
		} else {			
			$this->setRedirect(JRoute::_($confirmLink, false),$errorMsg);	
		}
		
		return $this;		
	}
	
	public function declined()
	{
		// Initialise some variables
		$app	 = JFactory::getApplication();		
		
		$model = $this->getModel();
		
		$errorMsg = '';
					
		if( ! $model->updateStatus('declined') )
		{			
			$errorMsg = $model->getError();			
		}			
		
		$reference_number = JRequest::getVar('reference_number');
		$confirmLink = 'index.php?option=com_sfs&view=transportbooking&layout=declined&reference_number='.$reference_number;				
		
		$userKey = $model->getUserKey();
		if( $userKey )
		{			
			$link = 'index.php?option=com_sfsuser&uk='.$userKey;
			$link .= '&return_link='.base64_encode($confirmLink);
			$this->setRedirect($link);	
		} else {			
			$this->setRedirect(JRoute::_($confirmLink, false),$errorMsg);	
		}
			
		return $this;
		
	}
	
}


