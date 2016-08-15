<?php
defined('_JEXEC') or die;

class SfsViewPopup extends JViewLegacy
{
	const CASE_OPEN = 'open';
	const CASE_ERROR = 'error';
	
	function display($tpl = null)
	{
		$type = JRequest::getVar('action');
		$script = '';
		switch ($type) {
			case self::CASE_OPEN:
				$url = JRequest::getVar('u');
				$w = JRequest::getVar('w');
				$h = JRequest::getVar('h');
				$closable = JRequest::getVar('closable') ? 'true' : 'false';
				$script = "
					//window.top.SqueezeBox.close();
					window.top.SqueezeBox.open('$url', {handler: 'iframe', size: {x: '$w', y: '$h'}, 'closable': $closable });
				";
				break;
			case self::CASE_ERROR:
				$this->message = JRequest::getVar('message');
				parent::display('error');
		}		
		JFactory::getDocument()->addScriptDeclaration($script);		
	}
}
