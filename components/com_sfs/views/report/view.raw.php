<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class SfsViewReport extends JView
{   
	protected $datatotalbookingoverview;
    public function display($tpl = null)
    {              
        $this->processLayout( $this->getLayout() );  
        parent::display($tpl);
    }
 
    protected function processLayout($layout){

    	switch ($layout) {
    		case 'hotelreport':    			
    			$this->hotelreport();
    		case 'tophotels':
    			$this->tophotels();
    			break;
    		case 'percentage':
    			$this->percentage();  
    			break; 
			case 'totalbookingoverview':
    			$this->totalbookingoverview();  
    			break; 
			default:			
				break;			
    	}
    }
   
    protected function tophotels()
    {    	
    	$this->drawType = JRequest::getInt('type');
    	$model = $this->getModel('Report');
    	
    	$this->items = $model->getTopHotelData($this->drawType);    	
    	$this->chartData = $model->getAirlineChartData($this->drawType);
    }
    
    protected function percentage()
    {    	
    	$this->drawType = JRequest::getInt('type');
    	$model = $this->getModel('Report');
    	$this->items = $model->getPercentages($this->drawType);
    }    
    protected function hotelreport()
    {
    	$hotel = SFactory::getHotel();
    	$this->currency = $hotel->getTaxes()->currency_name;
    	$this->drawType = JRequest::getInt('type');
    	$model = $this->getModel('Report');
    	$this->reportdata = $model->getHotelReportData($this->drawType);    	
    }
	
	protected function totalbookingoverview()
    {
    	$model = $this->getModel('Report');
		$this->datatotalbookingoverview = $model->getTotalbookingoverview();    	
    }
}
