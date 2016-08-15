<?php
defined('_JEXEC') or die;
class SfsControllerUserdetail extends JControllerLegacy
{
		
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Userdetail', $prefix = 'SfsModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}