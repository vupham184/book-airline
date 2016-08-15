<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class SfsViewClose extends JView
{

	function display($tpl = null)
	{
		
		$reload 	= JRequest::getInt('reload');
				
		$closeScript = 'window.parent.SqueezeBox.close();';	
			
		if($reload)
		{
			JFactory::getDocument()->addScriptDeclaration('
				window.parent.location.href=window.parent.location.href;
				window.parent.SqueezeBox.close();
			');
			return;	
		}		
		
		JFactory::getDocument()->addScriptDeclaration($closeScript);	
	}
}
