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
	
	public function printVoucher()
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
					
		if( $result = $model->printVoucher() )
		{			
			
		}
				
		JFactory::getApplication()->close();
	}	
	
}


