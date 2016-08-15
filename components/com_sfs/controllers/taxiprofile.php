<?php
defined('_JEXEC') or die();

class SfsControllerTaxiprofile extends JControllerLegacy
{
	public function __construct($config = array())
	{
		parent::__construct($config);		
	}	
	
	public function getModel($name = 'Taxiprofile', $prefix = 'SfsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
		
	public function save() 
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();		
		$model 		= $this->getModel();
		
		$result 	= $model->save();
		
		if($result)
		{
			$link = 'index.php?option=com_sfs&view=taxiprofile&Itemid='.JRequest::getInt('Itemid');
		} else {			
			$errors	= $model->getErrors();			
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}			
			$link = 'index.php?option=com_sfs&view=taxiprofile&layout=edit&Itemid='.JRequest::getInt('Itemid');
		}		
		
		$this->setRedirect(JRoute::_($link,false));		
		return $this;
	}
	
	
	public function saveRates() 
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();		
		$model 		= $this->getModel();
		
		$result 	= $model->saveRates();
		
		if(!$result)
		{	
			$errors	= $model->getErrors();			
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}						
		}		
		
		$link = 'index.php?option=com_sfs&view=taxiprofile&layout=rates&Itemid='.JRequest::getInt('Itemid');
		
		$this->setRedirect(JRoute::_($link,false));		
		return $this;
	}
	
			
}

