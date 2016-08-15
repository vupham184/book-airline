<?php
defined('_JEXEC') or die('Restricted access');
class SfsControllerStarttotalstayhotelsearches extends JController {
		
	protected $objPHPExcel = null;

	public function __construct($config = array())
	{
		$this->objPHPExcel = new PHPExcel();
		
		parent::__construct($config);															
	}
		
	
	public function getModel($name = 'Starttotalstayhotelsearches', $prefix = 'SfsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
	function autoSearch(){
		$m = $this->getModel();
		$m->autoSearch();
	}
}