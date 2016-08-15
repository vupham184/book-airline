<?php
defined('_JEXEC') or die;

class SfsControllerReports extends JControllerLegacy
{

	protected $context = 'com_sfs.reports';
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->registerTask('generatehr','processHotelReport');
		$this->registerTask('percentage','calculatePercentage');
		$this->registerTask('hotelchart','drawHotelChart');
		$this->registerTask('airlinechart','drawAirlineChart');																	
	}		

	public function getModel($name = 'Reports', $prefix = 'SfsModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function hotelReport()
	{		
		$document	= JFactory::getDocument();
		$vName		= 'reports';
		$vFormat	= 'raw';
		
		if ($view = $this->getView($vName, $vFormat)) {
			
			$model = $this->getModel();
						
			$model->setState('filter.hotel_id', JRequest::getInt('hotel_id',0));
			
			$value = JRequest::getInt('m_from');
    		$model->setState('filter.month_from',$value);
    		
    		$value = JRequest::getInt('y_from');
    		$model->setState('filter.year_from',$value);
    	
    		$value = JRequest::getInt('m_to');
    		$model->setState('filter.month_to',$value);
    	
    		$value = JRequest::getInt('y_to');
    		$model->setState('filter.year_to',$value);
			
			$view->setModel($model, true);
			$view->setLayout('hotelreportresult');
			// Push document object into the view.
			$view->assignRef('document', $document);
		
			$view->display();			
		}
		
		die;
	}
	
	public function airlineReport()
	{		
		$document	= JFactory::getDocument();
		$vName		= 'reports';
		$vFormat	= 'raw';
		
		if ($view = $this->getView($vName, $vFormat)) {
			
			$model = $this->getModel();
						
			$model->setState('report.airline_id', JRequest::getInt('airline_id',0));
			
			$value = JRequest::getVar('date_from');
    		$model->setState('filter.date_from',$value);
    		
    		$value = JRequest::getVar('date_to');
    		$model->setState('filter.date_to',$value);
    		
    		$statuses = JRequest::getVar('blockStatus', array(), 'post', 'array');
    		$model->setState('filter.statuses',$statuses);   
			
			$view->setModel($model, true);
			
			$view->setLayout('airlinereport');
			// Push document object into the view.
			$view->assignRef('document', $document);
		
			$view->display();			
		}
		
		die;
	}

	public function drawHotelChart()
    {
    	error_reporting(0);
		ini_set('display_errors', 0 );
		
    	$model = $this->getModel();	
    	$model->drawHotelChart();
    	
    	JFactory::getApplication()->close();
    }
    
    public function drawAirlineChart()
    {
		error_reporting(0);
		ini_set('display_errors', 0 );
		
    	$model = $this->getModel();	
    	$model->drawAirlineChart();
    	
    	JFactory::getApplication()->close();     	
    }  
    
	public function drawpie()
    {
    	error_reporting(0);
    	ini_set('display_errors', 0 );
    	
    	$model = $this->getModel();	
    	$model->drawPie();
    	
    	jexit();    	    	
    }
    
    /**
     * 
     * Report All Hotels
     */
    public function reportAllHotels()
	{		
		$document	= JFactory::getDocument();
		$vName		= 'reports';
		$vFormat	= 'raw';
		
		if ($view = $this->getView($vName, $vFormat)) {
			
			$model = $this->getModel();
												
			$value = JRequest::getInt('m_from');
    		$model->setState('filter.month_from',$value);
    		
    		$value = JRequest::getInt('y_from');
    		$model->setState('filter.year_from',$value);
    	
    		$value = JRequest::getInt('m_to');
    		$model->setState('filter.month_to',$value);
    	
    		$value = JRequest::getInt('y_to');
    		$model->setState('filter.year_to',$value);
    		
    		$statuses = JRequest::getVar('blockStatus', array(), 'post', 'array');
    		$model->setState('filter.statuses',$statuses);
			
			$view->setModel($model, true);
			$view->setLayout('resulthotels');
			// Push document object into the view.
			$view->assignRef('document', $document);
		
			$view->display();			
		}
				
	}
	
    
}

