<?php
defined('_JEXEC') or die;
require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel.php';
require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel/Worksheet/Drawing.php';

jimport('joomla.application.component.model');
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_sfs/models');

abstract class SfsReport extends JObject
{

    public $objPHPExcel = null;
    public $beginDate = null;
    public $endDate = null;
	
	public function __construct($config = array())
	{
		$this->objPHPExcel = new PHPExcel();																	
	}
	
	public function getPhpExcelObject()
	{
		return $this->objPHPExcel;
	}
	
	public function setPeriodDate( $beginDate, $endDate )
	{
		$this->set('beginDate', $beginDate);
		$this->set('endDate', $endDate);
	}
	
	protected function setCSVHeader($reportName)
	{
		$user = JFactory::getUser();
		
		// Set document properties
		$this->objPHPExcel->getProperties()->setCreator($user->get('name'))
		->setLastModifiedBy($user->get('name'))
		->setTitle($reportName)
		->setSubject($reportName)
		->setDescription($reportName)
		->setKeywords("sfs airline hotel reporting")
		->setCategory("Sfs Reporting");		
	}
	
	public function download($fileName)
	{
		// Redirect output to a client�s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}
	
}

class AirlineReport extends SfsReport
{
	
	public function __construct($config = array())
	{
		parent::__construct();
	}
	
	/**
	 * Get a airline report object.
	 *
	 * Returns the global airline report object, only creating it if it doesn't already exist.	 
	 *
	 * @return  AirlineReport object	 
	 */
	public static function getInstance()
	{
		static $instance;
		if (!$instance ) {
			$instance = new AirlineReport();			
		}
		return $instance;
	}
	
	protected function setCSVHeader($reportName)
	{
		parent::setCSVHeader($reportName);
		
		$user = JFactory::getUser();
		$airline = SFactory::getAirline();
		
		$exportDate = JHTML::_('date',JFactory::getDate()->toSql(), JText::_('DATE_FORMAT_LC3'));
		
		$startColumn = 0;
		$row = 0;
		
		$objPHPExcel = $this->getPhpExcelObject();
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
				
		$beginDate 	= $this->get('beginDate');
		$endDate	= $this->get('endDate');
		
		//SFS address	
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'SFS-web reporting',false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Creation date',false)
			->setCellValueByColumnAndRow($startColumn+1, $row, $exportDate,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Created by',false)
			->setCellValueByColumnAndRow($startColumn+1,  $row, $user->get('username'),false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Airport station',false)
			->setCellValueByColumnAndRow($startColumn+1,  $row, $airline->airport_code,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Report Period',false)
			->setCellValueByColumnAndRow($startColumn+1, $row, 'From: '.$beginDate.' To: '.$endDate,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Report Name',false)
			->setCellValueByColumnAndRow($startColumn+1, $row, $reportName,false);
		
		return $row;		
	}
	
	public function exportRoomnights( $beginDate , $endDate )
	{
		$airline = SFactory::getAirline();		
		$user    = JFactory::getUser();
		
		// Check if the user is Airline Admin
		if ( ! SFSAccess::check($user, 'a.admin') ) {
			return false;
		}
		
		$objPHPExcel = $this->getPhpExcelObject();
			
		$this->setPeriodDate($beginDate, $endDate);
				
		$currentRow = $this->setCSVHeader('Total number roomnights');
		
		$fileName = 'RoomnightsReport_'.$beginDate.'_'.$endDate.'.xlsx';
						
		//get data
		$data = $this->getRoomnights();	
		
		$row    = $currentRow + 2;
		
		if( $airline->grouptype==3 )
		{
			$column = 8;	
			$headers = array('Date','Airline','Hotel Name','Nr of Initial Rooms','Nr of Claimed Rooms','','','','Hotel Name','Total Nr of Initial Rooms','Total Nr of Claimed Rooms');
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(23);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(23);
		} else {
			$column = 7;
			$headers = array('Date','Hotel Name','Nr of Initial Rooms','Nr of Claimed Rooms','','','','Hotel Name','Total Nr of Initial Rooms','Total Nr of Claimed Rooms');
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(23);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(23);
		}
		
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, 'From: '.$beginDate.' To: '.$endDate,false);				
		
		$row++;
		$column = 0;
		
		foreach ($headers as $text)
		{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $text,false);
			$column++;				
		}
		
		$row++;
		$column = 0;
		
		
		$data2 = array();
		
		foreach ($data as $hotel) {	
			
			if( ! isset($data2[$hotel->hotel_id]) ) {
				$data2[$hotel->hotel_id] = new stdClass();
				$data2[$hotel->hotel_id]->hotel_name = $hotel->hotel_name;				
				$data2[$hotel->hotel_id]->initial_rooms = 0;
				$data2[$hotel->hotel_id]->claimed_rooms = 0;
			}
			$initial_rooms = (int)$hotel->sd_room+(int)$hotel->t_room+(int)$hotel->s_room+(int)$hotel->q_room;
			
			$data2[$hotel->hotel_id]->claimed_rooms += $hotel->claimed_rooms;
			$data2[$hotel->hotel_id]->initial_rooms += $initial_rooms;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->date,false);
			$column++;
			
			if( $airline->grouptype==3 )
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $hotel->gh_airline,false);
				$column++;
			}
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->hotel_name,false);
			$column++;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $initial_rooms ,false);
			$column++;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, (int)$hotel->claimed_rooms,false);
				
			$column=0;
			$row++;
		}
		
		$row    = $currentRow + 4;
			
		foreach ($data2 as $hotel) {
			if( $airline->grouptype==3 )
			{
				$column = 8;
			} else {
				$column = 7;	
			}		
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->hotel_name,false);
			$column++;

			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->initial_rooms ,false);
			$column++;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, (int)$hotel->claimed_rooms,false);							
			$row++;
		}	
				
		$this->download($fileName);
	}
	
	public function exportAveragePrices( $beginDate , $endDate )
	{
		$airline = SFactory::getAirline();		
		$user    = JFactory::getUser();
		
		// Check if the user is Airline Admin
		if ( ! SFSAccess::check($user, 'a.admin') ) {
			return false;
		}
		
		$objPHPExcel = $this->getPhpExcelObject();
			
		$this->setPeriodDate($beginDate, $endDate);
				
		$currentRow = $this->setCSVHeader('Average room prices booked');
		
		$fileName = 'Average_Room_Prices_Report_'.$beginDate.'_'.$endDate.'.xlsx';
						
		//get data
		$data = $this->getAveragePrices();	
		
		$row    = $currentRow + 2;
		
		$column = 7;
		
		if( $airline->grouptype==3 )
		{
			$column = 8;	
			$headers = array('Date','Airline','Hotel Name','S/D Room Rate','T Room Rate','Currency','','','Hotel Name','Average S/D Room Rate','Average T Room Rate','Currency');
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(23);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(23);
		} else {
			$column = 7;
			$headers = array('Date','Hotel Name','S Room Rate','S/D Room Rate','T Room Rate','Q Room Rate','Currency','Hotel Name','Average S Room Rate','Average S/D Room Rate','Average T Room Rate','Average Q Room Rate','Currency');
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
		}
		
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, 'From: '.$beginDate.' To: '.$endDate,false);		
				
		
		
		$row++;		
		$column = 0;
		
		foreach ($headers as $text)
		{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $text,false);
			$column++;				
		}
		
		$row++;
		$column = 0;
		
		
		$data2 = array();
		
		foreach ($data as $hotel) {	

			if( ! isset($data2[$hotel->hotel_id]) ) {
				$data2[$hotel->hotel_id] = new stdClass();
				$data2[$hotel->hotel_id]->hotel_name = $hotel->hotel_name;				
				$data2[$hotel->hotel_id]->currency = $hotel->currency;	
				$data2[$hotel->hotel_id]->s_rate = 0;			
				$data2[$hotel->hotel_id]->sd_rate = 0;
				$data2[$hotel->hotel_id]->t_rate = 0;
				$data2[$hotel->hotel_id]->q_rate = 0;
				$data2[$hotel->hotel_id]->s_count = 0;
				$data2[$hotel->hotel_id]->sd_count = 0;
				$data2[$hotel->hotel_id]->t_count = 0;
				$data2[$hotel->hotel_id]->q_count = 0;
			}
			
			if( $hotel->s_rate > 0 )
			{
				$data2[$hotel->hotel_id]->s_rate += $hotel->s_rate ;
				$data2[$hotel->hotel_id]->s_count++;
			}
			if( $hotel->sd_rate > 0 )
			{
				$data2[$hotel->hotel_id]->sd_rate += $hotel->sd_rate ;
				$data2[$hotel->hotel_id]->sd_count++;
			}
			if( $hotel->t_rate > 0 )
			{
				$data2[$hotel->hotel_id]->t_rate += $hotel->t_rate ;
				$data2[$hotel->hotel_id]->t_count++;
			}
			if( $hotel->q_rate > 0 )
			{
				$data2[$hotel->hotel_id]->q_rate += $hotel->q_rate ;
				$data2[$hotel->hotel_id]->q_count++;
			}
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->date,false);
			$column++;
			
			if( $airline->grouptype==3 )
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $hotel->gh_airline,false);
				$column++;
			}
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->hotel_name,false);
			$column++;			
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->s_rate ,false);
			$column++;

			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->sd_rate ,false);
			$column++;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->t_rate ,false);
			$column++;

			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->q_rate ,false);
			$column++;
				
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->currency ,false);
				
			$column=0;
			$row++;
		}
		
		$row    = $currentRow + 4;
		
		foreach ($data2 as $hotel) {

			if( $airline->grouptype==3 )
			{
				$column = 8;
			} else {
				$column = 7;	
			}
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->hotel_name,false);
			$column++;
			
			if( $hotel->s_rate )
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, number_format($hotel->s_rate/$hotel->s_count,2) ,false);	
			}			
			$column++;
			if( $hotel->sd_rate )
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, number_format($hotel->sd_rate/$hotel->sd_count,2) ,false);	
			}			
			$column++;			
			if( $hotel->t_rate )
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, number_format($hotel->t_rate/$hotel->t_count,2) ,false);	
			}
			$column++;		
			if( $hotel->q_rate )
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, number_format($hotel->q_rate/$hotel->q_count,2) ,false);	
			}
			$column++;	
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->currency,false);	
							
			$row++;
		}	
				
		$this->download($fileName);
	}
	
	public function exportRevenueBooked( $beginDate , $endDate )
	{
		$airline = SFactory::getAirline();		
		$user    = JFactory::getUser();
		
		// Check if the user is Airline Admin
		if ( ! SFSAccess::check($user, 'a.admin') ) {
			return false;
		}
		
		$objPHPExcel = $this->getPhpExcelObject();
			
		$this->setPeriodDate($beginDate, $endDate);
				
		$currentRow = $this->setCSVHeader('Revenue Booked');
		
		$fileName = 'Revenue_Booked_'.$beginDate.'_'.$endDate.'.xlsx';
						
		//get data
		$data = $this->getRevenueBooked();				
		
		$row    = $currentRow + 2;
		$column = 7;
		
		if( $airline->grouptype==3 )
		{
			$column = 8;	
			$headers = array('Date','Airline','Hotel Name','Revenue booked','Currency','','','','Hotel Name','Total Revenue booked','Currency');
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(23);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(23);
		} else {
			$column = 7;
			$headers = array('Date','Hotel Name','Revenue booked','Currency','','','','Hotel Name','Total Revenue booked','Currency');
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(23);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(23);
		}
		
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, 'From: '.$beginDate.' To: '.$endDate,false);		
				
		
		$row++;
		$column = 0;
		
		foreach ($headers as $text)
		{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $text,false);
			$column++;				
		}
		
		$row++;
		$column = 0;
		
		
		$data2 = array();
		
		foreach ($data as $hotel) {	
			
			if( ! isset($data2[$hotel->hotel_id]) ) {
				$data2[$hotel->hotel_id] = new stdClass();
				$data2[$hotel->hotel_id]->hotel_name = $hotel->hotel_name;				
				$data2[$hotel->hotel_id]->currency   = $hotel->currency;
				$data2[$hotel->hotel_id]->revenue_booked = 0;				
			}			
			$data2[$hotel->hotel_id]->revenue_booked += $hotel->revenue_booked;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->date,false);
			$column++;
			
			if( $airline->grouptype==3 )
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $hotel->gh_airline,false);
				$column++;
			}
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->hotel_name,false);
			$column++;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->revenue_booked ,false);
			$column++;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->currency,false);
				
			$column=0;
			$row++;
		}
		
		$row    = $currentRow + 4;		
		foreach ($data2 as $hotel) {
			if( $airline->grouptype==3 )
			{
				$column = 8;
			} else {
				$column = 7;	
			}			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->hotel_name,false);
			$column++;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->revenue_booked ,false);
			$column++;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $hotel->currency,false);
							
			$row++;
		}	
				
		$this->download($fileName);
	}
	
	
	public function exportIATACodeReason( $beginDate , $endDate )
	{
		$airline = SFactory::getAirline();		
		$user    = JFactory::getUser();
		
		// Check if the user is Airline Admin
		if ( ! SFSAccess::check($user, 'a.admin') ) {
			return false;
		}
		
		$objPHPExcel = $this->getPhpExcelObject();
			
		$this->setPeriodDate($beginDate, $endDate);
				
		$currentRow = $this->setCSVHeader('IATA code reason');
		
		$fileName = 'IATAcode_reason_'.$beginDate.'_'.$endDate.'.xlsx';
						
		//get data
		$data = $this->getTopIATACodes();	
		
		$row    = $currentRow + 2;
		
		if( $airline->grouptype==3 )
		{
			$column = 8;	
			$headers = array('Date','Airline','IATA Code','Passengers','Flight Number','Flight Class','','','Top IATA Code','Total Passengers','Reason');
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(23);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(23);
		} else {
			$column = 7;
			$headers = array('Date','IATA Code','Passengers','Flight Number','Flight Class','','','Top IATA Code','Total Passengers','Reason');
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(23);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(23);
		}
		
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, 'From: '.$beginDate.' To: '.$endDate,false);				
		
		$row++;
		$column = 0;
		
		foreach ($headers as $text)
		{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $text,false);
			$column++;				
		}
		
		$row++;
		$column = 0;
		
		
		$data2 = array();
		
		foreach ($data as $iatacode) {	
			
			if( ! isset($data2[$iatacode->delay_code]) ) {
				$data2[$iatacode->delay_code] = new stdClass();
				$data2[$iatacode->delay_code]->delay_code = $iatacode->delay_code;
				$data2[$iatacode->delay_code]->description = $iatacode->description;	
				$data2[$iatacode->delay_code]->seats = 0	;									
			}			
			$data2[$iatacode->delay_code]->seats  += $iatacode->seats;
					
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $iatacode->from_date,false);
			$column++;
			
			if( $airline->grouptype==3 )
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $iatacode->gh_airline,false);
				$column++;
			}
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $iatacode->delay_code,false);
			$column++;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $iatacode->seats,false);
			$column++;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $iatacode->flight_code ,false);
			$column++;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $iatacode->flight_class,false);
				
			$column=0;
			$row++;
		}

		$row    = $currentRow + 4;
			
		foreach ($data2 as $iatacode) {
			if( $airline->grouptype==3 )
			{
				$column = 8;
			} else {
				$column = 7;	
			}		
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $iatacode->delay_code,false);
			$column++;

			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $iatacode->seats ,false);
			$column++;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $iatacode->description,false);							
			$row++;
		}	
				
		$this->download($fileName);
	}
	
	public function exportMarketPickup( $beginDate , $endDate )
	{
		$airline = SFactory::getAirline();		
		$user    = JFactory::getUser();
		
		// Check if the user is Airline Admin
		if ( ! SFSAccess::check($user, 'a.admin') ) {
			return false;
		}
		
		$objPHPExcel = $this->getPhpExcelObject();
			
		$this->setPeriodDate($beginDate, $endDate);
				
		$currentRow = $this->setCSVHeader('Market pick up');
		
		$fileName = 'Market_Pickup_'.$beginDate.'_'.$endDate.'.xlsx';
						
		//get data
		$data = $this->getPercentageOfBookedRooms();	
				
		$row    = $currentRow + 2;
		
		if( $airline->grouptype==3 )
		{
			$column = 7;	
			$headers = array('Date','Number booked rooms','Number rooms in market','Picked Up(Percentage)','','','','Total Booked Rooms','Total rooms in market','Picked Up(Percentage)');
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(23);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(23);
		} else {
			$column = 7;
			$headers = array('Date','Number booked rooms','Number rooms in market','Picked Up(Percentage)','','','','Total Booked Rooms','Total rooms in market','Picked Up(Percentage)');
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(23);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(23);
		}
		
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, 'From: '.$beginDate.' To: '.$endDate,false);				
		
		$row++;
		$column = 0;
		
		foreach ($headers as $text)
		{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $text,false);
			$column++;				
		}
		
		$row++;
		$column = 0;
		
		
		$data2 = array();
		
		$totalBookedRooms=0;
		$totalRoomsInMarket=0;
		
		if( is_array($data[0]) && is_array($data[1]) ){
			if( count($data[0]) && count($data[1]) ) {
				foreach ($data[1] as $k => $v) {				
					$totalBookedRooms   += $v;
					$totalRoomsInMarket += $data[0][$k]; 
				}
			}
		}
		
		if( is_array($data[0]) && is_array($data[1]) ){
			if( count($data[0]) && count($data[1]) ) {
				foreach ($data[1] as $k => $v) {				
					$percentage  = number_format( $v/($data[0][$k]+$v)*100, 2);					
					
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, $k,false);
					$column++;

					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, $v,false);
					$column++;
					
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, $data[0][$k],false);
					$column++;
					
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, $percentage.'%',false);
					
					$column=0;					
					$row++;
				}
				
				$row    = $currentRow + 4;
				$column = 7;
				fputcsv( $this->fp, array($k,$v,$data[0][$k],$percentage.'%','','','',$totalBookedRooms,$totalRoomsInMarket,number_format( $totalBookedRooms/($totalBookedRooms+$totalRoomsInMarket)*100, 2).'%') );
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, $totalBookedRooms,false);
				$column++;
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, $totalRoomsInMarket,false);
				$column++;
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, number_format( $totalBookedRooms/($totalBookedRooms+$totalRoomsInMarket)*100, 2).'%' ,false);
				$column++;
			}
		}
				
		$this->download($fileName);
	}
	
	
	public function exportTransportationDetails( $beginDate , $endDate )
	{
		$airline = SFactory::getAirline();		
		$user    = JFactory::getUser();
		
		// Check if the user is Airline Admin
		if ( ! SFSAccess::check($user, 'a.admin') ) {
			return false;
		}
		
		$objPHPExcel = $this->getPhpExcelObject();
			
		$this->setPeriodDate($beginDate, $endDate);
				
		$currentRow = $this->setCSVHeader('Transportation Details');
		
		$fileName = 'Transportation_Details_'.$beginDate.'_'.$endDate.'.xlsx';
						
		//get data
		$data = $this->getTransportationDetails();
				
		$row    = $currentRow + 2;
		
		if( $airline->grouptype==3 )
		{
			$column = 5;	
			$headers = array('Date','Transport included','Transport Excluded','','','Percentage Transport included','Percentage Transport Excluded');
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		} else {
			$column = 5;
			$headers = array('Date','Transport included','Transport Excluded','','','Percentage Transport included','Percentage Transport Excluded');
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		}
		
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, 'From: '.$beginDate.' To: '.$endDate,false);				
		
		$row++;
		$column = 0;
		
		foreach ($headers as $text)
		{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $text,false);
			$column++;				
		}
		
		$row++;
		$column = 0;
		
		
		$totalRoomsWithTransportation = 0;
		$totalBookedRooms 			  = 0;
		
		if( is_array($data[0]) && is_array($data[1]) ){
			if( count($data[0]) && count($data[1]) ) {
				foreach ($data[1] as $k => $v) {				
					$totalBookedRooms  				 += $v;
					$totalRoomsWithTransportation	 += $data[0][$k]; 
				}
			}
		}
						
		
		if( is_array($data[0]) && is_array($data[1]) ){
			if( count($data[0]) && count($data[1]) ) {
				foreach ($data[1] as $k => $v) {					
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, $k,false);
					$column++;

					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, (int)$data[0][$k],false);
					$column++;
					
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, $v-$data[0][$k],false);
								
					$column=0;					
					$row++;			
				}
				
				$pti = number_format( $totalRoomsWithTransportation/$totalBookedRooms * 100, 2);
				
				$row    = $currentRow + 4;
				$column = 5;
								
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, $pti.'%',false);
				$column++;
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, (100-$pti).'%',false);				
				
			}
		}
		
		$this->download($fileName);
	}
	
	
	public function exportInitialBlockedPickup( $beginDate , $endDate )
	{
		$airline = SFactory::getAirline();		
		$user    = JFactory::getUser();
		
		// Check if the user is Airline Admin
		if ( ! SFSAccess::check($user, 'a.admin') ) {
			return false;
		}
		
		$objPHPExcel = $this->getPhpExcelObject();
			
		$this->setPeriodDate($beginDate, $endDate);
		
		$currentRow = $this->setCSVHeader('Initial blocked pick up');
		$fileName = 'Initial_blocked_pick_up_'.$beginDate.'_'.$endDate.'.xlsx';
						
		//get data
		$data = $this->getInitialBlockedDetails();
				
		$row  = $currentRow + 2;
		
		if( $airline->grouptype==3 )
		{
			$column  = 5;	
			$headers = array('Date','Initial Rooms','Claimed Rooms','','','Picked Up(Percentage)');
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		} else {
			$column  = 5;
			$headers = array('Date','Initial Rooms','Claimed Rooms','','','Picked Up(Percentage)');
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		}
		
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, 'From: '.$beginDate.' To: '.$endDate,false);				
		
		$row++;
		$column = 0;
		
		foreach ($headers as $text)
		{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $text,false);
			$column++;				
		}
		
		$row++;
		$column = 0;
				
		if(count($data)) {		
			foreach ($data as $v) {				
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, $v->date,false);
				$column++;
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, (int)$v->initial_rooms,false);
				$column++;
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, (int)$v->claimed_rooms,false);
				
				$column=0;					
				$row++;
				
			}	
			$row    = $currentRow + 4;
			$column = 5;
			
			$totalInitialRooms = 0;
			$totalClaimedRooms = 0;
			
			if(count($data)) {
				foreach ($data as $v) {				
					$totalInitialRooms   += $v->initial_rooms;
					$totalClaimedRooms	 += $v->claimed_rooms; 
				}
			}

			$pti = number_format( $totalClaimedRooms/ $totalInitialRooms * 100, 2);
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $pti.'%',false);		
		}
		
		$this->download($fileName);
	}
	
	
	
	protected function getRoomnights()
	{
		$user    = JFactory::getUser();
		$airline = SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;
		$db 	 = JFactory::getDbo();				

		$query = $db->getQuery(true);

		$query->select('a.claimed_rooms, a.s_room, a.sd_room, a.t_room, a.q_room, c.name AS hotel_name, b.date, b.hotel_id');
		$query->order('a.id ASC');
		
		$query->from('#__sfs_reservations AS a');

		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');

		$query->where('a.airline_id='.$airline->id);
		
		if( $airline->grouptype==3 )
    	{	    		
    		$gh_airline = (int)JRequest::getInt('gh_airline');
    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
    		
    		if($gh_airline > 0){
    			$query->where('ghr.airline_id='.$gh_airline);
    		}
			$query->select('ic.name AS gh_airline');
    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');
    		
    	}
        if((int)$airport_current_id != -1)
        {
            $query->innerJoin('#__sfs_hotel_airports AS e ON e.hotel_id=c.id AND e.airport_id='.$airport_current_id);
        }
		
    	$dateFrom = $this->get('beginDate');
    	$dateTo   = $this->get('endDate');
    	
    	$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').' OR a.status='.$db->Quote('T').' OR a.status='.$db->Quote('P').')');
				
		$query->where('b.date >='.$db->Quote($dateFrom) );
		$query->where('b.date <='.$db->Quote($dateTo));
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		if( $db->getErrorNum() )
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
				
		return $result;
	}	
	
	protected function getAveragePrices()
	{
		$user    = JFactory::getUser();
		$airline = SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;
		$db 	 = JFactory::getDbo();				

		$query = $db->getQuery(true);

		$query->select('a.s_rate, a.sd_rate, a.t_rate, a.q_rate, h.name AS hotel_name, b.date, b.hotel_id,e.code AS currency');
		$query->order('a.id ASC');
		
		$query->from('#__sfs_reservations AS a');

		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
        $query->innerJoin('#__sfs_hotel AS h ON a.hotel_id = h.id');

		$query->leftJoin('#__sfs_hotel_taxes AS d ON d.hotel_id=b.hotel_id');
		$query->leftJoin('#__sfs_currency AS e ON e.id=d.currency_id');
		
		$query->where('a.airline_id='.$airline->id);
		
		if( $airline->grouptype==3 )
    	{	    		
    		$gh_airline = (int)JRequest::getInt('gh_airline');
    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
    		
    		if($gh_airline > 0){
    			$query->where('ghr.airline_id='.$gh_airline);
    		}
			$query->select('ic.name AS gh_airline');
    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');
    		
    	}

        if((int)$airport_current_id != -1)
        {
            $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=h.id AND ha.airport_id='.$airport_current_id);
        }
		
    	$dateFrom = $this->get('beginDate');
    	$dateTo   = $this->get('endDate');
    	
    	$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').' OR a.status='.$db->Quote('T').' OR a.status='.$db->Quote('P').')');
    			
		$query->where('b.date >='.$db->Quote($dateFrom) );
		$query->where('b.date <='.$db->Quote($dateTo));
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		if( $db->getErrorNum() )
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		return $result;
	}
	
	protected function getRevenueBooked()
	{		
		$user    = JFactory::getUser();
		$airline = SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;
		$db 	 = JFactory::getDbo();				

		$query = $db->getQuery(true);

		$query->select('a.revenue_booked, h.name AS hotel_name, b.date, b.hotel_id,e.code AS currency');
		$query->order('a.id ASC');
		
		$query->from('#__sfs_reservations AS a');

		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->innerJoin('#__sfs_hotel AS h ON h.id=b.hotel_id');
		$query->innerJoin('#__sfs_hotel_taxes AS d ON d.hotel_id=b.hotel_id');
		$query->innerJoin('#__sfs_currency AS e ON e.id=d.currency_id');
		
		$query->where('a.airline_id='.$airline->id);
		
		if( $airline->grouptype==3 )
    	{	    		
    		$gh_airline = (int)JRequest::getInt('gh_airline');
    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
    		
    		if($gh_airline > 0){
    			$query->where('ghr.airline_id='.$gh_airline);
    		}
			$query->select('ic.name AS gh_airline');
    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');
    		
    	}
        if((int)$airport_current_id != -1)
        {
            $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=h.id AND ha.airport_id='.$airport_current_id);
        }
    	
    	$dateFrom = $this->get('beginDate');
    	$dateTo   = $this->get('endDate');
    	
    	$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').' OR a.status='.$db->Quote('T').' OR a.status='.$db->Quote('P').')');
    	
		$query->where('b.date >='.$db->Quote($dateFrom) );
		$query->where('b.date <='.$db->Quote($dateTo));
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		if( $db->getErrorNum() )
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
				
		return $result;
	}
	
	protected function getTopIATACodes( )
	{				
		$user    	= JFactory::getUser();
		$airline 	= SFactory::getAirline();
		$db 		= JFactory::getDbo();				

		$query = $db->getQuery(true);
		
		$query->select('b.code, b.description, a.delay_code,a.seats_issued,a.seats, a.created, a.from_date, a.end_date, a.flight_code, a.flight_class');
		$query->from('#__sfs_flights_seats AS a');
		$query->innerJoin('#__sfs_delaycodes AS b ON b.code=a.delay_code');
			
		$query->where('a.airline_id='.$airline->id);
		
		if( $airline->grouptype==3 )
    	{	    		
    		$gh_airline = (int)JRequest::getInt('gh_airline');
    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
    		
    		if($gh_airline > 0){
    			$query->where('ghr.airline_id='.$gh_airline);
    		}
			$query->select('ic.name AS gh_airline');
    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');
    		
    	}
    	
    	$dateFrom = $this->get('beginDate');
    	$dateTo   = $this->get('endDate');
		
		$query->where('a.from_date >='.$db->Quote($dateFrom));
		$query->where('a.end_date <='.$db->Quote($dateTo));
		
		$query->order('a.from_date ASC');
									
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		if( $db->getErrorNum() )
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		return $result;		
	}
	
	protected function getPercentageOfBookedRooms()
	{		
		$user    = JFactory::getUser();
		$airline = SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;
		$db		 = JFactory::getDbo();

		$dateFrom = $this->get('beginDate');
    	$dateTo   = $this->get('endDate');

		$result = array();
    	
    	// calculate total of loaded rooms of all hotels which is connected to the airline who was logged in to the system 
    	$query = $db->getQuery(true);   
    	 	
    	$query->select('a.date, SUM(a.sd_room_total+a.t_room_total) AS total');
    	
    	$query->from('#__sfs_room_inventory AS a');


    	$query->innerJoin('#__sfs_hotel_airports AS b ON b.hotel_id=a.hotel_id');
    	
    	$query->where('b.airport_id='.$airline->airport_id);
    	
		$query->where('a.date >='.$db->Quote($dateFrom));
		$query->where('a.date <='.$db->Quote($dateTo));
		    	
		$query->group('a.date');
		$query->order('a.date ASC');
		
    	$db->setQuery($query);
    	
    	//$result[0] = $db->loadObjectList();
    	$result[0] = $db->loadAssocList('date','total');
    	
        	    	
    	// calculate total of booked rooms of airline
    	$query = $db->getQuery(true);
    	$query->select('b.date, SUM(a.sd_room+a.t_room) AS total');
    	$query->from('#__sfs_reservations AS a');
    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
        $query->innerJoin('#__sfs_hotel AS h ON a.hotel_id = h.id');
    	
    	$query->where('a.airline_id='.$airline->id);	

		if( $airline->grouptype==3 )
    	{	    		
    		$gh_airline = (int)JRequest::getInt('gh_airline');
    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
    		
    		if($gh_airline > 0){
    			$query->where('ghr.airline_id='.$gh_airline);
    		}
			$query->select('ic.name AS gh_airline');
    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');
    		
    	}
        if((int)$airport_current_id != -1)
        {
            $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=h.id AND ha.airport_id='.$airport_current_id);
        }
    	
		$query->where('b.date >='.$db->Quote($dateFrom));
		$query->where('b.date <='.$db->Quote($dateTo));		    	
    	    	
    	$query->group('b.date');
    	
    	$db->setQuery($query);
    	
    	$result[1] =  $db->loadAssocList('date','total');
    	    	
    	return $result;    				
	}
	
	protected function getTransportationDetails()
	{
		$user    = JFactory::getUser();
		$airline = SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;
		$db 	 = JFactory::getDbo();	

		$dateFrom = $this->get('beginDate');
    	$dateTo   = $this->get('endDate');
				
		$result = array();

		$query = $db->getQuery(true);

		$query->select('SUM(a.sd_room+a.t_room) AS total_rooms, b.date');
				
		$query->from('#__sfs_reservations AS a');

		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');

		
		$query->where('a.airline_id='.$airline->id);		
		$query->where('b.date >='.$db->Quote($dateFrom) );
		$query->where('b.date <='.$db->Quote($dateTo));
		$query->where('a.transport=1');
		
		$query->group('b.date');
		$query->order('a.id ASC');
		
		$db->setQuery($query);
		$result[0] = $db->loadAssocList('date','total_rooms');
		
		$query = $db->getQuery(true);

		$query->select('SUM(a.sd_room+a.t_room) AS total_rooms, b.date');
				
		$query->from('#__sfs_reservations AS a');

		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');

        $query->innerJoin('#__sfs_hotel AS h ON a.hotel_id = h.id');
		
		$query->where('a.airline_id='.$airline->id);		
		
		if( $airline->grouptype==3 )
    	{	    		
    		$gh_airline = (int)JRequest::getInt('gh_airline');
    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
    		
    		if($gh_airline > 0){
    			$query->where('ghr.airline_id='.$gh_airline);
    		}
			$query->select('ic.name AS gh_airline');
    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');
    		
    	}

        if((int)$airport_current_id != -1)
        {
            $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=h.id AND ha.airport_id='.$airport_current_id);
        }
    	
		$query->where('b.date >='.$db->Quote($dateFrom) );
		$query->where('b.date <='.$db->Quote($dateTo));
				
		$query->group('b.date');
		$query->order('a.id ASC');
		
		$db->setQuery($query);
		$result[1] = $db->loadAssocList('date','total_rooms');
				
		return $result;		
	}
	
	
	protected function getInitialBlockedDetails()
	{
		$dateFrom = $this->get('beginDate');
    	$dateTo   = $this->get('endDate');
    	
		$user    	= JFactory::getUser();
		$airline 	= SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;
		$db 		= JFactory::getDbo();		
				
		$result = null;

		$query = $db->getQuery(true);

		$query->select('SUM(a.s_room+a.sd_room+a.t_room+a.q_room) AS initial_rooms, SUM(a.claimed_rooms) AS claimed_rooms, b.date');
				
		$query->from('#__sfs_reservations AS a');

		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');

        $query->innerJoin('#__sfs_hotel AS h ON a.hotel_id = h.id');
		
		$query->where('a.airline_id='.$airline->id);	

		if( $airline->grouptype==3 )
    	{	    		
    		$gh_airline = (int)JRequest::getInt('gh_airline');
    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
    		
    		if($gh_airline > 0){
    			$query->where('ghr.airline_id='.$gh_airline);
    		}
			$query->select('ic.name AS gh_airline');
    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');
    		
    	}
        if((int)$airport_current_id != -1)
        {
            $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=h.id AND ha.airport_id='.$airport_current_id);
        }
		
		$query->where('b.date >='.$db->Quote($dateFrom) );
		$query->where('b.date <='.$db->Quote($dateTo));
				
		$query->group('b.date');
		$query->order('a.id ASC');

		$db->setQuery($query);
		$result = $db->loadObjectList();
		
				
		return $result;		
	}
	
	//lchung	
	public function exportInitialNewReportAirline( $beginDate , $endDate )
	{
		$reportModel = JModelLegacy::getInstance( 'Report', 'SfsModel' );
		$check_userkey = $reportModel->getCheckUserkey();
		if ( !count( $check_userkey ) ) {
			$airline = SFactory::getAirline();		
			$user    = JFactory::getUser();
			///print_r( $airline);die;
			// Check if the user is Airline Admin
			if ( ! SFSAccess::check($user, 'a.admin') ) {
				return false;
			}
			
			$airport_code = $airline->airport_code;
			$currency_code = $this->getCurrencyCode( $airline->airport_id )->currency_code;
			$name = $airline->name;
		}
		else {
			$airport_code = $check_userkey->airport_code;
			$currency_code = $this->getCurrencyCode( $check_userkey->airport_id )->currency_code;
			$name = $check_userkey->name;
		}
		///print_r($airline);die;
		$objPHPExcel = $this->getPhpExcelObject();
			
		$this->setPeriodDate($beginDate, $endDate);
		
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow('A', 1, $name,false);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(31);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow('A', 2, 'period ' . $beginDate . ' untill ' . $endDate,false);
				
		$fileName = 'New_report_airline'.$beginDate.'_'.$endDate.'.xlsx';
		//get data
		$data = $this->getDataNewReportAirline( $check_userkey );
		
		///print_r($data);die;
		$count = count( $data );
		$row  = 4;
		
		$headers = array('Blockcode','Date','Airport','Hotelname','Status','Flight number','# Rooms','Rate per room','# pax BFST','Gross Price BFST','# pax Lunch', 'Gross Price Lunch', '# pax Dinner', 'Gross Price Dinner', 'Grand Total Amount');
		//$column = count( $headers );
		
		//Blue background
		$headerStyleArray = array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FF538dd5')
		  ),
		  'font'    => array(
				'color'     => array(
			    	'rgb' => 'ffffff'
			     )
			),
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		//Green background
		$headerStyleArray2 = array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FF00b050')
		  ),
		  'font'    => array(
				'color'     => array(
			    	'rgb' => 'ffffff'
			     )
			),
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);	
		//Orange background
		$headerStyleArray3 = array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FFe26b0a')
		  ),
		  'font'    => array(
				'color'     => array(
			    	'rgb' => 'ffffff'
			     )
			),
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		
		//Orange background
		$headerStyleArray4 = array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FF0000ff') //FF0000
		  ),
		  'font'    => array(
				'color'     => array(
			    	'rgb' => 'ffffff'
			     )
			),
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);			
		// set header table cell border

		for ( $i = 0;  $i <= 14; $i++ ) {
			if( $i < 7 ){
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->applyFromArray($headerStyleArray);	
			} 
			elseif( $i < 14 ){
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->applyFromArray($headerStyleArray2);
			}	
			elseif ( $i == 14 ) {
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->applyFromArray($headerStyleArray3);
				for ( $r = $row;  $r <= ($count+$row - 1); $r++ ) {
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r+1)->applyFromArray($headerStyleArray4);
				}
			}
		}
		
		//Last header column - Orange Background Color
		
		$FORMAT_CURRENCY = '"$"#,##0.00_-';
		if ( $currency_code == 'EUR' ) {
			$FORMAT_CURRENCY = '[$€ ]#,##0.00_-';
		}
		//const FORMAT_CURRENCY_USD_SIMPLE        = '"$"#,##0.00_-';
		//const FORMAT_CURRENCY_USD            = '$#,##0_-';
		//const FORMAT_CURRENCY_EUR_SIMPLE        = '[$EUR ]#,##0.00_-';
		//$FORMAT_CURRENCY_EUR_SIMPLE = '[$€ ]#,##0.00_-';
		
		if(count($data)) {
			$xy = 'H'.($row+1) . ':H'. ($count+$row);
			$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
			
			$xy = 'J'.($row+1) . ':J'. ($count+$row);
			$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
			
			$xy = 'L'.($row+1) . ':L'. ($count+$row);
			$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
			
			$xy = 'N'.($row+1) . ':N'. ($count+$row);
			$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
			
			$xy = 'O'.($row+1) . ':O'. ($count+$row);
			$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
			
			$r_total = ($count+$row)+1;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(14, $r_total, "=SUM($xy)", false);
			$xy = 'O'.($r_total) . ':O'. ($r_total);
			$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
		}
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);		
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);		
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);		
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(22);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
		
		$column = 0;
		
		foreach ($headers as $text)
		{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $text,false);
			$column++;				
		}
		
		$row++;
		$column = 0;
		//print_r( $data );die;
		if(count($data)) {		
			foreach ($data as $v) {	
				$rooms = 0;
				///$rooms = $v->s_room+$v->sd_room+$v->t_room+$v->q_room;
				
				$gross_price = 0;
				if ( $v->s_room > 0 ) {					
					$rooms = $v->s_room;
					$gross_price = $v->s_rate * $rooms;
				}
				if ( $v->sd_room > 0 ) {					
					$rooms = $v->sd_room;
					$gross_price = $v->sd_rate * $rooms;
				}
				if ( $v->t_room > 0 ) {					
					$rooms = $v->t_room;
					$gross_price = $v->t_rate * $rooms;
				}
				if ( $v->q_room > 0 ) {					
					$rooms = $v->q_room;
					$gross_price = $v->q_rate * $rooms;
				}
				if ( $v->ws_room == 1 && $rooms == 0){
					$rooms = 1;
					$gross_price = $v->s_rate;
				}
				
				
				$total = floatval( $gross_price );
				if ($v->breakfast > 0 ) {
					$total += $v->people_num * floatval( $v->breakfast );
				}
				if ($v->lunch > 0 ) {
					$total += $v->people_num * floatval( $v->lunch );
				}
				if ($v->mealplan > 0 ) {
					$total += $v->people_num * floatval( $v->mealplan );
				}
				
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $row, $v->blockcode, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $row, $v->date, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $row, $v->airport_code, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, $row, $v->hotel_name, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, $row, $v->status, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5, $row, $v->flight_number, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6, $row, $rooms, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7, $row, $gross_price, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8, $row, ($v->breakfast > 0 ) ? $v->people_num : "0", false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(9, $row, ($v->breakfast > 0 ) ? $v->people_num * $v->breakfast : "0.0", false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(10, $row, ($v->lunch > 0 ) ? $v->people_num : "0", false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(11, $row, ($v->lunch > 0 ) ? $v->people_num * $v->lunch : "0.0", false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(12, $row, ( $v->mealplan > 0 ) ? $v->people_num : "0", false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(13, $row, ( $v->mealplan > 0 ) ? $v->people_num * $v->mealplan : "0.0", false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(14, $row, $total, false);
						
				$row++;
				
			}	
			$row ++;	
		}

		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow('A', $row, "Issued by Stranded Flight Solutions on " . JURI::base(), false);
		//. strtolower($airport_code)
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow('A', $row+1, "date: " . date('d-m-Y H:i:s'), false);

		$this->download($fileName);
	}
	
	public function getDataNewReportAirline( $check_userkey = false, $is_currency_code = false )
	{
		
		///$dateFrom = $this->get('beginDate');
    	///$dateTo   = $this->get('endDate');
		$dateFrom = JRequest::getVar('date_from');
    	$dateTo   = JRequest::getVar('date_to');
    	
		if ( !count( $check_userkey ) ) {
			$user    	= JFactory::getUser();
			$airline 	= SFactory::getAirline();
			$airline_current = SAirline::getInstance()->getCurrentAirport();
			$airport_current_id = $airline_current->id;
			$airline_id = $airline->id;
			$currency_code = $this->getCurrencyCode( $airline->airport_id )->currency_code;
			
		}
		else {
			//print_r( $check_userkey );die;
			$airport_current_id = -1;//$check_userkey->airport_id;
			$airline_id = $check_userkey->airline_id;
			$currency_code = $this->getCurrencyCode( $check_userkey->airport_id )->currency_code;
		}
		
		$db 		= JFactory::getDbo();		
		$result = null;
		
		$query = $db->getQuery(true);

		/*$query->select(
		'SUM(
		a.s_room+a.sd_room+a.t_room+a.q_room
		) 
		AS rooms, 
		SUM(
		a.sd_rate+a.t_rate+a.s_rate+a.q_rate
		) 
		AS gross_price, 
		b.date,
		a.blockcode,
		a.status,
		h.name as hotel_name'
		);*/
		
		$query->select(
		'vc.sroom as s_room,vc.sdroom as sd_room,vc.troom as t_room,vc.qroom as q_room, a.ws_room, 
		a.sd_rate,a.t_rate,a.s_rate,a.q_rate,
		a.blockdate as date, a.breakfast,a.lunch,a.mealplan,
		a.blockcode,
		a.airport_code,
		a.status,
		h.name as hotel_name'
		);
				
		$query->from('#__sfs_reservations AS a');

		//$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');

        $query->innerJoin('#__sfs_hotel AS h ON a.hotel_id = h.id');
		
		$query->select(
		'fl.flight_code as flight_number'
		);
		
		$query->select(
		'(
		SELECT COUNT(tra.id) From #__sfs_trace_passengers as tra Where tra.voucher_id = vc.id Group BY tra.voucher_id) AS people_num 
		 '
		);
		
		///$query->innerJoin('#__sfs_voucher_codes AS vc ON a.id = vc.booking_id');
		$query->leftJoin('#__sfs_voucher_codes AS vc ON a.id = vc.booking_id');
		$query->leftJoin('#__sfs_flights_seats AS fl ON vc.flight_id = fl.id');
		///$query->innerJoin('#__sfs_flights_seats AS fl ON vc.flight_id = fl.id');
		
		/*$query->select(
		'hmea.lunch_standard_price as gross_price_lunch,
		hmea.bf_standard_price as gross_price_bfst,
		hmea.course_1 as gross_price_dinner'
		);
		$query->leftJoin('#__sfs_hotel_mealplans AS hmea ON hmea.hotel_id = h.id');
		*/
		///$query->innerJoin('#__sfs_hotel_mealplans AS hmea ON hmea.hotel_id = h.id');
		
		
		///echo (string)$query;die;
		$query->where('a.airline_id='.$airline_id);	
		$query->where('a.status!="D"');	

		/*if( $airline->grouptype==3 )
    	{	    		
    		$gh_airline = (int)JRequest::getInt('gh_airline');
    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
    		
    		if($gh_airline > 0){
    			$query->where('ghr.airline_id='.$gh_airline);
    		}
			$query->select('ic.name AS gh_airline');
    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');
    		
    	}*/
        /*if((int)$airport_current_id != -1)
        {
            $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=h.id AND ha.airport_id='.$airport_current_id);
        }*/
		
		//$query->where('b.date >='.$db->Quote($dateFrom) );
		///$query->where('b.date <='.$db->Quote($dateTo));
		$query->where('a.blockdate >='.$db->Quote($dateFrom) );
		$query->where('a.blockdate <='.$db->Quote($dateTo));
				
		//$query->group('a.blockcode');
		$query->order('a.id ASC');
		///echo (string)$query;die;
		$db->setQuery($query);
		$result = $db->loadObjectList();
		if ( $is_currency_code == true )
			return array($currency_code, $result );
		else
		return $result;	
	}
	
	public function getCurrencyCode( $airline_id = 0 )
	{
		$db 		= JFactory::getDbo();		
		$result = null;
		$query = $db->getQuery(true);
		$query->select('currency_code');
		$query->from('#__sfs_iatacodes AS a');
		$query->where('a.id='.$airline_id);	
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;	
	}
	
	public function FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY){
		$objPHPExcel->getActiveSheet()
			->getStyle($xy)
			->getNumberFormat()
			->setFormatCode(
				$FORMAT_CURRENCY
			);
	}
	
	protected function numberToText($number)
	{
		$number = number_format($number,2);
		$text = (string)$number;
		$text = JString::str_ireplace('.', ',', $text);
		return $text;
	}
	//End lchung
			
}

class HotelReport extends SfsReport
{
	
	public function __construct($config = array())
	{
		parent::__construct();
	}
		
	public static function getInstance()
	{
		static $instance;
		if (!$instance ) {
			$instance = new HotelReport();			
		}
		return $instance;
	}
	
	public function setPeriodDate( $mFrom, $yFrom,$mTo ,$yTo )
	{
		$this->beginDate = ( (int)$mFrom < 10 ) ? $yFrom.'-0'.$mFrom.'-01' : $yFrom.'-'.$mFrom.'-01';
		$this->endDate   = ( (int)$mTo < 10 ) ? $yTo.'-0'.$mTo.'-' : $yTo.'-'.$mTo.'-';
		
		$num = cal_days_in_month(CAL_GREGORIAN, $mTo , $yTo);
		$this->endDate	= $this->endDate.$num;
	}
	
	protected function setCSVHeader($reportName)
	{
		parent::setCSVHeader($reportName);
		
		$user 	= JFactory::getUser();
		$hotel 	= SFactory::getHotel();
		
		$exportDate = JHTML::_('date',JFactory::getDate()->toSql(), JText::_('DATE_FORMAT_LC3'));
		
		$startColumn = 0;
		$row = 0;
		
		$objPHPExcel = $this->getPhpExcelObject();
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
				
		$beginDate 	= $this->get('beginDate');
		$endDate	= $this->get('endDate');
		
		//SFS address	
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'SFS-web reporting',false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Creation date',false)
			->setCellValueByColumnAndRow($startColumn+1, $row, $exportDate,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Created by',false)
			->setCellValueByColumnAndRow($startColumn+1,  $row, $user->get('username'),false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Hotel',false)
			->setCellValueByColumnAndRow($startColumn+1,  $row, $hotel->name,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Report Period',false)
			->setCellValueByColumnAndRow($startColumn+1, $row, 'From: '.$beginDate.' To: '.$endDate,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Report Name',false)
			->setCellValueByColumnAndRow($startColumn+1, $row, $reportName,false);
		
		return $row;	
	}
	
	/**
	 * Export roomnights to the excel file.
	 *	 
	 */	
	public function exportRoomnights($mFrom, $yFrom,$mTo ,$yTo){
		
		$hotel = SFactory::getHotel();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;
		$user = JFactory::getUser();
		
		// Check if the user is Hotel Admin
		if ( ! SFSAccess::check($user, 'h.admin') ) {
			return false;
		}
		
		$objPHPExcel = $this->getPhpExcelObject();
			
		$this->setPeriodDate($mFrom, $yFrom, $mTo, $yTo);
		
		$currentRow = $this->setCSVHeader('Total number roomnights');
		
		$dateFrom = $this->get('beginDate');
    	$dateTo   = $this->get('endDate');
    	
		$fileName = 'RoomnightsReport_'.$dateFrom.'_'.$dateTo.'.xlsx';
			
		//get data
		$data = $this->getRoomnights();	
		
		$row    = $currentRow + 2;
		
		$column = 5;
		
		$headers = array('Date','Airline Name','Nr of Rooms','','','Date','Total Nr of rooms');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(23);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(23);
	
		
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, 'From: '.$dateFrom.' To: '.$dateTo,false);				
		
		$row++;
		$column = 0;
		
		foreach ($headers as $text)
		{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $text,false);
			$column++;				
		}
		
		$row++;
		
		$data2 = array();
		
		if( count($data) ){
					
			foreach ($data as $v) {		
				$column=0;
				
				$companyName = empty($v->company_name) ? $v->airline_name : $v->company_name;
				
				$ym = JString::substr($v->room_date, 0,7);
				
				if( ! isset($data2[$ym]) ) {
					$data2[$ym] = new stdClass();								
					$data2[$ym]->room_date = $ym;			
					$data2[$ym]->claimed_rooms = 0;
				}		
					
				$data2[$ym]->claimed_rooms += $v->claimed_rooms;
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $v->room_date,false);
				$column++;	
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $companyName,false);
				$column++;	
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $v->claimed_rooms,false);
							
				$row++;			
			}
			
			$row    = $currentRow + 4;
				
			foreach ($data2 as $k=>$v) {
				$column = 5;	
						
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $k,false);
				$column++;
	
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $v->claimed_rooms ,false);
											
				$row++;
			}
		
		}
				
		$this->download($fileName);
	}
	
	
	public function exportAveragePrices($mFrom, $yFrom,$mTo ,$yTo){
		
		$hotel = SFactory::getHotel();		
		$user = JFactory::getUser();
		
		// Check if the user is Hotel Admin
		if ( ! SFSAccess::check($user, 'h.admin') ) {
			return false;
		}
		
		$objPHPExcel = $this->getPhpExcelObject();
			
		$this->setPeriodDate($mFrom, $yFrom, $mTo, $yTo);
		
		$currentRow = $this->setCSVHeader('Average room prices booked');
		
		$dateFrom = $this->get('beginDate');
    	$dateTo   = $this->get('endDate');
    	
		$fileName = 'Average_Room_Prices_Report_'.$dateFrom.'_'.$dateTo.'.xlsx';
			
		//get data
		$data = $this->getAveragePrices();
		
		$row    = $currentRow + 2;
		
		$column = 7;
		
		$headers =  array('Date','Airline Name','SD Rate','T Rate','Nr of Rooms','','','Date','ADR');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(23);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(23);
	
		
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, 'From: '.$dateFrom.' To: '.$dateTo,false);				
		
		$row++;
		$column = 0;
		
		foreach ($headers as $text)
		{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $text,false);
			$column++;				
		}
		
		$row++;
		
		$data2 = array();
		
		if( count($data) ){
					
			foreach ($data as $v) {		
				$column=0;
				
				$companyName = empty($v->company_name) ? $v->airline_name : $v->company_name;
				
				$ym = JString::substr($v->room_date, 0,7);
				
				if( ! isset($data2[$ym]) ) {
					$data2[$ym] = new stdClass();								
					$data2[$ym]->room_date = $ym;								
					$data2[$ym]->booked_count = 0;
					$data2[$ym]->sd_rate = 0;
				}		
				if($v->sd_rate > 0)
				{
					$data2[$ym]->sd_rate += $v->sd_rate;
					$data2[$ym]->sd_count++;	
				}
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $v->room_date,false);
				$column++;	
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $companyName,false);
				$column++;	
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $v->sd_rate,false);
				$column++;
					
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $v->t_rate,false);	
				$column++;
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $v->claimed_rooms,false);

					
				$row++;			
			}
			
			$row    = $currentRow + 4;
				
			foreach ($data2 as $k=>$v) {
				$column = 7;	
						
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $k,false);
				$column++;
	
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, number_format( $v->sd_rate / $v->sd_count,2) ,false);
											
				$row++;
			}
		
		}
				
		$this->download($fileName);
	}
	
	
	public function exportRevenueBooked($mFrom, $yFrom,$mTo ,$yTo){
		
		$hotel = SFactory::getHotel();		
		$user = JFactory::getUser();
		
		// Check if the user is Hotel Admin
		if ( ! SFSAccess::check($user, 'h.admin') ) {
			return false;
		}
		
		$objPHPExcel = $this->getPhpExcelObject();
			
		$this->setPeriodDate($mFrom, $yFrom, $mTo, $yTo);
		
		$currentRow = $this->setCSVHeader('Revenue Booked');

		
		$dateFrom = $this->get('beginDate');
    	$dateTo   = $this->get('endDate');
    	
		$fileName = 'Revenue_Booked_'.$dateFrom.'_'.$dateTo.'.xlsx';
			
		//get data
		$data = $this->getRevenueBooked();
		
		$row    = $currentRow + 2;
		
		$column = 7;
		
		$headers = array('Date','Airline Name','SD Rate','T Rate','Nr of Rooms','Net Revenue','','Date','Net revenue');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(23);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(23);
	
		
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, 'From: '.$dateFrom.' To: '.$dateTo,false);				
		
		$row++;
		$column = 0;
		
		foreach ($headers as $text)
		{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $text,false);
			$column++;				
		}
		
		$row++;
		
		$data2 = array();
		
		if( count($data) ){
					
			foreach ($data as $v) {		
				$column=0;
				
				$companyName = empty($v->company_name) ? $v->airline_name : $v->company_name;
				
				$ym = JString::substr($v->room_date, 0,7);
				
				if( ! isset($data2[$ym]) ) {
					$data2[$ym] = new stdClass();								
					$data2[$ym]->room_date = $ym;			
					$data2[$ym]->revenue_booked = 0;
				}		
					
				$data2[$ym]->revenue_booked += $v->revenue_booked;
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $v->room_date,false);
				$column++;	
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $companyName,false);
				$column++;	
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $v->sd_rate,false);
				$column++;
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $v->t_rate,false);
				$column++;
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $v->claimed_rooms,false);
				$column++;

				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $v->revenue_booked,false);
																
				$row++;			
			}
			
			$row    = $currentRow + 4;
				
			foreach ($data2 as $k=>$v) {
				$column = 7;	
						
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $k,false);
				$column++;
	
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $row, $v->revenue_booked ,false);
											
				$row++;
			}
		
		}
				
		$this->download($fileName);
	}
	
	
	
	

	protected function getRoomnights()
	{
		$db		= JFactory::getDbo();
		$hotel 	= SFactory::getHotel();
		$dateFrom = $this->get('beginDate');
    	$dateTo   = $this->get('endDate');

		$query = $db->getQuery(true);
		
		$query->select('a.airline_id,a.claimed_rooms, c.company_name, b.date AS room_date, d.name AS airline_name');
		$query->from('#__sfs_reservations AS a');
		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
        $query->innerJoin('#__sfs_hotel AS h ON h.id=a.hotel_id');
		$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');				
		$query->leftJoin('#__sfs_iatacodes AS d ON d.id=c.iatacode_id');
		
		$query->where('a.hotel_id='.$hotel->id);
		$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');
		$query->where('b.date >='.$db->Quote($dateFrom));
		$query->where('b.date <='.$db->Quote($dateTo));
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		return $rows;
	}
	
	// protected function getAveragePrices()
	// {
	// 	$db 	= JFactory::getDbo();
	// 	$hotel 	= SFactory::getHotel();
		
	// 	$dateFrom = $this->get('beginDate');
 //    	$dateTo   = $this->get('endDate');

	// 	$query = $db->getQuery(true);
	// 	$query->select('a.airline_id,a.sd_rate,a.t_rate,a.claimed_rooms, c.company_name, b.date AS room_date, d.name AS airline_name');
	// 	$query->from('#__sfs_reservations AS a');
	// 	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
 //        $query->innerJoin('#__sfs_hotel AS h ON h.id=a.hotel_id');
	// 	$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');				
	// 	$query->leftJoin('#__sfs_iatacodes AS d ON d.id=c.iatacode_id');
		
	// 	$query->where('a.hotel_id='.$hotel->id);
	// 	$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');		
	// 	$query->where('b.date >='.$db->Quote($dateFrom));
	// 	$query->where('b.date <='.$db->Quote($dateTo));
		
	// 	$db->setQuery($query);
	// 	$rows = $db->loadObjectList();
	
	// 	return $rows;
	// }
	
	protected function getRevenueBooked()
	{
		$db		 = JFactory::getDbo();
		$hotel	 = SFactory::getHotel();
		
		$dateFrom = $this->get('beginDate');
    	$dateTo   = $this->get('endDate');

		$query = $db->getQuery(true);
		
		$query->select('a.airline_id,a.sd_rate,a.t_rate,a.claimed_rooms,a.revenue_booked, c.company_name, b.date AS room_date, d.name AS airline_name');
		$query->from('#__sfs_reservations AS a');
		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
        $query->innerJoin('#__sfs_hotel AS h ON h.id=a.hotel_id');
		$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');				
		$query->leftJoin('#__sfs_iatacodes AS d ON d.id=c.iatacode_id');
		
		$query->where('a.hotel_id='.$hotel->id);
		$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');		
		$query->where('b.date >='.$db->Quote($dateFrom));
		$query->where('b.date <='.$db->Quote($dateTo));
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		return $rows;
	}
	
}

class HReport {

    private static $_hreport = null;

    private $beginDate = null;
    private $endDate = null;
    private $fp = null;

    /**
     * Get a hotel report object.
     *
     * Returns the global hotel report object, only creating it if it doesn't already exist.
     *
     * @return  HReport object
     */
    public static function getInstance()
    {
        if ( ! self::$_hreport ) {
            self::$_hreport = new HReport();
        }
        return self::$_hreport;
    }

    /**
     * Method to set the period date for report.
     *
     * @param string $beginDate
     * @param string $endDate
     */
    public function setPeriodDate( $mFrom, $yFrom,$mTo ,$yTo )
    {
        $this->beginDate = ( (int)$mFrom < 10 ) ? $yFrom.'0'.$mFrom.'01' : $yFrom.''.$mFrom.'01';
        $this->endDate   = ( (int)$mTo < 10 ) ? $yTo.'0'.$mTo.'' : $yTo.''.$mTo.'';

        $num = cal_days_in_month(CAL_GREGORIAN, $mTo , $yTo);
        $this->endDate	= $this->endDate.$num;
    }

    public function getBeginDate()
    {
        return $this->beginDate;
    }
    public function getEndDate()
    {
        return $this->endDate;
    }
    public function getFileOpen()
    {
        $this->fp = fopen('php://output', 'w');
        return $this->fp;
    }
    public function closeStream()
    {
        fclose($this->fp);
    }

    /**
     * Export roomnights details to CSV file
     *
     * @param  string  $beginDate report begin date
     * @param  string  $endDate  report end date
     *
     */
    public function exportRoomnights($mFrom, $yFrom,$mTo ,$yTo){
        $hotel = SFactory::getHotel();
        $user = JFactory::getUser();

        // Check if the user is Hotel Admin
        if ( ! SFSAccess::check($user, 'h.admin') ) {
            return false;
        }

        $this->setPeriodDate($mFrom, $yFrom, $mTo, $yTo);

        $this->setHeader('Total number roomnights', 'RoomnightsReport_'.$this->getBeginDate().'_'.$this->getEndDate().'.csv');

		//get data
		$data = $this->getRoomnights($this->getBeginDate(), $this->getEndDate());

		$data2 = array();
		$tmpArray = array();

		foreach ($data as $v) {
            $ym = JString::substr($v->room_date, 0,7);

            if( ! isset($tmpArray[$ym]) ) {
                $tmpArray[$ym] = new stdClass();
                $tmpArray[$ym]->room_date = $ym;
                $tmpArray[$ym]->claimed_rooms = 0;
            }
            $tmpArray[$ym]->claimed_rooms += $v->claimed_rooms;
        }

		$i=0;
		foreach ($tmpArray as $k => $v) {
            $data2[$i] = $v;
            $i++;
        }


		fputcsv($this->fp, array('Date','Airline Name','Nr of Rooms','','','','Date','Total Nr of rooms'));

		$i = 0;
		foreach ($data as $v) {

            $companyName = empty($v->company_name) ? $v->airline_name : $v->company_name;

            if( isset($data2[$i]) ) {
                fputcsv( $this->fp, array($v->room_date,$companyName,$v->claimed_rooms,'','','',$data2[$i]->room_date,$data2[$i]->claimed_rooms) );
            } else {
                fputcsv( $this->fp, array($v->room_date,$companyName,$v->claimed_rooms) );
            }

            $i++;
        }

		$this->closeStream();
	}

    /**
     * Export average prices details to CSV file
     *
     * @param  string  $beginDate report begin date
     * @param  string  $endDate  report end date
     *
     */
    public function exportAveragePrices($mFrom, $yFrom,$mTo ,$yTo){
        $hotel = SFactory::getHotel();
        $user = JFactory::getUser();

        // Check if the user is Hotel Admin
        if ( ! SFSAccess::check($user, 'h.admin') ) {
            return false;
        }

        $this->setPeriodDate($mFrom, $yFrom, $mTo, $yTo);

        $this->setHeader('Average room prices booked', 'Average_Room_Prices_Report_'.$this->getBeginDate().'_'.$this->getEndDate().'.csv');

		//get data
		$data = $this->getAveragePrices($this->getBeginDate(), $this->getEndDate());

		$data2 = array();
		$tmpArray = array();

		foreach ($data as $v) {
            $ym = JString::substr($v->room_date, 0,7);

            if( ! isset($tmpArray[$ym]) ) {
                $tmpArray[$ym] = new stdClass();
                $tmpArray[$ym]->room_date = $ym;
                $tmpArray[$ym]->booked_count = 0;
                $tmpArray[$ym]->sd_rate = 0;
                $tmpArray[$ym]->t_rate = 0;
            }
            $tmpArray[$ym]->sd_rate += $v->sd_rate;
            $tmpArray[$ym]->t_rate += $v->t_rate;
            $tmpArray[$ym]->booked_count++;
        }

		$i=0;
		foreach ($tmpArray as $k => $v) {
            $data2[$i] = $v;
            $i++;
        }

		fputcsv($this->fp, array('Date','Airline Name','SD Rate','T Rate','Nr of Rooms','','','','Date','ADR'));

		$i = 0;
		foreach ($data as $v) {

            $companyName = empty($v->company_name) ? $v->airline_name : $v->company_name;

            if( isset($data2[$i]) ) {
                fputcsv( $this->fp, array($v->room_date,$companyName,$v->sd_rate,$v->t_rate,$v->claimed_rooms,'','','',$data2[$i]->room_date, number_format($data2[$i]->sd_rate / $data2[$i]->booked_count,2) ) );
			} else {
                fputcsv( $this->fp, array($v->room_date,$companyName,$v->sd_rate,$v->t_rate,$v->claimed_rooms) );
            }

            $i++;
        }

		$this->closeStream();
	}

    /**
     * Export RevenueBooked details to CSV file
     *
     * @param  string  $beginDate report begin date
     * @param  string  $endDate  report end date
     *
     */
    public function exportRevenueBooked($mFrom, $yFrom,$mTo ,$yTo){
        $hotel = SFactory::getHotel();
        $user = JFactory::getUser();

        // Check if the user is Hotel Admin
        if ( ! SFSAccess::check($user, 'h.admin') ) {
            return false;
        }

        $this->setPeriodDate($mFrom, $yFrom, $mTo, $yTo);

        $this->setHeader('Revenue booked', 'Revenue_Booked_'.$this->getBeginDate().'_'.$this->getEndDate().'.csv');

		//get data
		$data = $this->getRevenueBooked($this->getBeginDate(), $this->getEndDate());

		$data2 = array();
		$tmpArray = array();

		foreach ($data as $v) {
            $ym = JString::substr($v->room_date, 0,7);

            if( ! isset($tmpArray[$ym]) ) {
                $tmpArray[$ym] = new stdClass();
                $tmpArray[$ym]->room_date = $ym;
                $tmpArray[$ym]->revenue_booked = 0;
            }
            $tmpArray[$ym]->revenue_booked += $v->revenue_booked;
        }

		$i=0;
		foreach ($tmpArray as $k => $v) {
            $data2[$i] = $v;
            $i++;
        }

		fputcsv($this->fp, array('Date','Airline Name','SD Rate','T Rate','Nr of Rooms','Net Revenue','','','','Date','Net revenue'));

		$i = 0;
		foreach ($data as $v) {

            $companyName = empty($v->company_name) ? $v->airline_name : $v->company_name;

            if( isset($data2[$i]) ) {
                fputcsv( $this->fp, array($v->room_date,$companyName,$v->sd_rate,$v->t_rate,$v->claimed_rooms,$v->revenue_booked,'','','',$data2[$i]->room_date,$data2[$i]->revenue_booked ) );
            } else {
                fputcsv( $this->fp, array($v->room_date,$companyName,$v->sd_rate,$v->t_rate,$v->claimed_rooms,$v->revenue_booked) );
            }

            $i++;
        }

		$this->closeStream();
	}

    protected function getRoomnights($beginDate, $endDate)
    {
        $db = JFactory::getDbo();
        $hotel = SFactory::getHotel();

        $query = $db->getQuery(true);
        $query->select('a.airline_id,a.claimed_rooms, c.company_name, b.date AS room_date, d.name AS airline_name');
        $query->from('#__sfs_reservations AS a');
        $query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
        $query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');
        $query->leftJoin('#__sfs_iatacodes AS d ON d.id=c.iatacode_id');

        $query->where('a.hotel_id='.$hotel->id);
        $query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');
		$query->where('b.date >='.$db->Quote($beginDate));
		$query->where('b.date <='.$db->Quote($endDate));

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		//echo (string)$query;

		//echo $db>getErrorMsg();
		//print_r($rows);die;

		return $rows;
	}

    protected function getAveragePrices($beginDate, $endDate)
    {
        $db = JFactory::getDbo();
        $hotel = SFactory::getHotel();

        $query = $db->getQuery(true);
        $query->select('a.airline_id,a.sd_rate,a.t_rate,a.claimed_rooms, c.company_name, b.date AS room_date, d.name AS airline_name');
        $query->from('#__sfs_reservations AS a');
        $query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
        $query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');
        $query->leftJoin('#__sfs_iatacodes AS d ON d.id=c.iatacode_id');

        $query->where('a.hotel_id='.$hotel->id);
        $query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');
		$query->where('b.date >='.$db->Quote($beginDate));
		$query->where('b.date <='.$db->Quote($endDate));

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		//echo (string)$query;

		//echo $db>getErrorMsg();
		//print_r($rows);die;

		return $rows;
	}

    protected function getRevenueBooked($beginDate, $endDate)
    {
        $db = JFactory::getDbo();
        $hotel = SFactory::getHotel();

        $query = $db->getQuery(true);
        $query->select('a.airline_id,a.sd_rate,a.t_rate,a.claimed_rooms,a.revenue_booked, c.company_name, b.date AS room_date, d.name AS airline_name');
        $query->from('#__sfs_reservations AS a');
        $query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
        $query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');
        $query->leftJoin('#__sfs_iatacodes AS d ON d.id=c.iatacode_id');

        $query->where('a.hotel_id='.$hotel->id);
        $query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');
		$query->where('b.date >='.$db->Quote($beginDate));
		$query->where('b.date <='.$db->Quote($endDate));

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		//echo (string)$query;

		//echo $db>getErrorMsg();
		//print_r($rows);die;

		return $rows;
	}


    /**
     * Set CSV HTTP header
     */
    private function setHeader( $reportName, $fileName )
    {
        $user = JFactory::getUser();
        $hotel = SFactory::getHotel();

        header( 'ContentType: text/csv' );
        header( 'ContentDisposition: attachment;filename='.$fileName);

        $fp = $this->getFileOpen();

        fputcsv($fp, array('SFSweb reporting'));

        $exportDate = JHTML::_('date',JFactory::getDate()->toSql(), JText::_('DATE_FORMAT_LC3'));
        fputcsv($fp, array('Creation date',$exportDate));

        fputcsv($fp, array('Created by',$user->get('username')));
        fputcsv($fp, array('Hotel',$hotel->name));


        fputcsv($fp, array('Report Period','From: '.$this->getBeginDate().' To: '.$this->getEndDate()));
		fputcsv($fp, array('Report Name',$reportName));
	}
}



