<?php
defined('_JEXEC') or die;

class SfsControllerTooltip extends JControllerLegacy
{

	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function saveTooltip()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model			= $this->getModel('Tooltip','SfsModel');
		$tooltipType 	= JRequest::getVar('tooltip_type');
		
		$result = $model->saveTooltip($tooltipType);
		
		$msg = '';
		
		if( ! $result )
		{
			$msg = $model->getError();			
		} 
		
		$link = 'index.php?option=com_sfs&view=tooltip&layout='.$tooltipType;
		
		$this->setRedirect($link,$msg);	
		
		return $this;
	}
	
	
}

