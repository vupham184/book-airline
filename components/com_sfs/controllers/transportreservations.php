<?php
defined('_JEXEC') or die;

class SfsControllerTransportreservations extends JControllerLegacy
{	
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function filter()
	{
		$post['reference_number']  = JRequest::getVar('reference_number');
		$post['date_from']	= JRequest::getVar('date_from');
		$post['date_to']	= JRequest::getVar('date_to');		
		
				
		// set Itemid id for links from menu
		$app	= JFactory::getApplication();
		$menu	= $app->getMenu();
		$items	= $menu->getItems('link', 'index.php?option=com_sfs&view=transportreservations');

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
		$uri->setVar('view', 'transportreservations');
				
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
		
		$id = JRequest::getInt('id');
		
		$query = $db->getQuery(true);
				
		$query->select('a.*,b.name AS company,b.telephone,c.name AS departure_name');	
		$query->from('#__sfs_transportation_reservations AS a');
		
		$query->innerJoin('#__sfs_group_transportations AS b ON b.id=a.transport_company_id');
		$query->innerJoin('#__sfs_iatacodes AS c ON c.id=a.departure');
		
		
		$query->where('a.id='.$id);		
		$db->setQuery($query);
		
		$reservations = $db->loadObjectList();
		
		
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator($user->get('name'))
		->setLastModifiedBy($user->get('name'))
		->setTitle('Group Transportation Exporting')
		->setSubject('Group Transportation Exporting')
		->setDescription('Group Transportation Exporting')
		->setKeywords("sfs airline hotel exporting")
		->setCategory("Sfs Group Transportation Exporting");
		
		$row = 0;
		$headers = array(
			'Date','Reference number','Flight number','Departure','Requested Time','Persons','Rate','Total charges','Comment'
		);
				
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
		//$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
								
		//$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, ++$row, 'Company',false);
		//$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $row, $result->company,false);
		
		
		$row++;$row++;
		$col = 0;
		
		foreach ($headers as $text)
		{
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, $text,false);
			$col++;				
		}
		
		foreach ($reservations as $reservation)
		{
			$row++;
			$col = -1;		
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, SfsHelperDate::getDate($reservation->booked_date,'d/m/Y'),false);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $reservation->reference_number,false);
				
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $reservation->flight_number,false);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $reservation->departure_name,false);
			
			if($reservation->requested_time=='0')
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, 'ASAP',false);	
			} else {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $reservation->requested_time,false);
			}
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $reservation->total_passengers,false);
				
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $reservation->rate,false);

			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $reservation->rate,false);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(++$col, $row, $reservation->comment,false);

		}
		
		// Download
		$fileName = 'Group_Transportation.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		
		JFactory::getApplication()->close();		
	}
	
}


