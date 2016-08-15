<?php
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel.php';
require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel/Worksheet/Drawing.php';

class SfsControllerAirlinereporting extends JController {
	
	protected $fp = null;
	protected $objPHPExcel = null;

	public function __construct($config = array())
	{
		$this->objPHPExcel = new PHPExcel();
		
		parent::__construct($config);															
	}
		
	public function getModel($name = 'Airlinereporting', $prefix = 'SfsModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function exportRoomnights()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	    	
    	$from 		= JRequest::getVar('date_from');
    	$until  	= JRequest::getVar('date_to');    
    	$statuses   = JRequest::getVar('blockStatus', array(), 'post', 'array');	
    	
    	$fileName = 'RoomnightsReport_'.$from.'_'.$until.'.xlsx';
    	
    	$currentRow = $this->setCSVHeader($from, $until, 'Total number roomnights');

    	$model = $this->getModel();
    	
    	$data = $model->exportRoomnights($from,$until,$statuses);
    	
    	    	    	
    	$headers = $model->getHeaderDates($from,$until,$statuses);

    	$currentRow += 3;
    	$column = 2;
    	

		$headerStyle= array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FF738A98')
		  ),
		  'font'    => array(
				'color'     => array(
			    	'rgb' => 'ffffff'
			     )
			),
		);		
    	foreach ($headers as $date){
    		
    		$text = JHTML::_('date',$date,'d-M-Y');
    		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, $text,false);

			$this->objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($column)->setWidth(15);
			
			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($headerStyle);
			
			$column++;	
    	}
    	
    	$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, 'Total',false);
    	
    	$currentRow++;    	
    	$column = 1;
    	
    	
    	$data2 = array();
    	
    	$totals = array();
    	
    	$oddStyle= array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'f2f2f2')
		  ),
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN,
		  	'color'	=> array('argb' => 'cccccc')
		    )
		  )
		);
		$totalStyle= array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FFe26b0a')
		  ),
		  'font'    => array(
				'color'     => array(
			    	'rgb' => 'ffffff'
			     )
			),
		);			
    	$i = 0;
    	foreach ($data as $row)
    	{   
    		if($i==1)
    		{
    			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($oddStyle);
    		} 		
    		
    		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, $row->name ,false);
    		
    		$column++;
    		
    		$totalRooms = 0;
    		    		
    		foreach ($headers as $date)
    		{    	
	    		if($i==1)
	    		{
	    			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($oddStyle);
	    		}	
    			if( ! isset($totals[$date] ) ) {
    				$totals[$date] = 0;
    			}
    			
    			if( isset($row->dates[$date]) && (int)$row->dates[$date] )
    			{
    				//$month = JString::substr($date, 0,7);    				
    				$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $currentRow, $row->dates[$date] ,false);
					$totals[$date] +=	(int)$row->dates[$date];
					$totalRooms += (int)$row->dates[$date];
    			}     			
    			$column++;    			
    		}
    		//$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($totalStyle);
    		$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $currentRow, $totalRooms ,false);
    		
    		
    		$column = 1;
    		$currentRow++;
    		
    		$i = 1 - $i ;
    		
    	}
    	
    	$currentRow++;
    	$column = 2;
    	
    	$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column-1, $currentRow, 'Total',false);
    	
		foreach ($totals as $date=>$value){    		
			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($totalStyle);
			    	
    		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, $value,false);			
			$column++;	
    	}
    	
    	
    	
    	$this->download($fileName);
    	
    	JFactory::getApplication()->close();
	}
	
	public function exportAverages()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	    	
    	$from 	= JRequest::getVar('date_from');
    	$until  = JRequest::getVar('date_to');    	
    	
    	$fileName = 'Average_'.$from.'_'.$until.'.xlsx';
    	
    	$currentRow = $this->setCSVHeader($from, $until, 'Average room prices booked');

    	$model = $this->getModel();
    	
    	$data = $model->exportAverages($from,$until);
    	    	
    	$headers = $model->getAverageHeaderDates($from,$until);

    	$currentRow += 3;
    	$column = 2;
    	

		$headerStyle= array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FF738A98')
		  ),
		  'font'    => array(
				'color'     => array(
			    	'rgb' => 'ffffff'
			     )
			),
		);
		
		foreach ($headers as $date){
    		
    		$text = JHTML::_('date',$date,'d-M-Y');
    		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, $text,false);

			$this->objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($column)->setWidth(15);
			
			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($headerStyle);
			
			$column++;	
    	}
    	
    	$oddStyle= array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'f2f2f2')
		  ),
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN,
		  	'color'	=> array('argb' => 'cccccc')
		    )
		  )
		);
		$totalStyle= array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FFe26b0a')
		  ),
		  'font'    => array(
				'color'     => array(
			    	'rgb' => 'ffffff'
			     )
			),
		);		
		
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, 'Total',false);
    	
		
		$currentRow++;    	
    	$column = 1;
		
    	
		foreach ($data as $row)
    	{   
    		if($i==1)
    		{
    			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($oddStyle);
    		} 		
    		
    		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, $row->name ,false);
    		
    		$column++;

    		$totalBlocksRow = 0;
    		$totalSdPriceRow = 0;
    		foreach ($headers as $date)
    		{    	
	    		if($i==1)
	    		{
	    			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($oddStyle);
	    		}	
    			
    			if( isset($row->dates[$date]) )
    			{
    				if( count($row->dates[$date]->sd_rate) )
    				{
    					$totalBlocks = count($row->dates[$date]->sd_rate);
    					$totalSdPrice = 0;
    					foreach ( $row->dates[$date]->sd_rate as $sdRate )
    					{
    						$totalSdPrice += floatval($sdRate);
    					}
    					$sdAdr = number_format($totalSdPrice/$totalBlocks,2); 
	    				$this->objPHPExcel->setActiveSheetIndex(0)
							->setCellValueByColumnAndRow($column, $currentRow, $sdAdr ,false);
								
						$totalBlocksRow++;	
						$totalSdPriceRow+=$sdAdr;
    				}
					
    			}       					
    			$column++;    			
    		}
    		
    		$totalSdAdr = number_format($totalSdPriceRow/$totalBlocksRow,2); 
    		
    		$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $currentRow, $totalSdAdr ,false);
    		
    		$column = 1;
    		$currentRow++;
    		
    		$i = 1 - $i ;
    		
    	}
		
		
		$this->download($fileName);

		JFactory::getApplication()->close();
	}
	
	public function exportRevenues()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	    	
    	$from 	= JRequest::getVar('date_from');
    	$until  = JRequest::getVar('date_to');    	
    	
    	$fileName = 'Revenue_Booked_'.$from.'_'.$until.'.xlsx';
    	
    	$currentRow = $this->setCSVHeader($from, $until, 'Revenue booked');

    	$model = $this->getModel();
    	
    	$data = $model->exportRevenues($from,$until);
    	
    	$headers = $model->getRevenueHeaderDates($from,$until);

    	$currentRow += 3;
    	$column = 2;
    	

		$headerStyle= array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FF738A98')
		  ),
		  'font'    => array(
				'color'     => array(
			    	'rgb' => 'ffffff'
			     )
			),
		);		
    	foreach ($headers as $date){
    		
    		$text = JHTML::_('date',$date,'d-M-Y');
    		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, $text,false);

			$this->objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($column)->setWidth(15);
			
			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($headerStyle);
			
			$column++;	
    	}
    	
    	$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, 'Total',false);
    	
    	$currentRow++;    	
    	$column = 1;
    	
    	
    	$data2 = array();
    	
    	$totals = array();
    	
    	$oddStyle= array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'f2f2f2')
		  ),
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN,
		  	'color'	=> array('argb' => 'cccccc')
		    )
		  )
		);
		$totalStyle= array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FFe26b0a')
		  ),
		  'font'    => array(
				'color'     => array(
			    	'rgb' => 'ffffff'
			     )
			),
		);			
    	$i = 0;
    	foreach ($data as $row)
    	{   
    		if($i==1)
    		{
    			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($oddStyle);
    		} 		
    		
    		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, $row->name ,false);
    		
    		$column++;
    		
    		$totalRooms = 0;
    		    		
    		foreach ($headers as $date)
    		{    	
	    		if($i==1)
	    		{
	    			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($oddStyle);
	    		}	
    			if( ! isset($totals[$date] ) ) {
    				$totals[$date] = 0;
    			}
    			
    			if( isset($row->dates[$date]) && (int)$row->dates[$date] )
    			{
    				//$month = JString::substr($date, 0,7);    				
    				$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $currentRow, $row->dates[$date] ,false);
					$totals[$date] +=	(int)$row->dates[$date];
					$totalRooms += (int)$row->dates[$date];
    			}     			
    			$column++;    			
    		}
    		//$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($totalStyle);
    		$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $currentRow, $totalRooms ,false);
    		
    		
    		$column = 1;
    		$currentRow++;
    		
    		$i = 1 - $i ;
    		
    	}
    	
    	$currentRow++;
    	$column = 2;
    	
    	$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column-1, $currentRow, 'Total',false);
    	
		foreach ($totals as $date=>$value){    		
			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($totalStyle);
			    	
    		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, $value,false);			
			$column++;	
    	}
    	
    	
    	
    	$this->download($fileName);
    	
    	JFactory::getApplication()->close();
	}
	
	
	public function exportIATACodeReason()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
    	$from 	= JRequest::getVar('date_from');
    	$until  = JRequest::getVar('date_to');    	
    	
    	$fileName = 'IATAcode_reason_'.$from.'_'.$until.'.xlsx';
    	
    	$currentRow = $this->setCSVHeader($from, $until, 'IATA code reason');

    	$model = $this->getModel();
    	
    	$data = $model->exportIATACodeReason($from,$until);
    	
    	//print_r($data);die;
    	
    	$headers = $model->getIATAHeaderDates($from,$until);
    	
    	$currentRow += 3;
    	$column = 2;
    	

		$headerStyle= array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FF738A98')
		  ),
		  'font'    => array(
				'color'     => array(
			    	'rgb' => 'ffffff'
			     )
			),
		);		
    	foreach ($headers as $date){
    		
    		$text = JHTML::_('date',$date,'d-M-Y');
    		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, $text,false);

			$this->objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($column)->setWidth(15);
			
			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($headerStyle);
			
			$column++;	
    	}
    	
    	$currentRow++;    	
    	$column = 1;
    	  
    	
    	$oddStyle= array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'f2f2f2')
		  ),
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN,
		  	'color'	=> array('argb' => 'cccccc')
		    )
		  )
		);
		$totalStyle= array(
		  'fill' => array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FFe26b0a')
		  ),
		  'font'    => array(
				'color'     => array(
			    	'rgb' => 'ffffff'
			     )
			),
		);			
    	$i = 0;
    	foreach ($data as $row)
    	{   
    		if($i==1)
    		{
    			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($oddStyle);
    		} 		
    		
    		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, $row->name ,false);
    		
    		$column++;
    		
    	
    		    		
    		foreach ($headers as $date)
    		{    	
	    		if($i==1)
	    		{
	    			$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($oddStyle);
	    		}	    		
    			if( isset($row->dates[$date]) && count($row->dates[$date]) )
    			{
    				//$month = JString::substr($date, 0,7);    				
    				$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($column, $currentRow, implode(', ',$row->dates[$date]) ,false);										
    			}     			
    			$column++;    			
    		}    		    		
    		
    		$column = 1;
    		$currentRow++;
    		
    		$i = 1 - $i ;
    		
    	}
    	
    	$currentRow++;
    	$currentRow++;
    	$currentRow++;
    	$column = 2;
    	
    	$topCodes = $model->getTopIATACodes($from,$until);
    	
    	
    	$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($headerStyle);
    	$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, 'Code',false);
		$column++;		
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($headerStyle);
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, 'Seats created',false);
		$column++;		
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($headerStyle);
		$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, 'Seats Issued',false);				
	
				
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$currentRow)->applyFromArray($headerStyle);
		
		$currentRow++;
    	
    	
    	foreach ($topCodes as $code)
    	{
    		$column = 2;
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, $code->code,false);
			$column++;
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, $code->seats,false);
			$column++;	
			$this->objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow($column, $currentRow, $code->seats_issued,false);
			$column++;	
			$currentRow++;	
    	}
    	
    	
    	
    	
    	$this->download($fileName);
    	
    	JFactory::getApplication()->close();
    	
	}
	
	protected function setCSVHeader($from, $until,$reportName)
	{
		$user = JFactory::getUser();
			        
		$exportDate = JHTML::_('date',JFactory::getDate()->toSql(), JText::_('DATE_FORMAT_LC3'));

		$params = JComponentHelper::getParams('com_sfs');
		$siteSuffix = $params->get('sfs_system_suffix');
	
		
		// Set document properties
		$this->objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
		->setLastModifiedBy("Maarten Balliauw")
		->setTitle("Office 2007 XLSX Test Document")
		->setSubject("Office 2007 XLSX Test Document")
		->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		->setKeywords("office 2007 openxml php")
		->setCategory("Test result file");
		
		$startColumn = 0;
		$row = 1;
		
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		
		//SFS address	
		$this->objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'SFS-web reporting',false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Creation date',false)
			->setCellValueByColumnAndRow($startColumn+1, $row, $exportDate,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Created by',false)
			->setCellValueByColumnAndRow($startColumn+1,  $row, $user->get('username'),false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Airport station',false)
			->setCellValueByColumnAndRow($startColumn+1,  $row, $siteSuffix,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Report Period',false)
			->setCellValueByColumnAndRow($startColumn+1, $row, 'From: '.$from.' To: '.$until,false)
			->setCellValueByColumnAndRow($startColumn, ++$row, 'Report Name',false)
			->setCellValueByColumnAndRow($startColumn+1, $row, $reportName,false);
		
		return $row;
	}
	
	
	public function download($fileName)
	{
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}
		
}


