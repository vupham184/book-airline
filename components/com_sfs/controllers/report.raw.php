<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class SfsControllerReport extends JController {

	public function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->registerTask('totalbookingoverviewchart','drawTotalbookingoverviewChart');
		$this->registerTask('generatehr','processHotelReport');
		$this->registerTask('percentage','calculatePercentage');
		$this->registerTask('hotelchart','drawHotelChart');
		$this->registerTask('airlinechart','drawAirlineChart');																	
	}	
	
	//lchung
	public function totalbookingoverview()
	{
		// Get the document object.
    	$document	= JFactory::getDocument();
    	$vName		= 'report';
    	$vFormat	= 'raw';
    		
    	$model = $this->getModel('Report');
    	//print_r( $model->getTotalbookingoverview());
    	if ($view = $this->getView($vName, $vFormat)) {
    		// Push document object into the view.
    		$view->setModel($model);
    		$view->assignRef('document', $document);
			///$view->assignRef('test', 'Hung test');
    		$view->setLayout('totalbookingoverview');
    		$view->display();    		
    	}		
	}
	//End lchung
	
	public function hotelreport()
	{
	    	// Get the document object.
    	$document	= JFactory::getDocument();
    	$vName		= 'report';
    	$vFormat	= 'raw';
    		
    	$model = $this->getModel('Report');
    	    		
    	if ($view = $this->getView($vName, $vFormat)) {
    		// Push document object into the view.
    		$view->setModel($model);
    		$view->assignRef('document', $document);
    		$view->setLayout('hotelreport');
    		$view->display();    		
    	}		
	}       
    public function tophotels()
    {		
    	// Get the document object.
    	$document	= JFactory::getDocument();
    	$vName		= 'report';
    	$vFormat	= 'raw';
    		
    	$model = $this->getModel('Report');
    	    		
    	if ($view = $this->getView($vName, $vFormat)) {
    		// Push document object into the view.
    		$view->setModel($model);
    		$view->assignRef('document', $document);
    		$view->setLayout('tophotels');
    		$view->display();    		
    	}
		    	    	
    }    
    public function calculatePercentage()
    {   
        // Get the document object.
    	$document	= JFactory::getDocument();
    	$vName		= 'report';
    	$vFormat	= 'raw';
    		
    	$model = $this->getModel('Report');
    	    		
    	if ($view = $this->getView($vName, $vFormat)) {
    		// Push document object into the view.
    		$view->setModel($model);
    		$view->assignRef('document', $document);
    		$view->setLayout('percentage');
    		$view->display();    		
    	}    	
    }    
    public function drawpie()
    {
    	error_reporting(0);
    	ini_set('display_errors', 0 );
    	$model = $this->getModel('Report');	
    	$model->drawPie();
    	jexit();    	    	
    }    
    public function drawline()
    {
    	ini_set('display_errors', 0 );
    	$model = $this->getModel('Report');	
    	$model->drawLine();
    	jexit();    	    	
    }        
    public function drawHotelChart()
    {
		ini_set('display_errors', 0 );
    	$model = $this->getModel('Report');	
    	$model->drawHotelChart();
    	jexit();        	
    }
    public function drawAirlineChart()
    {
		ini_set('display_errors', 0 );
    	$model = $this->getModel('Report');	
    	$model->drawAirlineChart();
    	jexit();        	
    }    
	
	public function drawTotalbookingoverviewChart()
    {
		ini_set('display_errors', 0 );
    	$model = $this->getModel('Report');	
    	$model->drawTotalbookingoverviewChart();
    	jexit();        	
    }    
    
	
}

