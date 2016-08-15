<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class SfsModelHome extends JModel
{
	
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}	
	
}
