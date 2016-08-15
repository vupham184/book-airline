<?php
// no direct access
defined('_JEXEC') or die;

class SfsControllerTaxivouchers extends JControllerLegacy
{	
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function filter()
	{
		$post['blockcode']  = JRequest::getVar('blockcode');
		$post['taxi_id']  	= JRequest::getVar('taxi_id');
		$post['date_from']	= JRequest::getVar('date_from');
		$post['date_to']	= JRequest::getVar('date_to');		
		
				
		// set Itemid id for links from menu
		$app	= JFactory::getApplication();
		$menu	= $app->getMenu();
		$items	= $menu->getItems('link', 'index.php?option=com_sfs&view=taxivouchers');

		if(isset($items[0])) {
			$post['Itemid'] = $items[0]->id;
		} else if (JRequest::getInt('Itemid') > 0) { //use Itemid from requesting page only if there is no existing menu
			$post['Itemid'] = JRequest::getInt('Itemid');
		}
		unset($post['task']);
		unset($post['submit']);

		$uri = JURI::getInstance();
		$uri->setQuery($post);
		
		$uri->setVar('option', 'com_sfs');
		$uri->setVar('view', 'taxivouchers');
				
		$this->setRedirect(JRoute::_('index.php'.$uri->toString(array('query', 'fragment')), false),$msg);
	}
	
	public function export()
	{	
		$airline = SFactory::getAirline();		
		$user    = JFactory::getUser();
		$db		 = JFactory::getDbo();
		
		// Check if the user is Airline Admin
		if ( ! SFSAccess::check($user, 'a.admin') ) {
			return false;
		}
		
		require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel.php';
		require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel/Worksheet/Drawing.php';	
		
		$objPHPExcel 	= new PHPExcel();	
		
		
		$reservation_id = JRequest::getInt('reservation_id');

		$query = $db->getQuery(true);
		
		$query->select('a.*');
		$query->from('#__sfs_reservations AS a');
		
		$query->where('a.id = '.(int)$reservation_id);
		$query->where('a.airline_id = '.(int)$airline->id);
		
		$db->setQuery($query);
		$reservation = $db->loadObject();
		
		if( ! $reservation ) {
			JFactory::getApplication()->close();
		}
		
		$query->clear();		
		$query->select('DISTINCT a.taxi_id, b.name');	
		$query->from('#__sfs_taxi_vouchers AS a');
		$query->innerJoin('#__sfs_taxi_companies AS b ON b.id=a.taxi_id');
		$db->setQuery($query);
		
		$companies = $db->loadObjectList();
		
		$taxiCompanyText = '';

		if(count($companies))
		{
			$nameArray = array();
			foreach ($companies as $company)
			{
				$nameArray[] = $company->name;
			}
			$taxiCompanyText = implode(', ', $nameArray);
		}
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator($user->get('name'))
		->setLastModifiedBy($user->get('name'))
		->setTitle('Taxi Vouchers Exporting')
		->setSubject('Taxi Vouchers Exporting')
		->setDescription('Taxi Vouchers Exporting')
		->setKeywords("sfs airline hotel exporting")
		->setCategory("Sfs Taxi Vouchers Exporting");
		
		$row = 0;
		$headers = array('Flightnumber','First Name','Last Name','Company','Voucher Number','Number of persons','Transport from to','Rate','Transport from to','Rate','Total costs');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
		//$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
				
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, ++$row, 'SFS-Web Roominglist',false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, ++$row, 'Blockcode',false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $row, $reservation->blockcode,false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, ++$row, 'Company',false);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $row, $taxiCompanyText,false);
		
		$row++;$row++;
		$col = 0;
		
		foreach ($headers as $text)
		{
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, $text,false);
			$col++;				
		}
		
		$query->clear();		
		$query->select('a.*, c.code AS voucher_number, c.seats,t.name AS taxi_name,b.voucher_id');
		$query->from('#__sfs_taxi_vouchers AS a');
		
		$query->innerJoin('#__sfs_airline_taxi_voucher_map AS b ON b.taxi_voucher_id=a.id');
		$query->innerJoin('#__sfs_voucher_codes AS c ON c.id=b.voucher_id');
		
		$query->innerJoin('#__sfs_taxi_companies AS t ON t.id=a.taxi_id');	
		
		$query->where('a.booking_id = '.(int)$reservation_id);
		
		$db->setQuery($query);
		
		$vouchers = $db->loadObjectList();
		
		$query->clear();				
		$query->select('a.*');
		$query->from('#__sfs_passengers AS a');
		$query->innerJoin('#__sfs_voucher_codes AS b ON b.id=a.voucher_id');
		$query->where('b.booking_id='.(int)$reservation_id);
		
		$db->setQuery($query);
		
		$passengers = $db->loadObjectList();
		
		
		foreach ($vouchers as $voucher)
		{
			$row++;
			$col = -1;		
			$firstname = '';
			$lastname = '';
			
				
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $voucher->flight_number,false);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $firstname,false);
				
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $lastname,false);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $voucher->taxi_name,false);
				
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $voucher->voucher_number,false);

			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $voucher->seats,false);

			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, 'airp - hotel',false);					

			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $voucher->rate,false);
				
			$totalRates = $voucher->rate;
			if( (int)$voucher->is_return == 1 ) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, 'hotel - airport',false);				
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $voucher->rate,false);
				$totalRates += 	$voucher->rate;					
			} else {				
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, '',false);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, '',false);
			}

			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $totalRates,false);
		}
		
		// Download
		$fileName = 'Taxi_Vouchers_'.$reservation->blockcode.'.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		
		JFactory::getApplication()->close();		
	}
	
}


