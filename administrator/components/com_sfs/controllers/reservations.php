<?php
defined('_JEXEC') or die;

class SfsControllerReservations extends JControllerLegacy
{
	
	protected $text_prefix = 'Reservations';	
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}	
	
	public function getModel($name = 'Reservations', $prefix = 'SfsModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function batch()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model	= $this->getModel();				
		$result = $model->batch();
							
		$link = 'index.php?option=com_sfs&view=reservations';	
				
		if( $result === false )
		{
			$msg = $model->getError();
			JError::raiseWarning(500, $msg);
			$this->setRedirect($link);					
		} else {
			$msg = 'Successfully updated block statuses';					
			$this->setRedirect($link,$msg);		
		}
		return $this;
	}

	public function sendMailReminder(){ 
		
		$model	= $this->getModel();				
		$result = $model->sendMailReminder();

		return $result; 
	}
}