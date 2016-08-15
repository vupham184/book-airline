<?php
defined('_JEXEC') or die('');

class SfsControllerAirlinereporting extends JControllerLegacy {
	

	public function __construct($config = array())
	{
		parent::__construct($config);															
	}	
	
	public function getModel($name = 'Airlinereporting', $prefix = 'SfsModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	
	public function displayReports()
	{
		$document	= JFactory::getDocument();
		$vName		= 'airlinereporting';
		$vFormat	= 'raw';
		
		if ($view = $this->getView($vName, $vFormat)) {
			
			$model = $this->getModel();
			
			$value = JRequest::getVar('date_from');
    		$model->setState('filter.date_from',$value);
    		
    		$value = JRequest::getVar('date_to');
    		$model->setState('filter.date_to',$value);
   
    		$statuses = JRequest::getVar('blockStatus', array(), 'post', 'array');
    		$model->setState('filter.statuses',$statuses);
			
			$view->setModel($model, true);
			
			$view->setLayout('report');
			// Push document object into the view.
			$view->assignRef('document', $document);
		
			$view->display();			
		}
		
		JFactory::getApplication()->close();
	}
	
	public function chart()
    {
		error_reporting(0);
		ini_set('display_errors', 0 );
		
    	$model = $this->getModel();	
    	$model->drawChart();
    	
    	JFactory::getApplication()->close();     	
    }

    public function drawpie()
    {
    	error_reporting(0);
    	ini_set('display_errors', 0 );
    	
    	$model = $this->getModel();	
    	$model->drawPie();
    	
    	JFactory::getApplication()->close();    	
    }
    
		
}

