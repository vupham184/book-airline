<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
jimport('joomla.mail.helper');
class SfsModelReservations extends JModelList
{

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
	

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}		
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$filter_block_status = $this->getUserStateFromRequest($this->context.'.filter.blockstatus', 'filter_block_status');
		$this->setState('filter.blockstatus', $filter_block_status);
		
		$filter_airline_id = JRequest::getInt('filter_airline_id',0);
		$this->setState('filter.airline_id', $filter_airline_id);
		
		///$filter_hotel_id = JRequest::getInt('filter_hotel_id',0);
		$filter_hotel_id = JRequest::getString('filter_hotel_id', "");
		$this->setState('filter.hotel_id', $filter_hotel_id);		
		
		//lchung add
		$filter_ws_room = JRequest::getString('filter_ws_room',"");
		$this->setState('filter.ws_room', $filter_ws_room);
		
		$filter_fromdate = JRequest::getString('date_start',"");
		$this->setState('filter.fromdate', $filter_fromdate);
		$filter_todate = JRequest::getString('date_end',"");
		$this->setState('filter.todate', $filter_todate);
		//End lchung
		
		// List state information.
		parent::populateState('a.booked_date', 'desc');
	}

	protected function getListQuery()
	{
		$rtype = $this->getState('filter.rtype');
		$gid = $this->getState('filter.gid');
		
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		//Change airport session
        $session = JFactory::getSession();
        $airport_id = $session->get("airport_current_id");//code

		// Select the required fields from the table.
		$query->select('a.*, u.name AS booked_name, h.name AS hotel_name, h.id AS hotel_id');
						
		$query->from('#__sfs_reservations AS a');
		
		$query->select('b.transport_included,b.date AS room_date,b.sd_room_total,b.t_room_total');
		$query->leftJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->leftJoin('#__sfs_hotel AS h ON h.id=b.hotel_id');
				
		$query->select('ad.company_name,ic.name AS airline_name, ic.code AS airline_code, ad.city');
		$query->leftJoin('#__sfs_airline_details AS ad ON ad.id=a.airline_id');
		$query->leftJoin('#__sfs_iatacodes AS ic ON ic.id=ad.iatacode_id');

        $query->select('ic_gh.name AS airline_name_gh, ic_gh.code AS airline_code_gh');
        $query->leftJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
        $query->leftJoin('#__sfs_iatacodes AS ic_gh ON ic_gh.id=ghr.airline_id');

        $query->select('d.name AS country_name');
		$query->leftJoin('#__sfs_country AS d ON d.id=ad.country_id');
		
		$query->leftJoin('#__users AS u ON u.id=a.booked_by');
			
		if ( $filter_search = $this->getState('filter.search') ) {			
			$filter_search = $db->Quote('%'.$db->escape($filter_search, true).'%');
			$query->where('a.blockcode LIKE '.$filter_search);
		}
		
		if ( $filter_block_status = $this->getState('filter.blockstatus') ) {						
			$query->where('a.status = '.$db->quote($filter_block_status));
		}
		
		if ( $filter_airline_id = $this->getState('filter.airline_id') ) {						
			$query->where('a.airline_id = '.$filter_airline_id);
		}
		
		/*if ( $filter_hotel_id = $this->getState('filter.hotel_id') ) {						
			$query->where('a.hotel_id = '.$filter_hotel_id);
		}*/
		
		
		if ( $filter_hotel_id = $this->getState('filter.hotel_id') ) {
			$IN_filter_hotel_id = $this->getListHotelsID( $filter_hotel_id );
			if ( $IN_filter_hotel_id != "" )						
				$query->where('a.hotel_id IN( ' . $IN_filter_hotel_id . ')');
		}
		
		//lchung add
		if ( $filter_ws_room = $this->getState('filter.ws_room') ) {
			if ( $filter_ws_room == "Partner" ) {
				$query->where('(a.ws_room IS NULL OR a.ws_room = 0)');
			}
			elseif ( $filter_ws_room == 'WS' ) {
				$query->where('a.ws_room >=1 ');
			}
		}
		
		if ( $filter_fromdate = $this->getState('filter.fromdate') ) {
			$query->where("Date(a.blockdate) >= '$filter_fromdate'");
		}
		if ( $filter_todate = $this->getState('filter.todate') ) {
			$query->where("Date(a.blockdate) <= '$filter_todate'");
		}
		
		if ( $airport_id != "" ) {
			$query->where('a.airport_code="' . $airport_id . '"');
		}
		//End lchung
				
		$orderCol	= $this->state->get('list.ordering', 'a.booked_date');
		$orderDirn	= $this->state->get('list.direction', 'DESC');
		$orderDirn  = 'DESC';
		
		$query->order($db->escape($orderCol.' '.$orderDirn));

//		echo (string)$query;die;
						
		return $query;
	}
	
	//lchung 27-11-2015
	public function getListHotelsID( $hotel_name = '' )
	{
		$db = $this->getDbo();
		$query = "SELECT id FROM #__sfs_hotel WHERE block=0 And name LIKE '%$hotel_name%'";
		$db->setQuery($query);
		
		$hotels = $db->loadObjectList();
		$listID = array();
		$strID = "";
		if(count($hotels))
		{
			foreach ( $hotels as $vk => $v ) {
				$listID[] = $v->id;
			}
			$strID = implode(",", $listID);
		}
		return $strID;
	}
	//End lchung 27-11-2015
	
	public function getItems()
	{
		$items	= parent::getItems();	
		return $items;
	}	
	
	
	public function getAirlines()
	{
		$db = $this->getDbo();
		$query = 'SELECT a.id,a.company_name,b.name FROM #__sfs_airline_details AS a';
		$query .= ' LEFT JOIN #__sfs_iatacodes AS b ON b.id=a.iatacode_id';
		$query .= ' WHERE a.block=0 AND a.approved=1';
		$db->setQuery($query);
		
		$airlines = $db->loadObjectList();
		
		if(count($airlines))
		{
			return $airlines;
		}
		return null;
	}
	public function getHotels()
	{
		$db = $this->getDbo();
		$query = 'SELECT * FROM #__sfs_hotel WHERE block=0';
		$db->setQuery($query);
		
		$hotels = $db->loadObjectList();
		
		if(count($hotels))
		{
			return $hotels;
		}
		return null;
	}
	
	
	public function batch()
	{
		$db 		= JFactory::getDbo();
		$blockbatch = JRequest::getVar('blockbatch', array(), 'post', 'array');
		$ids 		= JRequest::getVar('cid', array(), 'post', 'array');
		
		if( empty($blockbatch['status']) )
		{
			$this->setError('Please select block status');
			return false;
		}
		
		$status = $blockbatch['status'];		
		
		$status_array = array('O' => 'Open','P' => 'Pending','T' => 'Tentative','C' => 'Challenged','A' => 'Approved','R' => 'Archived','D' => 'Deleted');
		
		$allow = false;
		
		foreach ( $status_array as $key => $value )
		{
			if( $key == $status )
			{
				$allow = true;
			}
		}
		
		if( $allow === false )
		{
			$this->setError('Status you selected was invalid');
			return false;
		}
		
		if( ! count($ids) )
		{
			$this->setError('No blocks are selected');
			return false;
		}
		
		// load blocks
		
		$query  = 'SELECT a.*,b.company_name,c.name AS airline_name FROM #__sfs_reservations AS a';
		$query .= ' INNER JOIN #__sfs_airline_details AS b ON b.id=a.airline_id';
		$query .= ' LEFT JOIN #__sfs_iatacodes AS c ON c.id=b.iatacode_id';
		$query .= ' WHERE a.id = '. implode(' OR a.id=', $ids);
		
		$db->setQuery($query);		
		$reservations = $db->loadObjectList('id');

		$hotelIds = array();		
		foreach ($reservations as $reservation)
		{
			$query = 'UPDATE #__sfs_reservations SET status='.$db->quote($status).' WHERE id='. (int) $reservation->id;			
			$db->setQuery($query);			
			if( !$db->query() )
			{
				$this->setError( $db->getErrorMsg() );
				return false;
			}

			if( ! in_array((int)$reservation->hotel_id, $hotelIds) )
			{
				$hotelIds[] = (int)$reservation->hotel_id;
			}			
		}
		
		$query  = 'SELECT a.user_id, a.group_id, a.gender, a.is_admin, a.job_title, u.name, u.email FROM #__sfs_contacts AS a';
		$query .= ' INNER JOIN #__users AS u ON u.id=a.user_id';
		$query .= ' WHERE a.grouptype = 1 AND (a.group_id = '. implode(' OR a.group_id=', $hotelIds).')';
		
		$db->setQuery($query);		
		$hotelContacts = $db->loadObjectList('user_id');

		ob_start();
		
		require_once JPATH_COMPONENT.'/helpers/emails/airlineaccepted.php';
		
		$body = ob_get_clean();
		
		$emailSubject = 'Rooms Sold on Stranded Flight Solutions';
		foreach ($reservations as $reservation)
		{
			if( (int)$reservation->association_id > 0)
			{
				continue;
			}
			
			if( !$reservation->airline_name )
			{
				$reservation->airline_name = $reservation->company_name;
			}
			
			$mainContact = null;
			$hotelStaff  = '';
			
			if($reservation->hotel_user_id && $hotelContacts[$reservation->hotel_user_id])
			{
				$mainContact = $hotelContacts[$reservation->hotel_user_id];
			}
			
			foreach ($hotelContacts as $contact)
			{
				if($contact->group_id == $reservation->hotel_id)
				{
					$hotelStaff .= '- '.$contact->name.', '.$contact->job_title.'<br />';
				}
			}
			
			$sendBody = JString::str_ireplace('{hotelcontact}', $mainContact->name, $body);
			$sendBody = JString::str_ireplace('{airline}', $reservation->airline_name, $sendBody);
			$sendBody = JString::str_ireplace('{blockcode}', $reservation->blockcode, $sendBody);
			$sendBody = JString::str_ireplace('{blockdate}', $reservation->blockdate, $sendBody);
			$sendBody = JString::str_ireplace('{status}', $status_array[$status], $sendBody);
			$sendBody = JString::str_ireplace('{hotelcontacts}', $hotelStaff, $sendBody);
			
			if($mainContact)
			{				
				JUtility::sendMail('noreply@sfs-web.com', 'Stranded Flight Solutions', $mainContact->email, $emailSubject , $sendBody, true);				
			}			
		}
		
		return true;
	}	

	public function sendMailReminder(){
		
		$db = JFactory::getDbo();
		// Info email
		ob_start();
		require_once JPATH_COMPONENT.'/views/reservations/tmpl/reminder_email.php';		
		$emailBody = ob_get_clean();
		$config = JFactory::getConfig();
		$data['fromname'] = $config->get('fromname');
		$data['mailfrom'] = $config->get('mailfrom');
		$emailSubject  = JText::_('Reminder to upload voucher codes and names on SFS');//COM_SFS_MATCH_PASSENGER_SUBJECT

		// Get value from send form
		$input = JFactory::getApplication()->input;
		$post_array = $input->getArray($_POST);
		$len = $post_array["arrData"];
		$hotelArr = $post_array["hotelArr"];
		$blockCodeList = $post_array["blockCode"];
		$hotelIDArr = array();
		foreach ( $hotelArr as $v ){
			$hotelIDArr['k'.$v] = $v;
		}
		$listMail = "";
		$listBlock = "";
		

		$query = $db->getQuery(true);

		$query->select('a.*,u.email');
		$query->from('#__sfs_contacts AS a');
		$query->innerJoin('#__users AS u ON u.id=a.user_id');
		$query->where('a.group_id IN ('.implode(",", $hotelIDArr).') AND a.grouptype=1');
		$query->where('u.block=0');

		$db->setQuery($query);
		$list_contacts = $db->loadObjectList();


		// $mHotel = $this->getModel("Hotel");
		// $list_contacts = $mHotel->getContacts( implode(",", $hotelIDArr) );
		$Arr_systemEmails = array();
		foreach ( $list_contacts as $k => $v ) {
			if( $v->systemEmails != '' ) {
				$obj = json_decode( $v->systemEmails);
				if ( isset( $obj->booking) && $obj->booking == 1) {
					$Arr_systemEmails[] = $v->email;
				}
			}
			
		}
		foreach ($blockCodeList as $key => $value) {									
			$listBlock .= $value . "\n"; 		
		}

		/*
		$query  = 'SELECT systemEmails FROM #__sfs_hotel AS a';
		$query .= ' INNER JOIN #__sfs_reservations AS b';
        $query .= ' ON a.id = b.hotel_id WHERE b.id IN(' . implode(",", $len) . ')';              
        $results = $this -> getData($query);
		
      
		$queryBlock = 'SELECT blockcode FROM #__sfs_reservations ';
		$queryBlock .= 'WHERE id IN(' . implode(",", $len) . ')';
		$results_ = $this -> getData($queryBlock);
			
		foreach ($results_ as $key => $value) {									
			$listBlock = $listBlock . "<br />" . $value -> blockcode; 		
		}
		*/
		
		$queryManagemail = 'SELECT info_email FROM #__sfs_managemail WHERE id =1';
		$resultManage = $this -> getData($queryManagemail);
			
		$emailBody = $resultManage->info_email;

		$emailBody = str_replace("{blockCode}", $listBlock, $emailBody);	
		
		foreach ($Arr_systemEmails as $key => $emailTo) {
			JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $emailTo, $emailSubject, $emailBody);
			// JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], 'nguyencongphuc.dev@gmail.com', $emailSubject, $emailBody,true);
		}
		
		///$params = JComponentHelper::getParams('com_sfs');
		///$adminEmails = explode(';', $adminEmails);
		// Send email
		
		/*foreach ($results as $key => $v) {
			$arrMail =(array) json_decode($v->systemEmails);
			//$arrMail = array('phamvu180485@gmail.com');
			foreach ($arrMail as $key => $emailTo) {
				JUtility::sendMail($data['mailfrom'], $data['fromname'], $emailTo, $emailSubject, $emailBody,true);	
			}
		}*/
		///JUtility::sendMail('noreply@sfs-web.com', 'Stranded Flight Solutions', $am, $adminSubject, $adminBody, true);
		echo json_encode( array('error' => 0, 'message' => 'Send email successful' ) );
		// print_r($SendMailstatus);
		die();
	}

	public function getData($query){
		$db = JFactory::getDbo();
		$db -> setQuery($query);
	    $results = $db -> loadObject();
	    return $results;
	}
	
}
