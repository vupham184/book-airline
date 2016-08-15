<?php
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT.'/helpers/report.php';

class SfsControllerReport extends JController {
	

	public function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->registerTask('generatehr',			'processHotelReport');													
	}	
		

    public function processHotelReport()
    {
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();				
		$user 		= JFactory::getUser();		
		$post 	    = JRequest::get('post'); 

		$m_from = (int)$post['m_from'];
		$y_from = (int)$post['y_from'];
		$m_to = (int)$post['m_to'];
		$y_to = (int)$post['y_to'];
		if($m_from && $y_from && $m_to && $y_to) {
			$link = 'index.php?option=com_sfs&view=report&layout=hotel';
			$link .= '&m_from='.$m_from;
			$link .= '&y_from='.$y_from;
			$link .= '&m_to='.$m_to;
			$link .= '&y_to='.$y_to;
			$link .= '&Itemid='.$post['Itemid'];
			$link = JRoute::_($link,false);
			$this->setRedirect( $link );			
		}						
    }
    
    public function exportHotel()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		$user = JFactory::getUser();

		// Make sure that just the hotel group can process this task
		if( ! SFSAccess::isHotel($user) ) {
			return false;	
		}    	
    	$exporttype = JRequest::getInt('exporttype');
    	
    	$model = $this->getModel('Report');						
		$data = $model->getData();
		
		$filename = '';
				
		switch ($exporttype) {
			case 1:
				$filename = 'hotel_roomnighs.csv';
				break;
			case 2:
				$filename = 'hotel_average.csv';
				break;
			case 3:
				$filename = 'hotel_revenue.csv';
				break;							
		}
		
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment;filename='.$filename);
        $fp = fopen('php://output', 'w');
        
    	switch ($exporttype) {
			case 1:
				fputcsv($fp, array('Month','Roomnights'));
				foreach ($data as $value) {
					$date = JHtml::_('date',$value->date,'Y - F');
        			fputcsv($fp, array($date,$value->number_rooms));	
       			}			
				break;
			case 2:
				fputcsv($fp, array('Month','ADR'));
				foreach ($data as $value) {
					$date = JHtml::_('date',$value->date,'Y - F');
        			fputcsv($fp, array($date,$value->average_price));	
       			}					
				break;
			case 3:
				fputcsv($fp, array('Month','Net Revenue'));
				foreach ($data as $value) {
					$date = JHtml::_('date',$value->date,'Y - F');
        			fputcsv($fp, array($date,$value->revenue_booked));	
       			}					
				break;							
		}        
		        
		fclose($fp);
    	die;
    }
    
    public function iatacode()
    {    	
    	$model = $this->getModel('Report');	
    	$model->drawIatacodeChart();
    	jexit();
    }
    
    /**
     * Report Type: Airline
     * 
     * Method: Allow airline to export total roomnights
     * 
     * Return: CSV file
     * 
     **/
    public function exportRoomnights()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$airlineId = JRequest::getInt('airline_id');
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
    	$reportObject = AReport::getInstance();
    	$reportObject->setAirline($airlineId);
    	
    	$reportObject->exportRoomnights($dateFrom, $dateTo);
    	
    	die();
    }
    
	/**
     * Report Type: Airline
     * 
     * Method: Allow airline to export average room prices booked
     * 
     * Return: CSV file
     * 
     **/
    public function exportAveragePrices()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$airlineId = JRequest::getInt('airline_id');
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
    	$reportObject = AReport::getInstance();
    	$reportObject->setAirline($airlineId);
    	
    	$reportObject->exportAveragePrices( $dateFrom, $dateTo );
    	die();
    }  

    /**
     * Report Type: Airline
     * 
     * Method: Allow airline to export revenue booked
     * 
     * Return: CSV file
     * 
     **/
    public function exportRevenueBooked()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$airlineId = JRequest::getInt('airline_id');
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
    	$reportObject = AReport::getInstance();
    	$reportObject->setAirline($airlineId);
    	
    	$reportObject->exportRevenueBooked( $dateFrom, $dateTo );
    	die();
    }   
    
	/**
     * Report Type: Airline
     * 
     * Method: Allow airline to export Top IATA codes
     * 
     * Return: CSV file
     * 
     **/
    public function exportIATACodeReason()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$airlineId = JRequest::getInt('airline_id');
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
    	$reportObject = AReport::getInstance();
    	$reportObject->setAirline($airlineId);
    	
    	$reportObject->exportIATACodeReason( $dateFrom, $dateTo );
    	die();
    }
        
	/**
     * Report Type: Airline
     * 
     * Method: Allow airline to export Market pick up
     * 
     * Return: CSV file
     * 
     **/
    public function exportMarketPickup()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$airlineId = JRequest::getInt('airline_id');
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
    	$reportObject = AReport::getInstance();
    	$reportObject->setAirline($airlineId);
    	
    	$reportObject->exportMarketPickup( $dateFrom, $dateTo );
    	die();
    }
    
    
	/**
     * Report Type: Airline
     * 
     * Method: Allow airline to export Transportation Details
     * 
     * Return: CSV file
     * 
     **/
    public function exportTransportationDetails()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$airlineId = JRequest::getInt('airline_id');
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
    	$reportObject = AReport::getInstance();
    	$reportObject->setAirline($airlineId);
    	
    	$reportObject->exportTransportationDetails( $dateFrom, $dateTo );
    	die();
    }
    
	/**
     * Report Type: Airline
     * 
     * Method: Allow airline to export Initial blocked pick up
     * 
     * Return: CSV file
     * 
     **/
    public function exportInitialBlockedPickup()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$airlineId = JRequest::getInt('airline_id');
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
    	$reportObject = AReport::getInstance();
    	$reportObject->setAirline($airlineId);
    	
    	$reportObject->exportInitialBlockedPickup( $dateFrom, $dateTo );
    	die();
    }
    
    
	/**
     * Report Type: Hotel
     * 
     * Method: Allow hotel to export roomnights details in date range
     * 
     * Return: CSV file
     * 
     **/
    public function exportHotelRoomnights()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$mFrom = JRequest::getVar('m_from');
    	$yFrom   = JRequest::getVar('y_from');
    	$mTo = JRequest::getVar('m_to');
    	$yTo   = JRequest::getVar('y_to');
    	
    	$hotelId = JRequest::getInt('hotel_id');
    	
    	$reportObject = HReport::getInstance();
    	$reportObject->setHotel($hotelId);
    	
    	$reportObject->exportRoomnights($mFrom, $yFrom,$mTo ,$yTo);
    	die();
    }
    
	/**
     * Report Type: Hotel
     * 
     * Method: Allow hotel to export average prices details in date range
     * 
     * Return: CSV file
     * 
     **/
    public function exportHotelAveragePrices()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$mFrom = JRequest::getVar('m_from');
    	$yFrom   = JRequest::getVar('y_from');
    	$mTo = JRequest::getVar('m_to');
    	$yTo   = JRequest::getVar('y_to');
    	$hotelId = JRequest::getInt('hotel_id');
    	
    	$reportObject = HReport::getInstance();
    	$reportObject->setHotel($hotelId);
    	
    	$reportObject->exportAveragePrices($mFrom, $yFrom,$mTo ,$yTo);
    	die();
    }
    
	/**
     * Report Type: Hotel
     * 
     * Method: Allow hotel to export revenue booked details in date range
     * 
     * Return: CSV file
     * 
     **/
    public function exportHotelRevenueBooked()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$mFrom = JRequest::getVar('m_from');
    	$yFrom   = JRequest::getVar('y_from');
    	$mTo = JRequest::getVar('m_to');
    	$yTo   = JRequest::getVar('y_to');
    	$hotelId = JRequest::getInt('hotel_id');
    	$reportObject = HReport::getInstance();
    	$reportObject->setHotel($hotelId);
    	
    	$reportObject->exportRevenueBooked($mFrom, $yFrom,$mTo ,$yTo);
    	die();
    }
    
    
	/**
     * Report Type: ALl Hotels
     * 
     **/
    public function exportRoomnightsForHotels()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$mFrom = JRequest::getVar('m_from');
    	$yFrom   = JRequest::getVar('y_from');
    	$mTo = JRequest::getVar('m_to');
    	$yTo   = JRequest::getVar('y_to');
    	
    	    	
    	$reportObject = HReport::getInstance();
    	    	
    	$reportObject->exportRoomnightsForHotels($mFrom, $yFrom,$mTo ,$yTo);
    	die();
    }
    
    public function exportRevenuesForHotels()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$mFrom = JRequest::getVar('m_from');
    	$yFrom   = JRequest::getVar('y_from');
    	$mTo = JRequest::getVar('m_to');
    	$yTo   = JRequest::getVar('y_to');
    	
    	    	
    	$reportObject = HReport::getInstance();
    	    	
    	$reportObject->exportRevenuesForHotels($mFrom, $yFrom,$mTo ,$yTo);
    	die();
    }
    
	public function exportAveragesForHotels()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$mFrom = JRequest::getVar('m_from');
    	$yFrom   = JRequest::getVar('y_from');
    	$mTo = JRequest::getVar('m_to');
    	$yTo   = JRequest::getVar('y_to');
    	
    	    	
    	$reportObject = HReport::getInstance();
    	    	
    	$reportObject->exportAveragesForHotels($mFrom, $yFrom,$mTo ,$yTo);
    	die();
    }
 
}


