<?php
defined('_JEXEC') or die;

class SfsControllerTaxibooking extends JControllerLegacy
{	
		
	public function __construct()
	{
		parent::__construct();		
	}	
	
	public function getModel($name = 'Taxibooking', $prefix = 'SfsModel', $config = array('ignore_request' => true))
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
			
			$url = 'index.php?option=com_sfs&view=taxibooking&Itemid='.JRequest::getInt('Itemid');
			
			$this->setRedirect(JRoute::_($url, false), $error);
			return false;
		}
				
		$taxi_reservation_id = $model->getState('taxi_reservation_id');
		$url = 'index.php?option=com_sfs&view=taxibooking&taxi_reservation_id='.$taxi_reservation_id.'&layout=confirm&Itemid='.JRequest::getInt('Itemid');		
		$this->setRedirect(JRoute::_($url, false));
			
		return true;
	}
	
	public function cancelVoucher()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));		
		// Initialise some variables
		
		$app	 = JFactory::getApplication();		
		$user 	 = JFactory::getUser();
		$airline = SFactory::getAirline();
		
		/*if( ! SFSAccess::check($user, 'a.admin') )  {			
			JError::raiseError(404, JText::_('Restricted Access'));
			return false;	
		}		
		$taxi_reservation_id = JRequest::getInt('taxi_reservation_id');			
		$model = $this->getModel();					
		if( ! $model->cancelVoucher() )
		{			
			$error = $model->getError();
			$url = 'index.php?option=com_sfs&view=taxibooking&Itemid='.JRequest::getInt('Itemid').'&layout=confirm&taxi_reservation_id='.$taxi_reservation_id;			
			$this->setRedirect(JRoute::_($url, false), $error);
			return false;
		}
		*/
		$url = 'index.php?option=com_sfs&view=taxibooking&Itemid='.JRequest::getInt('Itemid');		
		$this->setRedirect(JRoute::_($url, false));			
		return true;
	}
	
}


