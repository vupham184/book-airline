<?php
defined('_JEXEC') or die;

class SfsControllerMessage extends JControllerLegacy
{
	public function send()
	{				
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app	= JFactory::getApplication();
		
		if( SFSAccess::isAirline() || SFSAccess::isHotel() )
		{			
			$model = $this->getModel('Message');
						
			if($model->send()) 
			{
				$id 	 = JRequest::getInt('bookingid');
				$airport = JRequest::getVar('airport');
				$mlayout = JRequest::getVar('mlayout');	
				
				if($mlayout=='tentative') {
					$link = JRoute::_('index.php?option=com_sfs&view=close&closetype=closemtentative&tmpl=component&id='.$id.'&airport='.$airport.'&Itemid='.JRequest::getInt('Itemid'), false);
					$this->setRedirect($link);										
				} else if($mlayout=='hchallenge') {
					$link = JRoute::_('index.php?option=com_sfs&view=close&tmpl=component', false);
					$this->setRedirect($link);										
				}  else {
					$link = JRoute::_('index.php?option=com_sfs&view=close&closetype=closechallenge&tmpl=component&id='.$id.'&Itemid='.JRequest::getInt('Itemid'), false);
					$this->setRedirect($link);					
				}			
				    					
			} else {
				
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
    	} else {
    		jexit('SFS Restricted access');
    	}
    					
	}	
}

