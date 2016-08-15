<?php
error_reporting(0);
ini_set('display_errors', 0);
include_once("Model.php");
require_once 'libs/Mobile_Detect/yagendooismobile.php';
$db = new Model();

$db->DBname = 'sfs_dev7';
$db->User = 'root';
$db->Password = '123456';

// $db->DBname = 'sfs_dev3';
// $db->User = 'sfs_dev3';
// $db->Password = 'JgiSqTMH2P';

/*
$db->DBname = 'sfs_dev8';
$db->User = 'root';
$db->Password = '';
*/
$code = '';
$content = new stdClass();
$content->firstname = '';
$content->lastname = '';
$content->flight_number = '';
$content->pnr = '';
$content->code = '';
$content->dep = '';
$content->arr = '';
$content->std = '';
$content->etd = '';
$content->passenger_id = 0;

if( isset( $_GET['code'] ) && $_GET['code'] != '' ) {
	$code = $_GET['code'];
	$myUrl = explode("mobile", $_SERVER['PHP_SELF'] ); 
	$myUrl = $myUrl[0];
	$rootD = $_SERVER['DOCUMENT_ROOT'] . $myUrl;
	$pathFile = $rootD . 'tmp/mobile/' . $code . '.log';
	if( file_exists( $pathFile ) ) {
		$contentSub = file_get_contents( $pathFile );
		if( $contentSub != '' ) 
			$content = json_decode( $contentSub );
	}
}
//print_r($content);die;
$TMPcontent = new stdClass();
$TMPcontent->logo_header_mobile 		= '';
$TMPcontent->logo_voucher_mobile 		= '';
$TMPcontent->logo_creditcard_mobile 	= '';
$TMPcontent->mobile_color_MB 			= '';
$TMPcontent->mobile_color_MT 			= '';
$TMPcontent->mobile_color_MVB 			= '';
$TMPcontent->mobile_color_MVT 			= '';
$TMPcontent->mobile_color_MBB 			= '';
$tmp_id = '';
$cssCustom = '';
if( isset( $_GET['tmp'] ) && $_GET['tmp'] != '' ) {
	$tmp_id = $_GET['tmp'];
	$code .= '&tmp=' . $tmp_id;
	$myUrl = explode("mobile", $_SERVER['PHP_SELF'] ); 
	$myUrl = $myUrl[0];
	$rootD = $_SERVER['DOCUMENT_ROOT'] . $myUrl;
	$pathFile = $rootD . 'tmp/mobile/templates/' . $tmp_id . '.log';
	if( file_exists( $pathFile ) ) {
		$TMPcontentSub = file_get_contents( $pathFile );
		if( $TMPcontentSub != '' ) {
			$TMPcontent = json_decode( $TMPcontentSub );
			$cssCustom .= '.header-wrapper{background-color:' . $TMPcontent->mobile_color_MB . '}';
			
			$cssCustom .= '
			.pre-page, .pre-page .btn-label, 
			.common-site-wrap .site-content .section-2 .section-wrap .title-section{
				background-color:' . $TMPcontent->mobile_color_MVB . '
			}';
			
			$cssCustom .= '
			.common-site-wrap .site-content .section-2 .section-wrap .voucher-detail,
			.common-site-wrap .site-content .section-2 .section-wrap .billing-info{
				border-color:' . $TMPcontent->mobile_color_MVB . '
			}';
			
			
			$cssCustom .= '
			.pre-page, .pre-page .btn-label,
			.pre-page .btn-pre, 
			.common-site-wrap .site-content .section-2 .section-wrap .title-section{
				color: ' . $TMPcontent->mobile_color_MVT . '
			}';
			
			$cssCustom .= '.common-site-wrap .site-content .section-2 .section-wrap .billing-info{background-color:' . $TMPcontent->mobile_color_MBB . '}';
			
		}
	}
}

$cssCustom .= '.obj-service{ display:none; }';
$d = $db->getServicesOfPassenger( $content->passenger_id );
$dataService = array();
foreach( $d as $v ){
	$dataService[$v->id] = $v;
	$cssCustom .= '.obj-service.obj' . $v->id . '{ display:block; }';
}
$dhotel = new stdClass();
$dhotel->id = 0;
$dhotel->name = '';
$dhotel->address = '';
$dhotel->telephone = '';
$dhotel->code = '';
$dhotel->blockcode = '';
$dhotelArr = $db->getHotelOfPassenger( $content->passenger_id );
if( !empty( $dhotelArr ) ){
	$dhotel = (object)$dhotelArr;
}

$dairport = new stdClass();
$dairport->name = '';
$dairportArr = $db->getHotelAirports( $dhotel->id );
if( !empty( $dairportArr ) ){
	$dairport = (object)$dairportArr;
}

$dmealplan_refreshment = new stdClass();
$dmealplan_refreshment->valamount = 0;
$dmealplan_refreshment->currency = 'EUR';
$dmealplan_refreshment->delaytime = 0;

$mealplan_refreshmentArr = $db->getMealplanRefreshmentsOfPassenger( $content->passenger_id );
if( !empty( $mealplan_refreshmentArr ) ){
	$dmealplan_refreshment = (object)$mealplan_refreshmentArr;
}

$group_transport = new stdClass();
$group_transport->date_expire_time = '';
$group_transport->comment = '';
$group_transport->g_name = '';
$group_transport->from_airport_name = '';
$group_transport->to_hotel_name = '';
$group_transport->airline_airport_id = 0;
$group_transport->airport_id = 0;

$group_transportArr = $db->getGroupTransportOfPassenger( $content->passenger_id );
if( !empty( $group_transportArr ) ){
	$group_transport = (object)$group_transportArr;
}
$from_airportArr = $db->getAirport( $group_transport->airline_airport_id );
if( !empty( $from_airportArr ) ){
	$from_airport = (object)$from_airportArr;
	$group_transport->from_airport_name  = $from_airport->name;
}

$to_hotel_airportArr = $db->getHotelAirportGroup( $group_transport->airport_id );
if( !empty( $to_hotel_airportArr ) ){
	$to_hotel_airport = (object)$to_hotel_airportArr;
	$group_transport->to_hotel_name  = $to_hotel_airport->name;
}

$comment = 'View: ' . date('d/m/Y H:i:s') . ' ';

$PHP_SELF = str_replace('.php', "", str_replace('/mobile/', "", $_SERVER['PHP_SELF'] ) );
if( $PHP_SELF == 'index' ){
	$PHP_SELF = 'home';
}
$comment .= '/' . $PHP_SELF . '/ ';


$UserAgent = MobileDetector::getUserAgent();
$UserAgent = explode(") ", $UserAgent);
$comment .= $UserAgent[0] . ')';
$db->getInternalComment($content->passenger_id, $comment, $tmp_id);


$dataRental = $db->getDataRentalCar($content->passenger_id);

//echo date("d-M-Y H:i", strtotime( $group_transport->date_expire_time));
//print_r( $group_transport->to_hotel_name );die;
?>