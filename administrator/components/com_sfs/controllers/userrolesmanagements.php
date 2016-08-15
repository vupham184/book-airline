<?php
defined('_JEXEC') or die;
class SfsControllerUserrolesmanagements extends JControllerLegacy
{
		
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Userrolesmanagements', $prefix = 'SfsModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function save()
	{
		$model = $this->getModel('Userrolesmanagements','SfsModel');
		$airports_code = JRequest::getVar('airports_code', array());

		$dataArr = array();
		$dataArrUserId = array();
		foreach ( $airports_code as $v ) {
			$jsd = json_decode( str_replace("'",'"', $v ) );
			$airline_id = $jsd->airline_id;
			$airportcode = $jsd->airportcode;
			$user_id = $jsd->user_id;
			$dataArr[$user_id .';'. $airline_id .';'. $airportcode] = $airportcode;
		}
		
		$dataUser = array();
		foreach( $dataArr as $vk => $v ){
			$dataA = explode(";", $vk);
			$user_id = $dataA[0];
			$dataUser['k' . $user_id][] = $dataA;
		}
		foreach( $dataUser as $vk => $v ){
			$user_id = str_replace("k", "", $vk );
			$model->del( $user_id );
			foreach ( $v as $vks => $vs ){				
				$user_id = $vs[0];
				$airline_id = $vs[1];
				$airport_id = $vs[2];
				$d = $model->save( $airline_id, $airport_id, $user_id);
			}
		}
		$this->setRedirect('index.php?option=com_sfs&view=userrolesmanagements', false);
	}
}