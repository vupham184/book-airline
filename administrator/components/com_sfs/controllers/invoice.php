<?php
// No direct access
defined('_JEXEC') or die;

class SfsControllerInvoice extends JControllerLegacy
{

	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	public function generate()
	{
		$hotel_id = JRequest::getInt('hotel_id');
		$date_start  = JRequest::getVar('date_start');
		$date_end  = JRequest::getVar('date_end');

		$url = 'index.php?option=com_sfs&view=invoice';

		if( $hotel_id ) {
			$url .= '&hotel_id='.$hotel_id;
		}
		if($date_start && $date_end)
		{
			$url .= '&date_start='.$date_start;
			$url .= '&date_end='.$date_end;
		}
		$url = JRoute::_($url,false);
		$this->setRedirect($url);
	}

	public function export()
	{
		$db = JFactory::getDbo();
		
		require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel.php';
		require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel/Worksheet/Drawing.php';

		$model = $this->getModel('Invoice','SfsModel');
		
		$hotelId = JRequest::getInt('hotel_id');
		$model->setState('hotelId',$hotelId);
		
		$date_start  = JRequest::getVar('date_start');
		$model->setState('invoice.date_start',$date_start);
		
		$date_end  = JRequest::getVar('date_end');
		$model->setState('invoice.date_end',$date_end);
		
		$reservations = $model->getReservations();
		$hotel 		  = $model->getHotel();
		$merchantFee  = $model->getMerchantFee();	
		
		$hotelTax 	  = $hotel->getTaxes();
		$mealplanTax  = $hotel->getMealPlan();
		
		$currency 	  = $hotel->getTaxes()->currency_symbol;
		
		$referenceNumber = '';
		
		$referenceNumber = JHTML::_('date',$date_end,'my');
		
		$params = JComponentHelper::getParams('com_sfs');
		
		$sfs_system_suffix = trim($params->get('sfs_system_suffix','sfs'));	
		
		$referenceNumber .= '-'.$sfs_system_suffix;
		
		$query = 'SELECT COUNT(*) FROM #__sfs_admin_invoice_tracking';
		$db->setQuery($query);
		
		$invoice_count = (int) $db->loadResult() + 1;
		
		if($invoice_count < 10)
		{
			$referenceNumber .= '-0'.$invoice_count;
		} else {
			$referenceNumber .= '-'.$invoice_count;
		}
		
		$hotelEmail = null;
		if( (int) $hotel->created_by > 0 ) {
			$query = 'SELECT email FROM #__users WHERE id = '.$hotel->created_by ;
			$db->setQuery($query);
			$hotelEmail = $db->loadResult();
		}
		
		
		
		$blockStatusMap = array(
            	'O' => 'Open',
                'P' => 'Pending',
                'T' => 'Tentative',
                'C' => 'Challenged',
                'A' => 'Approved',
                'R' => 'Archived',
				'D' => 'Deleted'
		);
		
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
		
		$objPHPExcel->getActiveSheet()->SetCellValue('W2', 'Date');
		$objPHPExcel->getActiveSheet()->SetCellValue('X2', $generatedDate);
		$objPHPExcel->getActiveSheet()->SetCellValue('W3', 'Invoice number');
		$objPHPExcel->getActiveSheet()->SetCellValue('X3', $referenceNumber);
		
		$objPHPExcel->getActiveSheet()->SetCellValue('W4', 'Invoice for the period');
		$objPHPExcel->getActiveSheet()->SetCellValue('W5', 'from:');
		$objPHPExcel->getActiveSheet()->SetCellValue('X5', JHTML::_('date',$date_start,'d-F-Y'));
		$objPHPExcel->getActiveSheet()->SetCellValue('W6', 'until:');
		$objPHPExcel->getActiveSheet()->SetCellValue('X6', JHTML::_('date',$date_end,'d-F-Y'));
		
		
		$startColumn = 1;
		$row = 1;
		
		//SFS address	
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Stranded Flight Solutions ( SFS )',false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'De Nieuwe Vaart 42a',false)
			->setCellValueByColumnAndRow($startColumn, ++$row, '1401 GS Bussum',false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Netherlands',false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'KvK nummer 32088591',false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'NL8102.54.761.B02',false);
			
			
			
		$row++;
		
		$billing = $hotel->getBillingDetail();
		
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'TO: '.$billing->name,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, $hotelEmail,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, $hotel->name,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, $billing->address,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, $billing->zipcode.' '.$billing->city,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, $billing->country_name,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, $billing->tva_number,false);
		
		
		
		// Set column width
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(22);
		$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(18);
		
		$objPHPExcel->getActiveSheet()->getStyle('B7:Y100')->getAlignment()->setWrapText(true);
		  
		$row+=2;		
		
		$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':Y100')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':Y7')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		
		$col=0;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(++$col, $row, 'Block Code',false)
			->setCellValueByColumnAndRow(++$col, $row, 'Status',false)
			->setCellValueByColumnAndRow(++$col, $row, '# rooms',false)
			->setCellValueByColumnAndRow(++$col, $row, 'Gross Price in '.$currency,false)
			->setCellValueByColumnAndRow(++$col, $row, 'Net Price based on '.number_format($hotelTax->percent_total_taxes).'%',false)
			->setCellValueByColumnAndRow(++$col, $row, 'Merchant Fee %',false)
			->setCellValueByColumnAndRow(++$col, $row, 'Total Merchant Fee Room',false)
			->setCellValueByColumnAndRow(++$col, $row,  '# pax BFST',false)
			->setCellValueByColumnAndRow(++$col, $row,  'Gross Price BFST in '.$currency,false)
			->setCellValueByColumnAndRow(++$col, $row,  'Net Price based on '.number_format($mealplanTax->bf_tax).'%',false)
			->setCellValueByColumnAndRow(++$col, $row,  'Merchant Fee %',false)
			->setCellValueByColumnAndRow(++$col, $row,  'Total Merchant Fee BFST',false)
			->setCellValueByColumnAndRow(++$col, $row,  '# pax Lunch',false)
			->setCellValueByColumnAndRow(++$col, $row,  'Gross Price Lunch in '.$currency,false)
			->setCellValueByColumnAndRow(++$col, $row,  'Net Price based on '.number_format($mealplanTax->lunch_tax).'%',false)
			->setCellValueByColumnAndRow(++$col, $row,  'Merchant Fee %',false)
			->setCellValueByColumnAndRow(++$col, $row,  'Total Merchant Fee Lunch',false)
			->setCellValueByColumnAndRow(++$col, $row,  '# pax Dinner',false)
			->setCellValueByColumnAndRow(++$col, $row,  'Gross Price Dinner in '.$currency,false)
			->setCellValueByColumnAndRow(++$col, $row,  'Net Price based on '.number_format($mealplanTax->tax).'%',false)
			->setCellValueByColumnAndRow(++$col, $row,  'Merchant Fee %',false)
			->setCellValueByColumnAndRow(++$col, $row,  'Total Merchant Fee Dinner in '.$currency,false)
			->setCellValueByColumnAndRow(++$col, $row,  'Grand Total Amount in '.$currency,false)
			->setCellValueByColumnAndRow(++$col, $row,  'Message',false);
			
		
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
		while ( $col < 22 ) {
			if( $col < 7 ){
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
		$totalMerchatFeeRoom = 0;
		$totalMerchatFeeBreakfast = 0;
		$totalMerchatFeeLunch = 0;
		$totalMerchatFeeDinner = 0;
					
		foreach ($reservations as $reservation)
		{
			$col=0;
			
			$totalMerchatFeeRoom 		+= $reservation->totalMerchatFeeRoom;
			$totalMerchatFeeBreakfast 	+= $reservation->totalMerchatFeeBreakfast;
			$totalMerchatFeeLunch 		+= $reservation->totalMerchatFeeLunch;
			$totalMerchatFeeDinner 		+= $reservation->totalMerchatFeeDinner;
						
			$totalBreakfast  = $reservation->calculateTotalBreakfast();	
			$totalLunch  	 = $reservation->calculateTotalLunch();
			$totalDinner 	 = $reservation->calculateTotalMealplan();
			
			$blockNote = 'no';
			
			if( $reservation->block_note )
			{
				$blockNote = 'yes';
			}
			
			if( $blockStatusMap[$reservation->status] != 'Open' )
			{			
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$col, $row, $reservation->blockcode,false)
				->setCellValueByColumnAndRow(++$col, $row, $blockStatusMap[$reservation->status],false)
				->setCellValueByColumnAndRow(++$col, $row, $reservation->totalRooms,false)
				->setCellValueByColumnAndRow(++$col, $row, $this->numberToText($reservation->sd_rate),false)
				->setCellValueByColumnAndRow(++$col, $row, $this->numberToText($reservation->room_net_price),false)
				->setCellValueByColumnAndRow(++$col, $row, (int)$merchantFee->merchant_fee,false)
				->setCellValueByColumnAndRow(++$col, $row, $this->numberToText($reservation->totalMerchatFeeRoom),false)
				->setCellValueByColumnAndRow(++$col, $row, $totalBreakfast,false)
				->setCellValueByColumnAndRow(++$col, $row, $reservation->breakfast,false)
				->setCellValueByColumnAndRow(++$col, $row, $this->numberToText($reservation->breakfast_net_price),false)
				->setCellValueByColumnAndRow(++$col, $row, (int)$merchantFee->breakfast_merchant_fee,false)
				->setCellValueByColumnAndRow(++$col, $row, $this->numberToText($reservation->totalMerchatFeeBreakfast),false)
				->setCellValueByColumnAndRow(++$col, $row, $totalLunch,false)
				->setCellValueByColumnAndRow(++$col, $row, $reservation->lunch,false)
				->setCellValueByColumnAndRow(++$col, $row, $this->numberToText($reservation->lunch_net_price),false)
				->setCellValueByColumnAndRow(++$col, $row, (int)$merchantFee->lunch_merchant_fee,false)
				->setCellValueByColumnAndRow(++$col, $row, $this->numberToText($reservation->totalMerchatFeeLunch),false)
				->setCellValueByColumnAndRow(++$col, $row, $totalDinner,false)
				->setCellValueByColumnAndRow(++$col, $row, $reservation->mealplan,false)
				->setCellValueByColumnAndRow(++$col, $row, $this->numberToText($reservation->dinner_net_price),false)
				->setCellValueByColumnAndRow(++$col, $row, (int)$merchantFee->dinner_merchant_fee,false)
				->setCellValueByColumnAndRow(++$col, $row, $this->numberToText($reservation->totalMerchatFeeDinner),false)
				->setCellValueByColumnAndRow(++$col, $row, $this->numberToText($reservation->grandTotal),false)
				->setCellValueByColumnAndRow(++$col, $row, $blockNote,false);
			} else {
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(++$col, $row, $reservation->blockcode,false)
				->setCellValueByColumnAndRow(++$col, $row, $blockStatusMap[$reservation->status],false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, '',false)
				->setCellValueByColumnAndRow(++$col, $row, $blockNote,false);
			}
			$col = 0;
			
			while ( $col < 22 ) {
				if($col==6){
					$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(++$col,$row)->applyFromArray($styleArray2);
				} else if($col==11 || $col==16 || $col==21){
					$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(++$col,$row)->applyFromArray($styleArray3);
				} 
				else {
					$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(++$col,$row)->applyFromArray($styleArray);	
				}
				
				if($col==4 || $col==9 || $col==14 || $col==19){					
					$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(++$col,$row)->applyFromArray($styleArray4);
				} 
				
			}		
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(++$col,$row)->applyFromArray($styleArray5);
			
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(++$col,$row)->applyFromArray($styleArray3);
			
			$row++;
		}
		
		$grandTotal = $totalMerchatFeeRoom + $totalMerchatFeeBreakfast + $totalMerchatFeeLunch + $totalMerchatFeeDinner;
		$grandTotal = number_format($grandTotal,2);
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(7, $row, $this->numberToText($totalMerchatFeeRoom), false)
			->setCellValueByColumnAndRow(12, $row, $this->numberToText($totalMerchatFeeBreakfast),false)
			->setCellValueByColumnAndRow(17, $row, $this->numberToText($totalMerchatFeeLunch),false)
			->setCellValueByColumnAndRow(22, $row, $this->numberToText($totalMerchatFeeDinner),false)
			->setCellValueByColumnAndRow(23, $row, $this->numberToText($grandTotal),false);
	
		$row++;$row++;
		
		$lastStyleArray = array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FFe26b0a')
		  ),
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_NONE
		    )
		  )
		);
		
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(22,$row)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(23,$row)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(22,$row)->applyFromArray($lastStyleArray);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(23,$row)->applyFromArray($lastStyleArray);
		
		
		$objPHPExcel->setActiveSheetIndex(0)			
			->setCellValueByColumnAndRow(22, $row, 'SUB TOTAL',false)
			->setCellValueByColumnAndRow(23, $row, $this->numberToText($grandTotal),false);
			
		$row++;
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(22,$row)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(23,$row)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(22,$row)->applyFromArray($lastStyleArray);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(23,$row)->applyFromArray($lastStyleArray);
		$objPHPExcel->setActiveSheetIndex(0)			
			->setCellValueByColumnAndRow(22, $row, 'TAX',false)
			->setCellValueByColumnAndRow(23, $row, 'N/A',false);
			
		$row++;
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(22,$row)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(23,$row)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(22,$row)->applyFromArray($lastStyleArray);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(23,$row)->applyFromArray($lastStyleArray);
		
		$monthlyServiceFee = 'N/A';
		
		$totalCharge = $grandTotal;
		
		if( floatval( $merchantFee->monthly_fee) >0 )
		{
			$monthlyServiceFee = (string)$merchantFee->monthly_fee;
			$totalCharge +=  floatval( $merchantFee->monthly_fee);
		}
		
		$objPHPExcel->setActiveSheetIndex(0)			
			->setCellValueByColumnAndRow(22, $row, 'Monthly Service Fee',false)
			->setCellValueByColumnAndRow(23, $row,  $this->numberToText($monthlyServiceFee),false);
			
		$row++;
		$row++;	
		
		
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(22,$row)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(23,$row)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(22,$row)->applyFromArray($lastStyleArray);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(23,$row)->applyFromArray($lastStyleArray);
		$objPHPExcel->setActiveSheetIndex(0)			
			->setCellValueByColumnAndRow(22, $row, 'TOTAL',false)
			->setCellValueByColumnAndRow(23, $row, $this->numberToText($totalCharge),false);
				

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Invoice');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		$footerText = 'Your payment is due upon receipt and should be received no later than 14 days after the date appearing in the top section of the electronic invoice.';
			
		$objPHPExcel->getActiveSheet()->getStyle('B'.($row-3).':O'.($row-3))->getFont()->setSize(13);
		$objPHPExcel->getActiveSheet()->getStyle('B'.($row-3).':O'.($row-3))->getFont()->setBold(true);
		
		$objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow(1,$row-3,18,$row-3);
		
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(1, $row-3, $footerText, false);
		
		$fileName = $referenceNumber.'.xlsx';

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		
		
		$invOb = new stdClass();
		
		$invOb->user_id = JFactory::getUser()->id;		
		$invOb->created_date = JFactory::getDate()->toSql();
		
		$invOb->hotel_id = $hotelId;
		$invOb->from = $date_start;
		$invOb->until = $date_end;
		
		$db->insertObject('#__sfs_admin_invoice_tracking', $invOb );
		
		
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

