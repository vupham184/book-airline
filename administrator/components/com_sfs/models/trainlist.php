<?php // No direct access
defined('_JEXEC') or die;

//jimport('joomla.application.component.model');

class SfsModelTrainlist extends JModelLegacy
{
	public function getListAirline()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id,code,name');
		$query->from('#__sfs_iatacodes');
		$query->where('type = 2');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
	public function getListCountry(){
		$db 	= JFactory::getDbo();
		$query 	= $db->getQuery(true);

		$query->select('name');
		$query->from('#__sfs_country');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
	public function saveAirlineTrain()
	{
		$id 	= JRequest::getVar('id');
		
		$db		= $this->getDbo();
		$query 	= $db->getQuery(true);
		$query->select('a.code,a.code,b.name AS country');
		$query->from('#__sfs_iatacodes AS a');
		$query->leftJoin('#__sfs_country AS b ON a.country_id = b.id');
		$query->where('a.id ='.$id);
		$db->setQuery($query);
		$result= $db->loadObject();

		$iata_airportcode 	= $result->code;
		$stationname 		= JRequest::getVar('stationname');
		$cityname 	 		= JRequest::getVar('cityname');
		$country 	 		= $result->country;
		$status 	 		= JRequest::getInt('status',0);

		$row = new stdClass();	
							
		$row->iata_airportcode = $iata_airportcode;
		$row->stationname = $stationname;
		$row->cityname 	  = $cityname;
		$row->country     = $country;
		$row->status      = $status;
		return $db->insertObject('#__sfs_airline_trains',$row);
		
	}
	public function getAirlineTrain()
	{	
		$id 	= JRequest::getVar('airline_trains_id');
		if (isset($id)) {
			$db 	= JFactory::getDbo();
			$query 	= $db->getQuery(true);
			$query->select('a.*,b.name AS airlineName');
			$query->from('#__sfs_airline_trains AS a');
			$query->leftJoin('#__sfs_iatacodes AS b on a.iata_airportcode = b.code');
			$query->where("a.id = '".$id."' AND b.type = 2");
			$db->setQuery($query);
			$result = $db->loadObject();
			return $result;
		}
		else
			return null;
	}
	public function editAirlineTrain()
	{
		$db 					= JFactory::getDbo();
		$row 					= new stdClass();	
		$row->id 				= JRequest::getVar('id');		
		$row->iata_airportcode  = JRequest::getVar('iata_airportcode');
		$row->stationname 		= JRequest::getVar('stationname');
		$row->cityname 	  		= JRequest::getVar('cityname');
		$row->country     		= JRequest::getVar('country');
		$row->status      		= JRequest::getInt('status',0);
		$result 				= $db->updateObject('#__sfs_airline_trains', $row,'id');
		return $result;
	}
	
}
?>