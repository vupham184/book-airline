<?php
defined('_JEXEC') or die();

class SfsControllerTaxiregister extends JControllerLegacy
{

	public function __construct($config = array())
	{
		parent::__construct($config);		
	}	
	
	public function getModel($name = 'Taxiregister', $prefix = 'SfsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
	
	public function validate()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();				
		$session	= JFactory::getSession();		

		$model 		= $this->getModel('Taxiregister','SfsModel');
		
		$taxiDetails  = JRequest::getVar('taxidetails', array() , 'post' , 'array');
		$userData     = JRequest::getVar('account', array() , 'post' , 'array');
		
		$result 	  = $model->validate($taxiDetails, $userData);		
				
		if($result) {					
			$link = 'index.php?option=com_sfs&view=taxiregister&layout=confirm&Itemid='.JRequest::getInt('Itemid');
		} else {			
			$errors	= $model->getErrors();			
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}			
			$link = 'index.php?option=com_sfs&view=taxiregister&Itemid='.JRequest::getInt('Itemid');
		}				
		
		$app->setUserState('taxi.register.data.taxidetails', $taxiDetails);
		$app->setUserState('taxi.register.data.accountdetails', $userData);	
		
		$this->setRedirect(JRoute::_($link,false));		
		return $this;		
	}
	
	public function register() 
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();				
		$session	= JFactory::getSession();

		$accept_term = JRequest::getInt('accept_term', 0);
		
		if( $accept_term !=1 )
		{
			$link = 'index.php?option=com_sfs&view=taxiregister&layout=confirm&Itemid='.JRequest::getInt('Itemid');
			$this->setRedirect(JRoute::_($link,false),'Please tick the checkbox to approve General Terms and Conditions');		
			return $this;
		}

		$model 		= $this->getModel('Taxiregister','SfsModel');
		
		$result 	= $model->register();
		
		if($result) {
			$app->setUserState('taxi.register.data.taxidetails', null);
			$app->setUserState('taxi.register.data.accountdetails', null);				
			$link = 'index.php?option=com_sfs&view=taxiregister&layout=thankyou&Itemid='.JRequest::getInt('Itemid');
		} else {			
			$errors	= $model->getErrors();			
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}				
			$link = 'index.php?option=com_sfs&view=taxiregister&Itemid='.JRequest::getInt('Itemid');
		}		
		
		$this->setRedirect(JRoute::_($link,false));		
		return $this;
	}
	
			
}

