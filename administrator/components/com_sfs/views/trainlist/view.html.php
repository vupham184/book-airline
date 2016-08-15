<?php
defined('_JEXEC') or die();

class SfsViewTrainlist extends JViewLegacy
{
	
	protected $train;	
	protected $listairline;
	//protected $listcountry;
	public function display($tpl = null)
	{
		// get the Data		
		$this->train 		= $this->get('AirlineTrain');
		$this->listairline	= $this->get('ListAirline');
		//$this->listcountry 	= $this->get('ListCountry');
		parent::display($tpl);
	}

	

}


