<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

require_once JPATH_COMPONENT.'/libraries/report.php';

class SfsControllerAccounting2reports extends JController {

	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	//lchung
	public function TracepassExportExcel()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$Report = AirlineReport::getInstance();
		$objPHPExcel = $Report->objPHPExcel;
		
    	$model = $this->getModel('Accounting2reports');	
    	$filter_date	 = JRequest::getVar('filter_date');
    	$filter_until_date   = JRequest::getVar('filter_until_date');
		$export_withID   = JRequest::getVar('export_withID');
		$check_select_report   = JRequest::getVar('check_select_report');
		
		$txt_filter_airport = JRequest::getVar('txt_filter_airport');
		$txt_filter_date = JRequest::getVar('txt_filter_date');
		$txt_filter_FlightN = JRequest::getVar('txt_filter_FlightN');
		$txt_filter_BlockCode = JRequest::getVar('txt_filter_BlockCode');
		$txt_filter_PNR = JRequest::getVar('txt_filter_PNR');
		$txt_filter_Hotelname = JRequest::getVar('txt_filter_Hotelname');
		
		$txt_filter_airport = ($txt_filter_airport == '' ) ? 'All' : $txt_filter_airport;
		$txt_filter_date = ($txt_filter_date == '' ) ? 'All' : $txt_filter_date;
		$txt_filter_FlightN = ($txt_filter_FlightN == '' ) ? 'All' : $txt_filter_FlightN;
		$txt_filter_BlockCode = ($txt_filter_BlockCode == '' ) ? 'All' : $txt_filter_BlockCode;
		$txt_filter_PNR = "";//($txt_filter_PNR == '' ) ? 'All' : $txt_filter_PNR;
		$txt_filter_Hotelname = ($txt_filter_Hotelname == '' ) ? 'All' : $txt_filter_Hotelname;
		
		
		
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
		//background
		$headerStyleArray3 = array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FFA5A5A5')//e26b0a
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
		
		
		$data = $model->getPassengers( $export_withID );
		$airline = SFactory::getAirline();
		$currency_code = $airline->currency_code;	
		///print_r( $airline );die;
		$user 	= JFactory::getUser();	
		///print_r( $data );die;
		$row = 1;
		
		$FORMAT_CURRENCY = '"$"#,##0.00_-';
		if ( $currency_code == 'EUR' ) {
			$FORMAT_CURRENCY = '[$€ ]#,##0.00_-';
		}

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(23);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(23);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
		
		
		//$objPHPExcel->getActiveSheet()->getColumnDimension('A8:I8')->setWidth(15);
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 0, $row, 'Report for ' . $airline->name ,false);
		$row++;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 0, $row, 'Username:' ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 1, $row, $user->username ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 3, $row, 'Filter:' ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 4, $row, 'Airport:' ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 5, $row, $txt_filter_airport ,false);
					
		$row++;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 0, $row, 'Date:' ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 1, $row, date('Y/m/d H:i:s') ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 4, $row, 'Date:' ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 5, $row, $txt_filter_date ,false);
					
		$row++;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 0, $row, 'period start' ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 1, $row, str_replace("-", "/", $filter_date ) ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 4, $row, 'Block code' ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 5, $row, $txt_filter_BlockCode ,false);
					
		$row++;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 0, $row, 'period end' ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 1, $row, str_replace("-", "/", $filter_until_date ) ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 4, $row, 'FlightNumber' ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 5, $row, $txt_filter_FlightN ,false);
		
		$row++;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 0, $row, 'Selection:' ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 1, $row, $check_select_report ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 4, $row, '' ,false);//PNR:
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 5, $row, $txt_filter_PNR ,false);
					
		$row++;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 4, $row, 'Hotel:' ,false);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( 5, $row, $txt_filter_Hotelname ,false);
		
		
		
		$headers = array('Blockcode', 'Date','Valid Tru','Airport code','Flight number','Passenger Name','Service type','Status','#amount', 'Issued', 'Claimed', 'Supplier', 'Purchasedate', 'Servicedescription','Ticketnumber', 'Grand Total');
		$row++;
		$row++;
		$c_h = count( $headers );
		for( $i = 0; $i< $c_h ; $i++ ){
			
		// set header table cell border
		if( $i < 8  ){
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->applyFromArray($headerStyleArray);	
		} 
		elseif( $i < ($c_h-1) ) {
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->applyFromArray($headerStyleArray2);
		}
		else {
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->applyFromArray($headerStyleArray3);
		}
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow( $i, $row, $headers[$i] ,false);
		}
		
		$top_row = ($row+1);
		foreach( $data as $vk => $v ){
			
			$reservations_status = '';
			switch( $v->reservations_status ){
				case "A":
					$reservations_status = 'Accepted';
				break;
				case "O":
					$reservations_status = 'Opent';
				break;
				case "P":
					$reservations_status = 'Pending';
				break;
			}
			$value_hotel = $v->value_hotel;
			
			$Servicetype = '';
			$valid_thru = '';
			
			//case voucher_id == ''
			$voucher_id = $v->voucher_id;
			if($v->airplus_taxi == 1 ) {				
				$Servicetype = 'Taxi transfer';
				///$valid_thru = $v->taxi_valid_thru;
			}
			if($v->airplus_mealplan == 1 )	{			
				$Servicetype = 'Mealplan';
				///$valid_thru = $v->meal_valid_thru;
			}
			if($v->airplus_cash == 1 ){				
				$Servicetype = 'Cash';
				///$valid_thru = $v->cash_valid_thru;
			}
			if($v->airplus_phone == 1 )	 {			
				$Servicetype = 'Phone';
				///$valid_thru = $v->phone_valid_thru;
			}
			$valid_thru = '';
			if( $v->expiredate != '' )
				$valid_thru = date('Y/m/d', strtotime( $v->expiredate ) );
			$card_number = $v->blockcode;
			if ( $card_number == '' ) {
				$card_number = SfsHelper::getCardNumber( $v->card_number );
			}
			
			$is_not_user_sevice = 0;
			if( $v->airplus_mealplan == 0 && $v->airplus_taxi == 0 && $v->airplus_cash == 0 && $v->airplus_phone == 0 ) {
				$is_not_user_sevice = 1;
				$Servicetype = $v->hotel_name;
			}
			
			if( $is_not_user_sevice == 1 ) {
				$row++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 0, $row, $card_number ,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 1, $row, $v->startdate ,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 2, $row, $valid_thru ,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 3, $row, $v->airport_code ,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 4, $row, $v->flight_number ,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 5, $row, $v->first_name . ' ' . $v->last_name ,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 6, $row, $Servicetype, false);
				
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 7, $row, $reservations_status,false);
				
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 8, $row, $v->count_amount,false);
				
				
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 9, $row, $v->issued,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 10, $row, $v->claimed,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 11, $row, $v->supplier,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 12, $row, $v->purchase_date,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 13, $row, $v->service_desc,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 14, $row, $v->ticket_number,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 15, $row, $value_hotel,false);
				
			}
			elseif( $voucher_id == '' ) {
				$row++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 0, $row, $card_number ,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 1, $row, $v->startdate ,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 2, $row, $valid_thru ,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 3, $row, $v->airport_code ,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 4, $row, $v->flight_number ,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 5, $row, $v->first_name . ' ' . $v->last_name ,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 6, $row, $Servicetype, false);
				
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 7, $row, $reservations_status,false);
				
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 8, $row, $v->count_amount,false);
				
				
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 9, $row, $v->issued,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 10, $row, $v->claimed,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 11, $row, $v->supplier,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 12, $row, $v->purchase_date,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 13, $row, $v->service_desc,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 14, $row, $v->ticket_number,false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 15, $row, $value_hotel,false);
				
			}
			elseif( $voucher_id > 0 ) {
				if ( $v->airplus_mealplan == 1 ) {
					$row++;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 0, $row, $card_number ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 1, $row, $v->startdate ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 2, $row, $valid_thru ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 3, $row, $v->airport_code ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 4, $row, $v->flight_number ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 5, $row, $v->first_name . ' ' . $v->last_name ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 6, $row, 'Taxi transfer', false);
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 7, $row, $reservations_status,false);
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 8, $row, $v->count_amount,false);
				
				
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 9, $row, $v->issued,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 10, $row, $v->claimed,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 11, $row, $v->supplier,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 12, $row, $v->purchase_date,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 13, $row, $v->service_desc,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 14, $row, $v->ticket_number,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 15, $row, $value_hotel,false);
					
				}
				
				if ( $v->airplus_taxi == 1 ) {
					$row++;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 0, $row, $card_number ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 1, $row, $v->startdate ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 2, $row, $valid_thru ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 3, $row, $v->airport_code ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 4, $row, $v->flight_number ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 5, $row, $v->first_name . ' ' . $v->last_name ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 6, $row, 'Mealplan', false);
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 7, $row, $reservations_status,false);
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 8, $row, $v->count_amount,false);
				
				
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 9, $row, $v->issued,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 10, $row, $v->claimed,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 11, $row, $v->supplier,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 12, $row, $v->purchase_date,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 13, $row, $v->service_desc,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 14, $row, $v->ticket_number,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 15, $row, $value_hotel,false);
					
				}
				
				if ( $v->airplus_cash == 1 ) {
					$row++;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 0, $row, $card_number ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 1, $row, $v->startdate ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 2, $row, $valid_thru ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 3, $row, $v->airport_code ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 4, $row, $v->flight_number ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 5, $row, $v->first_name . ' ' . $v->last_name ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 6, $row, 'Cash', false);
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 7, $row, $reservations_status,false);
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 8, $row, $v->count_amount,false);
				
				
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 9, $row, $v->issued,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 10, $row, $v->claimed,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 11, $row, $v->supplier,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 12, $row, $v->purchase_date,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 13, $row, $v->service_desc,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 14, $row, $v->ticket_number,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 15, $row, $value_hotel,false);
					
				}
				
				if ( $v->airplus_phone == 1 ) {
					$row++;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 0, $row, $card_number ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 1, $row, $v->startdate ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 2, $row, $valid_thru ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 3, $row, $v->airport_code ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 4, $row, $v->flight_number ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 5, $row, $v->first_name . ' ' . $v->last_name ,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 6, $row, 'Phone', false);
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 7, $row, $reservations_status,false);
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 8, $row, $v->count_amount,false);
				
				
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 9, $row, $v->issued,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 10, $row, $v->claimed,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 11, $row, $v->supplier,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 12, $row, $v->purchase_date,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 13, $row, $v->service_desc,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 14, $row, $v->ticket_number,false);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 15, $row, $value_hotel,false);
				}

			}
			
		}
		
		if(count($data)) {
			$xy = 'J'.$top_row.':J'. $row;
			$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
			
			$xy = 'K'.$top_row.':K'. $row;
			$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
			
			$xy = 'P'.$top_row.':P'. $row;
			$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
			
			$objPHPExcel->getActiveSheet()->getStyle('P'.$top_row.':P'. $row)->applyFromArray($headerStyleArray3);
		
			$xy = 'P'.$top_row.':P'. $row;
			$row++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(15, $row, "=SUM($xy)", false);
			
			$xy = 'P'.$top_row.':P'. $row;
			$this->FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
		}
		
		$row++;
		$row++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow( 0, $row, 'Issued Stranded Flight Solutions ' . JURI::base(),false);
				
		$filename = 'Accounting_report_AIRPLUS.xlsx';
		$Report->download( $filename );
		exit;
	}
	
	public function FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY){
		$objPHPExcel->getActiveSheet()
			->getStyle($xy)
			->getNumberFormat()
			->setFormatCode(
				$FORMAT_CURRENCY
			);
	}
	//End lchung
 
}


