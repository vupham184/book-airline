<?php
defined('_JEXEC') or die();

class SfsuserViewClose extends JViewLegacy
{
	
	function display($tpl = null)
	{
		$loginerror  = JRequest::getInt('loginerror',0);
		if( $loginerror ) {
			$redirectUrl = JURI::base().'index.php?option=com_sfsuser&view=login&Itemid=104';
		} else {
			$redirectUrl = JURI::base().'index.php?option=com_sfs&view=dashboard&Itemid=103';	
		}
			
		JFactory::getDocument()->addScriptDeclaration('
			window.parent.location.href="'.$redirectUrl.'";
			window.parent.SqueezeBox.close();
		');
	}
	

}

