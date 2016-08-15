<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class SfsModelGh extends JModelAdmin
{

	public function getTable($type = 'Admingh', $prefix = 'SfsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true)
	{		
		// Get the form.
		$form = $this->loadForm('com_sfs.gh', 'gh', array('control' => 'jform', 'load_data' => $loadData));
		
		$ghParams 		 = JRequest::getVar('ghparams', array(), 'post', 'array');
				
		JFactory::getApplication()->setUserState('ghparams', $ghParams);
		
				
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_sfs.edit.gh.data', array());
		
		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {
			$db = JFactory::getDbo();
			
			$query = 'SELECT gha.airline_id FROM #__sfs_groundhandlers_airlines AS gha
                    INNER JOIN #__sfs_airline_details AS a ON a.iatacode_id = gha.airline_id AND a.gh_airline=1
                    WHERE gha.ground_id='.$item->id;
			$db->setQuery($query);
			$item->iatacodes = $db->loadResultArray();
			
			if( (int)$item->airport_id > 0) {
				$db->setQuery('SELECT code AS airport_code, name AS airport_name FROM #__sfs_iatacodes WHERE id='.$item->airport_id);
				$airport = $db->loadObject();
				$item->airport_code = $airport->airport_code;
				$item->airport_name = $airport->airport_name;
			}
			
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
								
			
		}		
		return $item;
	}
	
	public function save($data)
	{		
		$db = $this->getDbo();	
						
		if (parent::save($data)) {
			
			$item = $this->getItem();
			
			if(is_array($data['iatacodes']) && count($data['iatacodes']) ) {

                $db->setQuery('DELETE FROM #__sfs_groundhandlers_airlines where ground_id='.$item->id);
                $db->query();

                foreach ( $data['iatacodes'] as $code )
                {
                    $db->setQuery('INSERT INTO #__sfs_groundhandlers_airlines(id, ground_id, airline_id) VALUES(0,'.$item->id.','.$code.')');
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
				$billingTable->set('state_id',$data['billing_state']);
				$billingTable->set('zipcode',$data['billing_zipcode']);
				$billingTable->set('country_id',$data['billing_country_id']);
				$billingTable->set('tva_number',$data['billing_tva_number']);
						
				$billingTable->store(true);
			}
			
			$ghparams = JFactory::getApplication()->getUserState('ghparams', array());

			$registry = new JRegistry();
			$registry->loadArray($ghparams);
			$ghparams =  (string) $registry;
			
			$query = 'UPDATE #__sfs_airline_details SET params='.$db->quote($ghparams).' WHERE id='.$item->id;
			
			$db->setQuery($query);
			$db->query();
						
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
			' INNER JOIN #__user_usergroup_map AS um ON um.user_id=a.user_id AND um.group_id=15'.
			' WHERE a.airline_id='.(int)$item->id
		);
		$result = $db->loadObjectList();
		return $result;
	}	
	
	public function approved( $id=null )
	{
		$return = false; 
		
		$pk = ($id == null) ? $this->getState('gh.id') : $id;
		
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
			$query->where( 'a.group_id='.(int) $pk.' AND grouptype=3');	
					
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
			
			$query->select('f.name AS airline_name');
			$query->innerJoin('#__sfs_gh_reservations AS e ON e.reservation_id=a.id');
			$query->innerJoin('#__sfs_iatacodes AS f ON f.id=e.airline_id');
			
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

		$db->setQuery('SELECT id FROM #__usergroups WHERE title='.$db->quote('GH Administrator'));
		$group_id = $db->loadResult();
		
		if( ! $group_id ) {
			$this->setError('No GH Administrator Group created');
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
		$contactData['grouptype']   = 3;
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
	
}
