<?php
// Set flag that this is a parent file.
define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

//error_reporting(E_ALL | E_NOTICE);
//ini_set('display_errors', 1);

// Load system defines
if (file_exists(dirname(dirname(__FILE__)) . '/defines.php'))
{
	require_once dirname(dirname(__FILE__)) . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', dirname(dirname(__FILE__)));
	require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_BASE.'/includes/framework.php';

require_once JPATH_LIBRARIES . '/import.php';
require_once JPATH_LIBRARIES . '/cms.php';


$app = JFactory::getApplication('site');

// Initialise the application.
$app->initialise();

// Force library to be in JError legacy mode
JError::$legacy = true;

// Load the configuration
require_once JPATH_CONFIGURATION . '/configuration.php';

// System configuration.
$config = new JConfig;
$format = JRequest::getVar('format');
$allow = false;

$user = JFactory::getUser();
jimport('joomla.user.helper');
$uk = JRequest::getVar( 'uk' );
$status = '';
if ( $uk == "" ) {
	$uk = JRequest::getVar( 'UK' );
}

if ( $user->id > 0 && $user->username == '' && $uk == '') {
	$user->id = 0;
}


if ($uk)
{
    $db = JFactory::getDbo();
    $db->setQuery('SELECT user_id FROM #__sfs_contacts WHERE secret_key="'.$uk.'"');
    $user_id = $db->loadResult();
	$user->id = 0;
	
	if ( empty( $user_id ) && strtolower( $format ) == 'json' ) {
		$status = '401';
		printJsonError( $status );
	}
}

$user->id = $user->id ? $user->id : $user_id;

if( $user->id > 0 )
{
	$groups = JUserHelper::getUserGroups($user->id );
	if( in_array(8, $groups) || in_array(7, $groups) || in_array(11, $groups)  )
	{
		$allow = true;
	}
	else {
		$allow = false;
	}
}
else {
	$allow = false;
}

if( ! $allow )
{	
	$uriRoot = JURI::root();
	$uriRoot = str_ireplace('report/', '', $uriRoot);
	?>
	<a href="<?php echo $uriRoot?>index.php?option=com_users&view=login" target="_blank">Login</a>
	<?php 
	return;
}

require_once 'helpers/airline.php';

$period 	= airlineReportHelper::getVar(JRequest::getVar('period'),$config);
if( $period != '' ) {
	$start_period 	= $end_period = $period;
} 
else {
	$start_period 	= airlineReportHelper::getVar(JRequest::getVar('start_period'),$config);
	$end_period 	= airlineReportHelper::getVar(JRequest::getVar('end_period'),$config);	
	
	if ( empty( $start_period ) ){
		$start_period = date('Y-m-d');
	}
	
	if ( empty( $end_period ) ){
		$end_period = date('Y-m-d');
	}
}

if( JRequest::getVar('period') == 'current_day' || !isset($_GET['period']) ) {
	if( !isset($_GET['period']) && empty( $start_period ) ) {
		$start_period = date('Y-m-d');
	}
}
else if ( isset($_GET['period']) &&  $_GET['period'] != 'current_day' ) {
	$status = '400';
	if ( strtolower( $format ) == 'json' ) {
		printJsonError( $status );
	}
}

$airport = JRequest::getVar('airport');
$airline = airlineReportHelper::getAirline( $user->id );
$currency_code = airlineReportHelper::getCurrencyCode( $airline->airport_id );

if( ! $end_period ) $end_period = $start_period;

$data = airlineReportHelper::getDataNewReportAirline( $start_period, $end_period, $airport, $user );

if ( empty( $data ) && strtolower( $format ) == 'json' ) {
	$status = '204';
	printJsonError( $status );
}
/*elseif ( $status != '' ) {
	$status = '200';
}*/

$exportexcel = JRequest::getVar('exportexcel');

if ( $exportexcel == 1 ) {
	ExportExcel( $data, $airline , $currency_code, $start_period, $end_period);
}
if ( strtolower( $format ) == "json" ) {
	exportJson( $data, $airline);
	exit;
}

	//lchung
	function ExportExcel( $data, $airline, $currency_code, $beginDate, $endDate )
	{
		require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel.php';
		require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel/Worksheet/Drawing.php';
		$objPHPExcel = new PHPExcel();
				
    	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow('A', 1, $airline->name,false);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(31);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow('A', 2, 'period ' . $beginDate . ' untill ' . $endDate,false);
				
		$fileName = 'New_report_airline'.$beginDate.'_'.$endDate.'.xlsx';
		///print_r($data);die;
		$count = count( $data );
		$row  = 4;
		
		$headers = array('Blockcode','Date','Airport','Hotelname','Status','Flight number','# Rooms','Gross Price','# pax BFST','Gross Price BFST','# pax Lunch', 'Gross Price Lunch', '# pax Dinner', 'Gross Price Dinner', 'Grand Total Amount');
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
		
		if(count($data)) {
			$xy = 'H'.($row+1) . ':H'. ($count+$row);
			airlineReportHelper::FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
			
			$xy = 'J'.($row+1) . ':J'. ($count+$row);
			airlineReportHelper::FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
			
			$xy = 'L'.($row+1) . ':L'. ($count+$row);
			airlineReportHelper::FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
			
			$xy = 'N'.($row+1) . ':N'. ($count+$row);
			airlineReportHelper::FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
			
			$xy = 'O'.($row+1) . ':O'. ($count+$row);
			airlineReportHelper::FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
			
			$r_total = ($count+$row)+1;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(14, $r_total, "=SUM($xy)", false);
			$xy = 'O'.($r_total) . ':O'. ($r_total);
			airlineReportHelper::FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY );
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
				
				/*$total = floatval( $v->gross_price ) +
				$v->people_num * floatval( $v->gross_price_bfst ) + 
				$v->people_num * floatval( $v->gross_price_lunch ) + 
				$v->people_num * floatval( $v->gross_price_dinner );*/
				
				$rooms = 0;
				///$rooms = $v->s_room+$v->sd_room+$v->t_room+$v->q_room;
				
				$gross_price = 0;
				if ( $v->s_room > 0 ) {
					$gross_price = $v->s_rate;
					$rooms = $v->s_room;
				}
				if ( $v->sd_room > 0 ) {
					$gross_price = $v->sd_rate;
					$rooms = $v->sd_room;
				}
				if ( $v->t_room > 0 ) {
					$gross_price = $v->t_rate;
					$rooms = $v->t_room;
				}
				if ( $v->q_room > 0 ) {
					$gross_price = $v->q_rate;
					$rooms = $v->q_room;
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
						
						
				/*$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6, $row, $v->rooms, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7, $row, $v->gross_price, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8, $row, $v->people_num, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(9, $row, $v->people_num * $v->gross_price_bfst, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(10, $row, $v->people_num, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(11, $row, $v->people_num * $v->gross_price_lunch, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(12, $row, $v->people_num, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(13, $row, $v->people_num * $v->gross_price_dinner, false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(14, $row, $total, false);
						*/
				$row++;
				
			}	
			$row ++;	
		}

		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow('A', $row, "Issued by Stranded Flight Solutions on " . JURI::base(), false);
		//. strtolower($airport_code)
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow('A', $row+1, "date: " . date('d-m-Y H:i:s'), false);
		
		ob_start(); 
		$fileName = 'airline-report-' . date('ymd-His');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		$ob_content = ob_get_contents();
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
		header('Cache-Control: max-age=0');
		echo trim($ob_content);
		ob_end_flush();
		exit;
	}
	
	function FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY){
		$objPHPExcel->getActiveSheet()
			->getStyle($xy)
			->getNumberFormat()
			->setFormatCode(
				$FORMAT_CURRENCY
			);
	}
	
	function exportJson( $data = array(), $airline ){
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');
		$dataS = array();
		if(count($data)) {		
			foreach ($data as $v) {	
				$rooms = 0;
				$gross_price = 0;
				if ( $v->s_room > 0 ) {
					$gross_price = $v->s_rate;
					$rooms = $v->s_room;
				}
				if ( $v->sd_room > 0 ) {
					$gross_price = $v->sd_rate;
					$rooms = $v->sd_room;
				}
				if ( $v->t_room > 0 ) {
					$gross_price = $v->t_rate;
					$rooms = $v->t_room;
				}
				if ( $v->q_room > 0 ) {
					$gross_price = $v->q_rate;
					$rooms = $v->q_room;
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
				
				$arr['Blockcode'] =$v->blockcode;
				$arr['Date'] =$v->date;
				$arr['Airport'] =$v->airport_code;
				$arr['Hotelname'] =$v->hotel_name;
				$arr['Status'] =$v->status;
				$arr['Flight_number'] =$v->flight_number;
				$arr['Rooms'] = $rooms;
				$arr['Gross_Price'] = numberformat ( $gross_price );
				
				$pax_BFST = ( $v->breakfast > 0 ) ? $v->people_num : "0";
				$arr['pax_BFST'] =$pax_BFST;
				$Gross_Price_BFST = ($v->breakfast > 0 ) ? $v->people_num * $v->breakfast : "0.0";
				$arr['Gross_Price_BFST'] = numberformat ( $Gross_Price_BFST );
				
				$paxLunch = ($v->lunch > 0 ) ? $v->people_num : "0";				
				$arr['paxLunch'] = $paxLunch;
				$Gross_Price_Lunch = ($v->lunch > 0 ) ? $v->people_num * $v->lunch : "0.0";
				$arr['Gross_Price_Lunch'] = numberformat ( $Gross_Price_Lunch );
				
				$pax_Dinner = ( $v->mealplan > 0 ) ? $v->people_num : "0";
				$arr['pax_Dinner'] = $pax_Dinner;
				$Gross_Price_Dinner = ( $v->mealplan > 0 ) ? $v->people_num * $v->mealplan : "0.0";
				$arr['Gross_Price_Dinner'] = numberformat ( $Gross_Price_Dinner );
				
				$arr['Grand_Total_Amount'] = numberformat ( $total );
				
				$dataS[] = $arr;
			}
		}
		echo json_encode( $dataS );
	}
	
	function numberformat( $num = 0 ) {
		return number_format( $num, 2, ",",".");
	}
	
	function printJsonError( $status ){
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');
		if ( $status != '' && $status != 200 ) {
			echo json_encode( array('status' => $status ) );
			exit;
		}
	}
	//End lchung
require_once dirname(__FILE__).'/html/airline/default.php';
