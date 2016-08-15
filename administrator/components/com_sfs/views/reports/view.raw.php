<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewReports extends JView
{
	protected $state;

	public function display($tpl = null)
	{
		$this->state		= $this->get('State');		
		$this->statuses 	= $this->state->get('filter.statuses');
		
		if( $this->getLayout() == 'hotelreportresult' )
		{
			$this->hotel 		= $this->get('Hotel');		
			$this->reportdata	= $this->get('HotelReportData');
		}	
		if( $this->getLayout() == 'resulthotels' )
		{
			$this->reportdata	= $this->get('HotelReportData');
		}			
		if ( $this->getLayout() == 'airlinereport' )
		{			
			$this->roomnights 		= $this->get('Roomnights');
			$this->roomnightsChart  = $this->get('RoomnightsChartData');
			
			$this->averages 		= $this->get('Averages');
			$this->averageChart  	= $this->get('AverageChartData');
			
			$this->revenues 		= $this->get('Revenues');
			$this->revenueChart  	= $this->get('RevenueChartData');
			
			$this->iataCodePercentages 	= $this->get('IataCodePercentages');
			$this->marketPercentages 	= $this->get('MarketPercentages');
			$this->transportationPercentages = $this->get('TransportationPercentages');
			$this->initialPercentages = $this->get('InitialPercentages');								
		}
		
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}		
		
		parent::display($tpl);
	}

	
}


