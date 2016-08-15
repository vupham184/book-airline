<?php
defined('_JEXEC') or die();

class SfsControllerCommunication extends JControllerLegacy
{
	public function __construct($config = array())
	{
		parent::__construct($config);		
	}	
	
	public function getModel($name = 'Communication', $prefix = 'SfsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
	
	public function getDetailEmail(){		
		$model = $this->getModel('Communication','SfsModel');		
		$result = $model->getDetailEmail();

		return $result;
	}

	public function sendmailuser(){
		$model = $this->getModel('Communication','SfsModel');		
		$result = $model->sendmailuser();

		return $result;
	}
	
	public function sortDataCommunication(){
		$model = $this->getModel('Communication','SfsModel');		
		$result = $model->sortDataCommunication();

		return $result;
	}
	

	public function getChangeFlight(){
		$model = $this->getModel('Communication','SfsModel');		
		$result = $model->getChangeFlight();

		return $result;
	}

	public function getFillterChange(){
		$model = $this->getModel('Communication','SfsModel');		
		$result = $model->getFillterChange();

		return $result;
	}
			
	public function sendmessage(){
		$model = $this->getModel('Communication', 'SfsModel');
		$result = $model->sendmessage();

		return $result;
	}

	public function sendmessageovb(){
		$model = $this->getModel('Communication', 'SfsModel');
		$result = $model->sendmessageovb();

		return $result;
	}
}

