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
require_once JPATH_SITE.'/components/com_sfs/libraries/reservation.php';

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

if ( $uk )
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

    $allow = true;

    $db = JFactory::getDbo();
    $db->setQuery('SELECT airline_id FROM #__sfs_airline_user_map WHERE user_id='.(int)$user->id);
    $airline_id = $db->loadResult();

	if( in_array(8, $groups) || in_array(7, $groups) )
	{
        // Super admin -> show all airline
        $airline_id = 0;
	}
}

if( ! $allow )
{	
	$uriRoot = JURI::root();
	$uriRoot = str_ireplace('report/', '', $uriRoot);
	?>
	<a href="<?php echo $uriRoot?>index.php?option=com_users&view=login" target="_blank">Login</a> or Secret Key
	<?php 
	return;
}

require_once dirname(__FILE__).'/helpers/booking.php';

$period 	= bookingReportHelper::getVar(JRequest::getVar('period'),$config);
if( $period != '' ) {
	$start_period 	= $end_period = $period;
} 
else {
	$start_period 	= bookingReportHelper::getVar(JRequest::getVar('start_period'),$config);
	$end_period 	= bookingReportHelper::getVar(JRequest::getVar('end_period'),$config);	
	
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


$airline = bookingReportHelper::getAirline( $user->id );
$currency_code = bookingReportHelper::getCurrencyCode( $airline->airport_id );

// Gets Data
$dates		 = bookingReportHelper::getDates( $start_period, $end_period );
$reservations = bookingReportHelper::getReservations( $start_period, $end_period, $airline_id);

if ( empty( $reservations ) && strtolower( $format ) == 'json' ) {
	$status = '204';
	printJsonError( $status );
}

$exportexcel = JRequest::getVar('exportexcel');

if ( strtolower( $format ) == "json" ) {
	exportJson( $dates, $airline, $reservations );
	exit;
}

if ( $exportexcel == 1 ) {
	exportexcel( $dates, $airline, $reservations );
}
function exportexcel( $dates, $airline, $reservations )
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
	$column = 0;
	$row = 1;
	$count_reservations = count( $reservations ) + 2;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $row, 'Booking report for: ' . $airline->name, false);
	$row++;
	for ( $i = 0;  $i <= 13; $i++ ) {
		if( $i < 12 )
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->applyFromArray($headerStyleArray);	
		elseif( $i >= 12 )
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->applyFromArray($headerStyleArray3);	
	}
	
	$objPHPExcel->getActiveSheet()->getStyle("J2:J" . $count_reservations )->applyFromArray($headerStyleArray4);
	$objPHPExcel->getActiveSheet()->getStyle("M2:M" . $count_reservations )->applyFromArray($headerStyleArray4);
	$objPHPExcel->getActiveSheet()->getStyle("N2:N" . $count_reservations )->applyFromArray($headerStyleArray4);
	
	$headerArr[0] = 'Date';
	$headerArr[1] = 'WS or Partner';
	$headerArr[2] = 'Airportcode';
	$headerArr[3] = 'Airline name';
	$headerArr[4] = 'Airline code';
	$headerArr[5] = 'Date and time of booking';
	$headerArr[6] = 'Hotel name';
	$headerArr[7] = 'Type of room';
	$headerArr[8] = 'F&B';
	$headerArr[9] = 'Price per room';
	$headerArr[10] = 'Number of rooms';
	$headerArr[11] = 'How many persons';
	$headerArr[12] = 'Total sum price of the booked rooms';
	$headerArr[13] = 'Total sum price of the booked F&B';
	
	foreach ( $headerArr as $vk => $v ) {
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($vk, $row, $v,false);
			$arr_headers = getHexcel( 26, 26 );
			$c = $arr_headers[$vk];
			
		$objPHPExcel->getActiveSheet()->getColumnDimension( $c )->setWidth(18);
		
		$column++;
	}
	$objPHPExcel->getActiveSheet()->getColumnDimension( "G" )->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension( "N" )->setWidth(28);
		
	$row++;
    $temp_date = '';
    foreach ($reservations as $value) {
		
            $str = unserialize($value->ws_room_type);
            if ($temp_date != $value->blockdate)
            {
                $temp_date = $value->blockdate;
            }

            $newDate = date("m-d-Y", strtotime($value->blockdate));

            $is_ws = 'WS';
            if ($value->ws_room_type == '')
            {
                $is_ws = 'Partner';
            }

        $value->booked_date = date("m-d-Y h:s", strtotime($value->booked_date));
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $row, $newDate, false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $row, $is_ws, false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $row, $value->airport_code, false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, $row, $value->airline_name, false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, $row, $value->airline_code, false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5, $row, $value->booked_date, false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6, $row, $value->hotel_name, false);
        $person = 0;
        $total_sum = 0;
        $number_room = 0;
        $mealplan = 0;
        $per_room = array();
        $FB = array();
		
		$t = '';
        if (is_array($str))
        {
        
		 foreach ($str as $roomType)
            {
                $var = $roomType['roomType'];
                $var = unserialize(base64_decode($var));
                $array = (array) $var;
                $name = $array[Name];
                $FB[] =  $array[MealBasisName];
                $price = $array[Total];
                $per_room[] = $price;
                $number = $array[NumberOfRooms];
                $total_sum += (int) $number * (int) $price ;
                $number_room += $number;
                $person += (int) $array[NumAdultsPerRoom] + (int) $array[NumChildrenPerRoom] + (int) $array[NumInfantsPerRoom];
				
				$t = $name;
            }
        } // Not ws
        else
        {
				 if ($value->sd_room){
                    $person += 2 * $value->sd_room;
                    $number_room += $value->sd_room;
                    $total_sum += $value->sd_room * $value->sd_rate ;
                    $per_room[] = $value->sd_rate;
					$t = ' S/D Room: ';
               	} 
				 if ($value->t_room){
                    $person += 3 * $value->t_room;
                    $number_room += $value->t_room;
                    $total_sum += $value->t_room * $value->t_rate ;
                    $per_room[] = $value->t_rate;
					$t = ' T Room: ';
                } 
				
				if ($value->q_room){
                    $person += 4 * $value->q_room;
                    $number_room += $value->q_room;
                    $total_sum += $value->q_room * $value->q_rate ;
                    $per_room[] = $value->q_rate;
					$t = ' Q Room: ';
                                   } 
				if ($value->s_room){
                    $person += 1 * $value->s_room;
                    $number_room += $value->s_room;
                    $total_sum += $value->s_room * $value->s_rate ;
                    $per_room[] = $value->s_rate;
				$t = ' S Room: ';	
				}
        }
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7, $row, $t, false);
		$t8 = '';
		if (is_array($FB)) {
			foreach ($FB as $FB_room) {
				$t8 = $FB_room . ' ';
			}
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8, $row, $t8, false);
		$t9 = '';
		foreach($per_room as $price_room)
		{
			$t9 = '€'.$price_room.' ';
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(9, $row, $t9, false);
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(10, $row, $number_room, false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(11, $row, $person, false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(12, $row, '€'.$total_sum, false);
		
		$t13 = '';
		
		if ($value->breakfast >0){
			$mealplan += $value->breakfast;
			$t13 .= 'Breakfast, ';
		}
		if ($value->lunch >0) {
			$mealplan += $value->lunch;
			$t13 .= 'Lunch, ';
		}
		if ($value->mealplan >0) {
			$mealplan += $value->mealplan;
			$t13 .= 'Dinner ('.$value->course_type.')';
		}
		$mealplan = $mealplan * $person;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(13, $row, $t13 . '€'.$mealplan, false);
     
    $row++;
    }
	
	
	//End list T ROOM
	
	
	ob_start(); 
	$fileName = 'booking-report-' . date('ymd-His');
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

function exportJson( $dates, $airline, $reservations ){
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	$dataS = array();
	if(count($reservations)) {		
		foreach ($reservations as $value) {
			$arr = array();
            $str = unserialize($value->ws_room_type);
            if ($temp_date != $value->blockdate)
            {
                $temp_date = $value->blockdate;
            }

            $newDate = date("m-d-Y", strtotime($value->blockdate));

            $is_ws = 'WS';
            if ($value->ws_room_type == '')
            {
                $is_ws = 'Partner';
            }
			
			$value->booked_date = date("m-d-Y h:s", strtotime($value->booked_date));
			$person = 0;
			$total_sum = 0;
			$number_room = 0;
			$mealplan = 0;
			$per_room = array();
			$FB = array();
			
			$t = '';
			if (is_array($str))
			{
			 foreach ($str as $roomType)
				{
					$var = $roomType['roomType'];
					$var = unserialize(base64_decode($var));
					$array = (array) $var;
					$name = $array['Name'];
					$FB[] =  $array['MealBasisName'];
					$price = $array['Total'];
					$per_room[] = $price;
					$number = $array['NumberOfRooms'];
					$total_sum += (int) $number * (int) $price ;
					$number_room += $number;
					$person += (int) $array['NumAdultsPerRoom'] + (int) $array['NumChildrenPerRoom'] + (int) $array['NumInfantsPerRoom'];
					
					$t = $name;
				}
			} // Not ws
			else
			{
				 if ($value->sd_room){
					$person += 2 * $value->sd_room;
					$number_room += $value->sd_room;
					$total_sum += $value->sd_room * $value->sd_rate ;
					$per_room[] = $value->sd_rate;
					$t = ' S/D Room: ';
				} 
				 if ($value->t_room){
					$person += 3 * $value->t_room;
					$number_room += $value->t_room;
					$total_sum += $value->t_room * $value->t_rate ;
					$per_room[] = $value->t_rate;
					$t = ' T Room: ';
				} 
				
				if ($value->q_room){
					$person += 4 * $value->q_room;
					$number_room += $value->q_room;
					$total_sum += $value->q_room * $value->q_rate ;
					$per_room[] = $value->q_rate;
					$t = ' Q Room: ';
								   } 
				if ($value->s_room){
					$person += 1 * $value->s_room;
					$number_room += $value->s_room;
					$total_sum += $value->s_room * $value->s_rate ;
					$per_room[] = $value->s_rate;
				$t = ' S Room: ';	
				}
			}
		
			$t8 = '';
			if (is_array($FB)) {
				foreach ($FB as $FB_room) {
					$t8 = $FB_room . ' ';
				}
			}

			$t9 = '';
			foreach($per_room as $price_room)
			{
				$t9 = '€'. numberformat ( $price_room );
			}
		
			$t13 = '';
			
			if ($value->breakfast >0){
				$mealplan += $value->breakfast;
				$t13 .= 'Breakfast, ';
			}
			if ($value->lunch >0) {
				$mealplan += $value->lunch;
				$t13 .= 'Lunch, ';
			}
			if ($value->mealplan >0) {
				$mealplan += $value->mealplan;
				$t13 .= 'Dinner ('.$value->course_type.')';
			}
			$mealplan = $mealplan * $person;
			
			$arr['Date'] = $newDate;
			$arr['WS_or_Partner'] = $is_ws;
			$arr['Airportcode'] = $value->airport_code;
			$arr['Airline_name'] = $value->airline_name;
			$arr['Airline_code'] = $value->airline_code;
			$arr['Date_and_time_of_booking'] = $value->booked_date;
			$arr['Hotel_name'] = $value->hotel_name;
			$arr['Type_of_room'] = $t;
			$arr['F_B'] = $t8;
			$arr['Price_per_room'] = $t9;
			$arr['Number_of_rooms'] = $number_room;
			$arr['How_many_persons'] = $person;
			$arr['Total_booked_rooms'] = '€'. numberformat ( $total_sum );
			$arr['Total_booked_F_B'] = $t13 . '€'. numberformat ( $mealplan );
			
			
			$dataS[] = $arr;
		}
	}
	echo json_encode( $dataS );
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
require_once dirname(__FILE__).'/html/booking/default.php';


