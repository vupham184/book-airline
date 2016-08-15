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

require_once dirname(__FILE__).'/helpers/availability.php';

$period 	= availabilityReportHelper::getVar(JRequest::getVar('period'),$config);
if( $period != '' ) {
	$start_period 	= $end_period = $period;
} 
else {
	$start_period 	= availabilityReportHelper::getVar(JRequest::getVar('start_period'),$config);
	$end_period 	= availabilityReportHelper::getVar(JRequest::getVar('end_period'),$config);	
	
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

if( ! $end_period ) $end_period = $start_period;

$airline = availabilityReportHelper::getAirline( $user->id );
$currency_code = availabilityReportHelper::getCurrencyCode( $airline->airport_id );

// Gets Data
$dates		 = availabilityReportHelper::getDates( $start_period, $end_period );
$inventories = availabilityReportHelper::getInventories( $start_period, $end_period );
$sdRooms	 = availabilityReportHelper::getTotalRoomsByDates($inventories);
$tRooms		 = availabilityReportHelper::getTotalRoomsByDates($inventories,'T');

$avgSDRooms  = availabilityReportHelper::getAvgRateByDates($inventories);
$avgTRooms  = availabilityReportHelper::getAvgRateByDates($inventories);

if ( empty( $inventories ) && strtolower( $format ) == 'json' ) {
	$status = '204';
	printJsonError( $status );
}

$exportexcel = JRequest::getVar('exportexcel');

if ( strtolower( $format ) == "json" ) {
	exportJson( $dates, $airline, $avgSDRooms, $sdRooms, $tRooms, $avgTRooms, $inventories );
	exit;
}

if ( $exportexcel == 1 ) {
	exportexcel( $dates, $airline, $avgSDRooms, $sdRooms, $tRooms, $avgTRooms, $inventories );
}
function exportexcel( $dates, $airline, $avgSDRooms, $sdRooms, $tRooms, $avgTRooms, $inventories )
{
	
	require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel.php';
	require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel/Worksheet/Drawing.php';
	$objPHPExcel = new PHPExcel();
	
	$headerStyleArrayTop = array(
	  'fill' => array(
		'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
		'color'	=> array('argb' => 'FFE5E5E5')
	  ),
	  'font'    => array(
			'color'     => array(
				'rgb' => '000000'
			 )
		),
	  'borders' => array(
		'allborders' => array(
		  'style' => PHPExcel_Style_Border::BORDER_THIN
		)
	  )
	);
	
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
	
		
	//headers
	$column = 3;
	$row = 1;
	
	$count_date = count( $dates ) + $column;
	$count_inventories = count( $inventories );
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $row, 'Availability report for: ' . $airline->name , false);
	
	$row = 2;
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $row, 'Date', false);
	$objPHPExcel->getActiveSheet()->getColumnDimension( 'A' )->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension( 'B' )->setWidth(13);
	$objPHPExcel->getActiveSheet()->getColumnDimension( 'C' )->setWidth(25);
	
	foreach ( $dates as $vk => $date ) {
		//print_r( $v );die;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($column, $row, $date,false);
			$arr_headers = getHexcel( 26, $count_date );
			$c = $arr_headers[$column];
			
		$objPHPExcel->getActiveSheet()->getColumnDimension( $c )->setWidth(12);
		
		//Row 2 SD RATE
		$row = +1;
		$v = '';		
		if( isset($avgSDRooms[$date])){
			$v = $avgSDRooms[$date];
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, 2, $v, false);
		//End row 2 SD RATE
		
		//SD ROOM
		$row = +1;
		$v = 'T';		
		if( isset($sdRooms[$date])){
			$v = $sdRooms[$date];
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $count_inventories + 4, $v, false);
		//End SD ROOM
		
		//T ROOM
		$row = +1;
		$v = '';		
		if( isset($avgTRooms[$date])){
			$v = $avgTRooms[$date];
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $count_inventories+$count_inventories+6, $v, false);
		//End T RATE
		
		//T RATE
		$row = +1;
		$v = '';		
		if( isset($tRooms[$date])){
			$v = $tRooms[$date];
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $count_inventories+$count_inventories+$count_inventories+8, $v, false);
		//End T ROOM
		
		$column++;	
	}
	//List SD RATE
	$row++;
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $row, 'Total market', false);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $row, 'Ring', false);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $row, 'Avg Rate SD', false);
	// set header table cell border
	for ( $i = 0;  $i <= $count_date; $i++ ) {
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->applyFromArray($headerStyleArrayTop);	
	}
	
	$row++;	
	foreach ($inventories as $inventory) {
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $row, htmlspecialchars($inventory->name, ENT_COMPAT, 'UTF-8'), false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $row, $inventory->ring, false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $row, 'Rate SD', false);
		$column = 3;
		foreach ($dates as $date){
			$v = '';
			if( isset( $inventory->dates[$date] ) && $inventory->dates[$date]->sd_room_rate > 0  )
			{					
				$v = $inventory->dates[$date]->sd_room_rate;
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $row, $v, false);
			$column++;
		}
            
		$row++;
	}
	//End list SD RATE
	
	
	
	//List SD ROOM
	$row++;
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $row, 'Total market', false);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $row, 'Ring', false);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $row, 'Total number of rooms SD', false);
	// set header table cell border
	for ( $i = 0;  $i <= $count_date; $i++ ) {
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->applyFromArray($headerStyleArrayTop);	
	}
	
	$row++;	
	foreach ($inventories as $inventory) {
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $row, htmlspecialchars($inventory->name, ENT_COMPAT, 'UTF-8'), false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $row, $inventory->ring, false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $row, 'Number of rooms SD', false);
		$column = 3;
		foreach ($dates as $date){
			$v = '';
			
			if( isset( $inventory->dates[$date] ) )
			{					
				$v = $inventory->dates[$date]->sd_room_total + $inventory->dates[$date]->booked_sdroom;
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $row, $v, false);
			$column++;
		}
            
		$row++;
	}
	//End list SD ROOM
	
	
	//List T RATE
	$row++;
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $row, 'Total market', false);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $row, 'Ring', false);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $row, 'Avg Rate T', false);
	// set header table cell border
	for ( $i = 0;  $i <= $count_date; $i++ ) {
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->applyFromArray($headerStyleArrayTop);	
	}
	
	$row++;	
	foreach ($inventories as $inventory) {
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $row, htmlspecialchars($inventory->name, ENT_COMPAT, 'UTF-8'), false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $row, $inventory->ring, false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $row, 'Rate T', false);
		$column = 3;
		foreach ($dates as $date){
			$v = '';
			
			if( isset( $inventory->dates[$date] ) )
			{					
				$v = $inventory->dates[$date]->t_room_rate;
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $row, $v, false);
			$column++;
		}
            
		$row++;
	}
	//End list T RATE
	
	//List T ROOM
	$row++;
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $row, 'Total market', false);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $row, 'Ring', false);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $row, 'Total number of rooms T', false);
	// set header table cell border
	for ( $i = 0;  $i <= $count_date; $i++ ) {
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->applyFromArray($headerStyleArrayTop);	
	}
	
	$row++;	
	foreach ($inventories as $inventory) {
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $row, htmlspecialchars($inventory->name, ENT_COMPAT, 'UTF-8'), false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $row, $inventory->ring, false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $row, 'Number of rooms T', false);
		$column = 3;
		foreach ($dates as $date){
			$v = '';
			
			if( isset( $inventory->dates[$date] ) )
			{					
				$v = $inventory->dates[$date]->t_room_total + $inventory->dates[$date]->booked_troom;
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $row, $v, false);
			$column++;
		}
            
		$row++;
	}
	//End list T ROOM
	
	
	ob_start(); 
	$fileName = 'availability-report-' . date('ymd-His');
	// Save Excel 2007 file
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

function getHexcel( $begin = 26, $number = 26 )
{
	$headers = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$a = array();
	$ii = -1;
	$iii = -1;
	$iiii = -1;
	$a = array();
	$n = $number - 26;
	if( $n <= 0)
		return $headers;
	for( $i = 0; $i < $n ; $i++){
		$ij = 0;
		for( $j = 0; $j<26 ; $j++){
			
			if(  $i > 25 && $i < 52  ) {
				$a[$i.$headers[$j]] = $headers[$ii].$headers[$ii].$headers[$j];
			}
			elseif( $i >= 51 && $i < 78 ) {
				$a[$i.$headers[$j]] = $headers[$iii].$headers[$iii].$headers[$iii].$headers[$j];
			}
			elseif( $i >= 77 && $i < 104) {
				$a[$i.$headers[$j]] = $headers[$iiii].$headers[$iiii].$headers[$iiii].$headers[$iiii].$headers[$j];
			}
			elseif(  $i <= 25 ) {
				$a[$i.$headers[$j]] = $headers[$i].$headers[$j];
			}
		}
		if( $i >= 25 )	
			$ii ++;
		if( $i >= 51 )	
			$iii ++;
		if( $i >= 77 )	
			$iiii ++;
	}
	return array_values( array_merge($headers, $a ) );
}

function exportJson( $dates, $airline, $avgSDRooms, $sdRooms, $tRooms, $avgTRooms, $inventories ){
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');
		$data1 = array('Total_market');
		$data1 = array('Ring');
		$data1 = array('Avg_Rate_SD');
		$dataArr = array();
		
		//Begin Avg Rate SD
		$Avg_Rate_SDArr = array();
		$Avg_Rate_SDArr[] = 'Total market';
		$Avg_Rate_SDArr[] = 'Ring';
		$Avg_Rate_SDArr[] = 'Avg Rate SD';
		foreach ($dates as $date){			
			if( isset($avgSDRooms[$date])){
				$Avg_Rate_SDArr[]= array( 'date' => $date, 'val' => numberformat( $avgSDRooms[$date] ) );
			}
		}
		$dataArr[]['Avg_Rate_SD'] = $Avg_Rate_SDArr;
		foreach ($inventories as $inventory) :
			$data = array("name" => $inventory->name, 'ring' => $inventory->ring, 'Rate_SD' => 'Rate SD');
           	foreach ($dates as $date) : 
				$data['sd_room_rate'][] = numberformat( $inventory->dates[$date]->sd_room_rate );
			endforeach;
			$dataArr[] = $data;
        endforeach;		
		//End Avg Rate SD
		
		//Begin Total number of rooms SD
		$Total_number_of_rooms_SDArr = array();
		$Total_number_of_rooms_SDArr[] = 'Total market';
		$Total_number_of_rooms_SDArr[] = 'Ring';
		$Total_number_of_rooms_SDArr[] = 'Total number of rooms SD';
		foreach ($dates as $date){			
			if( isset($avgSDRooms[$date])){
				$Total_number_of_rooms_SDArr[]= array( 'date' => $date, 'val' => numberformat( $sdRooms[$date] ) );
			}
		}
		$dataArr[]['Total_number_of_rooms_SD'] = $Total_number_of_rooms_SDArr;
		foreach ($inventories as $inventory) :
			$data = array("name" => $inventory->name, 'ring' => $inventory->ring, 'Number_of_rooms_SD' => 'Number of rooms SD');
           	foreach ($dates as $date) : 
				$data['rooms_sd'][] = numberformat( $inventory->dates[$date]->sd_room_total + $inventory->dates[$date]->booked_sdroom );
			endforeach;
			$dataArr[] = $data;
        endforeach;		
		//End Total number of rooms SD
		
		//Begin Avg Rate T
		$Avg_Rate_TArr = array();
		$Avg_Rate_TArr[] = 'Total market';
		$Avg_Rate_TArr[] = 'Ring';
		$Avg_Rate_TArr[] = 'Avg Rate T';
		foreach ($dates as $date){			
			if( isset($avgTRooms[$date])){
				$Avg_Rate_TArr[]= array( 'date' => $date, 'val' => numberformat( $avgTRooms[$date] ) );
			}
		}
		$dataArr[]['Avg_Rate_T'] = $Avg_Rate_TArr;
		foreach ($inventories as $inventory) :
			$data = array("name" => $inventory->name, 'ring' => $inventory->ring, 'Rate_T' => 'Rate T');
           	foreach ($dates as $date) : 
				$data['t_room_rate'][] = numberformat( $inventory->dates[$date]->t_room_rate );
			endforeach;
			$dataArr[] = $data;
        endforeach;		
		//End Avg Rate T
		
		//Begin Total number of rooms T
		$Total_number_of_rooms_TArr = array();
		$Total_number_of_rooms_TArr[] = 'Total market';
		$Total_number_of_rooms_TArr[] = 'Ring';
		$Total_number_of_rooms_TArr[] = 'Total number of rooms T';
		foreach ($dates as $date){			
			if( isset($avgTRooms[$date])){
				$Total_number_of_rooms_TArr[]= array( 'date' => $date, 'val' => numberformat( $avgTRooms[$date] ) );
			}
		}
		$dataArr[]['Total_number_of_rooms_T'] = $Total_number_of_rooms_TArr;
		foreach ($inventories as $inventory) :
			$data = array("name" => $inventory->name, 'ring' => $inventory->ring, 'Number_of_rooms_T' => 'Number of rooms T');
           	foreach ($dates as $date) : 
				$data['rooms_t'][] = numberformat( $inventory->dates[$date]->t_room_total + $inventory->dates[$date]->booked_troom );
			endforeach;
			$dataArr[] = $data;
        endforeach;		
		//End Total number of rooms T
		
		echo json_encode( $dataArr );
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

function numberformat( $num = 0 ) {
	return number_format( $num, 2, ",",".");
}

require_once dirname(__FILE__).'/html/availability/default.php';
