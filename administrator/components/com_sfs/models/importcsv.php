<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');


class SfsModelImportcsv extends JModelList
{
	
	public function insertPassengersAirplusData( $data )
	{
		//$db = JFactory::getDbo();
		$db = $this->getDbo();
		$dataObject = (object)$data;
		//if( $this->ischeckInsert( $data ) == 0 ) {
			$db->insertObject('#__sfs_passengers_airplus_data', $dataObject);
		//}
	}
	
	public function ischeckInsert( $data )
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from('#__sfs_passengers_airplus_data');
		$query->where('account_number="' . $data['account_number'] . '"' );
		$query->where('aida_number="'.$data['aida_number'] . '"' );
		$query->where('dbi_au="'.$data['dbi_au'] . '"' );
		$query->where('start_date="'.$data['start_date'] . '"' );
		$query->where('end_date="'.$data['end_date'] . '"' );
		$db->setQuery($query);
		$result = $db->loadObject();
		if( $result->id > 0 ) {
			return 1;
		}
		return 0;
	}
}