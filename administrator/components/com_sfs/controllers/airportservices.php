<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Airports Controller
 */
class SfsControllerAirportservices extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Airportservices', $prefix = 'SfsModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	public function save(){
		$airports_code = JRequest::getVar('airports_code', array());		
		$airport_code = JRequest::getVar('airport_code', '');
		$list_service = array();
		$model = $this->getModel('Airportservices','SfsModel');		
		if($airport_code){
			foreach ($airports_code as $key => $value){
				$airport_id = explode('_', $key);	
				$list_service[] = $airport_id[1];
			}
		}
		$model->deleteAirportServices($list_service);
		if($airports_code){
			foreach ($airports_code as $key => $value) {
				$airport_id = explode('_', $key);	
				$model->saveAirport($airport_id[1],$value);
			}	
		}
		$this->setRedirect('index.php?option=com_sfs&view=airportservices', false);
	}
}
