<?php
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel.php';
require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel/Worksheet/Drawing.php';

class SfsControllerReportforhotelavailability extends JController {
		
	protected $objPHPExcel = null;

	public function __construct($config = array())
	{
		$this->objPHPExcel = new PHPExcel();
		
		parent::__construct($config);															
	}
		
	public function getModel($name = 'Reportforhotelavailability', $prefix = 'SfsModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function availabilityReport()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		
		$from 		= JRequest::getVar('date_from');
    	$until  	= JRequest::getVar('date_to');
		
		$this->reportAllHotels( $from, $until );
		
		
		JFactory::getApplication()->close();	
	}
	
	protected function reportAllHotels( $from, $until )
	{
		$model = $this->getModel();
		
		$AirportCode = $model->getAirportCode( $from, $until );
		$data = $model->getData( $from, $until );
		
		$count = count( $data );
		$row  = 3;
		
		$headers = array(
		'Airportcode',
		'Number of hotel total',
		'Number of partner hotels available',
		'AVG rate of partner hotels',
		'Number of first ring WS hotels available',
		'AVG price WS First ring hotels',
		'Lowest hotel rate WS first ring',
		'Highest hotel rate WS first ring',
		'Number of  second ring WS hotels available',
		'AVG price second ring WS hotels',
		'Lowest hotel rate WS second ring', 
		'Highest hotel rate WS second  ring', 
		'Rest of the WS hotels', 
		'AVG price rest of the WS hotels');
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
		$objPHPExcel = $this->objPHPExcel;
		$j = 0;
		for ( $i = 0;  $i <= 14; $i++ ) {
			if( $i < 2 ){
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($j,$row)->applyFromArray($headerStyleArray);	
			} 
			elseif( $i < 14 ){
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($j,$row)->applyFromArray($headerStyleArray2);
			}	
			/*elseif ( $i == 14 ) {
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->applyFromArray($headerStyleArray3);
				for ( $r = $row;  $r <= ($count+$row - 1); $r++ ) {
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r+1)->applyFromArray($headerStyleArray4);
				}
			}*/
			$j++;
		}
		$w = 14;
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth($w);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth($w);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth($w);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth($w);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth($w);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth($w);		
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth($w);		
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth($w);		
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth($w);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth($w);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth($w);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth($w);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth($w);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth($w);		
		//$objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':P'. $row)->getAlignment()->setWrapText(true); 
		$style = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$sheet = $objPHPExcel->getActiveSheet();
		$sheet->getStyle('A' . $row . ':P'. $row)->applyFromArray($style);
				
		foreach ($headers as $text)
		{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $row, $text,false);
			$column++;				
		}

		$row++;
		///$FORMAT_CURRENCY = '"$"#,##0.00_-';
		///if ( $currency_code == 'EUR' ) {
		$FORMAT_CURRENCY = '[$€ ]#,##0.00_-';
		///}
		
		$countR = count( $AirportCode );
		$endR = ($count+$row);
		$xy = 'D'.($row) . ':D'. $endR;
		$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
		
		$xy = 'F'.($row) . ':F'. $endR;
		$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
		
		$xy = 'G'.($row) . ':G'. $endR;
		$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
		
		$xy = 'H'.($row) . ':H'. $endR;
		$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
		
		$xy = 'J'.($row) . ':J'. $endR;
		$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
		
		$xy = 'K'.($row) . ':K'. $endR;
		$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
		
		$xy = 'L'.($row) . ':L'. $endR;
		$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
		
		$xy = 'N'.($row) . ':N'. $endR;
		$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
		
		$dataAirportCode = array();
		foreach ( $AirportCode as $vk => $v ) {
			///$dataNew = array();
			///$dataHotel = array();
			$dataHotel_Pn = array();
			$total_hotel_Pn = 0;
			$total_hotel = 0;
			$AVG_rate_of_partner_hotels = 0;
			
			$total_hotel_SW_10 = 0;
			$dataHotel_SW_10 = array();
			$AVG_priceWS_First_ring_hotels = 0;
			
			$total_hotel_SW_10_50 = 0;
			$dataHotel_SW_10_50 = array();
			$AVG_priceWS_Second_ring_hotels = 0;
			
			$total_hotel_SW_50 = 0;
			$dataHotel_SW_50 = array();
			$AVG_priceWS_Second_ring_hotels = 0;
			
			$LowestHotelRateWS_10 = 0;
			$HighestHotelRateWS_10 = 0;
			
			$LowestHotelRateWS_10_50 = 0;
			$HighestHotelRateWS_10_50 = 0;
			
			$LowestHotelRateWS_50 = 0;
			$HighestHotelRateWS_50 = 0;
		
			foreach ( $data as $vkS => $vS ) {
				if ( $v->airport_code == $vS->airport_code) {
					
					if ( !array_key_exists('k' . $vS->hotel_id, $dataHotel) ) {
						$total_hotel += 1;
						$dataHotel['k' . $vS->hotel_id] = $vS->hotel_id;
					}
					//Get Number of partner hotels available
					if ( $vS->ws_id == "" ) { 
						if ( !array_key_exists('k' . $vS->hotel_id, $dataHotel_Pn) ) {
							$total_hotel_Pn +=1;
							$t = $vS->sd_rate + $vS->t_rate + $vS->s_rate + $vS->q_rate;
							$AVG_rate_of_partner_hotels += $t;
							$dataHotel_Pn['k' . $vS->hotel_id] = $vS->hotel_id;
						}
					}//End if ( $vS->ws_id == "" )
					else { //is ws
						if ( $vS->distance < 10 ) { //10km
							//$Number_of_first_ringWS_hotels_available +=
							if ( !array_key_exists('k' . $vS->hotel_id, $dataHotel_SW_10) ) {
								$total_hotel_SW_10 +=1;
								$t = $vS->sd_rate + $vS->t_rate + $vS->s_rate + $vS->q_rate;
								$AVG_priceWS_First_ring_hotels += $t;
								$dataHotel_SW_10['k' . $vS->hotel_id] = $vS->hotel_id;
							}
							
						}//End < 10km
						elseif ( $vS->distance > 10  && $vS->distance < 50 ) { //between 10 and 50 KM
							if ( !array_key_exists('k' . $vS->hotel_id, $dataHotel_SW_10_50) ) {
								$total_hotel_SW_10_50 += 1;
								$t = $vS->sd_rate + $vS->t_rate + $vS->s_rate + $vS->q_rate;
								$AVG_priceWS_Second_ring_hotels += $t;
								$dataHotel_SW_10_50['k' . $vS->hotel_id] = $vS->hotel_id;
							}
						}
						// between 10 and 50 KM
						elseif ( $vS->distance > 50 ) { // > 50 KM
							if ( !array_key_exists('k' . $vS->hotel_id, $dataHotel_SW_50) ) {
								$total_hotel_SW_50 +=1;
								$t = $vS->sd_rate + $vS->t_rate + $vS->s_rate + $vS->q_rate;
								$AVG_priceWS_Second_ring_hotels += $t;
								$dataHotel_SW_50['k' . $vS->hotel_id] = $vS->hotel_id;
							}
						}//End > 50 KM
					}
				}
			}
			
			$hotel_id_10 = implode(",", $dataHotel_SW_10 );
			if ( $hotel_id_10 != '' ) {
				$Lowest_10 = $model->getHighestHotelRateWS( $from, $until, $hotel_id_10, "MIN" );
				//$model->getLowestHotelRateWS( $from, $until, $hotel_id_10 );
				//$LowestHotelRateWS_10 = $Lowest_10->price;
				$LowestHotelRateWS_10 = floatval( $Lowest_10->s_rate );
				if ( $LowestHotelRateWS_10 == 0 ) {
					$LowestHotelRateWS_10 = floatval( $Lowest_10->sd_rate );
				}
				
				$Highest_10 = $model->getHighestHotelRateWS( $from, $until, $hotel_id_10 );
				$HighestHotelRateWS_10 = floatval( $Highest_10->s_rate );
				if ( $HighestHotelRateWS_10 == 0 ) {
					$HighestHotelRateWS_10 = floatval( $Highest_10->sd_rate );
				}
			}
			
			$hotel_id_10_50 = implode(",", $dataHotel_SW_10_50 );
			if ( $hotel_id_10_50 != '' ) {
				$Lowest_10_50 = $model->getHighestHotelRateWS( $from, $until, $hotel_id_10, "MIN" );
				//$model->getLowestHotelRateWS( $from, $until, $hotel_id_10_50 );
				//$LowestHotelRateWS_10_50 = $Lowest_10_50->price;
				$LowestHotelRateWS_10_50 = floatval( $Lowest_10_50->s_rate );
				if ( $LowestHotelRateWS_10_50 == 0 ) {
					$LowestHotelRateWS_10_50 = floatval( $Lowest_10_50->sd_rate );
				}
				
				$Highest_10_50 = $model->getHighestHotelRateWS( $from, $until, $hotel_id_10_50 );
				$HighestHotelRateWS_10_50 = floatval( $Highest_10_50->s_rate );
				if ( $HighestHotelRateWS_10_50 == 0 ) {
					$HighestHotelRateWS_10_50 = floatval( $Highest_10_50->sd_rate );
				}
			}
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $row, $v->airport_code, false);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $row, $this->nformat( $total_hotel ), false);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $row, $this->nformat( $total_hotel_Pn ), false);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, $row, $this->nformat( $AVG_rate_of_partner_hotels / $total_hotel_Pn ), false);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, $row, $this->nformat( $total_hotel_SW_10), false);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5, $row, $this->nformat( $AVG_priceWS_First_ring_hotels/$total_hotel_SW_10 ), false);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6, $row, $LowestHotelRateWS_10, false);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7, $row, $HighestHotelRateWS_10, false);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8, $row, $this->nformat( $total_hotel_SW_10_50 ), false);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(9, $row, $this->nformat( $AVG_priceWS_Second_ring_hotels/$total_hotel_SW_10_50 ), false);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(10, $row, $LowestHotelRateWS_10_50, false);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(11, $row, $HighestHotelRateWS_10_50, false);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(12, $row, $this->nformat( $total_hotel_SW_50 ), false);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(13, $row, $this->nformat($AVG_priceWS_Rest_ring_hotels/$total_hotel_SW_50 ), false);
			$row++;
		}
		
		///print_r( $dataAirportCode );die;
		
		//die;
		
		$this->download('Report-for-Hotel-availability-' . time() . '.xlsx');
		
	}
	
	public function FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY){
		$objPHPExcel->getActiveSheet()
			->getStyle($xy)
			->getNumberFormat()
			->setFormatCode(
				$FORMAT_CURRENCY
			);
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
	
	function nformat( $num = 0 ) {
		return number_format( $num, 2, ".",",");
	}
	
}