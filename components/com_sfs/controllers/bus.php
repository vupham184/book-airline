<?php
defined('_JEXEC') or die();

class SfsControllerBus extends JControllerLegacy
{
	public function __construct($config = array())
	{
		parent::__construct($config);		
	}	
	
	public function getModel($name = 'Bus', $prefix = 'SfsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
	
	public function acceptBooking()
	{
		$msg = '';
		if( SFSAccess::isBus() )
		{
			$bus = SFactory::getBus();
			if( (int)$bus->id > 0 )
			{
				$resid = JRequest::getInt('id',0);
				$db = JFactory::getDbo();
				$query = 'SELECT * FROM #__sfs_transportation_reservations WHERE id='.$resid.' AND transport_company_id='.$bus->id.' AND status='.$db->quote('pending');
				$db->setQuery($query);
				$reservation = $db->loadObject();
				
				if($reservation)
				{
					$query = 'UPDATE #__sfs_transportation_reservations SET status='.$db->quote('accepted').' WHERE id='.$resid;
					$db->setQuery($query);
					$db->query();
				}				
			}	
		}
		$link = 'index.php?option=com_sfs&view=bus&layout=bookings';
		$this->setRedirect($link,$msg);
	}
	
	public function declineBooking()
	{
		$msg = '';
		if( SFSAccess::isBus() )
		{
			$bus = SFactory::getBus();
			if( (int)$bus->id > 0 )
			{
				$resid = JRequest::getInt('id',0);
				$db = JFactory::getDbo();
				$query = 'SELECT * FROM #__sfs_transportation_reservations WHERE id='.$resid.' AND transport_company_id='.$bus->id.' AND status='.$db->quote('pending');
				$db->setQuery($query);
				$reservation = $db->loadObject();
				
				if($reservation)
				{
					$query = 'UPDATE #__sfs_transportation_reservations SET status='.$db->quote('declined').' WHERE id='.$resid;
					$db->setQuery($query);
					$db->query();
				}				
			}	
		}
		$link = 'index.php?option=com_sfs&view=bus&layout=bookings';
		$this->setRedirect($link,$msg);
	}
	
	public function save() 
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();		
		$model 		= $this->getModel();
		
		$result 	= $model->save();
		
		if($result)
		{
			$link = 'index.php?option=com_sfs&view=bus&Itemid='.JRequest::getInt('Itemid');
		} else {			
			$errors	= $model->getErrors();			
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}			
			$link = 'index.php?option=com_sfs&view=bus&layout=edit&Itemid='.JRequest::getInt('Itemid');
		}		
		
		$this->setRedirect(JRoute::_($link,false));		
		return $this;
	}
	
	public function saveProfiles() 
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();		
		$model 		= $this->getModel();
		
		$result 	= $model->saveProfiles();
		
		if($result)
		{
			$link = 'index.php?option=com_sfs&view=bus&layout=profiles&Itemid='.JRequest::getInt('Itemid');
		} else {			
			$errors	= $model->getErrors();			
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}			
			$link = 'index.php?option=com_sfs&view=bus&layout=editprofile&Itemid='.JRequest::getInt('Itemid');
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
		
		if($result)
		{
			$link = 'index.php?option=com_sfs&view=close&reload=1&tmpl=component';
		} else {			
			$errors	= $model->getErrors();			
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}			
			$link = 'index.php?option=com_sfs&view=bus&tmpl=component&layout=rate&profile_id='.JRequest::getInt('profile_id');
		}		
		
		$this->setRedirect(JRoute::_($link,false));		
		return $this;
	}
	
	public function removeProfile()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();		
		$model 		= $this->getModel();	
			
		$result 	= $model->removeProfile();
		
		$profile_id = JRequest::getInt('profile_id');
				
		if($result)
		{
			$msg  = '';
			$link = 'index.php?option=com_sfs&view=close&reload=1&tmpl=component';
		} else {
			$msg  = $model->getError();								
			$link = 'index.php?option=com_sfs&view=bus&tmpl=component&layout=removeprofile&profile_id='.$profile_id.'&Itemid='.JRequest::getInt('Itemid');
		}		
		
		$this->setRedirect(JRoute::_($link,false),$msg);		
		return $this;
	}
	
	public function removeLineRate()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();		
		$model 		= $this->getModel();	
			
		$result 	= $model->removeLineRate();
		
		$profile_id = JRequest::getInt('profile_id');
				
		if($result)
		{
			$msg  = '';
			$link = 'index.php?option=com_sfs&view=close&reload=1&tmpl=component';
		} else {
			$msg  = $model->getError();								
			$link = 'index.php?option=com_sfs&view=bus&tmpl=component&layout=removeprofile&profile_id='.$profile_id.'&Itemid='.JRequest::getInt('Itemid');
		}		
		
		$this->setRedirect(JRoute::_($link,false),$msg);		
		return $this;
	}

	public function removeLineRateFixed()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();		
		$model 		= $this->getModel();	
			
		$result 	= $model->removeLineRateFixed();
		
		$profile_id = JRequest::getInt('profile_id');
				
		if($result)
		{
			$msg  = '';
			$link = 'index.php?option=com_sfs&view=close&reload=1&tmpl=component';
		} else {
			$msg  = $model->getError();								
			$link = 'index.php?option=com_sfs&view=bus&tmpl=component&layout=removeprofile&profile_id='.$profile_id.'&Itemid='.JRequest::getInt('Itemid');
		}		
		
		$this->setRedirect(JRoute::_($link,false),$msg);		
		return $this;
	}
			
}

