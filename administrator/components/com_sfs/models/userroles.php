<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');


class SfsModelUserroles extends JModelList
{

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();		
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		//Change airport session
        $session = JFactory::getSession();
        $airport_id = $session->get("airport_current_id");//code
		
		// Select the required fields from the table.
		$query->select('i.code, b.airline_id, c.iatacode_id, a.id, e.group_id, a.name, a.username, a.email, f.title as g_name');
		$query->from('#__users AS a');
		
		$query->leftJoin('#__sfs_airline_user_map AS b ON a.id = b.user_id');
		$query->leftJoin('#__sfs_airline_details AS c ON c.id = b.airline_id');
		$query->leftJoin('#__sfs_contacts AS d ON a.id = d.user_id');		
		$query->innerJoin('#__user_usergroup_map AS e ON a.id = e.user_id');
		$query->innerJoin('#__usergroups AS f ON f.id = e.group_id');
		$query->leftJoin('#__sfs_iatacodes AS i ON i.id = c.iatacode_id');
		$query->where('i.code !=""');
		$query->order('i.code ASC');
		//echo (string)$query;die;
		return $query;
	}
	
	public function getItems()
	{
		$arr1 = array();
		$arr_group_id = array();
		
		$items	= parent::getItems();
		///return $items;	
		//not get user default
		foreach ( $items as $v ) {
			///$arr1[] = $v;
			$arr_group_id['k' . $v->group_id] = $v->group_id;
		}
		$str_id = "'" . implode("','", $arr_group_id) . "'";
		$d_group = $this->getUsergroups( $str_id );
		$arraN = array_merge($d_group, $arr1);
		return $arraN;
	}
	
	public function getMenus()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id,a.title,a.access');
		$query->from('#__menu AS a');
		$query->where('a.menutype ="mainmenu"');
		///$query->where('a.level = 2');
		$query->where('a.level IN(1, 2)');
		$db->setQuery($query);
    	$result = $db->loadObjectList();
		$dataArr = array();
		$dataArr1 = array();
		$dataArr2 = array();
		foreach ( $result as $v ){
			$v->title = $this->asTitle( trim( $v->title ) );
			$exp = explode(" ",$v->title);
			if( is_numeric( $exp[0] ) ){
				$index = str_replace(".","", $exp[0]);
				$dataArr1[$index] = $v;
				
				if( $index == '1031' ){
					
					$vSub = (array)$v;
					$vSub['title'] = '1.035 guest detail page';
					$dataArr1['1036'] = (object)$vSub;
					
					$vSub = (array)$v;
					$vSub['title'] = '1.037 accounting detailpage';
					$dataArr1['1037'] = (object)$vSub;
				}
				// $exp[0].'<br>';
			}
			else {
				$dataArr2[] = $v;
			}
			///$dataArr[$v->title] = $v;
		}
		ksort($dataArr1);
		$dataArr1New = array();
		foreach ( $dataArr1 as $index => $v1 ){
			$dataArr1New[] = $v1;
			if( $index == '1006' ){
				$vSub = (array)$v1;
				$vSub['title'] = '1.006a trace passengers(guest relations)';
				$dataArr1New[] = (object)$vSub;
			}
			if( $index == '1030' ){
				$vSub = (array)$v1;
				$vSub['title'] = '1.030a Select passenger (local station)';
				$dataArr1New[] = (object)$vSub;
			}
			if( $index == '1031' ){
				$vSub = (array)$v1;
				$vSub['title'] = '1.031a Passenger detail page(local station)';
				$dataArr1New[] = (object)$vSub;
				
				$vSub = (array)$v1;
				$vSub['title'] = '1.031b Passenger detail page(guest relations)';
				$dataArr1New[] = (object)$vSub;
				
			}
		}
		$dataArr = array_merge( $dataArr1New, $dataArr2);
		///print_r( $dataArr1New );
		///die;
		return $dataArr;
		///return $result;
	}
	
	public function asTitle( $title = '' )
	{
		$arr['home'] = '1.001 Airline Dashboard';
		$arr['hotel'] = '1.002 Your hotel(s)';
		$arr['add hotel'] = '1.003 add hotel';
		$arr['add hotelrooms'] = '1.004 add hotel rooms';
		$arr['add passengers'] = '1.005 add passengers';
		$arr['trace passengers'] = '1.006 trace passengers';
		$arr['book taxi transportation'] = '1.007 Book taxi transportation';
		$arr['book group transportation'] = '1.008 Book group transportation';
		$arr['market overview'] = '1.009 Market overview';
		$arr['issue mealplan'] = '1.010 Issue Mealplan';
		$arr['issue taxi'] = '1.011 Issue taxi';
		$arr['issue train'] = '1.012 issue train';
		$arr['match / voucher'] = '1.013 match-voucher';
		$arr['blocked / booked rooms'] = '1.014 booked/blocked rooms';
		$arr['issued taxi vouchers'] = '1.015 issued taxi vouchers';
		$arr['booked group transportation'] = '1.016 Booked group transportation';
		$arr['reports'] = '1.017 reports';
		$arr['general reports'] = '1.018 general reports';
		$arr['accounting reports'] = '1.019 Accounting reports';
		$arr['accounting 2 reports'] = '1.020 Accounting 2 reports';
		$arr['airline data'] = '1.021 Airline Data';
		$arr['edit airline data'] = '1.022 Edit Airline Data';
		$arr['airline and all user details'] = '1.023 Airline/user details';
		$arr['edit airline/user details'] = '1.024 Edit Airline/user details';
		
		$arr['my contact details'] = '1.025 My contact details';
		$arr['edit my contact details'] = '1.026 Edit my contact details';
		
		$arr['passenger detail page'] = '1.031 Passenger detail page';
		
		$arr['taxi details'] = '1.027 taxi details';
		$arr['edit taxi details'] = '1.028 Edit Taxi details';
		$arr['rate agreement (taxi)'] = '1.029 Rate agreement (taxi)';

		
		$arr['select passengers'] = '1.030 Select passenger';
		
		$arr['passenger detail page'] = '1.031 Passenger detail page';
		
		///$arr['my contact details'] = '1.032 My contact details';
		$arr['invite hotel for registration'] = '1.033 Invite hotel for registration';
		$arr['change username & password'] = '1.034 change username/password';
		
		$arr['search hotelroom'] = '1.036 search hotelroom';
		
		$strT = $title;
		if( isset( $arr[strtolower( $title )] ) ){
			$strT = $arr[strtolower( $title )];
		}
		return $strT;
		
	}
	
	public function getUsergroups( $str_id = '' )
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id as group_id,a.title as g_name');
		$query->from('#__usergroups AS a');
		///$query->where("a.id NOT IN($str_id)");
		$db->setQuery($query);
    	$result = $db->loadObjectList();
		foreach( $result as $v ){
			$v->code = "";
			$v->airline_id = 0;
			$v->iatacode_id = 0;
			$v->id = 0;
			$v->name = "";
			$v->username = "";
			$v->email = "";
		}
		return $result;
	}
	
	public function getAirlines()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__sfs_iatacodes AS a');
		$query->leftJoin('#__sfs_contacts AS d ON a.id = d.group_id');
		$query->where('a.type = 1');
		$query->group('a.id');
		$db->setQuery($query);
    	$result = $db->loadObjectList();
		return $result;
	}
	
	public function getUserOfAirline( $airline_id ) {
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, a.name, a.username, a.email');
		$query->from('#__users AS a');
		
		$query->leftJoin('#__sfs_airline_user_map AS b ON a.id = b.user_id');
		$query->leftJoin('#__sfs_airline_details AS c ON c.id = b.airline_id');
		$query->leftJoin('#__sfs_iatacodes AS i ON i.id = c.iatacode_id');
		$query->where( 'i.id = ' . $airline_id );
		$query->order('a.name ASC');
		$db->setQuery($query);
    	$result = $db->loadObjectList();
		return $result;
	}
	
	public function save( $data ){
		///$user_idA = $data['users'];
		$user_idA = $this->getUserOfAirline( $data['airline_id'] );
		$userrole_id = $data['userrole'];
		$group_id = implode(",", $userrole_id);
		$db = $this->getDbo();
		foreach ( $user_idA as $user ) {
			$user_id = $user->id;
			$db->setQuery('DELETE FROM #__user_usergroup_map WHERE user_id='.$user_id);
            $db->query();
			
			foreach ( $userrole_id as $group_id ) {
				/*
				$query = $db->getQuery(true);
				$query->select('a.user_id');
				$query->from('#__user_usergroup_map AS a');
				$query->where( 'a.user_id = ' . $user_id );
				$query->where( 'a.group_id =' . $group_id);
				$db->setQuery($query);
				$result = $db->loadObject();
				if( empty( $result ) ) { //khong ton tai thi insert
				*/
					$usergroup_map = new stdClass();
					$usergroup_map->user_id = $user_id;
					$usergroup_map->group_id = $group_id;
					if (!$db->insertObject('#__user_usergroup_map', $usergroup_map)) {
						die('Could not create usergroup map');
					} 
				//}
			}
		}
		return 1;
	}
	
	
	public function saveList( $data ){
		$db = JFactory::getDbo();
        $query = 'UPDATE #__menu SET access =' . (int)$data['access'] . ' WHERE id='.(int)$data['id'];
		//echo $query;die;
        $db->setQuery($query);
        $db->query();
		return 1;
	}
	
	public function getUserGroupMap( $user_id, $group_id ) {
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__user_usergroup_map');
		$query->where( 'user_id = ' . $user_id );
		//$query->where( 'group_id = ' . $group_id );
		//echo (string)$query;
		//echo'<br>';
		$db->setQuery($query);
    	$results = $db->loadObjectList();
		$dataArr = array();
		foreach( $results as $vk => $v ){
			$dataArr['k' . $v->user_id.$v->group_id] = $v->user_id;
		}
		return $dataArr;
	}
	
	public function updateUserIDToAirlineAirport(){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.user_id, a.group_id, c.airport_id');
		$query->from('#__sfs_contacts AS a');
		//$query->leftJoin('#__sfs_contacts AS b ON a.id = b.user_id');
		$query->leftJoin('#__sfs_airline_airport AS c ON c.airline_detail_id = a.group_id');
		$query->where( 'a.grouptype = 2' );
		///$query->group( 'a.id' );
		///echo (string)$query;die;
		$db->setQuery($query);
    	$results = $db->loadObjectList();
		$group_idA = array();
		foreach ( $results as $item ) {
			$str = $item->user_id . ";" . $item->group_id . ";" . $item->airport_id;
			$group_idA['k'.$item->user_id][] = $str;
		}
		$db = $this->getDbo();
		foreach ( $group_idA as $group_id ){
			foreach ( $group_id as $str ) {
				$dataA = explode(";", $str );
				$user_id = $dataA[0];
				$airline_detail_id = $dataA[1];
				$airport_id = $dataA[2];
					$dat = new stdClass();
					$dat->user_id = $user_id;
					$dat->airline_detail_id = $airline_detail_id;
					$dat->airport_id = $airport_id;
					if (!$db->insertObject('#__sfs_airline_airport_new', $dat)) {
						die('Could not create usergroup map');
					} 
			}
		}
	}
	
}
