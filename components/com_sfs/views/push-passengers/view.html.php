<?php
defined('_JEXEC') or die();
jimport('joomla.application.component.view');

class SfsViewPush_Passengers extends JView
{
	protected $state;
	protected $params;
	protected $user;
	
	function display($tpl = null) 
	{
		/*$app	= JFactory::getApplication();
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}		*/	
				
		parent::display($tpl);		
	}
		
}
?>

