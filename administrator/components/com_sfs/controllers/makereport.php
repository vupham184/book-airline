<?php
defined('_JEXEC') or die;
require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel.php';
require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel/Worksheet/Drawing.php';
class SfsControllerMakereport extends JControllerLegacy
{
		
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Makereport', $prefix = 'SfsModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function export()
	{
		$db = JFactory::getDbo();
		$model = $this->getModel('Makereport','SfsModel');
		$reservations = $model->getList();
		
		$hotelId = JRequest::getInt('hotel_id');
		
		$date_start  = JRequest::getVar('date_start');
		
		$date_end  = JRequest::getVar('date_end');
		
		$referenceNumber = '';
		
		$referenceNumber = JHTML::_('date',$date_end,'my');
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
		->setLastModifiedBy("Maarten Balliauw")
		->setTitle("Office 2007 XLSX Test Document")
		->setSubject("Office 2007 XLSX Test Document")
		->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		->setKeywords("office 2007 openxml php")
		->setCategory("Test result file");
		
        $gdImage = imagecreatefromjpeg(JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'images'.DS.'invoicelogo.jpg');
		// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
		$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setName('SFS Logo');		
		$objDrawing->setImageResource($gdImage);
		$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
		$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);		
		
		$objDrawing->setCoordinates('B1');
		
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		
		$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(70);
		
		$generatedDate = JFactory::getDate()->toSql();
		
		$generatedDate = JHtml::_('date', $generatedDate , 'd/M/Y');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('O3', 'Date');
		$objPHPExcel->getActiveSheet()->SetCellValue('P3', $generatedDate);
		$objPHPExcel->getActiveSheet()->SetCellValue('O4', 'Makereport number');
		$objPHPExcel->getActiveSheet()->SetCellValue('P4', $referenceNumber);
		
		$objPHPExcel->getActiveSheet()->SetCellValue('O5', 'Makereport for the period');
		$objPHPExcel->getActiveSheet()->SetCellValue('O6', 'From date:');
		$objPHPExcel->getActiveSheet()->SetCellValue('P6', JHTML::_('date',$date_start,'d-F-Y'));
		$objPHPExcel->getActiveSheet()->SetCellValue('O7', 'To date:');
		$objPHPExcel->getActiveSheet()->SetCellValue('P7', JHTML::_('date',$date_end,'d-F-Y'));
		
		
		$startColumn = 1;
		
		// Set column width
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(27);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(17);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(22);
		$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(18);
		
		//$objPHPExcel->getActiveSheet()->getStyle('B7:Y100')->getAlignment()->setWrapText(true);
		  
		$row = 2;		
		
		//$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':Y100')->getFont()->setSize(8);
		//$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':Y7')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		
		$col=0;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(++$col, $row, 'Airportcode',false)
			->setCellValueByColumnAndRow(++$col, $row, 'Blockcode',false)
			->setCellValueByColumnAndRow(++$col, $row, 'Date',false)
			->setCellValueByColumnAndRow(++$col, $row, 'Airline',false)
			->setCellValueByColumnAndRow(++$col, $row, 'Hotel',false)
			->setCellValueByColumnAndRow(++$col, $row, 'WS or Partner',false)
			->setCellValueByColumnAndRow(++$col, $row, 'Initial Rooms',false)
			
			->setCellValueByColumnAndRow(++$col, $row, 'Flight number',false)
			->setCellValueByColumnAndRow(++$col, $row, 'Booked name',false)
			->setCellValueByColumnAndRow(++$col, $row, 'Iata stranded code',false)
			
			->setCellValueByColumnAndRow(++$col, $row,  'Claimed Rooms',false)
			->setCellValueByColumnAndRow(++$col, $row,  'Total estomated charges',false)
			->setCellValueByColumnAndRow(++$col, $row,  'Total nett charges WS',false);
			
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
		// set header table cell border
		$col = 0;			
		while ( $col < 11 ) {
			if( $col < 10 ){
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(++$col,$row)->applyFromArray($headerStyleArray);	
			} else {
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(++$col,$row)->applyFromArray($headerStyleArray2);
			}			
		}
		//Last header column - Orange Background Color
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(++$col,$row)->applyFromArray($headerStyleArray3);
		
		//Message Column
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(++$col,$row)->applyFromArray($headerStyleArray2);
		
		// Values style
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		//Blue Background
		$styleArray2 = array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FF8db4e2')
		  ),
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		//Green Background
		$styleArray3 = array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FF92d050')
		  ),
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		
		// Font color blue
		$styleArray4 = array(
			'font'    => array(
				'color'     => array(
			    	'rgb' => '366092'
			     )
			),
	    	'borders' => array(
		    	'allborders' => array(
		        	'style' => PHPExcel_Style_Border::BORDER_THIN
			    	)
			)
		);
		
		//Oragne Background
		$styleArray5 = array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FFe26b0a')
		  ),
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		 
		$row++;
		$totalRoomCharge = 0;
		$totalNettCharges = 0;
		foreach ($reservations['result'] as $i => $reservation){
			$col=0;
			if($reservation->ws_room > 0){
            	$ws_room = "WS hotel";
			}
			else{
				$ws_room = "Partner hotel";
			}
			$tota_room = (int)$reservation->sd_room+(int)$reservation->t_room+(int)$reservation->s_room+(int)$reservation->q_room;
			$total_room_charge = $reservations['reservation']['k' . $reservation->id]['total_room_charge'];
			$total_nett_charges = $reservations['reservation']['k' . $reservation->id]['total_nett_charges'];
			$totalRoomCharge += $total_room_charge;
			$totalNettCharges += $total_nett_charges;
			
			$flight_code = $reservation->flight_code;
			$booked_name = $reservation->booked_name;
			$delay_code = $reservation->delay_code;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$col, $row, $reservation->airport_code,false)
				->setCellValueByColumnAndRow(++$col, $row, $reservation->blockcode,false)
				->setCellValueByColumnAndRow(++$col, $row, sfsHelper::getATDate($reservation->booked_date,JText::_('DATE_FORMAT_LC2')),false)
				->setCellValueByColumnAndRow(++$col, $row, $reservation->airline_name,false)
				->setCellValueByColumnAndRow(++$col, $row, $reservation->hotel_name,false)
				->setCellValueByColumnAndRow(++$col, $row, $ws_room,false)
				->setCellValueByColumnAndRow(++$col, $row, $this->numberToText($tota_room),false)
				
				->setCellValueByColumnAndRow(++$col, $row, $flight_code,false)
				->setCellValueByColumnAndRow(++$col, $row, $booked_name,false)
				->setCellValueByColumnAndRow(++$col, $row, $delay_code,false)
				
				->setCellValueByColumnAndRow(++$col, $row, $reservation->claimed_rooms,false)
				->setCellValueByColumnAndRow(++$col, $row, $this->numberToText($total_room_charge),false)
				->setCellValueByColumnAndRow(++$col, $row, $this->numberToText($total_nett_charges),false);
			$row++;	
		}
		$t_row = $row++;
		$objPHPExcel->getActiveSheet()->SetCellValue('M' . $t_row, 'Total:' . $this->numberToText($totalRoomCharge));
		$objPHPExcel->getActiveSheet()->SetCellValue('N' . $t_row, 'Total:' . $this->numberToText($totalNettCharges));
			

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Makereport');
		$fileName = 'sfs-' . date('Y-m-d-H-i-s') . '.xlsx';

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;		
	}
	
	protected function numberToText($number)
	{
		$number = number_format($number,2);
		$text = (string)$number;
		$text = JString::str_ireplace('.', ',', $text);
		return $text;
	}
}