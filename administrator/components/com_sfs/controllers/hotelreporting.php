<?php
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel.php';
require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel/Worksheet/Drawing.php';

class SfsControllerHotelreporting extends JController {
		
	protected $objPHPExcel = null;

	public function __construct($config = array())
	{
		$this->objPHPExcel = new PHPExcel();
		
		parent::__construct($config);															
	}
		
	public function getModel($name = 'Hotelreporting', $prefix = 'SfsModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function availabilityReport()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$hotelId	= JRequest::getInt('hotel_id');
		$from 		= JRequest::getVar('date_from');
    	$until  	= JRequest::getVar('date_to');
    	
    	if( empty($from) && empty($until) )
    	{
    		if( (int) $hotelId > 0 ){
    			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelreporting&id='.$hotelId,false));	
    		} else {
    			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelreporting',false));
    		}
    		
    		return;
    	}
		
		if( (int) $hotelId > 0 )
		{
			$this->reportSingleHotel();
		} else {
			$this->reportAllHotels();
		}
		
		
		JFactory::getApplication()->close();		
	}
	
	protected function reportAllHotels()
	{
		$model = $this->getModel();
		
		$inventories = $model->getInventories();
		$dates = $model->getDates();
		
		if( !$dates )
		{
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelreporting',false));
			return;	
		}
		
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		
		$row=2;
		$column = 2;
		
		$this->objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($column)->setWidth(30);
		$this->objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($column, $row, 'Date' ,false);
		$column++;	
		
		foreach ($dates as $date){			
			$this->objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($column)->setWidth(13);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $date ,false);
			$column++;	
		}
		
		$row += 2;
		$column = 1;
		
		
		//Blue background
		$headerStyleArray = array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FFdfe3e8')
		  ),		 
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN,
		      'color'	=> array('argb' => 'FFdadcdd')
		    )
		  )
		);
		
		/*
		 * SD Rate
		 */
		$column = -1;
		$avg_row  =	$row;
		
		$sdRateTotal = array();
		$sdRateCount = array();
		
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Total market' ,false);
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$row)->applyFromArray($headerStyleArray);		
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Ring' ,false);
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$row)->applyFromArray($headerStyleArray);		
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Avg Rate SD' ,false);
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$row)->applyFromArray($headerStyleArray);		
		
		$row++;
		$column = -1;		
		foreach ($inventories as $inventory)
		{									
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, $inventory->name ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, $inventory->ring ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Rate SD' ,false);
	
			foreach ($dates as $date){
				$column++;
				if( $inventory->dates[$date] )
				{
					if( $inventory->dates[$date]->sd_room_rate > 0  )
					{
						if( ! $sdRateTotal[$date] )
						{
							$sdRateTotal[$date] = 0;
							$sdRateCount[$date] = 0;
						}
						$sdRateTotal[$date] = $sdRateTotal[$date] + $inventory->dates[$date]->sd_room_rate;
						$sdRateCount[$date]++;
					}
					$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, $inventory->dates[$date]->sd_room_rate ,false);
				}								
			}		

			$column = -1;
			$row++;	
		}		
		
		$column = 3;
		foreach ($dates as $date){
			
			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$avg_row)->applyFromArray($headerStyleArray);
			
			if( $sdRateTotal[$date] )
			{
				$avgValue = number_format( $sdRateTotal[$date] / $sdRateCount[$date] ,2);
				$this->objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $avg_row, $avgValue ,false);				
			}
			
			$column++;
		}

		/*
		 * SD Room
		 */		
		$row += 2;
		$avg_row  =	$row;
		$sdRoomTotal = array();
		
		$column = -1;
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Total market' ,false);
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$row)->applyFromArray($headerStyleArray);		
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Ring' ,false);
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$row)->applyFromArray($headerStyleArray);		
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Total number of rooms SD' ,false);
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$row)->applyFromArray($headerStyleArray);		
		$row++;
		$column = -1;
		
		foreach ($inventories as $inventory)
		{									
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, $inventory->name ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, $inventory->ring ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Number of rooms SD' ,false);
	
			foreach ($dates as $date){
				$column++;
				if( $inventory->dates[$date] )
				{
					if( ! isset($sdRoomTotal[$date]) )
					{
						$sdRoomTotal[$date] = 0;
					}
					
					$sdRoomTotal[$date] = $sdRoomTotal[$date] + $inventory->dates[$date]->sd_room_total + $inventory->dates[$date]->booked_sdroom;
					
					$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, $inventory->dates[$date]->sd_room_total + $inventory->dates[$date]->booked_sdroom ,false);
				}								
			}		

			$column = -1;
			$row++;	
		}

		$column = 3;
		foreach ($dates as $date){
			
			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$avg_row)->applyFromArray($headerStyleArray);
			if( $sdRoomTotal[$date] )
			{
				$this->objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $avg_row, $sdRoomTotal[$date] ,false);				
			}
			
			$column++;
		}
		
		//END SD
		
		
		
		/*
		 * T Rate
		 */
		
		$row += 2;
				
		$column = -1;
		$avg_row  =	$row;
		
		$sdRateTotal = array();
		$sdRateCount = array();
		
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Total market' ,false);
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$row)->applyFromArray($headerStyleArray);		
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Ring' ,false);
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$row)->applyFromArray($headerStyleArray);		
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Avg Rate T' ,false);
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$row)->applyFromArray($headerStyleArray);		
		
		$row++;
		$column = -1;		
		foreach ($inventories as $inventory)
		{									
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, $inventory->name ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, $inventory->ring ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Rate T' ,false);
	
			foreach ($dates as $date){
				$column++;
				if( $inventory->dates[$date] )
				{
					if( $inventory->dates[$date]->t_room_rate > 0 )
					{
						if( ! $sdRateTotal[$date] )
						{
							$sdRateTotal[$date] = 0;
							$sdRateCount[$date] = 0;
						}
						$sdRateTotal[$date] = $sdRateTotal[$date] + $inventory->dates[$date]->t_room_rate;
						$sdRateCount[$date]++;
					}
					$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, $inventory->dates[$date]->t_room_rate ,false);
				}								
			}		

			$column = -1;
			$row++;	
		}		
		
		$column = 3;
		foreach ($dates as $date){
			
			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$avg_row)->applyFromArray($headerStyleArray);
			
			if( $sdRateTotal[$date] )
			{
				$avgValue = number_format( $sdRateTotal[$date] / $sdRateCount[$date] ,2);
				$this->objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $avg_row, $avgValue ,false);				
			}
			
			$column++;
		}

		/*
		 * T Room
		 */		
		$row += 2;
		$avg_row  =	$row;
		$sdRoomTotal = array();
		
		$column = -1;
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Total market' ,false);
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$row)->applyFromArray($headerStyleArray);		
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Ring' ,false);
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$row)->applyFromArray($headerStyleArray);		
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Total number of rooms T' ,false);
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$row)->applyFromArray($headerStyleArray);		
		$row++;
		$column = -1;
		
		foreach ($inventories as $inventory)
		{									
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, $inventory->name ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, $inventory->ring ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$column, $row, 'Number of rooms T' ,false);
	
			foreach ($dates as $date){
				$column++;
				if( $inventory->dates[$date] )
				{
					if( ! isset($sdRoomTotal[$date]) )
					{
						$sdRoomTotal[$date] = 0;
					}
					
					$sdRoomTotal[$date] = $sdRoomTotal[$date] + $inventory->dates[$date]->t_room_total + $inventory->dates[$date]->booked_troom;
					
					$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $row, $inventory->dates[$date]->t_room_total + $inventory->dates[$date]->booked_troom ,false);
				}								
			}		

			$column = -1;
			$row++;	
		}

		$column = 3;
		foreach ($dates as $date){
			
			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$avg_row)->applyFromArray($headerStyleArray);
			
			if( $sdRoomTotal[$date] )
			{
				$this->objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, $avg_row, $sdRoomTotal[$date] ,false);				
			}
			
			$column++;
		}
		
		//END T

		
		
		$this->download('Availability_Report_All_Hotels.xlsx');
		
	}
	
	protected function reportSingleHotel()
	{
		$model = $this->getModel();
		
		$hotelId	= JRequest::getInt('hotel_id');
		
		$hotel = $model->getHotel($hotelId);
		$data  = $model->getInventoryData();
		
		
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(32);
		
		$column = 0;
		$row = 1;
		
		$this->objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($column, $row, 'Availability report for:',false)
			->setCellValueByColumnAndRow(++$column, $row, $hotel->name,false);
			
		$column = 0;	
		$row++;
		$row++;
		$row++;
		$this->objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($column, $row, 'Total market',false)
			->setCellValueByColumnAndRow($column+1, $row, 'Avg Rate SD',false)
			->setCellValueByColumnAndRow($column, ++$row, $hotel->name,false)
			->setCellValueByColumnAndRow($column+1, $row, 'Rate SD',false)
			->setCellValueByColumnAndRow($column+1, ++$row, 'Ranking',false);
			
		$row++;
		$row++;
		$this->objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($column, $row, 'Total market',false)
			->setCellValueByColumnAndRow($column+1, $row, 'Total number of rooms SD',false)
			->setCellValueByColumnAndRow($column, ++$row, $hotel->name,false)
			->setCellValueByColumnAndRow($column+1, $row, 'Number of rooms SD',false)
			->setCellValueByColumnAndRow($column+1, ++$row, 'Percentage of market',false)
			->setCellValueByColumnAndRow($column+1, ++$row, 'Ranking',false);	

		$row++;
		$row++;
		$this->objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($column, $row, 'Total market',false)
			->setCellValueByColumnAndRow($column+1, $row, 'Avg Rate T',false)
			->setCellValueByColumnAndRow($column, ++$row, $hotel->name,false)
			->setCellValueByColumnAndRow($column+1, $row, 'Rate T',false)
			->setCellValueByColumnAndRow($column+1, ++$row, 'Ranking',false);	
			
					
		$row++;
		$row++;
		$this->objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($column, $row, 'Total market',false)
			->setCellValueByColumnAndRow($column+1, $row, 'Total number of rooms T',false)
			->setCellValueByColumnAndRow($column, ++$row, $hotel->name,false)
			->setCellValueByColumnAndRow($column+1, $row, 'Number of rooms T',false)
			->setCellValueByColumnAndRow($column+1, ++$row, 'Percentage of market',false)
			->setCellValueByColumnAndRow($column+1, ++$row, 'Ranking',false);	
			
		
		$column = 2;
		$row=2;
		$this->objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($column-1, $row, 'Date',false);
			
		foreach ($data as $room)
		{
			$row=2;
			$this->objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($column)->setWidth(13);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $room->date ,false);

			$row++;$row++;
			
			//SD RATE
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $room->avg_rate_sd ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, ++$row, $room->sd_room_rate ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, ++$row, $room->sd_room_rank ,false);		

			$row++;$row++;	
			//SD ROOM
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $room->market_sd_room_total ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, ++$row, $room->sd_room_total ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueExplicitByColumnAndRow($column, ++$row, $room->sd_room_percentage.'%', PHPExcel_Cell_DataType::TYPE_NUMERIC);	
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, ++$row, $room->sd_num_rank ,false);		
				
			$row++;$row++;	
			
			//T RATE
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $room->avg_rate_t ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, ++$row, $room->t_room_rate ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, ++$row, $room->t_room_rank ,false);		

			$row++;$row++;	
			//T ROOM
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $room->market_t_room_total ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, ++$row, $room->t_room_total ,false);
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueExplicitByColumnAndRow($column, ++$row, $room->t_room_percentage.'%', PHPExcel_Cell_DataType::TYPE_NUMERIC);	
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, ++$row, $room->t_num_rank ,false);	
				
			$column++;
		}	
			
		$row++;$row++;
		$column = 0;
		
			
		
		$this->download('Availability_Report_'.$hotel->name.'.xlsx');
	}
	
	protected function download($fileName)
	{
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}
	
}