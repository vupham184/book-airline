<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel.php';
require_once JPATH_ROOT.'/components/com_sfs/libraries/excel/PHPExcel/Worksheet/Drawing.php';

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class SfsControllerAirplusreporting extends JControllerLegacy {
	

	public function __construct($config = array())
	{
		parent::__construct($config);															
	}	
	
	public function getModel($name = 'Airplusreporting', $prefix = 'SfsModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	
	public function importAirplus()
	{		
		$fileTemp = $_FILES['fileName']['tmp_name'];
		$fileName = $_FILES['fileName']['name'];
		//$uploadedFileNameParts = explode('.',$fileName);
		//$uploadedFileExtension = array_pop($uploadedFileNameParts);
		//$validFileExts = explode(',', 'jpeg,jpg,png,gif');
		
		//for security purposes, we will also do a getimagesize on the temp file (before we have moved it 
		//to the folder) to check the MIME type of the file, and whether it has a width and height
		$imageinfo = getimagesize($fileTemp);
		$uploadPath = JPATH_SITE.DS.'report'.DS.'airplus'.DS.$fileName;
 		if(!JFile::upload($fileTemp, $uploadPath)) 
		{
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=airplusreporting&error=1',false));
			//echo JText::_( 'ERROR MOVING FILE' );
				return;
		}
		
		if ( !file_exists( $uploadPath ) ) { //JPATH_BASE
			$csv_file =  JPATH_SITE.DS.'report'.DS.'airplus'.DS.'test_CSV_v2_org.csv';
		}
		else {
			$csv_file =  $uploadPath;
		}
		
		///$excel_file =  JPATH_BASE .'/uploads/csv/test_CSV_v2.xlsx';
		
		if ( !file_exists( $csv_file ) ) {
			return 0;
		}
		$model = JModel::getInstance('Importcsv','SfsModel');
		$data = array();
		if (($handle = fopen($csv_file, "r")) !== FALSE) {
			$r = 0;
			while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
				if ( $r >= 2 ) { //bo nhung muc tieu de khg lay data
					$data['account_number'] = trim( $row[0] );
					$data['amount'] = trim( $row[1] );
					$data['aida_number'] = trim( $row[2] );
					$data['curr'] = trim( $row[3] );
					$data['dbi_ae'] = trim( $row[4] );
					$data['dbi_ak'] = trim( $row[5] );
					$data['dbi_au'] = trim( $row[6] );
					$data['dbi_bd'] = trim( $row[7] );
					$data['dbi_ds'] = trim( $row[8] );
					$data['dbi_ik'] = trim( $row[9] );
					$data['dbi_ks'] = trim( $row[10] );
					$data['dbi_pk'] = trim( $row[11] );
					$data['dbi_pr'] = trim( $row[12] );
					$data['dbi_rz'] = trim( $row[13] );
					$data['iata_number'] = trim( $row[14] );
					$data['passenger'] = trim( $row[15] );
					$data['portal_user_id'] = trim( $row[16] );
					$data['purchase_date'] = trim( $row[17] );
					$data['travel_date'] = trim( $row[18] );
					$data['service_desc'] = trim( $row[19] );
					$data['supplier'] = trim( $row[20] );
					$data['ticket_number'] = trim( $row[21] );
					$data['travel_agency'] = trim( $row[22] );
					$data['transaction_type'] = trim( $row[23] );
					$data['amount_conv'] = trim( $row[24] );
					$data['amount_curr'] = trim( $row[25] );
					$data['fe_amount'] = trim( $row[26] );
					$data['fe_curr'] = trim( $row[27] );
					$data['start_date'] = trim( $row[28] );
					$data['end_date'] = trim( $row[29] );
					$data['days_nights_count'] = trim( $row[30] );
					$data['dept_city'] = trim( $row[31] );
					$data['dest_city'] = trim( $row[32] );
					$data['participant_count'] = trim( $row[33] );
					$data['carrier_code'] = trim( $row[34] );
					$data['service_class'] = trim( $row[35] );
					$data['routing'] = trim( $row[36] );
				
					$model->insertPassengersAirplusData( $data );
				}
					
				$r++;	
			}
			
		}
		fclose($handle);		
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=airplusreporting&suss=1',false));	
	}
	
}

