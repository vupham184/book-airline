<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

//class SfsControllerRentalcar extends JController
class SfsControllerRentalcar extends JControllerForm
{

	function __construct($config = array())
	{		
		parent::__construct($config);			
	}
	protected function allowAdd($data = array())
	{
		return parent::allowAdd($data);
	}

	protected function allowEdit($data = array(), $key = 'id')
	{		
		return true;
	}
	
	function cancel(){

		$link = 'index.php?option=com_sfs&view=rentalcars';
		$this->setRedirect($link,$msg);	
	}
}
