<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

require_once JPATH_COMPONENT.'/libraries/report.php';

class SfsControllerReport extends JController {

	public function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->registerTask('generatehr', 'processHotelReport');		
												
	}			

    public function processHotelReport()
    {
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$app		= JFactory::getApplication();				
		$user 		= JFactory::getUser();		
		$post 	    = JRequest::get('post'); 

		$m_from 	= (int)$post['m_from'];
		$y_from 	= (int)$post['y_from'];
		$m_to	 	= (int)$post['m_to'];
		$y_to 		= (int)$post['y_to'];
		
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
     * Return: Excel 2007 format
     * 
     **/
    public function exportRoomnights()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
    	$reportObject = AirlineReport::getInstance();
    	
    	$reportObject->exportRoomnights($dateFrom, $dateTo);
    	    	
    	JFactory::getApplication()->close();
    }
    
	/**
     * Report Type: Airline
     * 
     * Method: Allow airline to export average room prices booked
     * 
     * Return: Excel 2007 format
     * 
     **/
    public function exportAveragePrices()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
    	$reportObject = AirlineReport::getInstance();
    	
    	$reportObject->exportAveragePrices( $dateFrom, $dateTo );
    	
    	JFactory::getApplication()->close();
    }  

    /**
     * Report Type: Airline
     * 
     * Method: Allow airline to export revenue booked
     * 
     * Return: Excel 2007 format
     * 
     **/
    public function exportRevenueBooked()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
    	$reportObject = AirlineReport::getInstance();
    	
    	$reportObject->exportRevenueBooked( $dateFrom, $dateTo );
    	
    	JFactory::getApplication()->close();
    }   
    
	/**
     * Report Type: Airline
     * 
     * Method: Allow airline to export Top IATA codes
     * 
     * Return: Excel 2007 format
     * 
     **/
    public function exportIATACodeReason()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
    	$reportObject = AirlineReport::getInstance();
    	
    	$reportObject->exportIATACodeReason( $dateFrom, $dateTo );
    	
    	JFactory::getApplication()->close();
    }
        
	/**
     * Report Type: Airline
     * 
     * Method: Allow airline to export Market pick up
     * 
     * Return: Excel 2007 format
     * 
     **/
    public function exportMarketPickup()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
    	$reportObject = AirlineReport::getInstance();
    	
    	$reportObject->exportMarketPickup( $dateFrom, $dateTo );
    	
    	JFactory::getApplication()->close();
    }
    
    
	/**
     * Report Type: Airline
     * 
     * Method: Allow airline to export Transportation Details
     * 
     * Return: Excel 2007 format
     * 
     **/
    public function exportTransportationDetails()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
    	$reportObject = AirlineReport::getInstance();
    	
    	$reportObject->exportTransportationDetails( $dateFrom, $dateTo );
    	
    	JFactory::getApplication()->close();
    }
    
	/**
     * Report Type: Airline
     * 
     * Method: Allow airline to export Initial blocked pick up
     * 
     * Return: Excel 2007 format
     * 
     **/
    public function exportInitialBlockedPickup()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
    	$reportObject = AirlineReport::getInstance();
    	
    	$reportObject->exportInitialBlockedPickup( $dateFrom, $dateTo );
    	
    	JFactory::getApplication()->close();
    }
    
    
	/**
     * Report Type: Hotel
     * 
     * Method: Allow hotel to export roomnights details in date range
     * 
     * Return: Excel 2007 format
     * 
     **/
    public function exportHotelRoomnights()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$mFrom 		= JRequest::getVar('m_from');
    	$yFrom   	= JRequest::getVar('y_from');
    	$mTo 		= JRequest::getVar('m_to');
    	$yTo   		= JRequest::getVar('y_to');
    	
    	$reportObject = HotelReport::getInstance();
    	
    	$reportObject->exportRoomnights($mFrom, $yFrom,$mTo ,$yTo);
    	
    	// Close Application
    	JFactory::getApplication()->close();
    }
    
	/**
     * Report Type: Hotel
     * 
     * Method: Allow hotel to export average prices details in date range
     * 
     * Return: Excel 2007 format
     * 
     **/
    public function exportHotelAveragePrices()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$mFrom	 = JRequest::getVar('m_from');
    	$yFrom   = JRequest::getVar('y_from');
    	$mTo 	 = JRequest::getVar('m_to');
    	$yTo     = JRequest::getVar('y_to');
    	
    	$reportObject = HotelReport::getInstance();
    	
    	$reportObject->exportAveragePrices($mFrom, $yFrom,$mTo ,$yTo);
    	
    	// Close Application
    	JFactory::getApplication()->close();
    }
    
	/**
     * Report Type: Hotel
     * 
     * Method: Allow hotel to export revenue booked details in date range
     * 
     * Return: Excel 2007 format
     * 
     **/
    public function exportHotelRevenueBooked()
    {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$mFrom	 = JRequest::getVar('m_from');
    	$yFrom   = JRequest::getVar('y_from');
    	$mTo 	 = JRequest::getVar('m_to');
    	$yTo     = JRequest::getVar('y_to');
    	
    	$reportObject = HotelReport::getInstance();
    	
    	$reportObject->exportRevenueBooked($mFrom, $yFrom,$mTo ,$yTo);
    	
    	// Close Application
    	JFactory::getApplication()->close();
    }
	
	//lchung
	public function newReportAirline()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	
    	$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');

    	$reportObject = AirlineReport::getInstance();
    	
    	$reportObject->exportInitialNewReportAirline( $dateFrom, $dateTo );
    	JFactory::getApplication()->close();
	}
	//End lchung
 
}


