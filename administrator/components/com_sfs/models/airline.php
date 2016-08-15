<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');


class SfsModelAirline extends JModelAdmin
{

	public function getTable($type = 'Adminairline', $prefix = 'SfsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true)
	{		
		// Get the form.
		$form = $this->loadForm('com_sfs.airline', 'airline', array('control' => 'jform', 'load_data' => $loadData));
		
		$airlineParams   = JRequest::getVar('airlineparams', array(), 'post', 'array');
        $airplusParams   = JRequest::getVar('airplusparams', array(), 'post', 'array');
		$available_taxi  = JRequest::getVar('available_taxi', array(), 'post', 'array');
		
		//var_dump($available_taxi)
				
		JFactory::getApplication()->setUserState('airlineparams', $airlineParams);
		JFactory::getApplication()->setUserState('airplusparams', $airplusParams);
		JFactory::getApplication()->setUserState('available_taxi', $available_taxi);
		
		
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_sfs.edit.airline.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {
			$db = $this->getDbo();
			
			$query = 'SELECT code AS airline_code, name AS airline_name FROM #__sfs_iatacodes WHERE id='.$item->iatacode_id.' ORDER BY code';
			$db->setQuery($query);
			
			$iatacode = $db->loadObject();
			
			$item->airline_code = $iatacode->airline_code;
			$item->airline_name = $iatacode->airline_name;

			$query = 'SELECT airport_id FROM #__sfs_airline_airport WHERE airline_detail_id='.$item->id;
			$db->setQuery($query);
			//$item->airport = $db->loadResultArray();
			
			$listArr = $db->loadObjectList();
			$arr = array();
			foreach ($listArr as $value) {    
				array_push($arr, $value->airport_id);     
			}	
			$item->airport = $arr;
			
			if( $item->billing_id ) {
				$db = $this->getDbo();
				$query = $db->getQuery(true);

				$query->select('
								name AS billing_name, 
								address AS billing_address, 
								address1 AS billing_address1,
								address2 AS billing_address2,
								city AS billing_city,
								state_id AS billing_state_id,
								zipcode AS billing_zipcode,
								country_id AS billing_country_id,
								tva_number AS billing_tva_number');
				$query->from('#__sfs_billing_details');
				$query->where('id='.$item->billing_id);
				
				$db->setQuery($query);
				
				$billing = $db->loadObject();
				
				$billingArray = get_object_vars($billing);
				
				foreach ($billingArray as $k => $v) {
					$item->$k = $v;
				}
				
			}	

			$item->contacts = $this->getContacts($item->id);


            if (property_exists($item, 'airline_airplusws_id'))
            {
                $airluswsTable = JTable::getInstance('AirlineAirplusws', 'SfsTable');
                #print_r($airpluswsTable);die('x');
                #var_dump($airluswsTable);
                $airluswsTable->load($item->airline_airplusws_id);
                $db->setQuery($airluswsTable->getDbo()->getQuery());
                $item->airplusparams = $db->loadAssoc();
            }
		}		
		
		return $item;
	}
	
	public function save($data)
	{		
		$db = $this->getDbo();	
						
		if (parent::save($data)) {
			
			$item = $this->getItem();
            if(is_array($data['airport']) && count($data['airport']) ) {
                $db->setQuery("DELETE FROM #__sfs_airline_airport where airline_detail_id='".$item->id . "' And user_id=" . $data['user_id']);
                $db->query();
                foreach ( $data['airport'] as $code )
                {
                    $db->setQuery('INSERT INTO #__sfs_airline_airport(airline_detail_id, airport_id, user_id) VALUES('.$item->id.','.$code.', ' . $data['user_id'] . ')');
                    $db->query();
                }

			}

			if( (int)$data['billing_id']){
			
				$billingTable = JTable::getInstance('adminbilling', 'SfsTable');	
				
				$billingTable->load((int)$data['billing_id']);	
								
				$billingTable->set('name',$data['billing_name']);
				$billingTable->set('address',$data['billing_address']);
				$billingTable->set('address1',$data['billing_address1']);
				$billingTable->set('address2',$data['billing_address2']);
				$billingTable->set('city',$data['billing_city']);
				$billingTable->set('state_id',$data['billing_state_id']);
				$billingTable->set('zipcode',$data['billing_zipcode']);
				$billingTable->set('country_id',$data['billing_country_id']);
				$billingTable->set('tva_number',$data['billing_tva_number']);
						
				$billingTable->store(true);
			}
			
			$airlineparams = JFactory::getApplication()->getUserState('airlineparams', array());
			$airplusparams = JFactory::getApplication()->getUserState('airplusparams', array());

			$airpluswsTable = JTable::getInstance('AirlineAirplusws', 'SfsTable');
			#var_dump($airplusparams);die();
			if( (int)$item->airline_airplusws_id){
				/** @var $airpluswsTable SfsTableAirlineAirplusws */
				$airpluswsTable->load($item->airline_airplusws_id);
				$airpluswsTable->save($airplusparams);
				$airpluswsTable->store(true);
			} else {
				#var_dump($airplusparams);
				$airplusparams['id'] = $data['airline_airlusws_id'];
				$airpluswsTable->save($airplusparams);
				$item->airline_airplusws_id = $airpluswsTable->getDbo()->insertid();
				$query = 'UPDATE #__sfs_airline_details SET airline_airplusws_id='.$db->quote($item->airline_airplusws_id).'WHERE id='.$item->id;
				$db->setQuery($query);
				$db->query();
			}
			
			$registry = new JRegistry();
			$registry->loadArray($airlineparams);
			$airlineparams =  (string) $registry;

			$query = 'UPDATE #__sfs_airline_details SET params='.$db->quote($airlineparams). ' WHERE id='.$item->id;
			
			$db->setQuery($query);
			$db->query();
			
			$available_taxi = JFactory::getApplication()->getUserState('available_taxi', array());
					
			$taxiCompanies  = $this->getTaxiCompanies();
			if( count($taxiCompanies)) {
				foreach ($taxiCompanies as $company)
				{
					$query = 'UPDATE #__sfs_taxi_companies SET published=0 WHERE id='.$company->id;
					$db->setQuery($query);
					$db->query();
				}
			}	
			if(count($available_taxi))
			{
				foreach ($available_taxi as $company)
				{
					$query = 'UPDATE #__sfs_taxi_companies SET published=1 WHERE id='.(int)$company;
					$db->setQuery($query);
					$db->query();
				}
			}
			
			return true;
		} 
											
	}
	
	public function getAdmins()
	{
		$item = $this->getItem();
		$db = $this->getDbo();
		$db->setQuery(
			'SELECT a.user_id, u.username FROM #__sfs_airline_user_map AS a'.
			' INNER JOIN #__users AS u ON u.id=a.user_id'.
			' INNER JOIN #__user_usergroup_map AS um ON um.user_id=a.user_id AND um.group_id=11'.
			' WHERE a.airline_id='.(int)$item->id
		);
		$result = $db->loadObjectList();		
		return $result;
	}

    public function getStationUsers()
    {
        $item = $this->getItem();
        $db = $this->getDbo();
        $db->setQuery(
            'SELECT a.user_id, u.username FROM #__sfs_airline_user_map AS a'.
            ' INNER JOIN #__users AS u ON u.id=a.user_id AND u.airport IS NOT NULL'.
            ' INNER JOIN #__user_usergroup_map AS um ON um.user_id=a.user_id AND um.group_id=18'.
            ' WHERE a.airline_id='.(int)$item->id
        );
        $result = $db->loadObjectList();
        return $result;
    }

    public function approved( $id=null )
	{
		$return = false; 
		
		$pk = ($id == null) ? $this->getState('airline.id') : $id;
		
		$db = $this->getDbo();		
		
		$query = 'SELECT created_by FROM #__sfs_airline_details WHERE id='.$pk;
		$db->setQuery($query);
		$created_by = $db->loadResult();
		
		$user = JUser::getInstance($created_by);
		
		if( empty($user) ) 
		{						
			return false;
		}		

		$contacts = $this->getContacts($pk);
				
		$query = 'UPDATE #__sfs_airline_details SET approved=1 WHERE id='.$pk;
		$db->setQuery($query);
		
		if( !$db->query() ) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		foreach ($contacts as $contact) {
			$query = 'UPDATE #__users SET activation='.$db->Quote('').',block=0 WHERE id='.$contact->user_id;
			$db->setQuery($query);
			
			if( !$db->query() ) {
				$this->setError($db->getErrorMsg());
				return false;
			}				
		}
		
		$config = JFactory::getConfig();
		
		foreach ($contacts as $contact) {
			$data = array();								
			$data['email']		= $contact->email;	
			$data['username']	= $contact->username;	
			$data['fromname']	= $config->get('fromname');
			$data['mailfrom']	= $config->get('mailfrom');
			$data['sitename']	= $config->get('sitename');
			$data['siteurl']	= JUri::root();	
			$data['loginurl']	= JUri::root().'index.php?option=com_sfs&view=login&Itemid=104';	
							
			$emailSubject	= JText::_('COM_SFS_AIRLINE_APPROVED_SUBJECT');		
					
			$emailBody = JText::sprintf(
				'COM_SFS_AIRLINE_APPROVED_BODY',
				$contact->name.' '.$contact->surname,					
				$data['siteurl'],
				$data['username'],
				$data['loginurl']
			);					
					
			$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);							
		}
						
		return $return;		 
	}
	
	public function getContacts ( $pk = null ) 
	{
		if( ! $pk ){
			$item = $this->getItem();
			$pk = $item->id;			
		}
		
		if( (int) $pk > 0 )
		{			
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.id, a.user_id, u.username, u.email, a.name, a.surname, a.gender, a.telephone, a.fax, a.mobile, a.contact_type, a.is_admin, a.job_title,a.systemEmails');
			$query->from('#__sfs_contacts AS a');			
			$query->leftJoin('#__users AS u ON u.id=a.user_id');			
			$query->where( 'a.group_id='.(int) $pk.' AND grouptype=2');	
					
			$query->order('a.contact_type ASC,a.id ASC');
			
			$db->setQuery($query);
			
			$contacts = $db->loadObjectList('user_id');
			if( count($contacts) )
			{
				return $contacts;	
			}
					
		}		 		
		return false;
	}
	
	
	
	public function getReservations()
	{
		if( $item = $this->getItem() ) {
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.hotel_id,a.id,a.blockcode,a.status,a.sd_room,t_room,a.claimed_rooms,a.sd_rate,a.t_rate,a.revenue_booked');			
			$query->from('#__sfs_reservations AS a');
									
			$query->select('b.name AS hotel_name');
			$query->innerJoin('#__sfs_hotel AS b ON b.id=a.hotel_id');
			
			$query->select('d.symbol AS currency');
			$query->innerJoin('#__sfs_hotel_taxes AS c ON c.hotel_id=a.hotel_id');
			$query->innerJoin('#__sfs_currency AS d ON d.id=c.currency_id');
			
			
			$query->where('a.airline_id='.$item->id);
			$query->order('a.booked_date DESC');
			
			$db->setQuery($query,0,20);
			
			$rows = $db->loadObjectList();
			
			return $rows;
		}
		return null;
	}
	
	public function newAdmin()
	{
		jimport('joomla.mail.helper');
		require_once JPATH_ROOT . '/components/com_sfs/tables/sfscontact.php';
		
		$app	= JFactory::getApplication();
		$params	= JComponentHelper::getParams('com_users');
		
		$date = JFactory::getDate();
		$db = $this->getDbo();
		
		$userData = array();		
		$user = new JUser;
		
		$airlineId  = JRequest::getInt('id');
		$data = JRequest::getVar('contact',array(),'array'); 	
			
		$db = $this->getDbo();		

		$db->setQuery('SELECT id FROM #__usergroups WHERE title='.$db->quote('Airline Administrator'));
		$group_id = $db->loadResult();
		
		if( ! $group_id ) {
			$this->setError('No Airline Group created');
			return false;	
		}

		if ( !JMailHelper::isEmailAddress($data['email']) ) {
			$this->setError(JText::_('Email invalid'));
			return false;
		}
		
		if($data['password'] != $data['password2'] ) {
			$this->setError(JText::_('Password does not match'));
			return false;			
		}
		
		$userData['groups']   = array((int)$group_id);
		$userData['name']     = $data['name'].' '.$data['surname'];
		$userData['username'] = $data['username'];
		$userData['password'] = $data['password'];			
		$userData['email']	  = $data['email'];		
		
		if (!$user->bind($userData)) {
			$this->setError($user->getError());
			return false;
		}		
		
		
		$db->setQuery('SELECT time_zone FROM #__sfs_airline_details WHERE id='.$airlineId);
		$timezone = $db->loadResult();
		$user->setParam('timezone',$timezone );
		
		if (!$user->save()) {
			$this->setError($user->getError());
			return false;
		}
			
		$db->setQuery(
				'INSERT INTO #__sfs_airline_user_map (airline_id, user_id)' .
				' VALUES ('.$airlineId.', '.$user->id.')'
		);
		$db->query();	
		
		//Store main contact		
		$contactData = array();
		$contactData['user_id'] 	= $user->id;
		$contactData['grouptype']   = 2;
		$contactData['group_id']  	= $airlineId;
		$contactData['is_admin'] 	= 1;
		$contactData['gender'] 		= $data['gender'];
		$contactData['name'] 		= $data['name'];
		$contactData['surname'] 	= $data['surname'];
		$contactData['job_title'] 	= $data['job_title'];
		$contactData['telephone'] 	= sfsHelper::getPhoneString($data['tel_code'],$data['tel_number'] ) ;
		$contactData['fax'] 		= sfsHelper::getPhoneString($data['fax_code'],$data['fax_number']);
		$contactData['mobile'] 		= sfsHelper::getPhoneString($data['mobile_code'],$data['mobile_number']);
		
		$contactTable = JTable::getInstance('Sfscontact','JTable');
		
		if( ! $contactTable->bind($contactData) ) {
			$this->setError($contactTable->getError());
			return false;
		}
		
		if( ! $contactTable->check() ) {
			$this->setError($contactTable->getError());			
			return false;
		}
		
		if( ! $contactTable->store() ) {
			$this->setError($contactTable->getError());			
			return false;
		}	
				
		
		return true;
	}

    public function newStationUser()
    {
        jimport('joomla.mail.helper');
        require_once JPATH_ROOT . '/components/com_sfs/tables/sfscontact.php';

        $app	= JFactory::getApplication();
        $params	= JComponentHelper::getParams('com_users');

        $date = JFactory::getDate();
        $db = $this->getDbo();

        $userData = array();
        $user = new JUser;

        $airlineId  = JRequest::getInt('id');
        $data = JRequest::getVar('contact',array(),'array');

        $db = $this->getDbo();

        $db->setQuery('SELECT id FROM #__usergroups WHERE title='.$db->quote('Station User'));
        $group_id = $db->loadResult();

        if( ! $group_id ) {
            $this->setError('No Airline Group created');
            return false;
        }

        if ( !JMailHelper::isEmailAddress($data['email']) ) {
            $this->setError(JText::_('Email invalid'));
            return false;
        }

        if($data['password'] != $data['password2'] ) {
            $this->setError(JText::_('Password does not match'));
            return false;
        }

        $airport = new JRegistry();
        $airport->loadArray($data['airport']);

        $userData['groups']   = array((int)$group_id);
        $userData['name']     = $data['name'].' '.$data['surname'];
        $userData['username'] = $data['username'];
        $userData['password'] = $data['password'];
        $userData['email']	  = $data['email'];
        $userData['airport']  = (string)$airport;


        if (!$user->bind($userData)) {
            $this->setError($user->getError());
            return false;
        }


        $db->setQuery('SELECT time_zone FROM #__sfs_airline_details WHERE id='.$airlineId);
        $timezone = $db->loadResult();
        $user->setParam('timezone',$timezone );

        if (!$user->save()) {
            $this->setError($user->getError());
            return false;
        }

        $db->setQuery(
            'INSERT INTO #__sfs_airline_user_map (airline_id, user_id)' .
            ' VALUES ('.$airlineId.', '.$user->id.')'
        );
        $db->query();

        //Store main contact
        $contactData = array();
        $contactData['user_id'] 	= $user->id;
        $contactData['grouptype']   = 2;
        $contactData['group_id']  	= $airlineId;
        $contactData['is_admin'] 	= 0;
        $contactData['gender'] 		= $data['gender'];
        $contactData['name'] 		= $data['name'];
        $contactData['surname'] 	= $data['surname'];
        $contactData['job_title'] 	= $data['job_title'];
        $contactData['telephone'] 	= sfsHelper::getPhoneString($data['tel_code'],$data['tel_number'] ) ;
        $contactData['fax'] 		= sfsHelper::getPhoneString($data['fax_code'],$data['fax_number']);
        $contactData['mobile'] 		= sfsHelper::getPhoneString($data['mobile_code'],$data['mobile_number']);

        $contactTable = JTable::getInstance('Sfscontact','JTable');

        if( ! $contactTable->bind($contactData) ) {
            $this->setError($contactTable->getError());
            return false;
        }

        if( ! $contactTable->check() ) {
            $this->setError($contactTable->getError());
            return false;
        }

        if( ! $contactTable->store() ) {
            $this->setError($contactTable->getError());
            return false;
        }


        return true;
    }
	
	public function getTaxiDetails()
	{
		$item = $this->getItem();
		if($item) {
			$db		 = $this->getDbo();
			$query   = $db->getQuery(true);
			
			$query->select('a.*,b.*,c.name AS country_name,d.name AS billing_country_name');
			$query->from('#__sfs_airline_taxidetail_map AS a');
			$query->innerJoin('#__sfs_taxi_details AS b ON b.id=a.taxi_detail_id');
			$query->leftJoin('#__sfs_country AS c ON c.id=b.country_id');
			$query->leftJoin('#__sfs_country AS d ON d.id=b.billing_country_id');
			
			
			$query->where('a.airline_id = ' . (int) $item->id);
			
			$db->setQuery($query);
			
			$taxi = $db->loadObject();
			
			return $taxi;	
		}
		return null;
	}
	
	public function getTaxiCompanies()
	{
		$item = $this->getItem();
		if($item) {
			
			$db		 = $this->getDbo();
			$query   = $db->getQuery(true);
			
			$query->select('a.*,b.*');
			$query->from('#__sfs_airline_taxicompany_map AS a');
			$query->innerJoin('#__sfs_taxi_companies AS b ON b.id=a.taxi_id');
			
			$query->where('(b.published = 0 OR b.published = 1)');
			$query->where('a.airline_id = ' . (int) $item->id );
			
			$db->setQuery($query);
			
			if( $db->getErrorNum() ) {
				$error = $db->getErrorMsg();			
			}
			
			$taxi_companies = $db->loadObjectList();
			return $taxi_companies;
		}
		
		return null;
		
	}

    public function copyAirline()
    {
        $airlineId  = JRequest::getInt('id');
        $iatacodeId  = JRequest::getInt('iatacode_id');
        $affiliationCode  = JRequest::getVar('affiliation_code');

        $db		 = $this->getDbo();
        $query   = $db->getQuery(true);

        $query->select('*');
        $query->from('#__sfs_airline_details');
        $query->where('id = ' . (int) $airlineId );

        $db->setQuery($query);
        if( $db->getErrorNum() ) {
            $error = $db->getErrorMsg();
        }
        $airlineData = $db->loadAssoc();
        $airlineData['iatacode_id'] = $iatacodeId;
        $airlineData['affiliation_code'] = $affiliationCode;
        $airlineData['logo'] = '';
        unset($airlineData['id']);

        $airlineTable = $this->getTable();

        if( ! $airlineTable->bind($airlineData) ) {
            $this->setError($airlineTable->getError());
            return false;
        }

        if( ! $airlineTable->check() ) {
            $this->setError($airlineTable->getError());
            return false;
        }

        if( ! $airlineTable->store() ) {
            $this->setError($airlineTable->getError());
            return false;
        }

        return true;
    }
	
	//lchung
	public function getAirportEdit( $user_id = 0, $airline_id = 0 ){
		
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$db->setQuery("SELECT id AS value, CONCAT(code, ' - ' ,name) AS text FROM #__sfs_iatacodes WHERE type=2 ORDER BY code ASC");

		$options = $db->loadObjectList();
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		$str = '';
		$data_options = $this->getAirlineAirportOfUser( $user_id, $airline_id );
		foreach ( $options as $vk => $v ){
			$selected = '';
			foreach ( $data_options as $vks => $vs ){
				if( $vs->airport_id == $v->value ) {
					$selected = 'selected="selected"';
				}
			}
			$str .= '<option ' . $selected . ' value="' . $v->value . '">' . $v->text . '</option>';
		}
		return $str;
	}
	
	public function getAirlineAirportOfUser( $user_id = 0, $airline_id = 0 ){
		
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$db->setQuery("SELECT airport_id FROM #__sfs_airline_airport WHERE  user_id = $user_id And airline_detail_id = $airline_id");

		$data = $db->loadObjectList();
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		return $data;
	}
	//End lchung
	
}

