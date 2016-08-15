<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');


class SfsControllerDelaycode extends JControllerForm
{

	function __construct($config = array())
	{
		parent::__construct($config);
	}

	protected function allowAdd($data = array())
	{
		return true;
	}

	protected function allowEdit($data = array(), $key = 'id')
	{
		return true;
	}
}