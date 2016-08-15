<?php
defined('_JEXEC') or die;
class SfsControllerUserroles extends JControllerLegacy
{
		
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Userroles', $prefix = 'SfsModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function save() 
	{
		$model = $this->getModel();
		$airline_id = JRequest::getVar('airline_the_add_name');
		$users_the_add_name = JRequest::getVar('users_the_add_name', array() );
		$userrole_the_add_name = JRequest::getVar('userrole_the_add_name', array());
		$is_close = JRequest::getVar('is_close', "");
		if( count( $users_the_add_name ) == 0 || count( $userrole_the_add_name ) ==0 ){
			$this->setRedirect('index.php?option=com_sfs&view=userroles');
		}
		$data = array( 'airline_id' => $airline_id,'users'=>$users_the_add_name, 'userrole' => $userrole_the_add_name);
		$model->save( $data );
		$this->setRedirect('index.php?option=com_sfs&view=userroles&is_close=' . $is_close);
		
		return '';
	}
	
	
	
	public function saveList() 
	{
		$model = $this->getModel();		
		$newUserrole = JRequest::getVar('newUserrole', array());
		///print_r( $newUserrole );die;
		if( !empty( $newUserrole ) ){
			foreach ( $newUserrole as $k => $item ) {
				//echo $k;die;
				$item = json_decode( str_replace("'",'"', $item ) );
				$data['id'] = $item->menu_id;
				$data['access'] = $item->group_id;
				$model->saveList( $data );
			}
			
		}
		$this->setRedirect('index.php?option=com_sfs&view=userroles');
		return '';
	}
	
	public function getUserOfAirline() 
	{
		$model = $this->getModel();
		$airline_id = JRequest::getVar('airline_id');
		$d = $model->getUserOfAirline( $airline_id );
		$dg = $model->getUsergroups( );
		$str = '';
		$strSelected = '';
		foreach ( $d as $v ) {
			$dArr = $model->getUserGroupMap( $v->id,  "");
			$data_id = "";
			foreach ( $dg as $vkg => $vg ) {
				if ( array_key_exists("k" . $v->id.$vg->group_id, $dArr) && $dArr["k" . $v->id.$vg->group_id] == $v->id){
					$data_id .= $vg->group_id . ",";
				}
			}
			$str_id = substr($data_id, 0, -1);
			$str .= '<option onclick="findSelected(\'' . $str_id . '\');" value="' . $v->id . '">' . $v->name . '</option>';
		}
		echo $str;
		exit;
	}
	
	public function updateUserIDToAirlineAirport()
	{
		$model = $this->getModel();
		$model->updateUserIDToAirlineAirport( );
	}
	
}